@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
:root { --g:#d0f0c0; --gd:#2d6a4f; --gdd:#1e3a2f; --border:#d1e8d8; --text:#1a3a2a; --sub:#5a8a6a; }
.form-inp { width:100%; padding:12px 14px; border-radius:12px; font-size:14px; border:1.5px solid var(--border); background:#f8fdf9; color:var(--text); outline:none; transition:border-color 0.2s, box-shadow 0.2s; font-family:'Plus Jakarta Sans',sans-serif; }
.form-inp:focus { border-color:var(--gd); box-shadow:0 0 0 3px rgba(45,106,79,0.1); }
.form-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:var(--sub); display:block; margin-bottom:7px; }
</style>

<div style="min-height:100vh;background:#f0faf4;padding:32px;">
<div style="max-width:600px;margin:0 auto;">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:28px;">
        <a href="{{ route('admin_master.kelola.kepala_dinas') }}"
            style="background:var(--g);color:var(--gdd);width:36px;height:36px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:14px;flex-shrink:0;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 style="font-size:22px;font-weight:800;color:var(--text);margin:0;">Tambah Kepala Dinas / Kasubag</h1>
            <p style="font-size:13px;color:var(--sub);margin:3px 0 0;">Akses: laporan & rekomendasi harga saja</p>
        </div>
    </div>

    @if($errors->any())
    <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #ef4444;border-radius:12px;padding:12px 16px;margin-bottom:20px;">
        <div style="font-weight:700;color:#dc2626;font-size:13px;margin-bottom:5px;">Periksa kembali:</div>
        @foreach($errors->all() as $e)
        <p style="font-size:12px;color:#dc2626;margin:2px 0;">• {{ $e }}</p>
        @endforeach
    </div>
    @endif

    {{-- Info hak akses --}}
    <div style="background:linear-gradient(135deg,#ede9fe,#f5f3ff);border:1.5px solid #ddd6fe;border-radius:14px;padding:16px 18px;margin-bottom:20px;display:flex;gap:12px;align-items:flex-start;">
        <i class="fas fa-shield-halved" style="color:#7c3aed;font-size:18px;margin-top:2px;flex-shrink:0;"></i>
        <div>
            <div style="font-size:13px;font-weight:700;color:#5b21b6;margin-bottom:4px;">Hak Akses Role Ini</div>
            <ul style="margin:0;padding-left:16px;font-size:12px;color:#6d28d9;line-height:1.8;">
                <li>Melihat rekomendasi harga optimal dari semua pasar</li>
                <li>Mengunduh laporan harga (PDF & Excel)</li>
                <li>Tidak bisa input atau ubah data harga</li>
                <li>Tidak bisa kelola akun pengguna lain</li>
            </ul>
        </div>
    </div>

    <div style="background:white;border:1.5px solid var(--border);border-radius:20px;padding:28px;box-shadow:0 4px 20px rgba(45,106,79,0.07);">
        <form action="{{ route('admin_master.kelola.kepala_dinas.store') }}" method="POST">
            @csrf

            <div style="margin-bottom:18px;">
                <label class="form-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                <div style="position:relative;">
                    <i class="fas fa-user-tie" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#a3c4aa;font-size:13px;pointer-events:none;"></i>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Contoh: Drs. H. Ahmad Yani, M.Si" class="form-inp" style="padding-left:40px;">
                </div>
            </div>

            <div style="margin-bottom:18px;">
                <label class="form-label">Username <span style="color:#ef4444;">*</span></label>
                <div style="position:relative;">
                    <i class="fas fa-at" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#a3c4aa;font-size:13px;pointer-events:none;"></i>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        placeholder="Contoh: kepaladinas2" class="form-inp" style="padding-left:40px;">
                </div>
            </div>

            <div style="margin-bottom:24px;">
                <label class="form-label">Password <span style="color:#ef4444;">*</span></label>
                <div style="position:relative;">
                    <i class="fas fa-lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#a3c4aa;font-size:13px;pointer-events:none;"></i>
                    <input type="password" name="password" required minlength="6" id="pw"
                        placeholder="Minimal 6 karakter" class="form-inp" style="padding-left:40px;padding-right:42px;">
                    <button type="button" onclick="togglePw()"
                        style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#a3c4aa;font-size:13px;">
                        <i class="fas fa-eye" id="pwEye"></i>
                    </button>
                </div>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <a href="{{ route('admin_master.kelola.kepala_dinas') }}"
                    style="background:#f0f0f0;color:#666;text-decoration:none;border-radius:12px;padding:12px 22px;font-weight:700;font-size:13px;">
                    Batal
                </a>
                <button type="submit"
                    style="background:linear-gradient(135deg,var(--gdd),var(--gd));color:white;border:none;border-radius:12px;padding:12px 28px;font-weight:700;font-size:13px;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;box-shadow:0 4px 14px rgba(45,106,79,0.25);">
                    <i class="fas fa-user-plus" style="margin-right:8px;"></i>Buat Akun
                </button>
            </div>
        </form>
    </div>
</div>
</div>

<script>
function togglePw() {
    const inp = document.getElementById('pw');
    const ico = document.getElementById('pwEye');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    ico.className = inp.type === 'text' ? 'fas fa-eye-slash' : 'fas fa-eye';
}
</script>
@endsection
