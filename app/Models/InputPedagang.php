<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InputPedagang extends Model
{
    protected $table = 'input_pedagang';

    protected $fillable = [
        'pasar_id', 'user_id', 'kategori', 'nama_barang', 'tanggal',
        'harga_pedagang_1', 'harga_pedagang_2', 'harga_pedagang_3',
        'rata_rata', 'status', 'harga_harian_id',
    ];

    protected $casts = [
        'tanggal'          => 'date',
        'harga_pedagang_1' => 'integer',
        'harga_pedagang_2' => 'integer',
        'harga_pedagang_3' => 'integer',
        'rata_rata'        => 'integer',
    ];

    // Hitung rata-rata otomatis sebelum simpan
    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->rata_rata = round(
                ($model->harga_pedagang_1 + $model->harga_pedagang_2 + $model->harga_pedagang_3) / 3
            );
        });
    }

    public function pasar()
    {
        return $this->belongsTo(Pasar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hargaHarian()
    {
        return $this->belongsTo(HargaHarian::class, 'harga_harian_id');
    }
}
