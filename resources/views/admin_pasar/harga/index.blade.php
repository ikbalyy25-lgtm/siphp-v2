@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
:root { --g:#d0f0c0; --gd:#2d6a4f; --gdd:#1e3a2f; --border:#d1e8d8; --text:#1a3a2a; --sub:#5a8a6a; }
.inp { width:100%; padding:10px 14px; border-radius:10px; font-size:13px; border:1.5px solid var(--border); background:#f8fdf9; color:var(--text); outline:none; font-family:'Plus Jakarta Sans',sans-serif; transition:border-color 0.2s; }
.inp:focus { border-color:var(--gd); }
.row { display:grid; grid-template-columns:2fr 1fr 1fr 2fr 1fr 1.5fr; padding:13px 20px; border-bottom:1px solid #e8f5ee; align-items:center; background:white; transition:background 0.15s; font-size:13px; }
.row:hover { background:#f5fdf7; }
@keyframes pulse2 { 0%,100%{opacity:1} 50%{opacity:0.35} }
</style>

<div style="min-height:100vh;background:#f0faf4;padding:32px;">
<div style="max-width:1200px;margin:0 auto;">

    {{-- Header --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:5px;">
                <a href="{{ route('admin_pasar.dashboard') }}"
                    style="background:var(--g);color:var(--gdd);width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;flex-shrink:0;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 style="font-size:22px;font-weight:800;color:var(--text);">
                    Input Harga <span style="color:var(--gd);">{{ ucfirst($kategori) }}</span>
                </h1>
            </div>
            <p style="font-size:13px;color:var(--sub);margin-left:42px;">
                <i class="fas fa-map-marker-alt" style="color:#ef4444;margin-right:5px;"></i>
                {{ $pasar->nama_pasar }} &nbsp;·&nbsp;
                <span id="countLabel" style="font-weight:600;color:var(--gd);">{{ $inputs->count() }} data</span>
            </p>
        </div>
        <a href="{{ route('admin_pasar.harga.create', $kategori) }}"
            style="background:linear-gradient(135deg,var(--gdd),var(--gd));color:white;text-decoration:none;border-radius:12px;padding:11px 22px;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 14px rgba(45,106,79,0.25);">
            <i class="fas fa-plus"></i> Input Komoditas
        </a>
    </div>

    @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #22c55e;border-radius:12px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#15803d;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #ef4444;border-radius:12px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#dc2626;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    {{-- Filter --}}
    <div style="background:white;border:1.5px solid var(--border);border-radius:16px;padding:16px 20px;margin-bottom:18px;display:flex;gap:14px;align-items:center;flex-wrap:wrap;box-shadow:0 2px 8px rgba(45,106,79,0.05);">
        <div style="position:relative;flex:1;min-width:200px;">
            <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#a3c4aa;font-size:13px;pointer-events:none;"></i>
            <input type="text" id="searchInput" placeholder="Cari nama barang..." class="inp" style="padding-left:36px;" oninput="doFilter()">
        </div>
        <select id="filterStatus" class="inp" style="width:175px;" onchange="doFilter()">
            <option value="">Semua Status</option>
            <option value="terkirim">Menunggu Approve</option>
            <option value="diapprove">Disetujui</option>
            <option value="ditolak">Ditolak</option>
        </select>
    </div>

    {{-- Kotak info alur --}}
    <div style="background:#fffbf0;border:1px solid #f6e05e;border-radius:12px;padding:12px 18px;margin-bottom:18px;display:flex;align-items:center;gap:10px;">
        <i class="fas fa-info-circle" style="color:#b45309;"></i>
        <span style="font-size:12px;color:#92400e;">
            Data yang sudah <b>Disetujui</b> akan tampil di publik. Data <b>Menunggu Approve</b> sedang antri di Admin Master. Data <b>Ditolak</b> perlu diinput ulang.
        </span>
    </div>

    {{-- Tabel --}}
    <div style="border-radius:16px;overflow:hidden;box-shadow:0 2px 14px rgba(45,106,79,0.08);border:1.5px solid var(--border);">
        {{-- Header --}}
        <div class="row" style="background:linear-gradient(135deg,var(--gdd),var(--gd));color:var(--g);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;">
            <div>Nama Barang</div>
            <div style="text-align:center;">Satuan</div>
            <div style="text-align:center;">Tanggal</div>
            <div style="text-align:right;padding-right:4px;">Harga Pedagang</div>
            <div style="text-align:right;padding-right:8px;">Rata-rata</div>
            <div style="text-align:center;">Status</div>
        </div>

        {{-- Rows --}}
        <div id="tableBody">
        @forelse($inputs as $inp)
        <div class="row data-row" data-nama="{{ strtolower($inp->nama_barang) }}" data-status="{{ $inp->status }}">
            <div>
                <div style="font-weight:700;color:var(--text);">{{ $inp->nama_barang }}</div>
                <div style="font-size:11px;color:var(--sub);margin-top:1px;">{{ ucfirst($inp->kategori) }}</div>
            </div>
            <div style="text-align:center;color:var(--sub);font-size:12px;font-weight:500;">
                {{ $inp->satuan && $inp->satuan !== '-' ? $inp->satuan : '-' }}
            </div>
            <div style="text-align:center;">
                <span style="background:#f0faf4;color:var(--sub);border:1px solid var(--border);border-radius:7px;padding:3px 8px;font-size:11px;font-weight:600;white-space:nowrap;">
                    {{ \Carbon\Carbon::parse($inp->tanggal)->format('d M Y') }}
                </span>
            </div>
            <div style="text-align:right;padding-right:4px;color:var(--sub);font-size:12px;line-height:1.6;">
                @foreach($inp->hargaPedagangList as $index => $harga)
                    <div><span style="font-size:10px;color:#9ab8a8;margin-right:4px;">P{{ $index + 1 }}</span> Rp {{ number_format($harga,0,',','.') }}</div>
                @endforeach
            </div>
            <div style="text-align:right;padding-right:8px;font-weight:800;color:var(--gd);font-size:14px;">
                Rp {{ number_format($inp->rata_rata,0,',','.') }}
            </div>
            <div style="text-align:center;display:flex;align-items:center;justify-content:center;gap:8px;">
                @if($inp->status === 'diapprove')
                <span style="background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0;border-radius:999px;padding:4px 11px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:5px;">
                    <span style="width:6px;height:6px;border-radius:50%;background:#16a34a;display:inline-block;animation:pulse2 2s infinite;"></span> Disetujui
                </span>
                @elseif($inp->status === 'ditolak')
                <span style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-radius:999px;padding:4px 11px;font-size:11px;font-weight:700;">
                    <i class="fas fa-times" style="font-size:9px;"></i> Ditolak
                </span>
                @else
                <span style="background:#fef9c3;color:#b45309;border:1px solid #fde68a;border-radius:999px;padding:4px 11px;font-size:11px;font-weight:700;">
                    <i class="fas fa-clock" style="font-size:9px;"></i> Menunggu
                </span>
                @endif

                {{-- Tombol hapus hanya untuk yang belum diapprove --}}
                @if($inp->status !== 'diapprove')
                <form action="{{ route('admin_pasar.harga.destroy', $inp->id) }}" method="POST" style="display:inline;"
                    onsubmit="return confirm('Hapus data {{ addslashes($inp->nama_barang) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;width:28px;height:28px;border-radius:7px;cursor:pointer;font-size:11px;"
                        onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div style="padding:56px;text-align:center;background:white;">
            <i class="fas fa-box-open" style="font-size:2.5rem;color:#c6ebd4;display:block;margin-bottom:12px;"></i>
            <p style="color:var(--sub);font-size:14px;font-weight:600;">Belum ada data harga {{ $kategori }}.</p>
            <a href="{{ route('admin_pasar.harga.create', $kategori) }}" style="color:var(--gd);font-weight:700;font-size:13px;text-decoration:none;">
                + Input sekarang
            </a>
        </div>
        @endforelse
        </div>
    </div>

    {{-- Pagination JS sederhana --}}
    <div id="paginationBar" style="display:none;align-items:center;justify-content:space-between;margin-top:16px;">
        <span id="pageInfo" style="font-size:12px;color:var(--sub);"></span>
        <div style="display:flex;gap:8px;">
            <button id="prevBtn" onclick="changePage(-1)"
                style="background:white;border:1.5px solid var(--border);color:var(--gd);border-radius:10px;padding:8px 18px;font-weight:700;font-size:13px;cursor:pointer;">
                <i class="fas fa-chevron-left" style="font-size:11px;"></i> Prev
            </button>
            <button id="nextBtn" onclick="changePage(1)"
                style="background:white;border:1.5px solid var(--border);color:var(--gd);border-radius:10px;padding:8px 18px;font-weight:700;font-size:13px;cursor:pointer;">
                Next <i class="fas fa-chevron-right" style="font-size:11px;"></i>
            </button>
        </div>
    </div>
</div>
</div>

<script>
const PER = 15; let page = 1, filtered = [];
const getRows = () => Array.from(document.querySelectorAll('.data-row'));

function doFilter() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const s = document.getElementById('filterStatus').value;
    filtered = getRows().filter(r => (!q || r.dataset.nama.includes(q)) && (!s || r.dataset.status === s));
    page = 1; render();
}

function render() {
    getRows().forEach(r => r.style.display = 'none');
    const st = (page-1)*PER, en = st+PER, tot = filtered.length;
    filtered.slice(st, en).forEach(r => r.style.display = '');
    document.getElementById('pageInfo').textContent = tot===0 ? 'Tidak ada data' : `${st+1}–${Math.min(en,tot)} dari ${tot} data`;
    document.getElementById('countLabel').textContent = tot + ' data';
    if (document.getElementById('prevBtn')) {
        document.getElementById('prevBtn').disabled = page <= 1;
        document.getElementById('nextBtn').disabled = page >= Math.ceil(tot/PER);
    }
    document.getElementById('paginationBar').style.display = tot > PER ? 'flex' : 'none';
}

function changePage(d) {
    page = Math.max(1, Math.min(page+d, Math.ceil(filtered.length/PER)));
    render(); window.scrollTo({top:300,behavior:'smooth'});
}

filtered = getRows(); render();
</script>
@endsection
