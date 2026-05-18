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

    // Terima kategori dari Controller
    public function __construct($bulan, $tahun, $kategori)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->kategori = $kategori;
    }

    public function collection()
    {
        $query = DB::table('harga_harians')
            ->join('pasars', 'harga_harians.pasar_id', '=', 'pasars.id')
            ->select('pasars.nama_pasar', 'harga_harians.*')
            ->whereMonth('harga_harians.tanggal', $this->bulan)
            ->whereYear('harga_harians.tanggal', $this->tahun)
            ->where('status', 'update');

        // <--- LOGIKA FILTER KATEGORI --->
        if ($this->kategori != 'semua') {
            $query->where('harga_harians.kategori', $this->kategori);
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
            $row->harga_kemarin,
            $row->harga_hari_ini,
        ];
    }
}
