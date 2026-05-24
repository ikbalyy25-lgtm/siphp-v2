<?php

namespace App\Http\Controllers\AdminPasar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// ============================================================
//  AdminPasar\StatistikController
//  Admin Pasar melihat statistik harga pasarnya sendiri
// ============================================================
class StatistikController extends Controller
{
    public function index(string $kategori)
    {
        $pasar = Auth::user()->pasar;
        if (!$pasar) abort(403, 'Akun belum ditugaskan ke pasar.');

        $daftar_barang = DB::table('harga_harians')
            ->where('pasar_id', $pasar->id)
            ->where('kategori', $kategori)
            ->distinct()
            ->pluck('nama_barang')
            ->sort()
            ->values();

        return view('admin.statistik.show', compact('pasar', 'kategori', 'daftar_barang'));
    }

    public function getApiData(Request $request, string $kategori, string $nama_barang)
    {
        $pasar = Auth::user()->pasar;
        if (!$pasar) abort(403);

        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $data = DB::table('harga_harians')
            ->where('pasar_id', $pasar->id)
            ->where('kategori', $kategori)
            ->where('nama_barang', $nama_barang)
            ->where('status', 'published')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get(['tanggal', 'harga_hari_ini']);

        $labels = [];
        $prices = [];
        foreach ($data as $item) {
            $labels[] = \Carbon\Carbon::parse($item->tanggal)->format('d M');
            $prices[] = (float) $item->harga_hari_ini;
        }

        $bulan_nama = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');

        return response()->json([
            'labels' => $labels,
            'prices' => $prices,
            'bulan_nama' => $bulan_nama
        ]);
    }
}
