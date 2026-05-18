<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HargaHarianController extends Controller
{
    // 1. HALAMAN LIST
    public function index($kategori)
    {
        if (!Session::has('pasar_aktif_id')) {
            return redirect()->route('dashboard')->with('error', 'Pilih pasar dulu!');
        }
        $id_pasar = Session::get('pasar_aktif_id');
        $pasar = DB::table('pasars')->where('id', $id_pasar)->first();

        $data_harga = DB::table('harga_harians')
            ->where('pasar_id', $id_pasar)
            ->where('kategori', $kategori)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.harga.index', compact('pasar', 'data_harga', 'kategori'));
    }

    // 2. HALAMAN FORM INPUT
    public function create($kategori)
    {
        if (!Session::has('pasar_aktif_id')) return redirect()->route('dashboard');
        $id_pasar = Session::get('pasar_aktif_id');
        $pasar = DB::table('pasars')->where('id', $id_pasar)->first();
        return view('admin.harga.create', compact('pasar', 'kategori'));
    }

    // 3. PROSES SIMPAN (Data masuk sebagai Pending)
    public function store(Request $request)
    {
        $id_pasar = Session::get('pasar_aktif_id');

        DB::table('harga_harians')->insert([
            'pasar_id'       => $id_pasar,
            'kategori'       => $request->kategori,
            'nama_barang'    => $request->nama_barang,
            'tanggal'        => $request->tanggal,
            'harga_kemarin'  => $request->harga_kemarin,
            'harga_hari_ini' => $request->harga_hari_ini,
            'status'         => 'pending',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->route('admin.harga.index', $request->kategori)
            ->with('success', 'Data tersimpan. Silakan klik "Update" untuk memproses Prediksi AI dan Publikasi.');
    }

    /**
     * 4. FITUR UTAMA: TOGGLE STATUS & PREDIKSI AI
     * Fungsi ini dipicu saat admin klik tombol Update/Terbit
     */
    public function toggleStatus($id)
    {
        // Ambil data harga yang akan diproses
        $data = DB::table('harga_harians')->where('id', $id)->first();

        // Jika status saat ini adalah pending, maka kita jalankan AI
        if ($data->status == 'pending') {

            // Konfigurasi Path (Pastikan folder .venv dan ai_engine sudah benar)
            $pythonPath = base_path('.venv/Scripts/python.exe');
            $scriptPath = base_path('ai_engine/predict.py');

            // Jalankan Perintah Python: python predict.py "Nama Barang" "Harga"
            // escapearg digunakan agar karakter spesial di nama barang tidak error
            $command = "\"$pythonPath\" \"$scriptPath\" \"$data->nama_barang\" \"$data->harga_hari_ini\" 2>&1";
            $output = shell_exec($command);

            // Decode hasil dari Python (JSON)
            $result = json_decode($output, true);

            $updateData = [
                'status' => 'update',
                'updated_at' => now()
            ];

            // Jika AI berhasil memberikan angka prediksi, masukkan ke kolom prediksi_besok
            if (isset($result['prediksi'])) {
                $updateData['prediksi_besok'] = $result['prediksi'];
            }

            DB::table('harga_harians')->where('id', $id)->update($updateData);

            return back()->with('success', 'Berhasil! Data diterbitkan dan Prediksi AI telah diperbarui.');
        }

        // Jika sebelumnya sudah update, kembalikan ke pending (batalkan publikasi)
        else {
            DB::table('harga_harians')->where('id', $id)->update([
                'status' => 'pending',
                'updated_at' => now()
            ]);

            return back()->with('info', 'Data ditarik kembali ke status Pending.');
        }
    }

    // 5. HAPUS DATA
    public function destroy($id)
    {
        DB::table('harga_harians')->where('id', $id)->delete();
        return back()->with('success', 'Data berhasil dihapus');
    }
}
