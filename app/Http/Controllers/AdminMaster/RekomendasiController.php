<?php

namespace App\Http\Controllers\AdminMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// ============================================================
//  AdminMaster\RekomendasiController
//  INTI SISTEM: Rekomendasi Harga Optimal
//
//  Logika rekomendasi:
//  - Kumpulkan harga terbaru per komoditas dari semua pasar
//  - Hitung: rata-rata, harga terendah, harga tertinggi
//  - Harga optimal = harga yang direkomendasikan kepada pedagang
//    (diambil dari harga terendah yang wajar = di atas 80% rata-rata)
//  - Flagging: pasar yang harganya di atas 110% rata-rata = TINGGI
//  - Flagging: pasar yang harganya di bawah 90% rata-rata  = RENDAH
// ============================================================
class RekomendasiController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'pokok');

        // Ambil harga terbaru per komoditas per pasar (published saja)
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
            ->select(
                'h.nama_barang',
                'h.kategori',
                'h.harga_hari_ini',
                'h.tanggal',
                'pasars.id as pasar_id',
                'pasars.nama_pasar'
            )
            ->orderBy('h.nama_barang')
            ->get();

        // Kelompokkan per komoditas dan hitung statistik
        $rekomendasi = $hargaTerbaru
            ->groupBy('nama_barang')
            ->map(function ($items, $namaBarang) {
                $hargaList = $items->pluck('harga_hari_ini');
                $rataRata  = round($hargaList->avg());
                $minimum   = $hargaList->min();
                $maksimum  = $hargaList->max();

                // Harga optimal: rata-rata harga yang tidak terlalu jauh dari minimum
                // (menghindari outlier yang terlalu tinggi)
                $batasWajar   = $rataRata * 1.10;
                $hargaWajar   = $hargaList->filter(fn($h) => $h <= $batasWajar);
                $hargaOptimal = $hargaWajar->count() > 0
                    ? round($hargaWajar->avg())
                    : $rataRata;

                // Selisih persentase maks vs min
                $selisihPersen = $minimum > 0
                    ? round((($maksimum - $minimum) / $minimum) * 100, 1)
                    : 0;

                // Flagging tiap pasar
                $detailPasar = $items->map(function ($item) use ($rataRata) {
                    $persen = $rataRata > 0
                        ? round((($item->harga_hari_ini - $rataRata) / $rataRata) * 100, 1)
                        : 0;
                    $flag = 'normal';
                    if ($persen > 10)  $flag = 'tinggi';
                    if ($persen < -10) $flag = 'rendah';
                    return [
                        'pasar_id'    => $item->pasar_id,
                        'nama_pasar'  => $item->nama_pasar,
                        'harga'       => $item->harga_hari_ini,
                        'tanggal'     => $item->tanggal,
                        'selisih_pct' => $persen,
                        'flag'        => $flag,
                    ];
                })->values();

                return [
                    'nama_barang'   => $namaBarang,
                    'harga_optimal' => $hargaOptimal,
                    'rata_rata'     => $rataRata,
                    'harga_min'     => $minimum,
                    'harga_max'     => $maksimum,
                    'selisih_persen'=> $selisihPersen,
                    'jumlah_pasar'  => $items->count(),
                    'detail_pasar'  => $detailPasar,
                    'perlu_perhatian' => $selisihPersen > 15, // flag merah jika disparitas > 15%
                ];
            })
            ->sortBy('nama_barang')
            ->values();

        // Ringkasan untuk header
        $ringkasan = [
            'total_komoditas'   => $rekomendasi->count(),
            'perlu_perhatian'   => $rekomendasi->where('perlu_perhatian', true)->count(),
            'total_pasar'       => DB::table('pasars')->count(),
            'terakhir_update'   => DB::table('harga_harians')
                                     ->where('status', 'published')
                                     ->where('kategori', $kategori)
                                     ->max('tanggal'),
        ];

        return view('admin.rekomendasi.index', compact('rekomendasi', 'kategori', 'ringkasan'));
    }

    // API & View: data komparasi harga antar pasar untuk grafik
    public function komparasi(Request $request)
    {
        $namaBarang = $request->get('barang');
        $kategori   = $request->get('kategori', 'pokok');

        $data = DB::table('harga_harians as h')
            ->join('pasars', 'h.pasar_id', '=', 'pasars.id')
            ->where('h.status', 'published')
            ->where('h.kategori', $kategori)
            ->when($namaBarang, fn($q) => $q->where('h.nama_barang', $namaBarang))
            ->orderBy('h.tanggal', 'asc')
            ->select('h.nama_barang', 'h.harga_hari_ini', 'h.tanggal', 'pasars.nama_pasar')
            ->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($data);
        }

        // Ambil semua komoditas unik untuk kategori ini agar bisa dipilih di dropdown
        $komoditasList = DB::table('harga_harians')
            ->where('status', 'published')
            ->where('kategori', $kategori)
            ->distinct()
            ->pluck('nama_barang');

        // Jika tidak ada barang yang dipilih, ambil barang pertama dari list
        if (!$namaBarang && $komoditasList->isNotEmpty()) {
            $namaBarang = $komoditasList->first();
            // Re-fetch data dengan barang default ini
            $data = DB::table('harga_harians as h')
                ->join('pasars', 'h.pasar_id', '=', 'pasars.id')
                ->where('h.status', 'published')
                ->where('h.kategori', $kategori)
                ->where('h.nama_barang', $namaBarang)
                ->orderBy('h.tanggal', 'asc')
                ->select('h.nama_barang', 'h.harga_hari_ini', 'h.tanggal', 'pasars.nama_pasar')
                ->get();
        }

        return view('admin.rekomendasi.komparasi', compact('data', 'komoditasList', 'namaBarang', 'kategori'));
    }
}
