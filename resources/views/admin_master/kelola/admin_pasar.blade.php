@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
:root { --g:#d0f0c0; --gd:#2d6a4f; --gdd:#1e3a2f; --border:#d1e8d8; --text:#1a3a2a; --sub:#5a8a6a; }
.admin-card { background:white; border:1.5px solid var(--border); border-radius:16px; padding:20px 22px; transition:transform 0.2s,box-shadow 0.2s; }
.admin-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(45,106,79,0.1); }
.inp { width:100%; padding:11px 14px; border-radius:11px; font-size:14px; border:1.5px solid var(--border); background:#f8fdf9; color:var(--text); outline:none; transition:border-color 0.2s; font-family:'Plus Jakarta Sans',sans-serif; }
.inp:focus { border-color:var(--gd); box-shadow:0 0 0 3px rgba(45,106,79,0.1); }
.modal-overlay { display:none; position:fixed; inset:0; z-index:60; background:rgba(30,58,47,0.45); backdrop-filter:blur(5px); align-items:center; justify-content:center; }
.modal-overlay.open { display:flex; }
@keyframes popIn { from{transform:scale(0.9);opacity:0} to{transform:scale(1);opacity:1} }
</style>

<div style="min-height:100vh;background:#f0faf4;padding:32px;">
<div style="max-width:1000px;margin:0 auto;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:5px;">
                <a href="{{ route('admin.dashboard') }}" style="background:var(--g);color:var(--gdd);width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 style="font-size:22px;font-weight:800;color:var(--text);">Kelola Admin Pasar</h1>
            </div>
            <p style="font-size:13px;color:var(--sub);margin-left:42px;">1 akun per pasar · Maksimal 5 admin pasar</p>
        </div>
        <a href="{{ route('admin_master.kelola.admin_pasar.create') }}"
            style="background:linear-gradient(135deg,var(--gdd),var(--gd));color:white;text-decoration:none;border-radius:12px;padding:11px 22px;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 14px rgba(45,106,79,0.25);">
            <i class="fas fa-plus"></i> Tambah Admin Pasar
        </a>
    </div>

    @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #22c55e;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#15803d;display:flex;align-items:center;justify-content:space-between;">
        <span><i class="fas fa-check-circle" style="margin-right:8px;"></i>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#15803d;">✕</button>
    </div>
    @endif
    @if(session('error'))
    <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #ef4444;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#dc2626;">
        <i class="fas fa-exclamation-circle" style="margin-right:8px;"></i>{{ session('error') }}
    </div>
    @endif

    {{-- Progress Pasar --}}
    <div style="background:white;border:1.5px solid var(--border);border-radius:16px;padding:18px 22px;margin-bottom:20px;display:flex;align-items:center;gap:16px;">
        <div style="flex:1;">
            <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                <span style="font-size:13px;font-weight:700;color:var(--text);">Pasar Terisi Admin</span>
                <span style="font-size:13px;font-weight:800;color:var(--gd);">{{ $admins->count() }} / 5</span>
            </div>
            <div style="background:#e8f5ee;border-radius:999px;height:8px;overflow:hidden;">
                <div style="background:linear-gradient(90deg,var(--gdd),var(--gd));height:100%;border-radius:999px;width:{{ ($admins->count()/5)*100 }}%;transition:width 0.5s;"></div>
            </div>
        </div>
    </div>

    {{-- Daftar Admin Pasar --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        @foreach($pasars as $p)
        @php $adminPasar = $admins->firstWhere('pasar_id', $p->id); @endphp
        <div class="admin-card">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                <div style="width:42px;height:42px;border-radius:12px;background:{{ $adminPasar ? 'var(--g)' : '#f0f0f0' }};color:{{ $adminPasar ? 'var(--gdd)' : '#999' }};display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">
                    <i class="fas fa-store"></i>
                </div>
                <div>
                    <div style="font-weight:800;color:var(--text);font-size:15px;">{{ $p->nama_pasar }}</div>
                    <div style="font-size:11px;color:var(--sub);">{{ $p->alamat }}</div>
                </div>
                @if($adminPasar)
                <span style="margin-left:auto;background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0;border-radius:999px;padding:3px 10px;font-size:10px;font-weight:700;white-space:nowrap;">
                    ✓ Ada Admin
                </span>
                @else
                <span style="margin-left:auto;background:#fef9c3;color:#b45309;border:1px solid #fde68a;border-radius:999px;padding:3px 10px;font-size:10px;font-weight:700;white-space:nowrap;">
                    Belum Ada
                </span>
                @endif
            </div>

            @if($adminPasar)
            <div style="background:#f8fdf9;border-radius:12px;padding:12px 14px;margin-bottom:12px;">
                <div style="font-size:12px;font-weight:700;color:var(--text);margin-bottom:4px;">
                    <i class="fas fa-user" style="color:var(--gd);margin-right:6px;"></i>{{ $adminPasar->name }}
                </div>
                <div style="font-size:11px;color:var(--sub);">
                    <i class="fas fa-at" style="margin-right:6px;"></i>{{ $adminPasar->username }}
                </div>
            </div>
            <div style="display:flex;gap:8px;">
                <button onclick="openReset('{{ $adminPasar->id }}','{{ addslashes($adminPasar->name) }}')"
                    style="flex:1;background:#f0faf4;border:1.5px solid var(--border);color:var(--gd);border-radius:9px;padding:8px;font-size:12px;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">
                    <i class="fas fa-key" style="margin-right:5px;"></i>Reset Password
                </button>
                <button onclick="confirmHapus('{{ $adminPasar->id }}','{{ addslashes($adminPasar->name) }}')"
                    style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;border-radius:9px;padding:8px 14px;font-size:12px;cursor:pointer;">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            @else
            <a href="{{ route('admin_master.kelola.admin_pasar.create') }}?pasar={{ $p->id }}"
                style="display:flex;align-items:center;justify-content:center;gap:6px;background:var(--g);color:var(--gdd);text-decoration:none;border-radius:10px;padding:10px;font-size:13px;font-weight:700;">
                <i class="fas fa-plus"></i> Tambah Admin
            </a>
            @endif
        </div>
        @endforeach
    </div>
</div>
</div>

{{-- Modal Reset Password --}}
<div class="modal-overlay" id="resetModal">
    <div style="background:white;border-radius:20px;padding:28px;width:400px;box-shadow:0 32px 64px rgba(30,58,47,0.2);border-top:4px solid var(--gd);animation:popIn 0.25s ease;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
            <div>
                <div style="font-size:17px;font-weight:800;color:var(--text);">Reset Password</div>
                <div id="resetNama" style="font-size:13px;color:var(--gd);font-weight:600;margin-top:2px;"></div>
            </div>
            <button onclick="closeReset()" style="background:#f0f0f0;border:none;width:30px;height:30px;border-radius:8px;cursor:pointer;">✕</button>
        </div>
        <form id="resetForm" method="POST">
            @csrf
            <div style="margin-bottom:14px;">
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--sub);display:block;margin-bottom:6px;">Password Baru</label>
                <input type="password" name="password" required minlength="6" class="inp" placeholder="Minimal 6 karakter">
            </div>
            <div style="margin-bottom:20px;">
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--sub);display:block;margin-bottom:6px;">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="inp" placeholder="Ketik ulang password">
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeReset()" style="background:#f0f0f0;border:none;border-radius:10px;padding:10px 20px;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">Batal</button>
                <button type="submit" style="background:linear-gradient(135deg,var(--gdd),var(--gd));color:white;border:none;border-radius:10px;padding:10px 22px;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal-overlay" id="hapusModal">
    <div style="background:white;border-radius:20px;padding:28px;width:360px;text-align:center;box-shadow:0 32px 64px rgba(30,58,47,0.2);animation:popIn 0.25s ease;">
        <div style="width:52px;height:52px;background:#fef2f2;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
            <i class="fas fa-trash-alt" style="font-size:22px;color:#dc2626;"></i>
        </div>
        <div style="font-size:17px;font-weight:800;color:var(--text);margin-bottom:6px;">Hapus Admin Pasar?</div>
        <div id="hapusNama" style="font-size:13px;color:var(--sub);margin-bottom:22px;"></div>
        <div style="display:flex;gap:10px;justify-content:center;">
            <button onclick="closeHapus()" style="flex:1;background:#f0f0f0;border:none;border-radius:11px;padding:11px;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">Batal</button>
            <form id="hapusForm" method="POST" style="flex:1;">
                @csrf @method('DELETE')
                <button type="submit" style="width:100%;background:linear-gradient(135deg,#991b1b,#ef4444);color:white;border:none;border-radius:11px;padding:11px;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
function openReset(id, nama) {
    document.getElementById('resetNama').textContent = nama;
    document.getElementById('resetForm').action = `/admin/kelola-admin-pasar/${id}/reset-password`;
    document.getElementById('resetModal').classList.add('open');
}
function closeReset() { document.getElementById('resetModal').classList.remove('open'); }

function confirmHapus(id, nama) {
    document.getElementById('hapusNama').textContent = nama;
    document.getElementById('hapusForm').action = `/admin/kelola-admin-pasar/${id}`;
    document.getElementById('hapusModal').classList.add('open');
}
function closeHapus() { document.getElementById('hapusModal').classList.remove('open'); }

document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', function(e) { if(e.target===this) this.classList.remove('open'); });
});
</script>
@endsection
