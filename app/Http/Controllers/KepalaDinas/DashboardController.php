<?php

namespace App\Http\Controllers\KepalaDinas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// ============================================================
//  KepalaDinas\DashboardController
//  Kepala Dinas & Kasubag: hanya lihat laporan & rekomendasi
// ============================================================
class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ringkasan statistik untuk semua pasar
        $totalBarang  = DB::table('harga_harians')->where('status', 'published')->distinct('nama_barang')->count('nama_barang');
        $totalPasar   = DB::table('pasars')->count();
        $totalHarga   = DB::table('harga_harians')->where('status', 'published')->count();
        $updateHariIni = DB::table('harga_harians')->where('tanggal', date('Y-m-d'))->count();

        // Rekomendasi harga terbaru (ringkasan)
        $rekomendasi = DB::table('harga_harians')
            ->select(
                'nama_barang', 'kategori',
                DB::raw('ROUND(AVG(harga_hari_ini)) as harga_optimal'),
                DB::raw('MIN(harga_hari_ini) as harga_terendah'),
                DB::raw('MAX(harga_hari_ini) as harga_tertinggi'),
                DB::raw('COUNT(DISTINCT pasar_id) as jumlah_pasar')
            )
            ->where('status', 'published')
            ->groupBy('nama_barang', 'kategori')
            ->orderBy('kategori')
            ->orderBy('nama_barang')
            ->limit(12)
            ->get();

        return view('kepala_dinas.dashboard', compact(
            'user', 'totalBarang', 'totalPasar', 'totalHarga', 'updateHariIni', 'rekomendasi'
        ));
    }

    public function rekomendasi()
    {
        $user = Auth::user();
        $rekomendasi = DB::table('harga_harians')
            ->select(
                'nama_barang', 'kategori',
                DB::raw('ROUND(AVG(harga_hari_ini)) as harga_optimal'),
                DB::raw('MIN(harga_hari_ini) as harga_terendah'),
                DB::raw('MAX(harga_hari_ini) as harga_tertinggi'),
                DB::raw('COUNT(DISTINCT pasar_id) as jumlah_pasar')
            )
            ->where('status', 'published')
            ->groupBy('nama_barang', 'kategori')
            ->orderBy('kategori')
            ->orderBy('nama_barang')
            ->get();

        return view('kepala_dinas.rekomendasi', compact('user', 'rekomendasi'));
    }

    public function laporan()
    {
        $user   = Auth::user();
        $pasars = DB::table('pasars')->get();
        return view('kepala_dinas.laporan', compact('user', 'pasars'));
    }

    public function unduh(\Illuminate\Http\Request $request)
    {
        // Delegasi ke LaporanController yang benar
        return app(\App\Http\Controllers\AdminMaster\LaporanController::class)->download($request);
    }
}
