<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// ============================================================
//  Admin\StatistikController
//  Dipakai Admin Master untuk melihat grafik harga per pasar
//  Routes: admin_master.statistik.*
// ============================================================
class StatistikController extends Controller
{
    // Halaman pilih kategori sebelum lihat grafik
    public function pilihKategori(int $id_pasar)
    {
        $pasar = DB::table('pasars')->where('id', $id_pasar)->firstOrFail();
        return view('admin.statistik.pilih_kategori', compact('pasar'));
    }

    // Halaman grafik statistik
    public function showGrafik(int $id_pasar, string $kategori)
    {
        $pasar = DB::table('pasars')->where('id', $id_pasar)->firstOrFail();

        // Daftar barang yang tersedia untuk kategori ini
        $daftar_barang = DB::table('harga_harians')
            ->where('pasar_id', $id_pasar)
            ->where('kategori', $kategori)
            ->distinct()
            ->pluck('nama_barang')
            ->sort()
            ->values();

        return view('admin.statistik.show', compact('pasar', 'kategori', 'daftar_barang'));
    }

    // API data grafik (dipanggil JavaScript via fetch)
    public function getApiData(Request $request, int $id_pasar, string $kategori, string $nama_barang)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $data = DB::table('harga_harians')
            ->where('pasar_id', $id_pasar)
            ->where('kategori', $kategori)
            ->where('nama_barang', $nama_barang)
            ->where('status', 'published')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get(['tanggal', 'harga_hari_ini']);

        return response()->json($data);
    }
}
