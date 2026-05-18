<?php

namespace App\Http\Controllers\AdminPasar;

use App\Http\Controllers\Controller;
use App\Models\InputPedagang;
use App\Models\HargaHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// ============================================================
//  AdminPasar\HargaController
//  Alur: Admin Pasar input 3 harga pedagang
//        → sistem rata-ratakan otomatis
//        → masuk harga_harians status 'pending'
//        → menunggu approve Admin Master
// ============================================================
class HargaController extends Controller
{
    private function getPasar()
    {
        $pasar = Auth::user()->pasar;
        if (!$pasar) abort(403, 'Akun belum ditugaskan ke pasar.');
        return $pasar;
    }

    // Daftar input per kategori
    public function index(string $kategori)
    {
        $pasar = $this->getPasar();

        $inputs = InputPedagang::where('pasar_id', $pasar->id)
            ->where('kategori', $kategori)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin_pasar.harga.index', compact('pasar', 'kategori', 'inputs'));
    }

    // Form input 3 harga
    public function create(string $kategori)
    {
        $pasar = $this->getPasar();

        // Daftar komoditas per kategori (sesuai seeder)
        $daftarBarang = $this->getDaftarBarang($kategori);

        return view('admin_pasar.harga.create', compact('pasar', 'kategori', 'daftarBarang'));
    }

    // Simpan + hitung rata-rata + kirim ke antrian admin master
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang'      => 'required|string',
            'tanggal'          => 'required|date|before_or_equal:today',
            'harga_pedagang_1' => 'required|numeric|min:1',
            'harga_pedagang_2' => 'required|numeric|min:1',
            'harga_pedagang_3' => 'required|numeric|min:1',
            'kategori'         => 'required|in:pokok,subsidi,penting',
        ], [
            'harga_pedagang_1.required' => 'Harga pedagang 1 wajib diisi',
            'harga_pedagang_2.required' => 'Harga pedagang 2 wajib diisi',
            'harga_pedagang_3.required' => 'Harga pedagang 3 wajib diisi',
            'tanggal.before_or_equal'   => 'Tanggal tidak boleh lebih dari hari ini',
        ]);

        $pasar   = $this->getPasar();
        $user    = Auth::user();

        // Hitung rata-rata
        $rataRata = round(
            ($request->harga_pedagang_1 + $request->harga_pedagang_2 + $request->harga_pedagang_3) / 3
        );

        DB::transaction(function () use ($request, $pasar, $user, $rataRata) {
            // 1. Simpan 3 input pedagang
            $input = InputPedagang::create([
                'pasar_id'         => $pasar->id,
                'user_id'          => $user->id,
                'kategori'         => $request->kategori,
                'nama_barang'      => $request->nama_barang,
                'tanggal'          => $request->tanggal,
                'harga_pedagang_1' => $request->harga_pedagang_1,
                'harga_pedagang_2' => $request->harga_pedagang_2,
                'harga_pedagang_3' => $request->harga_pedagang_3,
                'rata_rata'        => $rataRata,
                'status'           => 'terkirim',
            ]);

            // 2. Masukkan rata-rata ke antrian harga_harians (pending)
            $hargaHarian = HargaHarian::create([
                'pasar_id'          => $pasar->id,
                'input_pedagang_id' => $input->id,
                'kategori'          => $request->kategori,
                'nama_barang'       => $request->nama_barang,
                'tanggal'           => $request->tanggal,
                'harga_hari_ini'    => $rataRata,
                'status'            => 'pending',
            ]);

            // 3. Update referensi balik
            $input->update(['harga_harian_id' => $hargaHarian->id]);
        });

        return redirect()->route('admin_pasar.harga.index', $request->kategori)
            ->with('success', "Harga {$request->nama_barang} berhasil dikirim. Menunggu persetujuan Admin Master.");
    }

    // Hapus input (hanya yang masih terkirim/belum diapprove)
    public function destroy(int $id)
    {
        $pasar = $this->getPasar();
        $input = InputPedagang::where('id', $id)
            ->where('pasar_id', $pasar->id)
            ->firstOrFail();

        if ($input->status === 'diapprove') {
            return back()->with('error', 'Data yang sudah disetujui tidak dapat dihapus.');
        }

        DB::transaction(function () use ($input) {
            // Hapus dari antrian harga_harians juga
            if ($input->harga_harian_id) {
                HargaHarian::where('id', $input->harga_harian_id)
                    ->where('status', 'pending')
                    ->delete();
            }
            $input->delete();
        });

        return back()->with('success', 'Data harga berhasil dihapus.');
    }

    private function getDaftarBarang(string $kategori): array
    {
        return match ($kategori) {
            'pokok' => [
                'Beras Medium', 'Beras Premium', 'Beras Pera', 'Gula Pasir', 'Gula Merah',
                'Minyak Goreng Kemasan', 'Tepung Terigu', 'Telur Ayam Ras', 'Telur Ayam Kampung',
                'Daging Ayam Ras', 'Daging Ayam Kampung', 'Daging Sapi',
                'Ikan Bandeng', 'Ikan Cakalang', 'Susu Kental Manis',
            ],
            'subsidi' => [
                'Minyak Goreng Curah', 'Beras BULOG', 'Gula BUMN', 'LPG 3 Kg',
                'Tepung Terigu Subsidi', 'Minyak Tanah', 'Garam Beryodium', 'Kedelai Lokal',
            ],
            'penting' => [
                'Semen Portland 50kg', 'Besi Beton 10mm', 'Besi Beton 8mm', 'Batu Bata Merah',
                'Pasir Urug (m3)', 'Kerikil/Koral (m3)', 'Cat Tembok 5kg', 'Triplek 4mm',
                'Pipa PVC 4 Inch', 'Genteng Tanah Liat', 'Bensin Pertalite (L)',
                'Solar (L)', 'Pertamax (L)', 'LPG 12 Kg', 'Bola Lampu LED 10W',
                'Kabel Listrik (m)', 'Pupuk Urea 50kg', 'Pupuk NPK 50kg',
            ],
            default => [],
        };
    }
}
