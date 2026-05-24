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
        $daftarBarangDefault = $this->getDaftarBarang($kategori);

        // Ambil komoditas custom yang pernah diinputkan
        $daftarBarangCustom = InputPedagang::where('kategori', $kategori)
            ->whereNotIn('nama_barang', $daftarBarangDefault)
            ->distinct()
            ->pluck('nama_barang')
            ->toArray();

        $daftarBarang = array_merge($daftarBarangDefault, $daftarBarangCustom);
        sort($daftarBarang);

        return view('admin_pasar.harga.create', compact('pasar', 'kategori', 'daftarBarang'));
    }

    // Simpan + hitung rata-rata + kirim ke antrian admin master
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang'      => 'required|string',
            'nama_barang_baru' => 'nullable|required_if:nama_barang,__baru__|string|max:255',
            'tanggal'          => 'required|date|before_or_equal:today',
            'harga_pedagang'   => 'required|array|min:1',
            'harga_pedagang.*' => 'required|numeric|min:1',
            'kategori'         => 'required|in:pokok,subsidi,penting',
        ], [
            'harga_pedagang.required'   => 'Minimal harus ada 1 harga pedagang',
            'harga_pedagang.*.required' => 'Harga pedagang wajib diisi',
            'tanggal.before_or_equal'   => 'Tanggal tidak boleh lebih dari hari ini',
            'nama_barang_baru.required_if' => 'Nama komoditas baru wajib diisi',
        ]);

        $pasar   = $this->getPasar();
        $user    = Auth::user();

        $namaBarang = $request->nama_barang === '__baru__' ? $request->nama_barang_baru : $request->nama_barang;

        // Hitung rata-rata
        $rataRata = count($request->harga_pedagang) > 0 
            ? round(array_sum($request->harga_pedagang) / count($request->harga_pedagang)) 
            : 0;

        DB::transaction(function () use ($request, $pasar, $user, $rataRata, $namaBarang) {
            // 1. Simpan input pedagang (bisa > 3 harga)
            $input = InputPedagang::create([
                'pasar_id'         => $pasar->id,
                'user_id'          => $user->id,
                'kategori'         => $request->kategori,
                'nama_barang'      => $namaBarang,
                'tanggal'          => $request->tanggal,
                'harga_pedagang'   => $request->harga_pedagang,
                // harga_pedagang_1, 2, 3 dan rata_rata otomatis diisi via boot method di Model
                'status'           => 'terkirim',
            ]);

            // 2. Masukkan rata-rata ke antrian harga_harians (pending)
            $hargaHarian = HargaHarian::create([
                'pasar_id'          => $pasar->id,
                'input_pedagang_id' => $input->id,
                'kategori'          => $request->kategori,
                'nama_barang'       => $namaBarang,
                'tanggal'           => $request->tanggal,
                'harga_hari_ini'    => $rataRata,
                'status'            => 'pending',
            ]);

            // 3. Update referensi balik
            $input->update(['harga_harian_id' => $hargaHarian->id]);
        });

        return redirect()->route('admin_pasar.harga.index', $request->kategori)
            ->with('success', "Harga {$namaBarang} berhasil dikirim. Menunggu persetujuan Admin Master.");
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
