@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
:root { --g:#d0f0c0; --gd:#2d6a4f; --gdd:#1e3a2f; --border:#d1e8d8; --text:#1a3a2a; --sub:#5a8a6a; }
.form-inp { width:100%; padding:12px 14px; border-radius:12px; font-size:14px; border:1.5px solid var(--border); background:#f8fdf9; color:var(--text); outline:none; transition:border-color 0.2s, box-shadow 0.2s; font-family:'Plus Jakarta Sans',sans-serif; }
.form-inp:focus { border-color:var(--gd); box-shadow:0 0 0 3px rgba(45,106,79,0.1); background:white; }
.form-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:var(--sub); display:block; margin-bottom:7px; }
</style>

<div style="min-height:100vh;background:#f0faf4;padding:32px;">
<div style="max-width:600px;margin:0 auto;">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:28px;">
        <a href="{{ route('admin_master.kelola.admin_pasar') }}"
            style="background:var(--g);color:var(--gdd);width:36px;height:36px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:14px;flex-shrink:0;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 style="font-size:22px;font-weight:800;color:var(--text);margin:0;">Tambah Admin Pasar</h1>
            <p style="font-size:13px;color:var(--sub);margin:3px 0 0;">Buat akun baru untuk mengelola harga di satu pasar</p>
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

    <div style="background:white;border:1.5px solid var(--border);border-radius:20px;padding:28px;box-shadow:0 4px 20px rgba(45,106,79,0.07);">

        <form action="{{ route('admin_master.kelola.admin_pasar.store') }}" method="POST">
            @csrf

            {{-- Pilih Pasar --}}
            <div style="margin-bottom:18px;">
                <label class="form-label">Pasar yang Dikelola <span style="color:#ef4444;">*</span></label>
                <select name="pasar_id" required class="form-inp">
                    <option value="" disabled {{ old('pasar_id') ? '' : 'selected' }}>— Pilih pasar —</option>
                    @foreach($pasarTersedia as $p)
                    <option value="{{ $p->id }}" {{ old('pasar_id', request('pasar')) == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_pasar }}
                    </option>
                    @endforeach
                </select>
                @if($pasarTersedia->isEmpty())
                <p style="font-size:12px;color:#b45309;margin-top:5px;">
                    <i class="fas fa-info-circle"></i> Semua pasar sudah memiliki admin.
                </p>
                @endif
            </div>

            {{-- Nama --}}
            <div style="margin-bottom:18px;">
                <label class="form-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                <div style="position:relative;">
                    <i class="fas fa-user" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#a3c4aa;font-size:13px;pointer-events:none;"></i>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Contoh: Ahmad Fauzan" class="form-inp" style="padding-left:40px;">
                </div>
            </div>

            {{-- Username --}}
            <div style="margin-bottom:18px;">
                <label class="form-label">Username <span style="color:#ef4444;">*</span></label>
                <div style="position:relative;">
                    <i class="fas fa-at" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#a3c4aa;font-size:13px;pointer-events:none;"></i>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        placeholder="Contoh: admin.lakessi" class="form-inp" style="padding-left:40px;">
                </div>
                <p style="font-size:11px;color:var(--sub);margin-top:5px;">
                    <i class="fas fa-info-circle"></i> Disarankan format: admin.namapasar
                </p>
            </div>

            {{-- Password --}}
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
                <a href="{{ route('admin_master.kelola.admin_pasar') }}"
                    style="background:#f0f0f0;color:#666;text-decoration:none;border-radius:12px;padding:12px 22px;font-weight:700;font-size:13px;">
                    Batal
                </a>
                <button type="submit" {{ $pasarTersedia->isEmpty() ? 'disabled' : '' }}
                    style="background:linear-gradient(135deg,var(--gdd),var(--gd));color:white;border:none;border-radius:12px;padding:12px 28px;font-weight:700;font-size:13px;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;box-shadow:0 4px 14px rgba(45,106,79,0.25);{{ $pasarTersedia->isEmpty() ? 'opacity:0.5;cursor:not-allowed;' : '' }}">
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
