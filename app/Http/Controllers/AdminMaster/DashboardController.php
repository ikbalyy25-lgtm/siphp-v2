<?php

namespace App\Http\Controllers\AdminMaster;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

// ============================================================
//  AdminMaster\DashboardController
//  Dashboard utama Admin Master:
//  - Ganti pasar aktif (via session)
//  - Monitor antrian harga dari semua admin pasar
//  - Lihat rekomendasi optimal semua pasar
// ============================================================
class DashboardController extends Controller
{
    public function index()
    {
        $user        = Auth::user();
        $semua_pasar = DB::table('pasars')->orderBy('nama_pasar')->get();

        // Pasar aktif dari session (default pasar pertama)
        $pasarAktifId = Session::get('pasar_aktif_id', $semua_pasar->first()?->id);
        $pasar_aktif  = DB::table('pasars')->where('id', $pasarAktifId)->first();

        // Data harga pasar aktif (semua status untuk monitoring)
        $data_harga = collect();
        if ($pasar_aktif) {
            $data_harga = DB::table('harga_harians')
                ->where('pasar_id', $pasar_aktif->id)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();
        }

        // Stat
        $totalHarga     = $data_harga->count();
        $totalPublished = $data_harga->where('status', 'published')->count();
        $totalPending   = $data_harga->where('status', 'pending')->count();

        // Rekomendasi harga optimal (ringkasan untuk dashboard)
        $rekomendasi_harga = DB::table('harga_harians')
            ->select(
                'nama_barang',
                'kategori',
                DB::raw('ROUND(AVG(harga_hari_ini)) as harga_optimal'),
                DB::raw('MIN(harga_hari_ini)        as harga_terendah'),
                DB::raw('MAX(harga_hari_ini)        as harga_tertinggi'),
                DB::raw('COUNT(DISTINCT pasar_id)   as jumlah_pasar_terdata')
            )
            ->where('status', 'published')
            ->groupBy('nama_barang', 'kategori')
            ->orderBy('kategori')
            ->orderBy('nama_barang')
            ->get();

        return view('admin.dashboard', compact(
            'user', 'semua_pasar', 'pasar_aktif', 'data_harga',
            'totalHarga', 'totalPublished', 'totalPending', 'rekomendasi_harga'
        ));
    }

    // Ganti pasar aktif via session
    public function setPasar(int $id)
    {
        $pasar = DB::table('pasars')->where('id', $id)->first();
        if (!$pasar) abort(404);

        Session::put('pasar_aktif_id', $id);
        return redirect()->route('admin.dashboard')
            ->with('success', "Pasar aktif diganti ke: {$pasar->nama_pasar}");
    }
}
