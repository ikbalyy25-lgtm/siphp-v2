<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PublicHargaController;

// ============================================================
//  ROUTES SIPHP v2
//  Role: admin_master | kepala_dinas | admin_pasar
//  Alur baru:
//  - Admin Pasar input 3 harga pedagang → rata-rata otomatis
//  - Masuk antrian (pending) di Admin Master
//  - Admin Master approve → published → tampil ke publik
//  - Rekomendasi harga optimal = analisis disparitas antar pasar
//  - Fitur AI (prediksi) dihapus
// ============================================================


// ──────────────────────────────────────────────────────────
//  1. PUBLIK (Masyarakat — tidak perlu login)
// ──────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/info-harga/{kategori}/{pasar_id}', [PublicHargaController::class, 'show'])
    ->name('harga.public.show');

Route::get('/pengaduan',          [PengaduanController::class, 'index'])->name('pengaduan');
Route::post('/pengaduan',         [PengaduanController::class, 'store'])->name('pengaduan.submit');
Route::get('/pengaduan/berhasil', [PengaduanController::class, 'berhasil'])->name('pengaduan.berhasil');


// ──────────────────────────────────────────────────────────
//  2. AUTH
// ──────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');

    Route::get('/forgot-password',         [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password',        [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}',  [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password',         [AuthController::class, 'processResetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ──────────────────────────────────────────────────────────
//  3. ADMIN MASTER
// ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin_master'])
    ->prefix('admin')
    ->name('admin_master.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard',        [App\Http\Controllers\AdminMaster\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/ganti-pasar/{id}', [App\Http\Controllers\AdminMaster\DashboardController::class, 'setPasar'])->name('gantiPasar');

    // ── ANTRIAN: approve / tolak input dari admin pasar ──
    Route::get('/antrian',                   [App\Http\Controllers\AdminMaster\AntrianController::class, 'index'])->name('antrian.index');
    Route::post('/antrian/{id}/approve',     [App\Http\Controllers\AdminMaster\AntrianController::class, 'approve'])->name('antrian.approve');
    Route::delete('/antrian/{id}/tolak',     [App\Http\Controllers\AdminMaster\AntrianController::class, 'tolak'])->name('antrian.tolak');
    Route::post('/antrian/approve-all',      [App\Http\Controllers\AdminMaster\AntrianController::class, 'approveAll'])->name('antrian.approveAll');

    // ── REKOMENDASI HARGA OPTIMAL (fitur utama) ──
    Route::get('/rekomendasi',           [App\Http\Controllers\AdminMaster\RekomendasiController::class, 'index'])->name('rekomendasi.index');
    Route::post('/rekomendasi/analyze',  [App\Http\Controllers\AdminMaster\RekomendasiController::class, 'analyze'])->name('rekomendasi.analyze');
    Route::get('/rekomendasi/komparasi', [App\Http\Controllers\AdminMaster\RekomendasiController::class, 'komparasi'])->name('rekomendasi.komparasi');

    // Statistik
    Route::get('/statistik/pasar/{id_pasar}',             [App\Http\Controllers\Admin\StatistikController::class, 'pilihKategori'])->name('statistik.pilihKategori');
    Route::get('/statistik/grafik/{id_pasar}/{kategori}', [App\Http\Controllers\Admin\StatistikController::class, 'showGrafik'])->name('statistik.grafik');
    Route::get('/statistik/api/{id_pasar}/{kategori}/{nama_barang}', [App\Http\Controllers\Admin\StatistikController::class, 'getApiData'])->name('statistik.api');

    // Manajemen Ritel
    Route::get('/retail',         [App\Http\Controllers\AdminRetailController::class, 'index'])->name('retail.index');
    Route::get('/retail/create',  [App\Http\Controllers\AdminRetailController::class, 'create'])->name('retail.create');
    Route::post('/retail',        [App\Http\Controllers\AdminRetailController::class, 'store'])->name('retail.store');
    Route::delete('/retail/{id}', [App\Http\Controllers\AdminRetailController::class, 'destroy'])->name('retail.destroy');

    // Pengaduan
    Route::get('/pengaduan',         [App\Http\Controllers\AdminMaster\PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::delete('/pengaduan/{id}', [App\Http\Controllers\AdminMaster\PengaduanController::class, 'destroy'])->name('pengaduan.destroy');

    // Laporan
    Route::get('/laporan', [App\Http\Controllers\AdminMaster\LaporanController::class, 'download'])->name('laporan.download');

    // Kelola Akun Admin Pasar
    Route::get('/kelola-admin-pasar',        [App\Http\Controllers\AdminMaster\KelolaAkunController::class, 'indexAdminPasar'])->name('kelola.admin_pasar');
    Route::get('/kelola-admin-pasar/create', [App\Http\Controllers\AdminMaster\KelolaAkunController::class, 'createAdminPasar'])->name('kelola.admin_pasar.create');
    Route::post('/kelola-admin-pasar',       [App\Http\Controllers\AdminMaster\KelolaAkunController::class, 'storeAdminPasar'])->name('kelola.admin_pasar.store');
    Route::delete('/kelola-admin-pasar/{id}',[App\Http\Controllers\AdminMaster\KelolaAkunController::class, 'destroyAdminPasar'])->name('kelola.admin_pasar.destroy');
    Route::post('/kelola-admin-pasar/{id}/reset-password', [App\Http\Controllers\AdminMaster\KelolaAkunController::class, 'resetPasswordAdminPasar'])->name('kelola.admin_pasar.resetPassword');

    // Kelola Akun Kepala Dinas
    Route::get('/kelola-kepala-dinas',        [App\Http\Controllers\AdminMaster\KelolaAkunController::class, 'indexKepalaDinas'])->name('kelola.kepala_dinas');
    Route::get('/kelola-kepala-dinas/create', [App\Http\Controllers\AdminMaster\KelolaAkunController::class, 'createKepalaDinas'])->name('kelola.kepala_dinas.create');
    Route::post('/kelola-kepala-dinas',       [App\Http\Controllers\AdminMaster\KelolaAkunController::class, 'storeKepalaDinas'])->name('kelola.kepala_dinas.store');
    Route::delete('/kelola-kepala-dinas/{id}',[App\Http\Controllers\AdminMaster\KelolaAkunController::class, 'destroyKepalaDinas'])->name('kelola.kepala_dinas.destroy');
});


// ──────────────────────────────────────────────────────────
//  4. KEPALA DINAS
// ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:kepala_dinas'])
    ->prefix('kepala-dinas')
    ->name('kepala_dinas.')
    ->group(function () {

    Route::get('/dashboard',    [App\Http\Controllers\KepalaDinas\DashboardController::class, 'index'])->name('dashboard');

    // ── REKOMENDASI HARGA (fitur utama kepala dinas) ──
    Route::get('/rekomendasi',          [App\Http\Controllers\KepalaDinas\RekomendasiController::class, 'index'])->name('rekomendasi');
    Route::post('/rekomendasi/analyze', [App\Http\Controllers\KepalaDinas\RekomendasiController::class, 'analyze'])->name('rekomendasi.analyze');

    Route::get('/laporan',      [App\Http\Controllers\KepalaDinas\DashboardController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/unduh',[App\Http\Controllers\KepalaDinas\DashboardController::class, 'unduh'])->name('laporan.unduh');
});


// ──────────────────────────────────────────────────────────
//  5. ADMIN PASAR
// ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin_pasar'])
    ->prefix('admin-pasar')
    ->name('admin_pasar.')
    ->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\AdminPasar\DashboardController::class, 'index'])->name('dashboard');

    // ── INPUT 3 HARGA PEDAGANG → rata-rata otomatis → antrian ──
    Route::get('/harga/{kategori}',       [App\Http\Controllers\AdminPasar\HargaController::class, 'index'])->name('harga.index');
    Route::get('/harga/{kategori}/input', [App\Http\Controllers\AdminPasar\HargaController::class, 'create'])->name('harga.create');
    Route::post('/harga',                 [App\Http\Controllers\AdminPasar\HargaController::class, 'store'])->name('harga.store');
    Route::delete('/harga/{id}',          [App\Http\Controllers\AdminPasar\HargaController::class, 'destroy'])->name('harga.destroy');

    // Statistik pasar sendiri
    Route::get('/statistik/{kategori}',                   [App\Http\Controllers\AdminPasar\StatistikController::class, 'index'])->name('statistik.index');
    Route::get('/statistik/api/{kategori}/{nama_barang}', [App\Http\Controllers\AdminPasar\StatistikController::class, 'getApiData'])->name('statistik.api');
});


// ──────────────────────────────────────────────────────────
//  ALIAS KOMPATIBILITAS — view lama pakai admin.* 
// ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin_master'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard',           [App\Http\Controllers\AdminMaster\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/ganti-pasar/{id}',    [App\Http\Controllers\AdminMaster\DashboardController::class, 'setPasar'])->name('gantiPasar');
    Route::get('/antrian',             [App\Http\Controllers\AdminMaster\AntrianController::class, 'index'])->name('antrian.index');
    Route::post('/antrian/{id}/approve', [App\Http\Controllers\AdminMaster\AntrianController::class, 'approve'])->name('antrian.approve');
    Route::delete('/antrian/{id}/tolak', [App\Http\Controllers\AdminMaster\AntrianController::class, 'tolak'])->name('antrian.tolak');
    Route::post('/antrian/approve-all',  [App\Http\Controllers\AdminMaster\AntrianController::class, 'approveAll'])->name('antrian.approveAll');
    Route::get('/rekomendasi',         [App\Http\Controllers\AdminMaster\RekomendasiController::class, 'index'])->name('rekomendasi.index');
    Route::post('/rekomendasi/analyze', [App\Http\Controllers\AdminMaster\RekomendasiController::class, 'analyze'])->name('rekomendasi.analyze');
    Route::get('/harga/{kategori}',    [App\Http\Controllers\AdminMaster\HargaController::class, 'index'])->name('harga.index');
    Route::get('/harga/{kategori}/input', [App\Http\Controllers\AdminMaster\HargaController::class, 'create'])->name('harga.create');
    Route::post('/harga',              [App\Http\Controllers\AdminMaster\HargaController::class, 'store'])->name('harga.store');
    Route::delete('/harga/{id}',       [App\Http\Controllers\AdminMaster\HargaController::class, 'destroy'])->name('harga.destroy');
    Route::get('/statistik/pasar/{id_pasar}',             [App\Http\Controllers\Admin\StatistikController::class, 'pilihKategori'])->name('statistik.pilihKategori');
    Route::get('/statistik/grafik/{id_pasar}/{kategori}', [App\Http\Controllers\Admin\StatistikController::class, 'showGrafik'])->name('statistik.grafik');
    Route::get('/retail',         [App\Http\Controllers\AdminRetailController::class, 'index'])->name('retail.index');
    Route::get('/retail/create',  [App\Http\Controllers\AdminRetailController::class, 'create'])->name('retail.create');
    Route::post('/retail',        [App\Http\Controllers\AdminRetailController::class, 'store'])->name('retail.store');
    Route::delete('/retail/{id}', [App\Http\Controllers\AdminRetailController::class, 'destroy'])->name('retail.destroy');
    Route::get('/pengaduan',         [App\Http\Controllers\AdminMaster\PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::delete('/pengaduan/{id}', [App\Http\Controllers\AdminMaster\PengaduanController::class, 'destroy'])->name('pengaduan.destroy');
    Route::get('/laporan',           [App\Http\Controllers\AdminMaster\LaporanController::class, 'download'])->name('laporan.download');
    Route::get('/pedagang',          function() { return redirect()->route('admin.dashboard')->with('info','Fitur ini digantikan oleh Kelola Akun.'); })->name('pedagang.index');
    Route::get('/pedagang/{id}',     function() { return redirect()->route('admin.dashboard'); })->name('pedagang.show');
    Route::post('/pedagang/{id}/pub',function() { return back(); })->name('pedagang.publish');
    Route::get('/pengajuan',         function() { return redirect()->route('admin.dashboard')->with('info','Fitur pengajuan dihapus. Gunakan Kelola Akun.'); })->name('pengajuan.index');
    Route::get('/pengajuan/{id}/verify',   function() { return redirect()->route('admin.dashboard'); })->name('pengajuan.verify');
    Route::post('/pengajuan/{id}/approve', function() { return back(); })->name('pengajuan.approve');
    Route::delete('/pengajuan/{id}',       function() { return back(); })->name('pengajuan.destroy');
});
