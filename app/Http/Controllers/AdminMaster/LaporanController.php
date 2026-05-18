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

        $namaFile = "laporan-harga-{$tahun}-{$bulan}.xlsx";

        return Excel::download(
            new LaporanHargaExport($bulan, $tahun, $kategori),
            $namaFile
        );
    }
}
