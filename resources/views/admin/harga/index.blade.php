@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{font-family:'Plus Jakarta Sans',sans-serif;box-sizing:border-box;}
:root{--g:#d0f0c0;--gd:#2d6a4f;--gdd:#1e3a2f;--border:#d1e8d8;--text:#1a3a2a;--sub:#5a8a6a;--bg:#f0faf4;}
body{background:var(--bg);}
.harga-row{display:grid;grid-template-columns:2.2fr 1.1fr 1.3fr 1.3fr 1.2fr 0.7fr;padding:14px 20px;border-bottom:1px solid #e8f5ee;align-items:center;background:white;transition:background 0.15s;}
.harga-row:hover{background:#f5fdf7;}
.badge-pub{background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0;border-radius:999px;padding:5px 13px;font-size:11px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:5px;border:none;}
.badge-pend{background:#fef9c3;color:#b45309;border:1px solid #fde68a;border-radius:999px;padding:5px 13px;font-size:11px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:5px;border:none;}
.inp{width:100%;padding:10px 14px;border-radius:10px;font-size:13px;border:1.5px solid var(--border);background:#f8fdf9;color:var(--text);outline:none;transition:border-color 0.2s;font-family:'Plus Jakarta Sans',sans-serif;}
.inp:focus{border-color:var(--gd);}
.btn-primary{background:linear-gradient(135deg,var(--gdd),var(--gd));color:white;border:none;border-radius:10px;padding:10px 20px;font-weight:700;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;gap:7px;text-decoration:none;transition:opacity 0.2s,transform 0.15s;font-family:'Plus Jakarta Sans',sans-serif;}
.btn-primary:hover{opacity:0.9;transform:translateY(-1px);}
.btn-outline{background:white;border:1.5px solid var(--border);color:var(--gd);border-radius:10px;padding:10px 18px;font-weight:700;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;gap:7px;font-family:'Plus Jakarta Sans',sans-serif;transition:background 0.15s;}
.btn-outline:hover{background:#f0faf4;}
@keyframes pulse2{0%,100%{opacity:1}50%{opacity:0.35}}
@keyframes popIn{from{transform:scale(0.92);opacity:0}to{transform:scale(1);opacity:1}}
</style>

<div style="min-height:100vh;background:var(--bg);padding:32px;">
<div style="max-width:1120px;margin:0 auto;">

    {{-- Header --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:5px;">
                <a href="{{ route('admin.dashboard') }}" style="background:var(--g);color:var(--gdd);width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;flex-shrink:0;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 style="font-size:22px;font-weight:800;color:var(--text);">
                    Harga Barang <span style="color:var(--gd);">{{ ucfirst($kategori) }}</span>
                </h1>
            </div>
            <p style="font-size:13px;color:var(--sub);margin-left:42px;">
                <i class="fas fa-map-marker-alt" style="color:#ef4444;margin-right:5px;"></i>
                {{ $pasar->nama_pasar }} &nbsp;·&nbsp;
                <span id="countLabel" style="font-weight:600;color:var(--gd);">{{ count($data_harga ?? []) }} data</span>
            </p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <button onclick="openShare()" class="btn-outline">
                <i class="fas fa-share-nodes"></i> Bagikan
            </button>
            <a href="{{ route('admin.harga.create', $kategori) }}" class="btn-primary">
                <i class="fas fa-plus"></i> Input Komoditas
            </a>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div style="background:white;border:1.5px solid var(--border);border-radius:16px;padding:16px 20px;margin-bottom:18px;display:flex;gap:14px;align-items:center;flex-wrap:wrap;box-shadow:0 2px 8px rgba(45,106,79,0.05);">
        <div style="position:relative;flex:1;min-width:200px;">
            <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#a3c4aa;font-size:13px;pointer-events:none;"></i>
            <input type="text" id="searchInput" placeholder="Cari nama barang..." class="inp" style="padding-left:36px;" oninput="doFilter()">
        </div>
        <select id="filterStatus" class="inp" style="width:155px;" onchange="doFilter()">
            <option value="">Semua Status</option>
            <option value="published">Published</option>
            <option value="pending">Pending</option>
        </select>
        <span style="font-size:12px;color:var(--sub);white-space:nowrap;">
            <span id="perPageLabel">10</span>/halaman
        </span>
    </div>

    {{-- Tabel --}}
    <div style="border-radius:16px;overflow:hidden;box-shadow:0 2px 14px rgba(45,106,79,0.08);border:1.5px solid var(--border);">
        {{-- Header --}}
        <div style="display:grid;grid-template-columns:2.5fr 1.2fr 1.5fr 1.2fr 0.7fr;padding:13px 20px;background:linear-gradient(135deg,var(--gdd),var(--gd));color:var(--g);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;">
            <div>Nama Barang</div>
            <div style="text-align:center;">Tanggal</div>
            <div style="text-align:right;padding-right:8px;">Harga Rata-rata</div>
            <div style="text-align:center;">Status</div>
            <div style="text-align:center;">Hapus</div>
        </div>

        {{-- Rows --}}
        <div id="tableBody">
        @forelse($data_harga ?? [] as $h)
        <div class="harga-row data-row" data-nama="{{ strtolower($h->nama_barang) }}" data-status="{{ $h->status }}" style="grid-template-columns:2.5fr 1.2fr 1.5fr 1.2fr 0.7fr;">

            <div>
                <div style="font-weight:700;color:var(--text);font-size:14px;">{{ $h->nama_barang }}</div>
                <div style="font-size:11px;color:var(--sub);margin-top:2px;">{{ ucfirst($kategori) }}</div>
            </div>

            <div style="text-align:center;">
                <span style="background:#f0faf4;color:var(--sub);border:1px solid var(--border);border-radius:7px;padding:4px 9px;font-size:11px;font-weight:600;white-space:nowrap;">
                    {{ \Carbon\Carbon::parse($h->tanggal)->format('d M Y') }}
                </span>
            </div>

            <div style="text-align:right;padding-right:8px;font-weight:800;color:var(--text);font-size:14px;">
                Rp {{ number_format($h->harga_hari_ini, 0, ',', '.') }}
            </div>

            <div style="text-align:center;">
                @if($h->status === 'published')
                <span class="badge-pub">
                    <span style="width:6px;height:6px;border-radius:50%;background:#16a34a;display:inline-block;animation:pulse2 2s infinite;"></span> Published
                </span>
                @else
                <span class="badge-pend">
                    <span style="width:6px;height:6px;border-radius:50%;background:#b45309;display:inline-block;"></span> Pending
                </span>
                @endif
            </div>

            <div style="text-align:center;">
                <form action="{{ route('admin.harga.destroy', $h->id) }}" method="POST" style="display:inline;"
                    onsubmit="return confirm('Hapus {{ addslashes($h->nama_barang) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:12px;"
                        onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="padding:56px;text-align:center;background:white;">
            <i class="fas fa-box-open" style="font-size:2.5rem;color:#c6ebd4;display:block;margin-bottom:12px;"></i>
            <p style="color:var(--sub);font-size:14px;font-weight:600;">Belum ada data harga.</p>
        </div>
        @endforelse
        </div>
    </div>

    {{-- Pagination --}}
    <div id="paginationBar" style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;">
        <span id="pageInfo" style="font-size:12px;color:var(--sub);"></span>
        <div style="display:flex;gap:8px;">
            <button id="prevBtn" onclick="changePage(-1)" class="btn-outline" style="padding:8px 18px;">
                <i class="fas fa-chevron-left" style="font-size:11px;"></i> Prev
            </button>
            <button id="nextBtn" onclick="changePage(1)" class="btn-outline" style="padding:8px 18px;">
                Next <i class="fas fa-chevron-right" style="font-size:11px;"></i>
            </button>
        </div>
    </div>
</div>
</div>

{{-- Modal Share --}}
<div id="shareModal" style="display:none;position:fixed;inset:0;z-index:60;background:rgba(30,58,47,0.45);backdrop-filter:blur(5px);align-items:center;justify-content:center;">
    <div style="background:white;border-radius:22px;padding:28px;width:360px;box-shadow:0 32px 64px rgba(30,58,47,0.2);border-top:4px solid var(--gd);animation:popIn 0.25s ease;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
            <div style="font-size:17px;font-weight:800;color:var(--text);"><i class="fas fa-share-nodes" style="color:var(--gd);margin-right:8px;"></i>Bagikan Data</div>
            <button onclick="closeShare()" style="background:#f0f0f0;border:none;width:30px;height:30px;border-radius:8px;cursor:pointer;">✕</button>
        </div>
        <div style="display:flex;flex-direction:column;gap:9px;">
            <button onclick="shareWA()" style="background:#25d366;color:white;border:none;border-radius:11px;padding:11px;font-weight:700;font-size:13px;cursor:pointer;display:flex;align-items:center;gap:10px;">
                <i class="fab fa-whatsapp" style="font-size:16px;"></i> Bagikan via WhatsApp
            </button>
            <button onclick="copyURL()" style="background:var(--g);color:var(--gdd);border:none;border-radius:11px;padding:11px;font-weight:700;font-size:13px;cursor:pointer;display:flex;align-items:center;gap:10px;">
                <i class="fas fa-link"></i> Salin Tautan
            </button>
            <button onclick="window.print()" style="background:#f0faf4;color:var(--gd);border:1.5px solid var(--border);border-radius:11px;padding:11px;font-weight:700;font-size:13px;cursor:pointer;display:flex;align-items:center;gap:10px;">
                <i class="fas fa-print"></i> Cetak / PDF
            </button>
        </div>
    </div>
</div>

<script>
const PER = 10; let page = 1, filtered = [];
const getAllRows = () => Array.from(document.querySelectorAll('.data-row'));

function doFilter() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const s = document.getElementById('filterStatus').value;
    filtered = getAllRows().filter(r => (!q || r.dataset.nama.includes(q)) && (!s || r.dataset.status === s));
    page = 1; render();
}

function render() {
    getAllRows().forEach(r => r.style.display = 'none');
    const st = (page-1)*PER, en = st+PER, tot = filtered.length;
    filtered.slice(st, en).forEach(r => r.style.display = '');
    document.getElementById('pageInfo').textContent = tot===0 ? 'Tidak ada data' : `${st+1}–${Math.min(en,tot)} dari ${tot} data`;
    document.getElementById('countLabel').textContent = tot + ' data';
    document.getElementById('prevBtn').disabled = page <= 1;
    document.getElementById('nextBtn').disabled = page >= Math.ceil(tot/PER);
    document.getElementById('paginationBar').style.display = tot <= PER && page===1 && !document.getElementById('searchInput').value ? 'none' : 'flex';
}

function changePage(d) {
    page = Math.max(1, Math.min(page+d, Math.ceil(filtered.length/PER)));
    render(); window.scrollTo({top:300,behavior:'smooth'});
}

function openShare() { document.getElementById('shareModal').style.display='flex'; }
function closeShare() { document.getElementById('shareModal').style.display='none'; }

function shareWA() {
    const rows = getAllRows().slice(0,10).map(r => `• ${r.querySelector('div div:first-child').textContent.trim()}`).join('\n');
    window.open('https://wa.me/?text=' + encodeURIComponent(`*Harga {{ ucfirst($kategori) }} — {{ $pasar->nama_pasar }}*\n\n${rows}\n\n_SIPHP Kota Parepare_`));
    closeShare();
}
function copyURL() { navigator.clipboard.writeText(window.location.href); alert('Tautan disalin!'); closeShare(); }
document.getElementById('shareModal').addEventListener('click', e => { if(e.target===document.getElementById('shareModal')) closeShare(); });

filtered = getAllRows(); render();
</script>
@endsection
