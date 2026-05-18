<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PasarSeeder extends Seeder
{
    public function run()
    {
        // Data 5 Pasar
        $data = [
            ['nama_pasar' => 'Pasar Sentral Lakessi'],
            ['nama_pasar' => 'Pasar Labukkang'],
            ['nama_pasar' => 'Pasar Senggol'],
            ['nama_pasar' => 'Pasar Sumpang Minangae'],
            ['nama_pasar' => 'Pasar Rakyat'],
        ];

        // Masukkan data beserta timestamp waktu sekarang
        foreach ($data as $pasar) {
            DB::table('pasars')->insert([
                'nama_pasar' => $pasar['nama_pasar'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}