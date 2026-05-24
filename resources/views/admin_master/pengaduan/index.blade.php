@extends('layouts.app')
@section('content')
<div style="min-height:100vh;background:#f0faf4;padding:32px;font-family:'Plus Jakarta Sans',sans-serif;">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>*{font-family:'Plus Jakarta Sans',sans-serif;box-sizing:border-box;}:root{--g:#d0f0c0;--gd:#2d6a4f;--gdd:#1e3a2f;--border:#d1e8d8;--text:#1a3a2a;--sub:#5a8a6a;}.row-item{transition:background 0.15s;background:white;}.row-item:hover{background:#f5fdf7;}.inp-field{padding:10px 14px;border-radius:10px;font-size:13px;border:1.5px solid var(--border);background:#f8fdf9;color:var(--text);outline:none;transition:border-color 0.2s;font-family:'Plus Jakarta Sans',sans-serif;}.inp-field:focus{border-color:var(--gd);}</style>
<div style="max-width:1100px;margin:0 auto;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:5px;">
                <a href="{{ route('admin_master.dashboard') }}" style="background:#d0f0c0;color:#1e3a2f;width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 style="font-size:22px;font-weight:800;color:var(--text);">Daftar Pengaduan</h1>
            </div>
            <p style="font-size:13px;color:var(--sub);margin-left:42px;">Pantau masukan dan keluhan dari masyarakat</p>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <div style="background:#d0f0c0;border-radius:12px;padding:9px 16px;font-size:13px;font-weight:700;color:var(--gdd);">
                <i class="fas fa-bell" style="margin-right:6px;"></i>{{ $pengaduan->count() }} Pengaduan
            </div>
        </div>
    </div>

    @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #22c55e;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#15803d;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Search & Filter --}}
    <div style="background:white;border:1.5px solid var(--border);border-radius:16px;padding:16px 20px;margin-bottom:18px;display:flex;gap:14px;flex-wrap:wrap;align-items:center;">
        <div style="position:relative;flex:1;min-width:200px;">
            <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#a3c4aa;font-size:13px;"></i>
            <input type="text" id="searchPengaduan" placeholder="Cari nama atau isi pengaduan..." class="inp-field" style="padding-left:36px;" oninput="filterPengaduan()">
        </div>
        <select id="filterKategori" class="inp-field" style="width:180px;" onchange="filterPengaduan()">
            <option value="">Semua Kategori</option>
            <option value="pasar">Pasar</option>
            <option value="kebersihan">Kebersihan</option>
            <option value="los kosong">Los Kosong</option>
            <option value="harga">Harga</option>
            <option value="sistem">Sistem</option>
        </select>
    </div>

    {{-- Tabel --}}
    <div style="border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(45,106,79,0.08);border:1.5px solid var(--border);">
        <div style="display:grid;grid-template-columns:0.4fr 1.5fr 1fr 1.8fr 0.9fr 0.5fr;padding:13px 20px;background:linear-gradient(135deg,var(--gdd),var(--gd));color:var(--g);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;">
            <div style="text-align:center;">No</div>
            <div>Pelapor</div>
            <div>Pasar</div>
            <div>Isi Pengaduan</div>
            <div style="text-align:center;">Tanggal</div>
            <div style="text-align:center;">Aksi</div>
        </div>

        <div id="pengaduanBody">
        @forelse($pengaduan as $i => $p)
        <div class="row-item pengaduan-row" style="display:grid;grid-template-columns:0.4fr 1.5fr 1fr 1.8fr 0.9fr 0.5fr;padding:14px 20px;border-bottom:1px solid #e8f5ee;align-items:center;"
            data-nama="{{ strtolower($p->nama ?? '') }}" data-pesan="{{ strtolower($p->pesan ?? '') }}" data-kategori="{{ strtolower($p->kategori ?? '') }}">

            <div style="text-align:center;font-weight:700;color:var(--sub);font-size:13px;">{{ $i+1 }}</div>

            <div>
                <div style="font-weight:700;color:var(--text);font-size:14px;">{{ $p->nama ?? 'Anonim' }}</div>
                <div style="font-size:11px;color:var(--sub);margin-top:2px;">{{ $p->nomor_telepon ?? '-' }}</div>
            </div>

            <div>
                <span style="background:#f0faf4;color:var(--gd);border:1px solid var(--border);border-radius:7px;padding:4px 10px;font-size:11px;font-weight:600;">
                    {{ $p->pasar ?? '-' }}
                </span>
            </div>

            <div>
                <div style="font-size:13px;color:var(--text);line-height:1.5;">{{ \Illuminate\Support\Str::limit($p->pesan ?? '-', 80) }}</div>
                @if(isset($p->kategori))
                <span style="background:#fef9c3;color:#b45309;border:1px solid #fde68a;border-radius:999px;padding:2px 8px;font-size:10px;font-weight:700;margin-top:4px;display:inline-block;">
                    {{ ucfirst($p->kategori) }}
                </span>
                @endif
            </div>

            <div style="text-align:center;">
                <div style="font-size:12px;font-weight:600;color:var(--text);">{{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}</div>
                <div style="font-size:11px;color:var(--sub);">{{ \Carbon\Carbon::parse($p->created_at)->format('H:i') }} WIB</div>
            </div>

            <div style="text-align:center;">
                <form action="{{ route('admin_master.pengaduan.destroy', $p->id) }}" method="POST"
                    onsubmit="return confirm('Hapus pengaduan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:12px;transition:background 0.2s;"
                        onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="padding:56px;text-align:center;background:white;">
            <i class="fas fa-bell-slash" style="font-size:2.5rem;color:#c6ebd4;display:block;margin-bottom:12px;"></i>
            <p style="color:var(--sub);font-weight:600;">Belum ada pengaduan masuk.</p>
        </div>
        @endforelse
        </div>
    </div>
</div>

<script>
function filterPengaduan() {
    const q = document.getElementById('searchPengaduan').value.toLowerCase();
    const k = document.getElementById('filterKategori').value.toLowerCase();
    document.querySelectorAll('.pengaduan-row').forEach(r => {
        const match = (!q || r.dataset.nama.includes(q) || r.dataset.pesan.includes(q)) &&
                      (!k || r.dataset.kategori.includes(k));
        r.style.display = match ? '' : 'none';
    });
}
</script>
</div>
@endsection
