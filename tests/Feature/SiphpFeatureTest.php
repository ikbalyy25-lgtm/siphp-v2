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
        // 1. ARRANGE: Masukkan data manual ke tabel 'admins'
        // Karena controller pakai DB::table('admins'), bukan Model User.
        DB::table('admins')->insert([
            'username' => 'admin',
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
        // Cek apakah Guard 'admin' sudah login
        $this->assertTrue(Auth::guard('admin')->check(), 'Admin gagal login. Cek konfigurasi auth.php atau database.');
        
        // Pastikan redirect ke route dashboard (sesuai controller: return redirect()->route('dashboard'))
        $response->assertRedirect(route('dashboard'));
    }

    /**
     * Test Buat Akun (Sesuai BuatAkunController)
     */
    public function test_ajukan_akun_gagal_nama_kosong()
    {
        // 1. ACT: Kirim data ke /buatakun/submit
        $response = $this->post('/buatakun/submit', [
            'nama' => '', // KOSONGKAN INI (Sesuai validasi controller: 'nama')
            'email' => 'alya@gmail.com',
            'jenis_barang' => 'Sayur',
            'kontak' => '082192518835',
            'lokasi_penjualan' => 'Pasar Lakessi'
        ]);

        // 2. ASSERT:
        // Cek session error tidak kosong
        $this->assertNotEmpty(session('errors'), 'Tidak ada error validasi yang tertangkap!');
        
        // Ambil pesan error untuk field 'nama' (BUKAN nama_pedagang)
        $errors = session('errors')->get('nama');
        
        // Pastikan validasi error muncul untuk field 'nama'
        $this->assertNotEmpty($errors, 'Field "nama" tidak error, pastikan nama field di test sama dengan controller.');

        // Cek pesan error mengandung kata "wajib diisi"
        $this->assertStringContainsString('wajib diisi', $errors[0]);
    }

    /**
     * Test Simpan Pengaduan (Sudah Benar)
     */
    public function test_simpan_pengaduan_berhasil()
    {
        // 1. ACT: Kirim data
        $this->post('/pengaduan', [
            'nama' => 'Alya',
            'email' => 'alya@gmail.com',
            'nomor_telepon' => '082192518835', // Sesuai Controller
            'kategori' => 'Pasar',             // Sesuai Controller
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