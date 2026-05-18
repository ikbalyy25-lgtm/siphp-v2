<?php

namespace App\Http\Controllers\KepalaDinas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// ============================================================
//  KepalaDinas\RekomendasiController
//  Kepala Dinas melihat rekomendasi harga optimal
//  (read-only, sama logikanya dengan admin master)
// ============================================================
class RekomendasiController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'pokok');

        $hargaTerbaru = DB::table('harga_harians as h')
            ->join(
                DB::raw('(SELECT pasar_id, nama_barang, MAX(tanggal) as max_tgl
                          FROM harga_harians
                          WHERE status = "published"
                          GROUP BY pasar_id, nama_barang) as latest'),
                function ($join) {
                    $join->on('h.pasar_id', '=', 'latest.pasar_id')
                         ->on('h.nama_barang', '=', 'latest.nama_barang')
                         ->on('h.tanggal', '=', 'latest.max_tgl');
                }
            )
            ->join('pasars', 'h.pasar_id', '=', 'pasars.id')
            ->where('h.status', 'published')
            ->where('h.kategori', $kategori)
            ->select('h.nama_barang', 'h.harga_hari_ini', 'h.tanggal', 'pasars.nama_pasar')
            ->orderBy('h.nama_barang')
            ->get();

        $rekomendasi = $hargaTerbaru
            ->groupBy('nama_barang')
            ->map(function ($items, $namaBarang) {
                $hargaList    = $items->pluck('harga_hari_ini');
                $rataRata     = round($hargaList->avg());
                $batasWajar   = $rataRata * 1.10;
                $hargaWajar   = $hargaList->filter(fn($h) => $h <= $batasWajar);
                $hargaOptimal = $hargaWajar->count() > 0 ? round($hargaWajar->avg()) : $rataRata;
                $selisihPersen = $hargaList->min() > 0
                    ? round((($hargaList->max() - $hargaList->min()) / $hargaList->min()) * 100, 1)
                    : 0;

                return [
                    'nama_barang'     => $namaBarang,
                    'harga_optimal'   => $hargaOptimal,
                    'rata_rata'       => $rataRata,
                    'harga_min'       => $hargaList->min(),
                    'harga_max'       => $hargaList->max(),
                    'selisih_persen'  => $selisihPersen,
                    'jumlah_pasar'    => $items->count(),
                    'perlu_perhatian' => $selisihPersen > 15,
                ];
            })
            ->sortBy('nama_barang')
            ->values();

        $ringkasan = [
            'total_komoditas' => $rekomendasi->count(),
            'perlu_perhatian' => $rekomendasi->where('perlu_perhatian', true)->count(),
            'terakhir_update' => DB::table('harga_harians')
                ->where('status', 'published')->where('kategori', $kategori)->max('tanggal'),
        ];

        return view('kepala_dinas.rekomendasi', compact('rekomendasi', 'kategori', 'ringkasan'));
    }
}
