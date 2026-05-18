<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Kepala Dinas SIPHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        :root { --g:#d0f0c0; --gd:#2d6a4f; --gdd:#1e3a2f; --border:#d1e8d8; --text:#1a3a2a; --sub:#5a8a6a; }
        body { background: #f0faf4; margin: 0; }
        .sidebar { width:240px; position:fixed; top:0; left:0; bottom:0; background:white; border-right:1.5px solid var(--border); box-shadow:4px 0 16px rgba(45,106,79,0.07); display:flex; flex-direction:column; z-index:40; }
        .nav-item { display:flex; align-items:center; gap:10px; padding:10px 16px; border-radius:10px; margin:2px 10px; font-size:13px; font-weight:600; color:#3a5a4a; text-decoration:none; cursor:pointer; transition:all 0.18s; }
        .nav-item:hover { background:#f0faf4; color:var(--gdd); }
        .nav-item.active { background:#e8f5ee; color:var(--gdd); border-left:3px solid var(--gd); }
        .nav-item i { width:16px; text-align:center; font-size:13px; flex-shrink:0; }
        .nav-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:1.5px; color:#9ab8a8; padding:0 20px; margin:14px 0 5px; }
        .main { margin-left:240px; padding:28px 32px; min-height:100vh; }
        .stat-card { background:white; border:1.5px solid var(--border); border-radius:16px; padding:20px; box-shadow:0 2px 8px rgba(45,106,79,0.06); transition:transform 0.2s; }
        .stat-card:hover { transform:translateY(-2px); }
        .rek-card { background:white; border:1.5px solid var(--border); border-radius:14px; padding:16px; box-shadow:0 2px 8px rgba(45,106,79,0.05); transition:transform 0.2s,box-shadow 0.2s; }
        .rek-card:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(45,106,79,0.1); }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.35} }
    </style>
</head>
<body>

{{-- SIDEBAR --}}
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
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div style="min-width:0;">
            <div style="font-size:13px;font-weight:700;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Auth::user()->name }}</div>
            <div style="font-size:11px;color:var(--sub);">Kepala Dinas / Kasubag</div>
        </div>
    </div>

    <nav style="flex:1;padding:8px 0;">
        <div class="nav-label">Menu</div>
        <a href="{{ route('kepala_dinas.dashboard') }}" class="nav-item active">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('kepala_dinas.rekomendasi') }}" class="nav-item">
            <i class="fas fa-lightbulb"></i> Rekomendasi Harga
        </a>
        <a href="{{ route('kepala_dinas.laporan') }}" class="nav-item">
            <i class="fas fa-file-arrow-down"></i> Unduh Laporan
        </a>
        <div class="nav-label">Lainnya</div>
        <a href="{{ url('/') }}" class="nav-item" target="_blank">
            <i class="fas fa-globe"></i> Portal Publik
        </a>
    </nav>

    <div style="padding:12px 10px;border-top:1px solid #f0f5f1;">
        <form action="{{ route('logout') }}" method="POST" id="logoutForm">
            @csrf
            <button type="button" onclick="document.getElementById('logoutForm').submit()"
                class="nav-item" style="color:#dc2626;width:calc(100% - 20px);">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<main class="main">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-size:22px;font-weight:800;color:var(--text);">
                Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }} 👋
            </h1>
            <p style="font-size:13px;color:var(--sub);margin-top:2px;">
                {{ now()->translatedFormat('l, d F Y') }} &nbsp;·&nbsp;
                <span style="color:var(--gd);font-weight:600;">Dinas Perdagangan Kota Parepare</span>
            </p>
        </div>
        <div style="display:flex;align-items:center;gap:6px;background:#dcfce7;border:1.5px solid #bbf7d0;border-radius:10px;padding:8px 14px;font-size:12px;font-weight:700;color:#15803d;">
            <span style="width:7px;height:7px;border-radius:50%;background:#22c55e;display:inline-block;animation:pulse 2s infinite;"></span>
            Sistem Aktif
        </div>
    </div>

    {{-- Stat Bar --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:28px;">
        @foreach([
            ['Total Komoditas', $totalBarang, 'fa-boxes-stacked', '#d0f0c0', '#2d6a4f'],
            ['Total Pasar', $totalPasar, 'fa-store', '#bbf7d0', '#16a34a'],
            ['Total Data Harga', $totalHarga, 'fa-database', '#ede9fe', '#7c3aed'],
            ['Update Hari Ini', $updateHariIni, 'fa-calendar-check', '#fef9c3', '#b45309'],
        ] as [$label, $val, $icon, $bg, $color])
        <div class="stat-card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:var(--sub);">{{ $label }}</span>
                <div style="width:38px;height:38px;border-radius:11px;background:{{ $bg }};color:{{ $color }};display:flex;align-items:center;justify-content:center;">
                    <i class="fas {{ $icon }} text-sm"></i>
                </div>
            </div>
            <div style="font-size:28px;font-weight:800;color:var(--text);">{{ $val }}</div>
        </div>
        @endforeach
    </div>

    {{-- Aksi Cepat --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:28px;">
        <a href="{{ route('kepala_dinas.rekomendasi') }}"
            style="background:linear-gradient(135deg,var(--gdd),var(--gd));color:white;border-radius:16px;padding:24px;text-decoration:none;display:flex;align-items:center;gap:16px;box-shadow:0 6px 20px rgba(45,106,79,0.25);transition:transform 0.2s,opacity 0.2s;"
            onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="width:48px;height:48px;background:rgba(255,255,255,0.15);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-lightbulb" style="font-size:20px;color:#d0f0c0;"></i>
            </div>
            <div>
                <div style="font-size:16px;font-weight:800;">Rekomendasi Harga Optimal</div>
                <div style="font-size:12px;opacity:0.75;margin-top:2px;">Kalkulasi rata-rata harga terbaik dari 5 pasar</div>
            </div>
            <i class="fas fa-arrow-right" style="margin-left:auto;opacity:0.7;"></i>
        </a>

        <a href="{{ route('kepala_dinas.laporan') }}"
            style="background:white;border:1.5px solid var(--border);color:var(--text);border-radius:16px;padding:24px;text-decoration:none;display:flex;align-items:center;gap:16px;box-shadow:0 2px 10px rgba(45,106,79,0.06);transition:transform 0.2s,box-shadow 0.2s;"
            onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(45,106,79,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 10px rgba(45,106,79,0.06)'">
            <div style="width:48px;height:48px;background:var(--g);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-file-arrow-down" style="font-size:20px;color:var(--gdd);"></i>
            </div>
            <div>
                <div style="font-size:16px;font-weight:800;">Unduh Laporan</div>
                <div style="font-size:12px;color:var(--sub);margin-top:2px;">Export data harga ke PDF atau Excel</div>
            </div>
            <i class="fas fa-arrow-right" style="margin-left:auto;color:var(--sub);"></i>
        </a>
    </div>

    {{-- Rekomendasi Ringkasan --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <h2 style="font-size:16px;font-weight:800;color:var(--text);">Rekomendasi Harga Terkini</h2>
        <a href="{{ route('kepala_dinas.rekomendasi') }}" style="font-size:12px;color:var(--gd);font-weight:700;text-decoration:none;">
            Lihat Semua <i class="fas fa-arrow-right" style="font-size:10px;"></i>
        </a>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
        @forelse($rekomendasi as $opt)
        <div class="rek-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <span style="font-size:10px;font-weight:800;text-transform:uppercase;color:var(--gd);background:var(--g);padding:2px 8px;border-radius:999px;">
                    {{ $opt->kategori }}
                </span>
                <span style="font-size:10px;color:var(--sub);">
                    <i class="fas fa-store" style="font-size:9px;"></i> {{ $opt->jumlah_pasar }}
                </span>
            </div>
            <div style="font-weight:700;font-size:13px;color:var(--text);margin-bottom:8px;">{{ $opt->nama_barang }}</div>
            <div style="background:#f0faf4;border-radius:10px;padding:10px;text-align:center;margin-bottom:8px;">
                <div style="font-size:9px;color:var(--sub);font-weight:600;margin-bottom:2px;">OPTIMAL</div>
                <div style="font-size:17px;font-weight:800;color:var(--gd);">Rp {{ number_format($opt->harga_optimal,0,',','.') }}</div>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:11px;">
                <span style="color:#16a34a;font-weight:700;">↓ Rp {{ number_format($opt->harga_terendah,0,',','.') }}</span>
                <span style="color:#dc2626;font-weight:700;">↑ Rp {{ number_format($opt->harga_tertinggi,0,',','.') }}</span>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;padding:40px;text-align:center;background:white;border-radius:14px;border:1.5px dashed var(--border);">
            <i class="fas fa-lightbulb" style="font-size:2rem;color:#c6ebd4;display:block;margin-bottom:10px;"></i>
            <p style="color:var(--sub);">Belum ada data rekomendasi.</p>
        </div>
        @endforelse
    </div>
</main>
</body>
</html>
