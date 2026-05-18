<?php

namespace App\Http\Controllers\AdminMaster;

use App\Http\Controllers\Controller;
use App\Exports\LaporanHargaExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

// ============================================================
//  AdminMaster\LaporanController
//  Download laporan harga dalam format Excel
//  Filter: bulan, tahun, kategori
// ============================================================
class LaporanController extends Controller
{
    public function download(Request $request)
    {
        $bulan    = $request->get('bulan',    date('m'));
        $tahun    = $request->get('tahun',    date('Y'));
        $kategori = $request->get('kategori', 'semua');
        $tipe     = $request->get('tipe', 'excel');
        $pasar_id = $request->get('pasar_id');

        if ($tipe === 'pdf') {
            $query = \Illuminate\Support\Facades\DB::table('harga_harians')
                ->join('pasars', 'harga_harians.pasar_id', '=', 'pasars.id')
                ->select('pasars.nama_pasar', 'harga_harians.*')
                ->selectSub(function($q) {
                    $q->from('harga_harians as h2')
                      ->select('h2.harga_hari_ini')
                      ->whereColumn('h2.pasar_id', 'harga_harians.pasar_id')
                      ->whereColumn('h2.nama_barang', 'harga_harians.nama_barang')
                      ->where('h2.status', 'published')
                      ->whereColumn('h2.tanggal', '<', 'harga_harians.tanggal')
                      ->orderBy('h2.tanggal', 'desc')
                      ->limit(1);
                }, 'harga_kemarin')
                ->whereMonth('harga_harians.tanggal', $bulan)
                ->whereYear('harga_harians.tanggal', $tahun)
                ->where('status', 'published');

            if ($kategori !== 'semua') {
                $query->where('harga_harians.kategori', $kategori);
            }

            if ($pasar_id) {
                $query->where('harga_harians.pasar_id', $pasar_id);
            }

            $rawData = $query->orderBy('pasars.nama_pasar')
                ->orderBy('harga_harians.tanggal')
                ->get();

            $laporan = $rawData->groupBy('nama_pasar');

            $nama_kategori = $kategori === 'semua' ? 'Semua Kategori' : 'Kategori ' . ucfirst($kategori);

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf', compact('laporan', 'bulan', 'tahun', 'nama_kategori'));
            return $pdf->download("laporan-harga-{$tahun}-{$bulan}.pdf");
        }

        $namaFile = "laporan-harga-{$tahun}-{$bulan}.xlsx";

        return Excel::download(
            new LaporanHargaExport($bulan, $tahun, $kategori, $pasar_id),
            $namaFile
        );
    }
}
