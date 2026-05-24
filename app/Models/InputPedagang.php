<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InputPedagang extends Model
{
    protected $table = 'input_pedagang';

    protected $fillable = [
        'pasar_id', 'user_id', 'kategori', 'nama_barang', 'satuan', 'tanggal', 'harga_pedagang',
        'rata_rata', 'status', 'harga_harian_id',
    ];

    protected $casts = [
        'tanggal'          => 'date',
        'harga_pedagang'   => 'array',
        'rata_rata'        => 'integer',
    ];

    // Hitung rata-rata otomatis sebelum simpan
    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (is_array($model->harga_pedagang) && count($model->harga_pedagang) > 0) {
                // Kalkulasi rata-rata dari array
                $total = array_sum($model->harga_pedagang);
                $count = count($model->harga_pedagang);
                $model->rata_rata = round($total / $count);
            } else {
                $model->rata_rata = 0;
            }
        });
    }

    public function getHargaPedagangListAttribute()
    {
        if (is_array($this->harga_pedagang) && count($this->harga_pedagang) > 0) {
            return $this->harga_pedagang;
        }

        return [];
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
