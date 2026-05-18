<?php

namespace App\Services;

use App\Models\PengajuanPedagang;

class BuatAkunServices
{
    public function simpan(array $data)
    {
        // Menyimpan data pengajuan pedagang ke database
        return PengajuanPedagang::create($data);
    }
    public function isNamaValid($nama)
    {
        // Hanya huruf dan spasi
        return preg_match('/^[A-Za-z ]+$/', $nama);
    }

    public function isEmailValid($email)
    {
        // Menggunakan filter validasi email bawaan PHP
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? 1 : 0;
    }

    public function isJenisBarangFilled($jenisBarang)
    {
        return !empty($jenisBarang);
    }

    public function isKontakValid($kontak)
    {
        // Hanya angka
        return preg_match('/^[0-9]+$/', $kontak);
    }

    public function isLokasiFilled($lokasi)
    {
        return !empty($lokasi);
    }
}
