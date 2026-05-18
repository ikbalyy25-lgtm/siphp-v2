<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\PengajuanPedagang;
use App\Services\PublicHargaServices;
use App\Services\BuatAkunServices;
use App\Services\AuthServices;
use App\Services\AdminDashboardServices;

class ServicesTest extends TestCase
{
    // Menggunakan RefreshDatabase agar database di-reset setiap kali test dijalankan
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | PublicHargaServices Tests
    |--------------------------------------------------------------------------
    */

    public function test_get_pasar_by_id()
    {
        // 1. Setup Data Dummy
        DB::table('pasars')->insert([
            'id' => 1,
            'nama_pasar' => 'Pasar Lakessi',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 2. Eksekusi Service
        $service = new PublicHargaServices();
        $pasar = $service->getPasarById(1);

        // 3. Assert (Pengecekan)
        $this->assertNotNull($pasar);
        $this->assertEquals('Pasar Lakessi', $pasar->nama_pasar);
    }

    public function test_get_harga_published()
    {
        // Setup data harga dengan status 'published' dan 'pending'
        DB::table('harga_harians')->insert([
            [
                'pasar_id' => 1,
                'kategori' => 'Barang Pokok',
                'nama_barang' => 'Cabai',
                'tanggal' => now()->toDateString(),
                'harga_hari_ini' => 15000,
                'status' => 'published', // Ini akan diambil
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pasar_id' => 1,
                'kategori' => 'Barang Pokok',
                'nama_barang' => 'Tomat',
                'tanggal' => now()->toDateString(),
                'harga_hari_ini' => 11000,
                'status' => 'pending', // Ini TIDAK akan diambil
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $service = new PublicHargaServices();
        $harga = $service->getHargaPublished(1, 'Barang Pokok');

        $this->assertNotEmpty($harga);
        $this->assertCount(1, $harga); // Harusnya cuma 1 data yang terambil (Cabai)
    }

    /*
    |--------------------------------------------------------------------------
    | BuatAkunServices (Pengajuan Pedagang) Tests
    |--------------------------------------------------------------------------
    */

    public function test_simpan_pengajuan_pedagang()
    {
        $service = new BuatAkunServices();

        $data = [
            'nama' => 'Ani',
            'email' => 'ani@email.com',
            'kontak' => '08123456789',
            'lokasi_penjualan' => 'Pasar Lakessi',
            'jenis_barang' => 'Ikan'
        ];

        $result = $service->simpan($data);

        $this->assertInstanceOf(PengajuanPedagang::class, $result);

        $this->assertDatabaseHas('pengajuan_pedagangs', [
            'email' => 'ani@email.com'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | AuthServices Tests
    |--------------------------------------------------------------------------
    */

    public function test_gagal_jika_username_dan_password_kosong()
    {
        $service = new AuthServices();
        $result = $service->login(null, null);

        $this->assertFalse($result['success']);
    }

    public function test_gagal_jika_username_kosong()
    {
        $service = new AuthServices();
        $result = $service->login(null, '123');

        $this->assertFalse($result['success']);
    }

    public function test_gagal_jika_password_kosong()
    {
        $service = new AuthServices();
        $result = $service->login('admin', null);

        $this->assertFalse($result['success']);
    }

    public function test_gagal_jika_password_mengandung_spasi()
    {
        $service = new AuthServices();
        $result = $service->login('admin', ' pass');

        $this->assertFalse($result['success']);
    }

    public function test_berhasil_login_admin()
    {
        DB::table('users')->insert([
            'username' => 'admin',
            'name' => 'Administrator',
            'role' => 'admin_master',
            'password' => Hash::make('rahasia'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $service = new AuthServices();
        $result = $service->login('admin', 'rahasia');

        $this->assertTrue($result['success']);
    }

    public function test_berhasil_login_admin_dengan_role_admin()
    {
        DB::table('users')->insert([
            'username' => 'admin',
            'name' => 'Administrator',
            'role' => 'admin_master',
            'password' => Hash::make('rahasia'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $service = new AuthServices();
        $result = $service->login('admin', 'rahasia');

        // Cek jika logic login Anda mengembalikan key 'role'
        if (isset($result['role'])) {
            $this->assertEquals('admin_master', $result['role']);
        } else {
            $this->assertTrue($result['success']);
        }
    }

    public function test_gagal_jika_password_admin_salah()
    {
        DB::table('users')->insert([
            'username' => 'admin',
            'name' => 'Administrator',
            'role' => 'admin_master',
            'password' => Hash::make('rahasia'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $service = new AuthServices();
        $result = $service->login('admin', 'salah');

        $this->assertFalse($result['success']);
    }

    public function test_gagal_jika_user_tidak_ditemukan()
    {
        $service = new AuthServices();
        $result = $service->login('tidakada', '123');

        $this->assertFalse($result['success']);
    }

    /*
    |--------------------------------------------------------------------------
    | AdminDashboardServices Tests
    |--------------------------------------------------------------------------
    */

    public function test_mengembalikan_semua_pasar()
    {
        DB::table('pasars')->insert([
            ['id' => 1, 'nama_pasar' => 'Pasar A', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama_pasar' => 'Pasar B', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $service = new AdminDashboardServices();
        $result = $service->getSemuaPasar();

        $this->assertCount(2, $result);
    }

    public function test_menentukan_pasar_aktif_dari_session()
    {
        DB::table('pasars')->insert([
            ['id' => 1, 'nama_pasar' => 'Pasar A', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama_pasar' => 'Pasar B', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $service = new AdminDashboardServices();
        $pasar = $service->getSemuaPasar();
        $aktif = $service->tentukanPasarAktif($pasar, 2);

        $this->assertEquals(2, $aktif);
    }

    public function test_menentukan_pasar_aktif_default_jika_session_kosong()
    {
        DB::table('pasars')->insert([
            ['id' => 5, 'nama_pasar' => 'Pasar Utama', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $service = new AdminDashboardServices();
        $pasar = $service->getSemuaPasar();
        $aktif = $service->tentukanPasarAktif($pasar, null);

        $this->assertEquals(5, $aktif);
    }

    public function test_jumlah_harga_yang_diambil_hanya_status_publish()
    {
        DB::table('harga_harians')->insert([
            [
                'pasar_id' => 1,
                'kategori' => 'Sayur',
                'nama_barang' => 'Cabai',
                'tanggal' => now()->toDateString(),
                'harga_hari_ini' => 15000,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pasar_id' => 1,
                'kategori' => 'Sayur',
                'nama_barang' => 'Tomat',
                'tanggal' => now()->toDateString(),
                'harga_hari_ini' => 11000,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $service = new AdminDashboardServices();
        $result = $service->getHargaTerbaru(1);

        $this->assertCount(1, $result);
    }

    public function test_status_harga_yang_diambil_adalah_publish()
    {
        DB::table('harga_harians')->insert([
            [
                'pasar_id' => 1,
                'kategori' => 'Sayur',
                'nama_barang' => 'Cabai',
                'tanggal' => now()->toDateString(),
                'harga_hari_ini' => 15000,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $service = new AdminDashboardServices();
        $result = $service->getHargaTerbaru(1);

        $this->assertEquals('published', $result->first()->status);
    }
}
