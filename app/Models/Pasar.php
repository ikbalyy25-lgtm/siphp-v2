<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasar extends Model
{
    protected $table = 'pasars';

    protected $fillable = [
        'nama_pasar',
        'alamat',
    ];

    // Relasi ke harga harian
    public function hargaHarians()
    {
        return $this->hasMany(HargaHarian::class);
    }

    // Relasi ke admin pasar (user)
    public function adminPasar()
    {
        return $this->hasOne(User::class, 'pasar_id')->where('role', 'admin_pasar');
    }

    // Relasi ke input pedagang
    public function inputPedagangs()
    {
        return $this->hasMany(InputPedagang::class);
    }
}
