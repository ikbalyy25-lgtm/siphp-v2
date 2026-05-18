<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Harga — SIPHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        :root { --g:#d0f0c0; --gd:#2d6a4f; --gdd:#1e3a2f; --border:#d1e8d8; --text:#1a3a2a; --sub:#5a8a6a; }
        body { background:#f0faf4; margin:0; }
        .sidebar { width:240px; position:fixed; top:0; left:0; bottom:0; background:white; border-right:1.5px solid var(--border); box-shadow:4px 0 16px rgba(45,106,79,0.07); display:flex; flex-direction:column; z-index:40; }
        .nav-item { display:flex; align-items:center; gap:10px; padding:10px 16px; border-radius:10px; margin:2px 10px; font-size:13px; font-weight:600; color:#3a5a4a; text-decoration:none; transition:all 0.18s; }
        .nav-item:hover { background:#f0faf4; color:var(--gdd); }
        .nav-item.active { background:#e8f5ee; color:var(--gdd); border-left:3px solid var(--gd); }
        .nav-item i { width:16px; text-align:center; flex-shrink:0; }
        .nav-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:1.5px; color:#9ab8a8; padding:0 20px; margin:14px 0 5px; }
        .main { margin-left:240px; padding:28px 32px; min-height:100vh; }
        .rek-card { background:white; border:1.5px solid var(--border); border-radius:14px; padding:16px; box-shadow:0 2px 8px rgba(45,106,79,0.05); transition:transform 0.2s,box-shadow 0.2s; }
        .rek-card:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(45,106,79,0.1); }
        .filter-btn { border:1.5px solid var(--border); background:white; color:var(--sub); border-radius:8px; padding:7px 16px; font-size:12px; font-weight:700; cursor:pointer; transition:all 0.2s; font-family:'Plus Jakarta Sans',sans-serif; }
        .filter-btn.active, .filter-btn:hover { background:var(--gdd); color:white; border-color:var(--gdd); }
    </style>
</head>
<body>

<aside class="sidebar">
    <div style="padding:18px 20px;border-bottom:1px solid #e8f5ee;">
        <div style="display:flex;align-items:center;gap:10px;">
            <img src="{{ asset('img/logo.png') }}" style="width:36px;height:36px;" onerror="this.style.display='none'">
            <div>
                <div style="font-size:14px;font-weight:800;color:var(--gdd);">SIPHP</div>
                <div style="font-size:10px;color:var(--sub);">Kepala Dinas / Kasubag</div>
            </div>
        </div>
    </div>
    <div style="padding:14px 20px;border-bottom:1px solid #f0f5f1;display:flex;align-items:center;gap:10px;">
        <div style="width:38px;height:38px;border-radius:10px;background:var(--g);color:var(--gdd);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:15px;flex-shrink:0;">
            {{ strtoupper(substr(Auth::user()->name,0,1)) }}
        </div>
        <div style="min-width:0;">
            <div style="font-size:13px;font-weight:700;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Auth::user()->name }}</div>
            <div style="font-size:11px;color:var(--sub);">Kepala Dinas / Kasubag</div>
        </div>
    </div>
    <nav style="flex:1;padding:8px 0;">
        <div class="nav-label">Menu</div>
        <a href="{{ route('kepala_dinas.dashboard') }}" class="nav-item"><i class="fas fa-home"></i> Dashboard</a>
        <a href="{{ route('kepala_dinas.rekomendasi') }}" class="nav-item active"><i class="fas fa-lightbulb"></i> Rekomendasi Harga</a>
        <a href="{{ route('kepala_dinas.laporan') }}" class="nav-item"><i class="fas fa-file-arrow-down"></i> Unduh Laporan</a>
        <div class="nav-label">Lainnya</div>
        <a href="{{ url('/') }}" class="nav-item" target="_blank"><i class="fas fa-globe"></i> Portal Publik</a>
    </nav>
    <div style="padding:12px 10px;border-top:1px solid #f0f5f1;">
        <form action="{{ route('logout') }}" method="POST" id="logoutForm">@csrf
            <button type="button" onclick="document.getElementById('logoutForm').submit()"
                class="nav-item" style="color:#dc2626;width:calc(100% - 20px);">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</aside>

<main class="main">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-size:22px;font-weight:800;color:var(--text);">Rekomendasi Harga Optimal</h1>
            <p style="font-size:13px;color:var(--sub);margin-top:2px;">
                Kalkulasi rata-rata harga terbaik dari seluruh pasar di Kota Parepare
            </p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <button onclick="shareWA()" style="background:white;border:1.5px solid var(--border);color:var(--gd);border-radius:10px;padding:9px 16px;font-weight:700;font-size:12px;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;display:flex;align-items:center;gap:6px;">
                <i class="fab fa-whatsapp" style="color:#25d366;"></i> Bagikan
            </button>
            <span style="background:var(--g);color:var(--gdd);font-size:11px;font-weight:700;padding:9px 14px;border-radius:10px;">
                <i class="fas fa-store" style="margin-right:5px;"></i>5 Pasar
            </span>
        </div>
    </div>

    {{-- Filter --}}
    <div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
        <button class="filter-btn active" id="btnSemua" onclick="filterRek('')">Semua</button>
        <button class="filter-btn" id="btnPokok"   onclick="filterRek('pokok')">Barang Pokok</button>
        <button class="filter-btn" id="btnSubsidi" onclick="filterRek('subsidi')">Barang Subsidi</button>
        <button class="filter-btn" id="btnPenting" onclick="filterRek('penting')">Barang Penting</button>
    </div>

    {{-- Grid Rekomendasi --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;" id="rekGrid">
        @forelse($rekomendasi as $opt)
        <div class="rek-card" data-kat="{{ $opt->kategori }}">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                <span style="font-size:10px;font-weight:800;text-transform:uppercase;color:var(--gd);background:var(--g);padding:3px 9px;border-radius:999px;">
                    {{ $opt->kategori }}
                </span>
                <span style="font-size:10px;color:var(--sub);">
                    <i class="fas fa-store" style="font-size:9px;"></i> {{ $opt->jumlah_pasar }} pasar
                </span>
            </div>
            <div style="font-weight:700;font-size:14px;color:var(--text);margin-bottom:10px;">{{ $opt->nama_barang }}</div>
            <div style="background:linear-gradient(135deg,#f0faf4,#e0f5e8);border-radius:12px;padding:12px;text-align:center;margin-bottom:10px;">
                <div style="font-size:10px;color:var(--sub);font-weight:600;margin-bottom:3px;">HARGA OPTIMAL</div>
                <div style="font-size:20px;font-weight:800;color:var(--gd);">Rp {{ number_format($opt->harga_optimal,0,',','.') }}</div>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;">
                <div>
                    <div style="font-size:9px;color:var(--sub);font-weight:600;margin-bottom:2px;">TERENDAH</div>
                    <div style="font-weight:700;color:#16a34a;">Rp {{ number_format($opt->harga_terendah,0,',','.') }}</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:9px;color:var(--sub);font-weight:600;margin-bottom:2px;">TERTINGGI</div>
                    <div style="font-weight:700;color:#dc2626;">Rp {{ number_format($opt->harga_tertinggi,0,',','.') }}</div>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;padding:56px;text-align:center;background:white;border-radius:16px;border:1.5px dashed var(--border);">
            <i class="fas fa-lightbulb" style="font-size:2.5rem;color:#c6ebd4;display:block;margin-bottom:12px;"></i>
            <p style="color:var(--sub);font-weight:600;">Belum ada data rekomendasi.</p>
        </div>
        @endforelse
    </div>
</main>

<script>
function filterRek(kat) {
    document.querySelectorAll('#rekGrid .rek-card').forEach(c => {
        c.style.display = (!kat || c.dataset.kat === kat) ? '' : 'none';
    });
    ['btnSemua','btnPokok','btnSubsidi','btnPenting'].forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.classList.remove('active'); }
    });
    const map = {'':'btnSemua','pokok':'btnPokok','subsidi':'btnSubsidi','penting':'btnPenting'};
    const btn = document.getElementById(map[kat]);
    if (btn) btn.classList.add('active');
}

function shareWA() {
    const cards = Array.from(document.querySelectorAll('#rekGrid .rek-card:not([style*="display: none"])'));
    const lines = cards.slice(0,15).map(c => {
        const nama  = c.querySelector('[style*="font-size:14px"]')?.textContent?.trim() || '';
        const harga = c.querySelector('[style*="font-size:20px"]')?.textContent?.trim() || '';
        return '- ' + nama + ': ' + harga;
    });
    const txt = 'Rekomendasi Harga Optimal SIPHP Parepare\n\n' + lines.join('\n') + '\n\nSumber: SIPHP Kota Parepare';
    window.open('https://wa.me/?text=' + encodeURIComponent(txt));
}
</script>
</body>
</html>
