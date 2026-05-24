<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaHarian extends Model
{
    protected $table = 'harga_harians';

    protected $fillable = [
        'pasar_id', 'input_pedagang_id', 'kategori',
        'nama_barang', 'satuan', 'tanggal', 'harga_hari_ini', 'status',
    ];

    protected $casts = [
        'tanggal'       => 'date',
        'harga_hari_ini' => 'integer',
    ];

    public function pasar()
    {
        return $this->belongsTo(Pasar::class);
    }

    public function inputPedagang()
    {
        return $this->belongsTo(InputPedagang::class, 'input_pedagang_id');
    }

    // Scope: hanya yang sudah dipublish (tampil ke publik)
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Scope: pending approval admin master
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
