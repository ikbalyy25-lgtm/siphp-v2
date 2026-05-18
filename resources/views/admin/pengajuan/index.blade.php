@extends('layouts.app')
@section('content')
<div style="min-height:100vh;background:#f0faf4;padding:32px;font-family:'Plus Jakarta Sans',sans-serif;">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>*{font-family:'Plus Jakarta Sans',sans-serif;box-sizing:border-box;}:root{--g:#d0f0c0;--gd:#2d6a4f;--gdd:#1e3a2f;--border:#d1e8d8;--text:#1a3a2a;--sub:#5a8a6a;}.row-item{transition:background 0.15s;background:white;}.row-item:hover{background:#f5fdf7;}.inp-field{padding:10px 14px;border-radius:10px;font-size:13px;border:1.5px solid var(--border);background:#f8fdf9;color:var(--text);outline:none;font-family:'Plus Jakarta Sans',sans-serif;}</style>
<div style="max-width:1100px;margin:0 auto;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:5px;">
                <a href="{{ route('admin.dashboard') }}" style="background:#d0f0c0;color:#1e3a2f;width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 style="font-size:22px;font-weight:800;color:var(--text);">Pengajuan Akun Pedagang</h1>
            </div>
            <p style="font-size:13px;color:var(--sub);margin-left:42px;">Verifikasi dan kelola calon pedagang baru</p>
        </div>
        @if($pengajuan->count() > 0)
        <div style="background:#fef9c3;border:1px solid #fde68a;border-radius:12px;padding:9px 16px;font-size:13px;font-weight:700;color:#b45309;">
            <i class="fas fa-clock" style="margin-right:6px;"></i>{{ $pengajuan->count() }} Menunggu
        </div>
        @endif
    </div>

    @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #22c55e;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#15803d;">
        <i class="fas fa-check-circle" style="margin-right:8px;"></i>{{ session('success') }}
    </div>
    @endif

    {{-- Search --}}
    <div style="background:white;border:1.5px solid var(--border);border-radius:14px;padding:14px 18px;margin-bottom:18px;display:flex;gap:12px;">
        <div style="position:relative;flex:1;">
            <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#a3c4aa;font-size:13px;"></i>
            <input type="text" id="searchAjuan" placeholder="Cari nama atau pasar..." class="inp-field" style="padding-left:36px;width:100%;" oninput="filterAjuan()">
        </div>
    </div>

    <div style="border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(45,106,79,0.08);border:1.5px solid var(--border);">
        <div style="display:grid;grid-template-columns:0.4fr 2fr 1.5fr 1.2fr 1fr 1fr;padding:13px 20px;background:linear-gradient(135deg,var(--gdd),var(--gd));color:var(--g);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;">
            <div style="text-align:center;">No</div>
            <div>Nama & Kontak</div>
            <div>Lokasi Pasar</div>
            <div>Jenis Barang</div>
            <div style="text-align:center;">Tanggal</div>
            <div style="text-align:center;">Aksi</div>
        </div>

        <div id="ajuanBody">
        @forelse($pengajuan as $i => $p)
        <div class="row-item ajuan-row" style="display:grid;grid-template-columns:0.4fr 2fr 1.5fr 1.2fr 1fr 1fr;padding:14px 20px;border-bottom:1px solid #e8f5ee;align-items:center;"
            data-nama="{{ strtolower($p->nama) }}" data-pasar="{{ strtolower($p->nama_pasar ?? $p->lokasi_pasar ?? '') }}">

            <div style="text-align:center;font-weight:700;color:var(--sub);">{{ $i+1 }}</div>

            <div>
                <div style="font-weight:700;color:var(--text);font-size:14px;">{{ $p->nama }}</div>
                <div style="font-size:11px;color:var(--sub);margin-top:2px;">{{ $p->email ?? '-' }}</div>
                <div style="font-size:11px;color:var(--gd);font-weight:600;">{{ $p->kontak ?? '-' }}</div>
            </div>

            <div>
                <div style="font-size:13px;color:var(--text);">{{ $p->nama_pasar ?? $p->lokasi_pasar ?? '-' }}</div>
                <div style="font-size:11px;color:var(--sub);">{{ $p->los ?? '-' }}</div>
            </div>

            <div>
                <span style="background:#f0faf4;color:var(--gd);border:1px solid var(--border);border-radius:7px;padding:4px 10px;font-size:11px;font-weight:600;">
                    {{ $p->jenis_barang ?? 'Umum' }}
                </span>
            </div>

            <div style="text-align:center;font-size:12px;color:var(--sub);">
                {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}
            </div>

            <div style="display:flex;gap:6px;justify-content:center;">
                <a href="{{ route('admin.pengajuan.verify', $p->id) }}"
                    style="background:var(--g);color:var(--gdd);border:none;border-radius:8px;padding:7px 14px;font-size:12px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
                    <i class="fas fa-check"></i> Verifikasi
                </a>
                <form action="{{ route('admin.pengajuan.destroy', $p->id) }}" method="POST" style="display:inline;"
                    onsubmit="return confirm('Tolak pengajuan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:12px;">
                        <i class="fas fa-times"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="padding:56px;text-align:center;background:white;">
            <i class="fas fa-user-check" style="font-size:2.5rem;color:#c6ebd4;display:block;margin-bottom:12px;"></i>
            <p style="color:var(--sub);font-weight:600;">Tidak ada pengajuan akun.</p>
        </div>
        @endforelse
        </div>
    </div>
</div>
<script>
function filterAjuan() {
    const q = document.getElementById('searchAjuan').value.toLowerCase();
    document.querySelectorAll('.ajuan-row').forEach(r => {
        r.style.display = !q || r.dataset.nama.includes(q) || r.dataset.pasar.includes(q) ? '' : 'none';
    });
}
</script>
</div>
@endsection
