<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retail extends Model
{
    use HasFactory;

    protected $table = 'retails';

    protected $fillable = [
        'nama_toko',
        'kategori',
        'alamat',
        'kontak',
        'jam_buka',
        'link_maps',
        'gambar'
    ];
}
