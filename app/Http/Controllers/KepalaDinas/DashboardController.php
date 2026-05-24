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

        return view('kepala_dinas.dashboard', compact(
            'user', 'totalBarang', 'totalPasar', 'totalHarga', 'updateHariIni'
        ));
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
