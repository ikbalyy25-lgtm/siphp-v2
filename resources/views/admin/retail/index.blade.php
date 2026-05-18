@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{font-family:'Plus Jakarta Sans',sans-serif;box-sizing:border-box;}
:root{--g:#d0f0c0;--gd:#2d6a4f;--gdd:#1e3a2f;--border:#d1e8d8;--text:#1a3a2a;--sub:#5a8a6a;--bg:#f0faf4;}
.retail-card{background:white;border:1.5px solid var(--border);border-radius:16px;overflow:hidden;transition:transform 0.2s,box-shadow 0.2s;}
.retail-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(45,106,79,0.14);}
.inp{width:100%;padding:10px 14px;border-radius:10px;font-size:13px;border:1.5px solid var(--border);background:#f8fdf9;color:var(--text);outline:none;font-family:'Plus Jakarta Sans',sans-serif;transition:border-color 0.2s;}
.inp:focus{border-color:var(--gd);}
@keyframes popIn{from{transform:scale(0.9);opacity:0}to{transform:scale(1);opacity:1}}
</style>

<div style="min-height:100vh;background:var(--bg);padding:32px;">
<div style="max-width:1120px;margin:0 auto;">

    {{-- Header --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:5px;">
                <a href="{{ route('admin.dashboard') }}" style="background:var(--g);color:var(--gdd);width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 style="font-size:22px;font-weight:800;color:var(--text);">Manajemen Ritel</h1>
            </div>
            <p style="font-size:13px;color:var(--sub);margin-left:42px;">Kelola toko retail yang tampil di portal publik</p>
        </div>
        <a href="{{ route('admin.retail.create') }}"
            style="background:linear-gradient(135deg,var(--gdd),var(--gd));color:white;text-decoration:none;border-radius:12px;padding:11px 22px;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 14px rgba(45,106,79,0.25);">
            <i class="fas fa-plus"></i> Tambah Ritel
        </a>
    </div>

    @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #22c55e;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#15803d;display:flex;align-items:center;justify-content:space-between;">
        <span><i class="fas fa-check-circle" style="margin-right:8px;"></i>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#15803d;font-size:16px;">✕</button>
    </div>
    @endif

    {{-- Search --}}
    <div style="background:white;border:1.5px solid var(--border);border-radius:14px;padding:14px 18px;margin-bottom:20px;display:flex;gap:12px;align-items:center;">
        <div style="position:relative;flex:1;">
            <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#a3c4aa;font-size:13px;pointer-events:none;"></i>
            <input type="text" id="searchRitel" placeholder="Cari nama toko..." class="inp" style="padding-left:36px;" oninput="filterRitel()">
        </div>
        <span style="font-size:12px;color:var(--sub);" id="ritelCount">{{ $retails->count() }} toko</span>
    </div>

    {{-- Grid Kartu --}}
    @forelse($retails as $i => $r)
    <div class="retail-card ritel-item" data-nama="{{ strtolower($r->nama_toko) }}" style="margin-bottom:16px;">
        <div style="display:grid;grid-template-columns:80px 1fr auto;align-items:center;gap:16px;padding:16px 20px;">

            {{-- Foto --}}
            <div style="width:72px;height:72px;border-radius:12px;overflow:hidden;flex-shrink:0;background:#f0faf4;border:1.5px solid var(--border);">
                @if($r->gambar)
                <img src="{{ url('storage/'.$r->gambar) }}" style="width:100%;height:100%;object-fit:cover;"
                    onerror="this.parentElement.innerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;\'><i class=\'fas fa-store\' style=\'color:#c6ebd4;font-size:24px;\'></i></div>'">
                @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-store" style="color:#c6ebd4;font-size:24px;"></i>
                </div>
                @endif
            </div>

            {{-- Info --}}
            <div style="min-width:0;">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:6px;">
                    <span style="font-weight:800;color:var(--text);font-size:15px;">{{ $r->nama_toko }}</span>
                    <span style="background:var(--g);color:var(--gdd);border-radius:999px;padding:2px 10px;font-size:10px;font-weight:700;">{{ $r->kategori }}</span>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:12px;">
                    <span style="font-size:12px;color:var(--sub);display:flex;align-items:center;gap:5px;">
                        <i class="fas fa-map-marker-alt" style="color:#ef4444;font-size:11px;"></i>
                        {{ \Illuminate\Support\Str::limit($r->alamat, 40) }}
                    </span>
                    <span style="font-size:12px;color:var(--sub);display:flex;align-items:center;gap:5px;">
                        <i class="fas fa-phone" style="color:var(--gd);font-size:11px;"></i>
                        {{ $r->kontak }}
                    </span>
                    <span style="font-size:12px;color:var(--sub);display:flex;align-items:center;gap:5px;">
                        <i class="fas fa-clock" style="color:var(--gd);font-size:11px;"></i>
                        {{ $r->jam_buka }}
                    </span>
                </div>
            </div>

            {{-- Aksi --}}
            <div style="display:flex;gap:8px;flex-shrink:0;">
                <a href="{{ $r->link_maps }}" target="_blank"
                    style="background:#f0faf4;border:1.5px solid var(--border);color:var(--gd);width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;"
                    title="Lihat Maps">
                    <i class="fas fa-map"></i>
                </a>
                <button onclick="confirmDelete({{ $r->id }}, '{{ addslashes($r->nama_toko) }}')"
                    style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;width:36px;height:36px;border-radius:9px;cursor:pointer;font-size:13px;"
                    title="Hapus">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div style="padding:64px;text-align:center;background:white;border-radius:16px;border:1.5px dashed var(--border);">
        <i class="fas fa-store-slash" style="font-size:3rem;color:#c6ebd4;display:block;margin-bottom:14px;"></i>
        <p style="color:var(--sub);font-weight:700;font-size:15px;">Belum ada data ritel.</p>
        <a href="{{ route('admin.retail.create') }}" style="color:var(--gd);font-weight:600;font-size:13px;margin-top:8px;display:inline-block;">
            + Tambah toko sekarang
        </a>
    </div>
    @endforelse
</div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div id="deleteModal" style="display:none;position:fixed;inset:0;z-index:60;background:rgba(30,58,47,0.45);backdrop-filter:blur(5px);align-items:center;justify-content:center;">
    <div style="background:white;border-radius:22px;padding:32px;width:340px;text-align:center;box-shadow:0 32px 64px rgba(30,58,47,0.2);animation:popIn 0.25s ease;">
        <div style="width:52px;height:52px;background:#fef2f2;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="fas fa-trash-alt" style="font-size:22px;color:#dc2626;"></i>
        </div>
        <div style="font-size:17px;font-weight:800;color:var(--text);margin-bottom:6px;">Hapus Ritel?</div>
        <div id="deleteNama" style="font-size:14px;color:var(--sub);margin-bottom:24px;"></div>
        <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeDelete()" style="flex:1;background:#f0f0f0;border:none;border-radius:11px;padding:12px;font-weight:700;font-size:13px;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">Batal</button>
                <button type="submit" style="flex:1;background:linear-gradient(135deg,#991b1b,#ef4444);color:white;border:none;border-radius:11px;padding:12px;font-weight:700;font-size:13px;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">
                    <i class="fas fa-trash-alt" style="margin-right:6px;"></i>Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function filterRitel() {
    const q = document.getElementById('searchRitel').value.toLowerCase();
    let count = 0;
    document.querySelectorAll('.ritel-item').forEach(r => {
        const show = !q || r.dataset.nama.includes(q);
        r.style.display = show ? '' : 'none';
        if (show) count++;
    });
    document.getElementById('ritelCount').textContent = count + ' toko';
}
function confirmDelete(id, nama) {
    document.getElementById('deleteNama').textContent = nama;
    document.getElementById('deleteForm').action = `/admin/retail/${id}`;
    document.getElementById('deleteModal').style.display = 'flex';
}
function closeDelete() { document.getElementById('deleteModal').style.display = 'none'; }
document.getElementById('deleteModal').addEventListener('click', e => { if(e.target===document.getElementById('deleteModal')) closeDelete(); });
</script>
@endsection
