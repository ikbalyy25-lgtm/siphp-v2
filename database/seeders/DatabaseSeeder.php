<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

// ============================================================
//  DatabaseSeeder
//  Membuat akun default untuk semua role
//
//  Akun yang dibuat:
//  - 1 Admin Master
//  - 1 Kepala Dinas/Kasubag
//  - 5 Admin Pasar (1 per pasar)
// ============================================================
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Pasars ──
        $pasars = [
            ['nama_pasar' => 'Pasar Lakessi',          'alamat' => 'Jl. Lasinrang, Parepare'],
            ['nama_pasar' => 'Pasar Senggol',          'alamat' => 'Jl. Baso Dg. Patompo, Parepare'],
            ['nama_pasar' => 'Pasar Labukkang',        'alamat' => 'Jl. Andi Mappatola, Parepare'],
            ['nama_pasar' => 'Pasar Sumpang Minangae', 'alamat' => 'Jl. Bau Massepe, Parepare'],
            ['nama_pasar' => 'Pasar Wekkee',           'alamat' => 'Kawasan Lumpue, Parepare'],
        ];

        foreach ($pasars as $pasar) {
            if (!DB::table('pasars')->where('nama_pasar', $pasar['nama_pasar'])->exists()) {
                DB::table('pasars')->insert(array_merge($pasar, [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]));
            }
        }

        // ── 2. Admin Master ──
        if (!User::where('username', 'admin')->exists()) {
            User::create([
                'name'     => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role'     => 'admin_master',
                'pasar_id' => null,
            ]);
        }

        // ── 3. Kepala Dinas / Kasubag ──
        if (!User::where('username', 'kepaladinas')->exists()) {
            User::create([
                'name'     => 'Kepala Dinas Perdagangan',
                'username' => 'kepaladinas',
                'password' => Hash::make('dinas123'),
                'role'     => 'kepala_dinas',
                'pasar_id' => null,
            ]);
        }

        // ── 4. Admin Pasar (1 per pasar) ──
        $adminPasarData = [
            ['username' => 'admin.lakessi',   'name' => 'Admin Pasar Lakessi',          'pasar' => 'Pasar Lakessi'],
            ['username' => 'admin.senggol',   'name' => 'Admin Pasar Senggol',          'pasar' => 'Pasar Senggol'],
            ['username' => 'admin.labukkang', 'name' => 'Admin Pasar Labukkang',        'pasar' => 'Pasar Labukkang'],
            ['username' => 'admin.sumpang',   'name' => 'Admin Pasar Sumpang Minangae', 'pasar' => 'Pasar Sumpang Minangae'],
            ['username' => 'admin.wekkee',    'name' => 'Admin Pasar Wekkee',           'pasar' => 'Pasar Wekkee'],
        ];

        foreach ($adminPasarData as $ap) {
            if (!User::where('username', $ap['username'])->exists()) {
                $pasarId = DB::table('pasars')->where('nama_pasar', $ap['pasar'])->value('id');
                User::create([
                    'name'     => $ap['name'],
                    'username' => $ap['username'],
                    'password' => Hash::make('pasar123'),
                    'role'     => 'admin_pasar',
                    'pasar_id' => $pasarId,
                ]);
            }
        }

        // ── 5. Data Harga Dummy ──
        $this->call(HargaHarianSeeder::class);
    }
}
