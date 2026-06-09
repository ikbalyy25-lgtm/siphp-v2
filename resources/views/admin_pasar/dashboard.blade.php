<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Pasar — SIPHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        :root { --g:#d0f0c0; --gd:#2d6a4f; --gdd:#1e3a2f; --border:#d1e8d8; --text:#1a3a2a; --sub:#5a8a6a; }
        body { background: #f0faf4; margin: 0; }

        .sidebar {
            width: 240px; position: fixed; top: 0; left: 0; bottom: 0;
            background: white; border-right: 1.5px solid var(--border);
            box-shadow: 4px 0 16px rgba(45,106,79,0.07);
            display: flex; flex-direction: column; z-index: 40;
            overflow-y: auto;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 16px; border-radius: 10px; margin: 2px 10px;
            font-size: 13px; font-weight: 600; color: #3a5a4a;
            text-decoration: none; cursor: pointer; transition: all 0.18s;
        }
        .nav-item:hover { background: #f0faf4; color: var(--gdd); }
        .nav-item.active { background: #e8f5ee; color: var(--gdd); border-left: 3px solid var(--gd); }
        .nav-item i { width: 16px; text-align: center; font-size: 13px; flex-shrink: 0; }
        .nav-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #9ab8a8; padding: 0 20px; margin: 14px 0 5px; }
        .main { margin-left: 240px; padding: 28px 32px; min-height: 100vh; }
        .stat-card { background: white; border: 1.5px solid var(--border); border-radius: 16px; padding: 20px; box-shadow: 0 2px 8px rgba(45,106,79,0.06); transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-2px); }
        .kat-card { position: relative; border-radius: 18px; overflow: hidden; cursor: pointer; height: 180px; transition: transform 0.3s, box-shadow 0.3s; box-shadow: 0 4px 14px rgba(30,58,47,0.1); border: 1.5px solid var(--border); text-decoration: none; display: block; }
        .kat-card:hover { transform: translateY(-5px); box-shadow: 0 16px 36px rgba(30,58,47,0.18); }
        .kat-card:hover img { transform: scale(1.07); }
        .kat-card img { width:100%; height:100%; object-fit:cover; transition: transform 0.5s; }
        .kat-card .ov { position:absolute; inset:0; background:linear-gradient(to top,rgba(15,40,25,0.88) 0%,rgba(15,40,25,0.2) 55%,transparent 100%); }
        .kat-card .cnt { position:absolute; bottom:0; left:0; right:0; padding:16px 18px; }
        .kat-card .arrow { position:absolute; top:12px; right:12px; width:28px; height:28px; border-radius:50%; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; color:white; font-size:11px; transition:all 0.2s; }
        .kat-card:hover .arrow { background:var(--g); color:var(--gdd); transform:rotate(45deg); }
        .tbl-row { display:grid; grid-template-columns:2fr 1fr 1.2fr 1.2fr 1fr 0.7fr; padding:13px 20px; border-bottom:1px solid #e8f5ee; align-items:center; background:white; transition:background 0.15s; font-size:13px; }
        .tbl-row:hover { background:#f5fdf7; }
        .modal-overlay { display:none; position:fixed; inset:0; z-index:60; background:rgba(30,58,47,0.45); backdrop-filter:blur(5px); align-items:center; justify-content:center; }
        .modal-overlay.open { display:flex; }
        @keyframes popIn { from{transform:scale(0.9);opacity:0} to{transform:scale(1);opacity:1} }
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
                <div style="font-size:10px;color:var(--sub);">Admin Pasar</div>
            </div>
        </div>
    </div>

    <div style="padding:14px 20px;border-bottom:1px solid #f0f5f1;display:flex;align-items:center;gap:10px;">
        <div style="width:38px;height:38px;border-radius:10px;background:var(--g);color:var(--gdd);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:15px;flex-shrink:0;">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div style="min-width:0;">
            <div style="font-size:13px;font-weight:700;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Auth::user()->name }}</div>
            <div style="font-size:11px;color:var(--sub);">{{ $pasar->nama_pasar }}</div>
        </div>
    </div>

    <nav style="flex:1;padding:8px 0;">
        <div class="nav-label">Menu</div>
        <a href="{{ route('admin_pasar.dashboard') }}" class="nav-item active">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('admin_pasar.harga.index', 'pokok') }}" class="nav-item">
            <i class="fas fa-shopping-basket"></i> Barang Pokok
        </a>
        <a href="{{ route('admin_pasar.harga.index', 'subsidi') }}" class="nav-item">
            <i class="fas fa-tags"></i> Barang Subsidi
        </a>
        <a href="{{ route('admin_pasar.harga.index', 'penting') }}" class="nav-item">
            <i class="fas fa-hard-hat"></i> Barang Penting
        </a>
    </nav>

    <div style="padding:12px 10px;border-top:1px solid #f0f5f1;">
        <form action="{{ route('logout') }}" method="POST" id="logoutForm">
            @csrf
            <button type="button" onclick="document.getElementById('logoutForm').submit()"
                class="nav-item w-full text-left" style="color:#dc2626;width:calc(100% - 20px);">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<main class="main">

    @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #22c55e;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#15803d;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Topbar --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-size:22px;font-weight:800;color:var(--text);">Dashboard</h1>
            <p style="font-size:13px;color:var(--sub);margin-top:2px;">
                {{ now()->translatedFormat('l, d F Y') }} &nbsp;·&nbsp;
                <span style="color:var(--gd);font-weight:600;">{{ $pasar->nama_pasar }}</span>
            </p>
        </div>
        <div style="display:flex;align-items:center;gap:6px;background:#dcfce7;border:1.5px solid #bbf7d0;border-radius:10px;padding:8px 14px;font-size:12px;font-weight:700;color:#15803d;">
            <span style="width:7px;height:7px;border-radius:50%;background:#22c55e;display:inline-block;animation:pulse 2s infinite;"></span>
            Sistem Aktif
        </div>
    </div>

    {{-- Stat Bar --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
        @foreach([
            ['Harga Published', $totalPublished, 'fa-check-circle', '#d0f0c0', '#2d6a4f'],
            ['Menunggu Approve', $totalPending, 'fa-clock', '#fef9c3', '#b45309'],
            ['Terkirim Hari Ini', $hariIni, 'fa-calendar-check', '#ede9fe', '#7c3aed'],
            ['Belum Disetujui', $inputTerkirim, 'fa-paper-plane', '#fef2f2', '#dc2626'],
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

    {{-- Kartu Kategori --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <h2 style="font-size:16px;font-weight:800;color:var(--text);">Input Harga Barang</h2>
        <span style="font-size:12px;color:var(--sub);">Klik kategori untuk input harga</span>
    </div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px;">
        @foreach([
            ['pokok',   'Barang Pokok',   'Beras, gula, minyak...', 'barangpokok.png'],
            ['subsidi', 'Barang Subsidi', 'LPG 3kg, beras BULOG...', 'barangsubsidi.png'],
            ['penting', 'Barang Penting', 'Semen, besi, BBM...', 'barangpenting.png'],
        ] as [$kat, $title, $desc, $img])
        <a href="{{ route('admin_pasar.harga.index', $kat) }}" class="kat-card">
            <img src="{{ asset('img/'.$img) }}" onerror="this.parentElement.style.background='linear-gradient(135deg,#1e3a2f,#2d6a4f)'">
            <div class="ov"></div>
            <div class="arrow"><i class="fas fa-arrow-right"></i></div>
            <div class="cnt">
                <h3 style="font-size:18px;font-weight:800;color:white;margin:0 0 3px;">{{ $title }}</h3>
                <p style="font-size:11px;color:rgba(255,255,255,0.65);margin:0;">{{ $desc }}</p>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Tabel Harga Terkini --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
        <h2 style="font-size:16px;font-weight:800;color:var(--text);">Harga Terkini (Published)</h2>
        <span style="font-size:12px;color:var(--sub);">{{ $data_harga->count() }} data</span>
    </div>

    <div style="border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(45,106,79,0.08);border:1.5px solid var(--border);">
        <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1.5fr 1fr;padding:13px 20px;background:linear-gradient(135deg,var(--gdd),var(--gd));color:var(--g);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;text-align:center;">
            <div>Nama Barang</div>
            <div>Satuan</div>
            <div>Kategori</div>
            <div>Tanggal</div>
            <div>Harga Rata-Rata</div>
            <div>Status</div>
        </div>

        @forelse($data_harga->take(10) as $h)
        <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1.5fr 1fr;padding:13px 20px;border-bottom:1px solid #e8f5ee;align-items:center;background:white;transition:background 0.15s;font-size:13px;text-align:center;"
             onmouseover="this.style.background='#f5fdf7'" onmouseout="this.style.background='white'">
            <div style="font-weight:700;color:var(--text);">
                {{ $h->nama_barang }}
            </div>
            <div style="color:var(--sub);font-size:12px;font-weight:500;">
                {{ $h->satuan && $h->satuan !== '-' ? $h->satuan : '-' }}
            </div>
            <div style="color:var(--sub);font-size:12px;">
                {{ ucfirst($h->kategori) }}
            </div>
            <div style="color:var(--sub);font-size:12px;">
                {{ \Carbon\Carbon::parse($h->tanggal)->format('d M Y') }}
            </div>
            <div style="font-weight:800;color:var(--gd);font-size:14px;">
                Rp {{ number_format($h->harga_hari_ini,0,',','.') }}
            </div>
            <div>
                @if($h->status === 'published')
                <span style="background:#dcfce7;color:#16a34a;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                    <i class="fas fa-check"></i> Published
                </span>
                @else
                <span style="background:#fef9c3;color:#b45309;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                    <i class="fas fa-clock"></i> Pending
                </span>
                @endif
            </div>
        </div>
        @empty
        <div style="padding:48px;text-align:center;background:white;">
            <i class="fas fa-inbox" style="font-size:2.5rem;color:#c6ebd4;display:block;margin-bottom:10px;"></i>
            <p style="color:var(--sub);font-size:14px;">Belum ada data harga yang disetujui. Klik kartu di atas untuk mulai input.</p>
        </div>
        @endforelse
    </div>
</main>
</body>
</html>
