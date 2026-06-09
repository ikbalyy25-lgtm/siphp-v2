<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// ============================================================
//  HargaHarianSeeder
//  Dataset lengkap: 41 barang x 5 pasar x 7 hari
//  Total: ±1.435 baris harga realistis
// ============================================================
class HargaHarianSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('harga_harians')->truncate();

        $pasarIds = DB::table('pasars')->orderBy('id')->pluck('id')->toArray();

        if (count($pasarIds) < 5) {
            $this->command->warn('Kurang dari 5 pasar. Pastikan DatabaseSeeder dijalankan dulu.');
            return;
        }

        $barangPokok = [
            'Beras Medium'          => ['dasar' => 12000, 'varian' => 1500],
            'Beras Premium'         => ['dasar' => 15000, 'varian' => 1500],
            'Beras Pera'            => ['dasar' => 11000, 'varian' => 1000],
            'Gula Pasir'            => ['dasar' => 17000, 'varian' => 1000],
            'Gula Merah'            => ['dasar' => 20000, 'varian' => 2000],
            'Minyak Goreng Kemasan' => ['dasar' => 20000, 'varian' => 1500],
            'Tepung Terigu'         => ['dasar' => 10000, 'varian' => 1000],
            'Telur Ayam Ras'        => ['dasar' => 27000, 'varian' => 2000],
            'Telur Ayam Kampung'    => ['dasar' => 45000, 'varian' => 3000],
            'Daging Ayam Ras'       => ['dasar' => 35000, 'varian' => 3000],
            'Daging Ayam Kampung'   => ['dasar' => 70000, 'varian' => 5000],
            'Daging Sapi'           => ['dasar' => 130000,'varian' => 10000],
            'Ikan Bandeng'          => ['dasar' => 35000, 'varian' => 3000],
            'Ikan Cakalang'         => ['dasar' => 40000, 'varian' => 4000],
            'Susu Kental Manis'     => ['dasar' => 15000, 'varian' => 1000],
        ];

        $barangSubsidi = [
            'Minyak Goreng Curah'   => ['dasar' => 14000, 'varian' => 1000],
            'Beras BULOG'           => ['dasar' => 10000, 'varian' => 500],
            'Gula BUMN'             => ['dasar' => 14500, 'varian' => 500],
            'LPG 3 Kg'              => ['dasar' => 20000, 'varian' => 1000],
            'Tepung Terigu Subsidi' => ['dasar' => 8500,  'varian' => 500],
            'Minyak Tanah'          => ['dasar' => 10000, 'varian' => 500],
            'Garam Beryodium'       => ['dasar' => 3000,  'varian' => 500],
            'Kedelai Lokal'         => ['dasar' => 12000, 'varian' => 1000],
        ];

        $barangPenting = [
            'Semen Portland 50kg'   => ['dasar' => 65000,  'varian' => 3000],
            'Besi Beton 10mm'       => ['dasar' => 85000,  'varian' => 5000],
            'Besi Beton 8mm'        => ['dasar' => 55000,  'varian' => 4000],
            'Batu Bata Merah'       => ['dasar' => 1200,   'varian' => 200],
            'Pasir Urug (m3)'       => ['dasar' => 250000, 'varian' => 20000],
            'Kerikil/Koral (m3)'    => ['dasar' => 300000, 'varian' => 25000],
            'Cat Tembok 5kg'        => ['dasar' => 75000,  'varian' => 5000],
            'Triplek 4mm'           => ['dasar' => 90000,  'varian' => 8000],
            'Pipa PVC 4 Inch'       => ['dasar' => 55000,  'varian' => 3000],
            'Genteng Tanah Liat'    => ['dasar' => 2500,   'varian' => 300],
            'Bensin Pertalite (L)'  => ['dasar' => 10000,  'varian' => 0],
            'Solar (L)'             => ['dasar' => 6800,   'varian' => 0],
            'Pertamax (L)'          => ['dasar' => 13500,  'varian' => 500],
            'LPG 12 Kg'             => ['dasar' => 190000, 'varian' => 5000],
            'Bola Lampu LED 10W'    => ['dasar' => 25000,  'varian' => 3000],
            'Kabel Listrik (m)'     => ['dasar' => 8000,   'varian' => 1000],
            'Pupuk Urea 50kg'       => ['dasar' => 225000, 'varian' => 10000],
            'Pupuk NPK 50kg'        => ['dasar' => 300000, 'varian' => 15000],
        ];

        // Faktor harga per pasar (variasi realistis antar pasar)
        $pasarFaktor = [
            $pasarIds[0] => 1.00,  // Pasar Lakessi (acuan)
            $pasarIds[1] => 1.02,  // Pasar Senggol
            $pasarIds[2] => 0.98,  // Pasar Labukkang
            $pasarIds[3] => 1.03,  // Pasar Sumpang Minangae
            $pasarIds[4] => 0.97,  // Pasar Wekkee
        ];

        $allKategori = [
            'pokok'   => $barangPokok,
            'subsidi' => $barangSubsidi,
            'penting' => $barangPenting,
        ];

        $today = Carbon::today();
        $rows  = [];

        foreach ($allKategori as $kategori => $barangList) {
            foreach ($barangList as $namaBarang => $config) {
                foreach ($pasarIds as $pasarId) {
                    $faktor     = $pasarFaktor[$pasarId];
                    $hargaDasar = round($config['dasar'] * $faktor / 500) * 500;

                    for ($hari = 6; $hari >= 0; $hari--) {
                        $tanggal   = $today->copy()->subDays($hari);
                        $varian    = rand(-$config['varian'], $config['varian']);
                        $varian    = round($varian / 500) * 500;
                        $hariIni   = max(1000, $hargaDasar + $varian);
                        $kemarin   = max(1000, $hargaDasar + round(rand(-$config['varian'], $config['varian']) / 500) * 500);
                        $ts        = $tanggal->copy()->setTime(7, rand(0, 59));

                        $rows[] = [
                            'pasar_id'          => $pasarId,
                            'input_pedagang_id' => null,
                            'kategori'          => $kategori,
                            'nama_barang'       => $namaBarang,
                            'satuan'            => '-',
                            'tanggal'           => $tanggal->format('Y-m-d'),
                            'harga_hari_ini'    => $hariIni,
                            'status'            => 'published',
                            'created_at'        => $ts,
                            'updated_at'        => $ts,
                        ];
                    }
                }
            }
        }

        // Insert dalam batch 200 agar tidak timeout
        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('harga_harians')->insert($chunk);
        }

        $this->command->info('✅ HargaHarianSeeder: ' . count($rows) . ' baris berhasil diinsert.');
    }
}
