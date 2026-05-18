# Panduan Implementasi Perombakan User System SIPHP
**Dari: Multi-guard (admin + pedagang) → Single-guard dengan Role System**

---

## Ringkasan Perubahan Arsitektur

| Role Lama | Role Baru |
|-----------|-----------|
| Admin (semua akses) | **Admin Master** — semua fitur + kelola akun |
| Pedagang | **Dihapus** |
| *(baru)* | **Kepala Dinas/Kasubag** — laporan & rekomendasi |
| *(baru)* | **Admin Pasar** — input harga per pasar (5 akun) |
| Umum | **Umum** — tetap, tanpa login |

---

## File yang Harus Diganti / Ditambah

### 1. CONFIG
**Ganti** `config/auth.php` dengan file baru (single guard `web`, model `User`)

---

### 2. MODEL
**Ganti** `app/Models/User.php` dengan model baru yang punya method role helper:
- `isAdminMaster()` / `isKepalaDinas()` / `isAdminPasar()`
- Relasi `pasar()` untuk admin pasar

**Hapus** model-model lama:
```
app/Models/Admin.php
app/Models/Pedagang.php
```

---

### 3. MIGRATION

**Jalankan** migration baru untuk tabel `users`:
```
database/migrations/2026_01_01_000001_create_users_table.php
```

**Catatan penting:** Tabel `admins` dan `pedagangs` akan **tidak dipakai** setelah ini.
Jalankan `php artisan migrate:fresh --seed` untuk mulai dari awal.

---

### 4. MIDDLEWARE

**Tambah** file baru:
```
app/Http/Middleware/RoleMiddleware.php
```

**Daftarkan** di `bootstrap/app.php` (Laravel 12) atau `app/Http/Kernel.php` (Laravel sebelum 12):

**Untuk Laravel 12** — tambahkan di `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

**Untuk Laravel 9-11** — tambahkan di `app/Http/Kernel.php`, bagian `$routeMiddleware`:
```php
'role' => \App\Http\Middleware\RoleMiddleware::class,
```

---

### 5. CONTROLLER

**Ganti:**
```
app/Http/Controllers/AuthController.php
```

**Tambah folder dan file baru:**
```
app/Http/Controllers/AdminMaster/KelolaAkunController.php
app/Http/Controllers/AdminPasar/DashboardController.php
app/Http/Controllers/AdminPasar/HargaController.php
app/Http/Controllers/KepalaDinas/DashboardController.php
```

**Hapus:**
```
app/Http/Controllers/Admin/ (semua file lama yang pakai Auth::guard('admin'))
app/Http/Controllers/Pedagang/ (semua file terkait pedagang)
app/Http/Controllers/BuatAkunController.php
```

---

### 6. ROUTES

**Ganti seluruh** `routes/web.php` dengan file baru.

Struktur route baru:
```
/                    → Publik (umum)
/login               → Auth (semua role)
/admin/...           → Admin Master (middleware: role:admin_master)
/kepala-dinas/...    → Kepala Dinas (middleware: role:kepala_dinas)
/admin-pasar/...     → Admin Pasar (middleware: role:admin_pasar)
```

---

### 7. VIEWS

**Tambah folder dan file baru:**
```
resources/views/admin_pasar/
    dashboard.blade.php
    harga/index.blade.php
    harga/create.blade.php

resources/views/kepala_dinas/
    dashboard.blade.php
    rekomendasi.blade.php
    laporan.blade.php

resources/views/admin_master/kelola/
    admin_pasar.blade.php
    create_admin_pasar.blade.php
    kepala_dinas.blade.php
    create_kepala_dinas.blade.php
```

**Update** `resources/views/admin/dashboard.blade.php`:
Tambahkan menu "Kelola Akun" di sidebar sebelum tombol Keluar:
```html
<div class="nav-section-label">Kelola Akun</div>
<a href="{{ route('admin_master.kelola.admin_pasar') }}" class="nav-item">
    <i class="fas fa-store"></i> Admin Pasar
</a>
<a href="{{ route('admin_master.kelola.kepala_dinas') }}" class="nav-item">
    <i class="fas fa-user-tie"></i> Kepala Dinas
</a>
```

**Hapus:**
```
resources/views/Pedagang/ (semua)
resources/views/buatakun.blade.php
resources/views/berhasilpengajuan.blade.php
```

---

### 8. SEEDER

**Ganti:**
```
database/seeders/DatabaseSeeder.php
database/seeders/HargaHarianSeeder.php
```

---

## Urutan Implementasi

```bash
# Langkah 1 — Ganti semua file sesuai daftar di atas

# Langkah 2 — Daftarkan middleware RoleMiddleware
# (edit bootstrap/app.php atau Kernel.php)

# Langkah 3 — Reset database dan jalankan seeder
php artisan migrate:fresh --seed

# Langkah 4 — Bersihkan cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Langkah 5 — Jalankan server
php artisan serve
```

---

## Akun Default Setelah Seeder

| Role | Username | Password | Keterangan |
|------|----------|----------|------------|
| Admin Master | `admin` | `admin123` | Akses penuh |
| Kepala Dinas | `kepaladinas` | `dinas123` | Laporan & rekomendasi |
| Admin Lakessi | `admin.lakessi` | `pasar123` | Pasar Lakessi |
| Admin Senggol | `admin.senggol` | `pasar123` | Pasar Senggol |
| Admin Labukkang | `admin.labukkang` | `pasar123` | Pasar Labukkang |
| Admin Sumpang | `admin.sumpang` | `pasar123` | Pasar Sumpang Minangae |
| Admin Wekkee | `admin.wekkee` | `pasar123` | Pasar Wekkee |

---

## Hak Akses Per Role

### Admin Master
- Dashboard + semua statistik
- Kelola harga semua pasar
- Rekomendasi harga & komparasi pasar
- Manajemen ritel
- Kelola pengaduan
- Unduh laporan
- **Kelola akun Admin Pasar** (buat, hapus, reset password)
- **Kelola akun Kepala Dinas** (buat, hapus)

### Kepala Dinas / Kasubag
- Dashboard ringkasan statistik
- Rekomendasi harga optimal
- Unduh laporan (PDF & Excel)
- **Tidak bisa** input/ubah data harga
- **Tidak bisa** kelola akun

### Admin Pasar
- Dashboard pasar sendiri
- Input harga (pokok, subsidi, penting) untuk pasarnya saja
- Toggle status pending/update
- **Tidak bisa** akses data pasar lain
- **Tidak bisa** kelola akun

### Umum (Masyarakat)
- Lihat harga publik per pasar
- Kirim pengaduan
- Lihat informasi ritel
- **Tidak perlu** login

---

## Catatan Teknis

1. **Tabel `admins` dan `pedagangs`** — tidak dipakai lagi, bisa dibiarkan atau dihapus manual dari database.

2. **Guard lama** (`auth:admin`, `auth:pedagang`) — semua middleware lama di routes harus diganti ke `auth` + `role:nama_role`.

3. **Relasi data harga** — kolom `pedagang_id` di tabel `harga_harians` tidak terpakai lagi, bisa diisi `null`.

4. **Model User** — karena sekarang pakai satu model, `Auth::user()` otomatis mengembalikan User dengan property `role` dan `pasar_id`.
