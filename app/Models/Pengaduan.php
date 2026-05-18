<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';

    protected $fillable = [
        'nama',
        'pasar',
        'nomor_telepon',
        'kategori',
        'pesan',
    ];
}
