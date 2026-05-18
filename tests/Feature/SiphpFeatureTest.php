<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengaduan; 

class SiphpFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Login Admin (Sesuai AuthController)
     */
    public function test_login_admin_berhasil()
    {
        // 1. ARRANGE: Masukkan data manual ke tabel 'users'
        DB::table('users')->insert([
            'username' => 'admin',
            'name' => 'Administrator',
            'role' => 'admin_master',
            'password' => bcrypt('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. ACT: Lakukan Login
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        // 3. ASSERT:
        // Cek apakah Guard 'web' sudah login
        $this->assertTrue(Auth::check(), 'Admin gagal login.');
        
        // Pastikan redirect ke route admin.dashboard
        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * Test Pengaduan Gagal Nama Mengandung Angka
     */
    public function test_pengaduan_gagal_nama_angka()
    {
        // 1. ACT: Kirim data dengan nama invalid ke /pengaduan
        $response = $this->post('/pengaduan', [
            'nama' => 'Alya123', // INVALID: mengandung angka
            'pasar' => 'Pasar Lakessi',
            'nomor_telepon' => '082192518835',
            'kategori' => 'Pasar',
            'pesan' => 'Pasar senggol banyak sampahnya'
        ]);

        // 2. ASSERT:
        $this->assertNotEmpty(session('errors'), 'Tidak ada error validasi yang tertangkap!');
        $errors = session('errors')->get('nama');
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('hanya boleh huruf', $errors[0]);
    }

    /**
     * Test Simpan Pengaduan (Sesuai PengaduanController)
     */
    public function test_simpan_pengaduan_berhasil()
    {
        // 1. ACT: Kirim data valid
        $this->post('/pengaduan', [
            'nama' => 'Alya',
            'pasar' => 'Pasar Lakessi',
            'nomor_telepon' => '082192518835',
            'kategori' => 'Pasar',
            'pesan' => 'Pasar senggol banyak sampahnya'
        ]);

        // 2. ASSERT:
        // Cek database bertambah
        $this->assertCount(1, Pengaduan::all());

        // Cek data masuk
        $pengaduanTerbaru = Pengaduan::first();
        $this->assertNotNull($pengaduanTerbaru);
        $this->assertEquals('Alya', $pengaduanTerbaru->nama);
    }
    
    public function test_file_exists_check()
    {
        $this->assertFileExists(base_path('.env'));
    }
}