<?php

namespace App\Http\Controllers\AdminPasar;

use App\Http\Controllers\Controller;
use App\Models\InputPedagang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// ============================================================
//  AdminPasar\DashboardController
//  Dashboard untuk Admin Pasar
//  Hanya melihat & mengelola data pasar yang ditugaskan
// ============================================================
class DashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $pasar = $user->pasar;

        if (!$pasar) {
            abort(403, 'Akun ini belum ditugaskan ke pasar manapun.');
        }

        // Data harga pasar ini yang sudah published
        $data_harga = DB::table('harga_harians')
            ->where('pasar_id', $pasar->id)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistik
        $totalPublished = $data_harga->count();

        $totalPending = DB::table('harga_harians')
            ->where('pasar_id', $pasar->id)
            ->where('status', 'pending')
            ->count();

        $hariIni = DB::table('harga_harians')
            ->where('pasar_id', $pasar->id)
            ->where('tanggal', date('Y-m-d'))
            ->count();

        // Input pedagang yang terkirim (belum diapprove)
        $inputTerkirim = InputPedagang::where('pasar_id', $pasar->id)
            ->where('status', 'terkirim')
            ->count();

        return view('admin_pasar.dashboard', compact(
            'user', 'pasar', 'data_harga',
            'totalPublished', 'totalPending', 'hariIni', 'inputTerkirim'
        ));
    }
}
