@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
:root { --g:#d0f0c0; --gd:#2d6a4f; --gdd:#1e3a2f; --border:#d1e8d8; --text:#1a3a2a; --sub:#5a8a6a; }
.user-card { background:white; border:1.5px solid var(--border); border-radius:16px; padding:20px 22px; transition:transform 0.2s,box-shadow 0.2s; }
.user-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(45,106,79,0.1); }
.modal-overlay { display:none; position:fixed; inset:0; z-index:60; background:rgba(30,58,47,0.45); backdrop-filter:blur(5px); align-items:center; justify-content:center; }
.modal-overlay.open { display:flex; }
@keyframes popIn { from{transform:scale(0.9);opacity:0} to{transform:scale(1);opacity:1} }
</style>

<div style="min-height:100vh;background:#f0faf4;padding:32px;">
<div style="max-width:900px;margin:0 auto;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:5px;">
                <a href="{{ route('admin_master.dashboard') }}"
                    style="background:var(--g);color:var(--gdd);width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 style="font-size:22px;font-weight:800;color:var(--text);">Kelola Kepala Dinas / Kasubag</h1>
            </div>
            <p style="font-size:13px;color:var(--sub);margin-left:42px;">Akses: melihat laporan dan rekomendasi harga</p>
        </div>
        <a href="{{ route('admin_master.kelola.kepala_dinas.create') }}"
            style="background:linear-gradient(135deg,var(--gdd),var(--gd));color:white;text-decoration:none;border-radius:12px;padding:11px 22px;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 14px rgba(45,106,79,0.25);">
            <i class="fas fa-plus"></i> Tambah Akun
        </a>
    </div>

    @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #22c55e;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#15803d;">
        <i class="fas fa-check-circle" style="margin-right:8px;"></i>{{ session('success') }}
    </div>
    @endif

    {{-- Daftar akun --}}
    @if($users->isEmpty())
    <div style="padding:64px;text-align:center;background:white;border-radius:16px;border:1.5px dashed var(--border);">
        <i class="fas fa-user-tie" style="font-size:3rem;color:#c6ebd4;display:block;margin-bottom:14px;"></i>
        <p style="color:var(--sub);font-weight:700;font-size:15px;">Belum ada akun Kepala Dinas/Kasubag.</p>
        <a href="{{ route('admin_master.kelola.kepala_dinas.create') }}"
            style="color:var(--gd);font-weight:600;font-size:13px;margin-top:8px;display:inline-block;">
            + Tambah sekarang
        </a>
    </div>
    @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px;">
        @foreach($users as $u)
        <div class="user-card">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div style="width:46px;height:46px;border-radius:14px;background:linear-gradient(135deg,var(--gdd),var(--gd));color:white;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;flex-shrink:0;">
                    {{ strtoupper(substr($u->name,0,1)) }}
                </div>
                <div>
                    <div style="font-weight:800;color:var(--text);font-size:15px;">{{ $u->name }}</div>
                    <div style="font-size:11px;color:var(--sub);">@{{ $u->username }}</div>
                </div>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <span style="background:#ede9fe;color:#7c3aed;border:1px solid #ddd6fe;border-radius:999px;padding:3px 10px;font-size:10px;font-weight:700;">
                    <i class="fas fa-user-tie" style="font-size:9px;margin-right:4px;"></i>Kepala Dinas / Kasubag
                </span>
                <span style="font-size:11px;color:var(--sub);">
                    {{ $u->created_at->format('d M Y') }}
                </span>
            </div>

            <div style="border-top:1px solid #e8f5ee;padding-top:12px;display:flex;gap:8px;justify-content:flex-end;">
                <button onclick="confirmHapus('{{ $u->id }}','{{ addslashes($u->name) }}')"
                    style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;border-radius:9px;padding:7px 14px;font-size:12px;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">
                    <i class="fas fa-trash-alt" style="margin-right:5px;"></i>Hapus
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
</div>

{{-- Modal Hapus --}}
<div class="modal-overlay" id="hapusModal">
    <div style="background:white;border-radius:20px;padding:28px;width:360px;text-align:center;box-shadow:0 32px 64px rgba(30,58,47,0.2);animation:popIn 0.25s ease;">
        <div style="width:52px;height:52px;background:#fef2f2;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
            <i class="fas fa-trash-alt" style="font-size:22px;color:#dc2626;"></i>
        </div>
        <div style="font-size:17px;font-weight:800;color:var(--text);margin-bottom:6px;">Hapus Akun?</div>
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
function confirmHapus(id, nama) {
    document.getElementById('hapusNama').textContent = nama;
    document.getElementById('hapusForm').action = `/admin/kelola-kepala-dinas/${id}`;
    document.getElementById('hapusModal').classList.add('open');
}
function closeHapus() { document.getElementById('hapusModal').classList.remove('open'); }
document.getElementById('hapusModal').addEventListener('click', function(e) { if(e.target===this) closeHapus(); });
</script>
@endsection
