<?php

namespace App\Services;

use App\Models\Pasar;
use App\Models\HargaHarian;

class PublicHargaServices
{
    public function getPasarById($id)
    {
        return Pasar::find($id);
    }

    public function getHargaPublished($pasarId, $kategori)
    {
        // Logika: Ambil harga yang statusnya 'publish' atau 'update'
        return HargaHarian::where('pasar_id', $pasarId)
            ->where('kategori', $kategori)
            ->where('status', 'published')
            ->orderBy('tanggal', 'desc')
            ->get();
    }
}
