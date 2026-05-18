<?php

namespace App\Services;

use App\Models\Pasar;
use App\Models\HargaHarian;

class AdminDashboardServices
{
    public function getSemuaPasar()
    {
        return Pasar::all();
    }

    public function tentukanPasarAktif($pasars, $sessionPasarId)
    {
        // Jika ada ID pasar di session, gunakan itu
        if ($sessionPasarId) {
            return $sessionPasarId;
        }

        // Jika tidak, default ke pasar pertama di database
        return $pasars->isNotEmpty() ? $pasars->first()->id : null;
    }

    public function getHargaTerbaru($pasarId)
    {
        // Hanya ambil harga dengan status 'published'
        return HargaHarian::where('pasar_id', $pasarId)
            ->where('status', 'published')
            ->orderBy('tanggal', 'desc')
            ->get();
    }
}
