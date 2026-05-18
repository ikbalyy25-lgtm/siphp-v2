<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class PengaduanServices
{
    public function validatePengaduanData(array $data)
    {
        // Aturan validasi disesuaikan dengan Test Case 12 & 13
        $rules = [
            'nama' => ['required', 'regex:/^[A-Za-z ]+$/'],
            'email' => 'required|email',
            'nomor_telepon' => 'required|numeric',
            'kategori' => 'required',
            'pesan' => 'required',
        ];

        $messages = [
            'nama.regex' => 'Nama hanya boleh huruf & spasi',
            // Pesan lain optional, tapi 'nama' penting untuk Test Case 13
        ];

        return Validator::make($data, $rules, $messages);
    }
}