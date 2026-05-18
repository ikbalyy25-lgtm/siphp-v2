<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanHargaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $bulan;
    protected $tahun;
    protected $kategori; // <--- Variabel Baru
    protected $pasar_id;

    // Terima kategori dari Controller
    public function __construct($bulan, $tahun, $kategori, $pasar_id = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->kategori = $kategori;
        $this->pasar_id = $pasar_id;
    }

    public function collection()
    {
        $query = DB::table('harga_harians')
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
            ->whereMonth('harga_harians.tanggal', $this->bulan)
            ->whereYear('harga_harians.tanggal', $this->tahun)
            ->where('status', 'published');

        // <--- LOGIKA FILTER KATEGORI --->
        if ($this->kategori != 'semua') {
            $query->where('harga_harians.kategori', $this->kategori);
        }

        if ($this->pasar_id) {
            $query->where('harga_harians.pasar_id', $this->pasar_id);
        }

        return $query->orderBy('pasars.nama_pasar')
            ->orderBy('harga_harians.tanggal')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama Pasar',
            'Kategori',
            'Nama Barang',
            'Tanggal Input',
            'Harga Kemarin',
            'Harga Hari Ini',
        ];
    }

    public function map($row): array
    {
        return [
            $row->nama_pasar,
            ucfirst($row->kategori),
            $row->nama_barang,
            $row->tanggal,
            $row->harga_kemarin ?? 0,
            $row->harga_hari_ini,
        ];
    }
}
