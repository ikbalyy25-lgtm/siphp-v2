<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPedagang extends Model
{
    use HasFactory;

    // INI YANG PENTING: Arahkan ke tabel yang baru kita buat
    protected $table = 'pengajuan_pedagangs'; 

    protected $fillable = [
        'nama',
        'email',
        'kontak',
        'lokasi_penjualan',
        'jenis_barang'
    ];
}