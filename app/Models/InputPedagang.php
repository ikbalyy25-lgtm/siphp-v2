<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InputPedagang extends Model
{
    protected $table = 'input_pedagang';

    protected $fillable = [
        'pasar_id', 'user_id', 'kategori', 'nama_barang', 'tanggal', 'harga_pedagang',
        'harga_pedagang_1', 'harga_pedagang_2', 'harga_pedagang_3',
        'rata_rata', 'status', 'harga_harian_id',
    ];

    protected $casts = [
        'tanggal'          => 'date',
        'harga_pedagang'   => 'array',
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
            if (is_array($model->harga_pedagang) && count($model->harga_pedagang) > 0) {
                // Kalkulasi rata-rata dari array
                $total = array_sum($model->harga_pedagang);
                $count = count($model->harga_pedagang);
                $model->rata_rata = round($total / $count);
                
                // Backward compatibility untuk 3 harga pertama
                $model->harga_pedagang_1 = $model->harga_pedagang[0] ?? null;
                $model->harga_pedagang_2 = $model->harga_pedagang[1] ?? null;
                $model->harga_pedagang_3 = $model->harga_pedagang[2] ?? null;
            } else {
                // Fallback untuk legacy input (jika JSON kosong)
                $count = 0;
                $total = 0;
                if ($model->harga_pedagang_1) { $total += $model->harga_pedagang_1; $count++; }
                if ($model->harga_pedagang_2) { $total += $model->harga_pedagang_2; $count++; }
                if ($model->harga_pedagang_3) { $total += $model->harga_pedagang_3; $count++; }
                
                if ($count > 0) {
                    $model->rata_rata = round($total / $count);
                } else {
                    $model->rata_rata = 0;
                }
            }
        });
    }

    public function getHargaPedagangListAttribute()
    {
        if (is_array($this->harga_pedagang) && count($this->harga_pedagang) > 0) {
            return $this->harga_pedagang;
        }

        $list = [];
        if ($this->harga_pedagang_1) $list[] = $this->harga_pedagang_1;
        if ($this->harga_pedagang_2) $list[] = $this->harga_pedagang_2;
        if ($this->harga_pedagang_3) $list[] = $this->harga_pedagang_3;

        return $list;
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
