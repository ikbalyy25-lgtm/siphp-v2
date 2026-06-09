<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — SIPHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

        :root {
            --g:    #d0f0c0;
            --gd:   #2d6a4f;
            --gdd:  #1e3a2f;
            --bg:   #f0faf4;
            --card: #ffffff;
            --border: #d1e8d8;
            --text: #1a3a2a;
            --sub:  #5a8a6a;
        }

        body { background: var(--bg); min-height: 100vh; overflow-x: hidden; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 256px; flex-shrink: 0;
            background: #ffffff;
            position: fixed; top: 0; left: 0; bottom: 0; z-index: 100;
            display: flex; flex-direction: column;
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
            overflow-y: auto;
        }

        .sidebar::-webkit-scrollbar { width: 0; }

        .nav-section-label {
            font-size: 10px; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #9ab8a8;
            padding: 0 20px; margin: 16px 0 6px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 16px; border-radius: 10px; margin: 1px 10px;
            font-size: 13px; font-weight: 600;
            color: #3a5a4a;
            text-decoration: none; cursor: pointer;
            transition: all 0.18s;
            border: 1px solid transparent;
        }

        .nav-item:hover {
            background: #f0faf4;
            color: #1a3a2a;
        }

        .nav-item.active {
            background: #e8f5ee;
            color: #1a3a2a;
            border-color: #c0deca;
            border-left: 3px solid #2d6a4f;
        }

        .nav-item i { width: 16px; text-align: center; font-size: 13px; flex-shrink: 0; }

        .nav-badge {
            margin-left: auto;
            background: #ef4444;
            color: white;
            font-size: 10px; font-weight: 700;
            padding: 1px 6px; border-radius: 999px;
        }

        /* Pasar dropdown */
        .pasar-dropdown { display: none; }
        .pasar-dropdown.open { display: block; }
        .pasar-option {
            display: block;
            padding: 8px 16px 8px 44px;
            font-size: 12px; font-weight: 500;
            color: #5a8a6a;
            text-decoration: none;
            border-radius: 8px; margin: 1px 10px;
            transition: all 0.15s;
        }
        .pasar-option:hover { background: #f0faf4; color: #1a3a2a; }
        .pasar-option.active { background: #e8f5ee; color: #2d6a4f; font-weight: 700; border-left: 3px solid #2d6a4f; }

        /* ── MAIN ── */
        .main { margin-left: 256px; padding: 28px 32px; min-height: 100vh; position: relative; z-index: 1; }

        /* Topbar */
        .topbar {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 28px;
        }

        /* Stat cards */
        .stat-card {
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 18px;
            padding: 20px 22px;
            box-shadow: 0 2px 10px rgba(45,106,79,0.06);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(45,106,79,0.1); }

        .stat-icon {
            width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
        }

        /* Kategori cards */
        .kat-card {
            position: relative; border-radius: 18px; overflow: hidden;
            cursor: pointer; height: 185px;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 14px rgba(30,58,47,0.1);
            border: 1.5px solid var(--border);
            text-decoration: none; display: block;
        }
        .kat-card:hover { transform: translateY(-5px); box-shadow: 0 16px 36px rgba(30,58,47,0.18); }
        .kat-card:hover img { transform: scale(1.07); }
        .kat-card img { width:100%; height:100%; object-fit:cover; transition:transform 0.5s; }
        .kat-card .ov {
            position:absolute; inset:0;
            background: linear-gradient(to top, rgba(15,40,25,0.88) 0%, rgba(15,40,25,0.25) 55%, transparent 100%);
        }
        .kat-card .cnt { position:absolute; bottom:0; left:0; right:0; padding:16px 18px; }
        .kat-card .num-badge {
            position:absolute; top:12px; left:12px;
            background: rgba(255,255,255,0.92);
            color: var(--gd); font-size:10px; font-weight:800;
            padding:3px 9px; border-radius:999px;
        }
        .kat-card .arrow {
            position:absolute; top:12px; right:12px;
            width:28px; height:28px; border-radius:50%;
            background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3);
            display:flex; align-items:center; justify-content:center;
            color:white; font-size:11px; transition:all 0.2s;
        }
        .kat-card:hover .arrow { background:var(--g); color:var(--gdd); transform:rotate(45deg); }
        .kat-card h3 { font-size:17px; font-weight:800; color:white; margin-bottom:2px; }
        .kat-card p  { font-size:11px; color:rgba(255,255,255,0.65); }

        /* Tabel harga */
        .tbl-head {
            display: grid;
            grid-template-columns: 2fr 1fr 1.5fr 1fr 1fr;
            padding: 12px 20px;
            background: linear-gradient(135deg, var(--gdd), var(--gd));
            color: var(--g);
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.7px;
            border-radius: 14px 14px 0 0;
        }

        .tbl-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1.5fr 1fr 1fr;
            padding: 13px 20px;
            border-bottom: 1px solid #e8f5ee;
            align-items: center;
            background: white;
            font-size: 13px;
            transition: background 0.15s;
        }
        .tbl-row:hover { background: #f5fdf7; }
        .tbl-row:last-child { border-radius: 0 0 14px 14px; border-bottom: none; }

        /* Modal */
        .modal-overlay { display:none; position:fixed; inset:0; z-index:60; background:rgba(30,58,47,0.45); backdrop-filter:blur(5px); align-items:center; justify-content:center; }
        .modal-overlay.open { display:flex; }
        .modal-box {
            background:white; border-radius:22px;
            box-shadow: 0 32px 64px rgba(30,58,47,0.2);
            animation: popIn 0.25s cubic-bezier(0.34,1.56,0.64,1);
            border-top: 4px solid var(--gd);
        }
        @keyframes popIn { from{transform:scale(0.9);opacity:0} to{transform:scale(1);opacity:1} }

        .form-inp {
            width:100%; padding:11px 14px; border-radius:11px; font-size:14px;
            border:1.5px solid var(--border); background:#f8fdf9; color:var(--text);
            outline:none; transition:border-color 0.2s, box-shadow 0.2s;
            font-family:'Plus Jakarta Sans',sans-serif;
        }
        .form-inp:focus { border-color:var(--gd); box-shadow:0 0 0 3px rgba(45,106,79,0.1); }

        .btn-green {
            background: linear-gradient(135deg,var(--gdd),var(--gd));
            color:white; border:none; border-radius:11px;
            padding:11px 22px; font-weight:700; font-size:13px;
            cursor:pointer; transition:opacity 0.2s, transform 0.2s;
            font-family:'Plus Jakarta Sans',sans-serif;
        }
        .btn-green:hover { opacity:0.9; transform:translateY(-1px); }

        .btn-ghost {
            background:transparent; color:var(--sub); border:none;
            padding:11px 18px; font-weight:600; font-size:13px;
            cursor:pointer; border-radius:11px; transition:background 0.2s;
            font-family:'Plus Jakarta Sans',sans-serif;
        }
        .btn-ghost:hover { background:#f0f0f0; }

        /* Prediksi badge */
        .pred-up   { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
        .pred-down { background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; }
        .pred-same { background:#f8fafc; color:#64748b; border:1px solid #e2e8f0; }
        .pred-none { background:#f9f9f9; color:#9ca3af; }

        .badge-update  { background:#dcfce7; color:#16a34a; border:1px solid #bbf7d0; }
        .badge-pending { background:#fef9c3; color:#b45309; border:1px solid #fde68a; }

        /* Notif */
        .notif-dot { width:8px; height:8px; border-radius:50%; background:#ef4444; animation:blink 2s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

        /* Alert */
        .alert-success {
            background:#f0fdf4; border:1px solid #bbf7d0; border-left:4px solid #22c55e;
            border-radius:12px; padding:12px 16px; color:#15803d; font-size:13px; margin-bottom:20px;
        }

        /* Rekomendasi harga optimal page */
        .rek-card {
            background:white; border:1.5px solid var(--border); border-radius:16px;
            padding:18px; box-shadow:0 2px 8px rgba(45,106,79,0.06);
            transition:transform 0.2s, box-shadow 0.2s;
        }
        .rek-card:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(45,106,79,0.1); }

        /* Scrollbar */
        ::-webkit-scrollbar { width:5px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:#c6ebd4; border-radius:3px; }

        /* Pulse */
        .pulse { animation:pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.35} }
    </style>
</head>
<body>

{{-- ════════════════════════════════════
     SIDEBAR
════════════════════════════════════ --}}
<aside class="sidebar">

    {{-- Logo --}}
    <div style="padding:20px; border-bottom:1px solid #e8f0eb;">
        <div style="display:flex; align-items:center; gap:12px;">
            <img src="{{ asset('img/logo.png') }}" style="width:38px;height:38px;">
            <div>
                <div style="font-size:15px;font-weight:800;color:#1a3a2a;">SIPHP</div>
                <div style="font-size:11px;color:#7a9a8a;">Panel Administrator</div>
            </div>
        </div>
    </div>

    {{-- Admin info --}}
    <div style="padding:14px 20px; border-bottom:1px solid #f0f5f1; display:flex; align-items:center; gap:10px;">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username ?? 'Admin') }}&background=2d6a4f&color=d0f0c0&bold=true"
            style="width:38px;height:38px;border-radius:10px;flex-shrink:0;">
        <div style="min-width:0;">
            <div style="font-size:13px;font-weight:700;color:#1a3a2a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ Auth::user()->username ?? 'Admin' }}
            </div>
            <div style="font-size:11px;color:#7a9a8a;">Administrator</div>
        </div>
        <span class="notif-dot" style="margin-left:auto;flex-shrink:0;"></span>
    </div>

    {{-- Nav --}}
    <nav style="flex:1;padding:8px 0;">

        <div class="nav-section-label">Utama</div>

        <a href="/admin/dashboard" class="nav-item active">
            <i class="fas fa-home"></i> Dashboard
        </a>

        {{-- Pilih Pasar --}}
        <div class="nav-item" onclick="togglePasar()" style="cursor:pointer;">
            <i class="fas fa-store"></i>
            <span>Pilih Pasar</span>
            <i class="fas fa-chevron-down" id="pasarArrow" style="margin-left:auto;font-size:10px;transition:transform 0.2s;color:#9ab8a8;"></i>
        </div>
        <div class="pasar-dropdown" id="pasarDropdown">
            @foreach($semua_pasar as $p)
            <a href="{{ url('/admin/ganti-pasar/' . $p->id) }}" class="pasar-option {{ session('pasar_aktif_id') == $p->id ? 'active' : '' }}">
                <i class="fas fa-circle-dot" style="font-size:8px;margin-right:6px;"></i>{{ $p->nama_pasar }}
            </a>
            @endforeach
        </div>

        <div class="nav-section-label">Manajemen</div>

        <a href="/admin/antrian" class="nav-item" id="navAntrian">
            <i class="fas fa-inbox"></i> Antrian Harga
            @php $jmlAntrianNav = \App\Models\HargaHarian::pending()->count(); @endphp
            @if($jmlAntrianNav > 0)<span class="nav-badge">{{ $jmlAntrianNav }}</span>@endif
        </a>

        {{-- FITUR UTAMA: Rekomendasi Harga --}}
        <a href="/admin/rekomendasi" class="nav-item" id="navRekomendasi">
            <i class="fas fa-lightbulb"></i> Rekomendasi Harga
        </a>

        {{-- Komparasi Pasar --}}
        <a href="/admin/rekomendasi/komparasi" class="nav-item" id="navKomparasi">
            <i class="fas fa-scale-balanced"></i> Komparasi Pasar
        </a>

        {{-- Statistik --}}
        <a href="{{ $pasar_aktif ? url('/admin/statistik/pasar/' . $pasar_aktif->id) : '#' }}" class="nav-item">
            <i class="fas fa-chart-line"></i> Statistik Harga
        </a>

        <div class="nav-section-label">Data & Laporan</div>

        <a href="/admin/pengaduan" class="nav-item">
            <i class="fas fa-bell"></i> Pengaduan
            @php 
                $lastSeenId = session('last_seen_pengaduan_id', 0);
                $jmlPengaduan = \Illuminate\Support\Facades\DB::table('pengaduan')->where('id', '>', $lastSeenId)->count(); 
            @endphp
            @if($jmlPengaduan > 0)<span class="nav-badge">{{ $jmlPengaduan }}</span>@endif
        </a>

        <a href="/admin/kelola-kepala-dinas" class="nav-item">
            <i class="fas fa-user-tie"></i> Kelola Kepala Dinas
        </a>

         <a href="/admin/kelola-admin-pasar" class="nav-item">
            <i class="fas fa-user-cog"></i> Kelola Admin Pasar
        </a>

        <a href="/admin/retail" class="nav-item">
            <i class="fas fa-shop"></i> Manajemen Ritel
        </a>

        <a href="#" onclick="openLaporanModal(event)" class="nav-item">
            <i class="fas fa-file-arrow-down"></i> Unduh Laporan
        </a>

        <div class="nav-section-label">Sistem</div>

        <a href="#" onclick="showLogoutModal(event)" class="nav-item" style="color:#dc2626;">
            <i class="fas fa-sign-out-alt"></i> Keluar
        </a>
    </nav>

    {{-- Versi --}}
    <div style="padding:14px 20px; border-top:1px solid #f0f5f1; font-size:10px; color:rgba(208,240,192,0.3); text-align:center;">
        SIPHP v1.0 &nbsp;·&nbsp; &copy; 2026 MAROA TEAM
    </div>
</aside>

{{-- ════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════ --}}
<main class="main" id="mainContent">

    @if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle" style="margin-right:8px;"></i>{{ session('success') }}
    </div>
    @endif

    {{-- PANEL: DASHBOARD (default) --}}
    <div id="panelDashboard">

        {{-- Topbar --}}
        <div class="topbar">
            <div>
                <h1 style="font-size:22px;font-weight:800;color:var(--text);">
                    {{ $pasar_aktif->nama_pasar ?? 'Dashboard Admin' }}
                </h1>
                <p style="font-size:13px;color:var(--sub);margin-top:2px;">
                    {{ now()->translatedFormat('l, d F Y') }} &nbsp;·&nbsp;
                    <span style="color:var(--gd);font-weight:600;">{{ now()->format('H:i') }} WIB</span>
                </p>
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
                {{-- Search --}}
                <div style="display:flex;align-items:center;gap:8px;background:white;border:1.5px solid var(--border);border-radius:12px;padding:9px 16px;width:220px;">
                    <i class="fas fa-search" style="color:#a3c4aa;font-size:13px;"></i>
                    <input type="text" placeholder="Cari barang..." id="searchBar"
                        style="border:none;outline:none;background:transparent;font-size:13px;color:var(--text);width:100%;font-family:'Plus Jakarta Sans',sans-serif;"
                        oninput="filterTabel(this.value)">
                </div>
                {{-- Pasar badge --}}
                @if($pasar_aktif)
                <div style="display:flex;align-items:center;gap:7px;background:var(--g);border-radius:12px;padding:9px 16px;">
                    <span class="pulse" style="width:7px;height:7px;border-radius:50%;background:var(--gd);flex-shrink:0;"></span>
                    <span style="font-size:12px;font-weight:700;color:var(--gdd);">{{ $pasar_aktif->nama_pasar }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Stat Bar --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px;">
            <div class="stat-card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:var(--sub);">Total Barang</span>
                    <div class="stat-icon" style="background:#d0f0c0;color:var(--gd);"><i class="fas fa-box"></i></div>
                </div>
                <div style="font-size:30px;font-weight:800;color:var(--text);">{{ $totalHarga }}</div>
                <div style="font-size:11px;color:var(--sub);margin-top:3px;">Data tercatat hari ini</div>
            </div>
            <div class="stat-card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:var(--sub);">Sudah Update</span>
                    <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="fas fa-check-circle"></i></div>
                </div>
                <div style="font-size:30px;font-weight:800;color:#16a34a;">{{ $totalPublished }}</div>
                <div style="font-size:11px;color:var(--sub);margin-top:3px;">Harga terkonfirmasi</div>
            </div>
            <div class="stat-card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:var(--sub);">Menunggu Update</span>
                    <div class="stat-icon" style="background:#fef9c3;color:#b45309;"><i class="fas fa-clock"></i></div>
                </div>
                <div style="font-size:30px;font-weight:800;color:#b45309;">{{ $totalPending }}</div>
                <div style="font-size:11px;color:var(--sub);margin-top:3px;">Perlu dikonfirmasi</div>
            </div>
            <div class="stat-card" style="cursor:pointer;" onclick="window.location='/admin/antrian'">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:var(--sub);">Antrian Masuk</span>
                    <div class="stat-icon" style="background:#fef9c3;color:#b45309;"><i class="fas fa-inbox"></i></div>
                </div>
                @php $jmlAntrian = \App\Models\HargaHarian::pending()->count(); @endphp
                <div style="font-size:30px;font-weight:800;color:#b45309;">{{ $jmlAntrian }}</div>
                <div style="font-size:11px;color:var(--sub);margin-top:3px;">Menunggu persetujuan</div>
            </div>
        </div>

        {{-- Kartu Kategori --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <h2 style="font-size:16px;font-weight:800;color:var(--text);">Kelola Harga Barang</h2>
            <span style="font-size:12px;color:var(--sub);">Klik kategori untuk input & update harga</span>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-bottom:28px;">
            <a href="{{ url('/admin/harga/pokok') }}" class="kat-card">
                <img src="{{ asset('img/barangpokok.png') }}" alt=""
                    onerror="this.parentElement.style.background='linear-gradient(135deg,#1e3a2f,#2d6a4f)'">
                <div class="ov"></div>
                <span class="num-badge">01</span>
                <div class="arrow"><i class="fas fa-arrow-right"></i></div>
                <div class="cnt">
                    <h3>Barang Pokok</h3>
                    <p>Beras, gula, minyak, telur...</p>
                </div>
            </a>
            <a href="{{ url('/admin/harga/subsidi') }}" class="kat-card">
                <img src="{{ asset('img/barangsubsidi.png') }}" alt=""
                    onerror="this.parentElement.style.background='linear-gradient(135deg,#1a2a4a,#2d4a6f)'">
                <div class="ov"></div>
                <span class="num-badge">02</span>
                <div class="arrow"><i class="fas fa-arrow-right"></i></div>
                <div class="cnt">
                    <h3>Barang Subsidi</h3>
                    <p>LPG 3kg, minyak curah...</p>
                </div>
            </a>
            <a href="{{ url('/admin/harga/penting') }}" class="kat-card">
                <img src="{{ asset('img/barangpenting.png') }}" alt=""
                    onerror="this.parentElement.style.background='linear-gradient(135deg,#3a2a1a,#6f4a2d)'">
                <div class="ov"></div>
                <span class="num-badge">03</span>
                <div class="arrow"><i class="fas fa-arrow-right"></i></div>
                <div class="cnt">
                    <h3>Barang Penting</h3>
                    <p>Semen, besi, BBM...</p>
                </div>
            </a>
        </div>

        {{-- Tabel Harga Terkini --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <h2 style="font-size:16px;font-weight:800;color:var(--text);">
                Data Harga Terkini
                <span style="font-size:12px;font-weight:500;color:var(--sub);margin-left:8px;">
                    {{ $pasar_aktif->nama_pasar ?? 'Pilih Pasar' }}
                </span>
            </h2>
            <span id="tabelCount" style="font-size:12px;color:var(--sub);">{{ $data_harga->count() }} data</span>
        </div>

        <div style="border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(45,106,79,0.08);border:1.5px solid var(--border);">
            <div class="tbl-head" style="grid-template-columns:2fr 1fr 1.5fr 1fr;">
                <div>Nama Barang</div>
                <div>Tanggal</div>
                <div>Harga Rata-rata</div>
                <div style="text-align:center;">Status</div>
            </div>

            <div id="tabelBody">
            @forelse($data_harga as $h)
            <div class="tbl-row harga-row" data-nama="{{ strtolower($h->nama_barang) }}" style="grid-template-columns:2fr 1fr 1.5fr 1fr;">
                <div>
                    <div style="font-weight:700;color:var(--text);">{{ $h->nama_barang }}</div>
                    <div style="font-size:11px;color:var(--sub);margin-top:2px;">{{ ucfirst($h->kategori) }}</div>
                </div>
                <div style="color:var(--sub);font-size:12px;">
                    {{ \Carbon\Carbon::parse($h->tanggal)->format('d M Y') }}
                </div>
                <div style="font-weight:700;color:var(--gd);font-size:14px;">
                    Rp {{ number_format($h->harga_hari_ini, 0, ',', '.') }}
                </div>
                <div style="text-align:center;">
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
            <div style="padding:48px;text-align:center;background:white;border-radius:0 0 14px 14px;">
                <i class="fas fa-inbox" style="font-size:2.5rem;color:#c6ebd4;display:block;margin-bottom:12px;"></i>
                <p style="color:var(--sub);font-size:14px;">Belum ada data harga untuk pasar ini.</p>
            </div>
            @endforelse
            </div>
        </div>
    </div>

    {{-- PANEL: REKOMENDASI HARGA OPTIMAL --}}
    <div id="panelRekomendasi" style="display:none;">
        <div class="topbar">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
                    <button onclick="backToDashboard()" style="background:var(--g);border:none;color:var(--gdd);width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:13px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <h1 style="font-size:22px;font-weight:800;color:var(--text);">Rekomendasi Harga Optimal</h1>
                </div>
                <p style="font-size:13px;color:var(--sub);margin-left:42px;">Kalkulasi rata-rata harga terbaik dari seluruh pasar di Kota Parepare</p>
            </div>
            <span style="background:var(--g);color:var(--gdd);font-size:11px;font-weight:700;padding:7px 14px;border-radius:999px;">
                <i class="fas fa-store" style="margin-right:5px;"></i>Berdasarkan {{ count($semua_pasar) }} Pasar
            </span>
        </div>

        {{-- Filter Kategori Rekomendasi --}}
        <div style="display:flex;gap:8px;margin-bottom:18px;flex-wrap:wrap;">
            <button onclick="filterRek('')" id="rekAll" style="background:var(--gdd);color:white;border:none;border-radius:8px;padding:7px 16px;font-size:12px;font-weight:700;cursor:pointer;">Semua</button>
            <button onclick="filterRek('pokok')" id="rekPokok" style="background:white;border:1.5px solid var(--border);color:var(--gd);border-radius:8px;padding:7px 16px;font-size:12px;font-weight:700;cursor:pointer;">Pokok</button>
            <button onclick="filterRek('subsidi')" id="rekSubsidi" style="background:white;border:1.5px solid var(--border);color:var(--gd);border-radius:8px;padding:7px 16px;font-size:12px;font-weight:700;cursor:pointer;">Subsidi</button>
            <button onclick="filterRek('penting')" id="rekPenting" style="background:white;border:1.5px solid var(--border);color:var(--gd);border-radius:8px;padding:7px 16px;font-size:12px;font-weight:700;cursor:pointer;">Penting</button>
            <div style="margin-left:auto;">
                <button onclick="shareRekomendasi()" style="background:var(--g);color:var(--gdd);border:none;border-radius:8px;padding:7px 16px;font-size:12px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-share-nodes"></i> Bagikan Semua
                </button>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;" id="rekContainer">
            @forelse($rekomendasi_harga as $opt)
            <div class="rek-card" data-kategori="{{ $opt->kategori }}">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                    <span style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:0.8px;color:var(--gd);background:var(--g);padding:3px 9px;border-radius:999px;">
                        {{ $opt->kategori }}
                    </span>
                    <span style="font-size:10px;color:var(--sub);">
                        <i class="fas fa-store" style="font-size:9px;"></i> {{ $opt->jumlah_pasar_terdata }} pasar
                    </span>
                </div>
                <div style="font-weight:700;font-size:14px;color:var(--text);margin-bottom:10px;">{{ $opt->nama_barang }}</div>
                <div style="background:linear-gradient(135deg,#f0faf4,#e0f5e8);border-radius:12px;padding:12px;text-align:center;margin-bottom:10px;">
                    <div style="font-size:10px;color:var(--sub);font-weight:600;margin-bottom:4px;">HARGA OPTIMAL</div>
                    <div style="font-size:20px;font-weight:800;color:var(--gd);">Rp {{ number_format($opt->harga_optimal, 0, ',', '.') }}</div>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <div>
                        <div style="font-size:9px;color:var(--sub);text-transform:uppercase;font-weight:600;">Terendah</div>
                        <div style="font-size:12px;font-weight:700;color:#16a34a;">Rp {{ number_format($opt->harga_terendah, 0, ',', '.') }}</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:9px;color:var(--sub);text-transform:uppercase;font-weight:600;">Tertinggi</div>
                        <div style="font-size:12px;font-weight:700;color:#dc2626;">Rp {{ number_format($opt->harga_tertinggi, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1;padding:48px;text-align:center;background:white;border-radius:16px;border:1.5px dashed var(--border);">
                <i class="fas fa-lightbulb" style="font-size:2.5rem;color:#c6ebd4;display:block;margin-bottom:12px;"></i>
                <p style="color:var(--sub);">Belum ada data untuk kalkulasi rekomendasi.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- PANEL: KOMPARASI HARGA ANTAR PASAR (FITUR BARU) --}}
    <div id="panelKomparasi" style="display:none;">
        <div class="topbar">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
                    <button onclick="backToDashboard()" style="background:var(--g);border:none;color:var(--gdd);width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:13px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <h1 style="font-size:22px;font-weight:800;color:var(--text);">Komparasi Harga Antar Pasar</h1>
                </div>
                <p style="font-size:13px;color:var(--sub);margin-left:42px;">Bandingkan harga barang yang sama di seluruh pasar sekaligus</p>
            </div>
        </div>

        {{-- Filter --}}
        <div style="background:white;border:1.5px solid var(--border);border-radius:16px;padding:20px;margin-bottom:20px;display:flex;align-items:flex-end;gap:16px;">
            <div style="flex:1;">
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:var(--sub);display:block;margin-bottom:6px;">Nama Barang</label>
                <input type="text" id="komparasiSearch" placeholder="Contoh: Beras Medium"
                    class="form-inp" oninput="loadKomparasi()">
            </div>
            <div>
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:var(--sub);display:block;margin-bottom:6px;">Kategori</label>
                <select id="komparasiKategori" class="form-inp" style="width:160px;" onchange="loadKomparasi()">
                    <option value="">Semua</option>
                    <option value="pokok">Barang Pokok</option>
                    <option value="subsidi">Barang Subsidi</option>
                    <option value="penting">Barang Penting</option>
                </select>
            </div>
        </div>

        {{-- Tabel komparasi --}}
        <div style="border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(45,106,79,0.08);border:1.5px solid var(--border);">
            <div style="display:grid;grid-template-columns:2fr repeat({{ count($semua_pasar) }},1fr);background:linear-gradient(135deg,var(--gdd),var(--gd));padding:12px 20px;color:var(--g);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;">
                <div>Nama Barang</div>
                @foreach($semua_pasar as $p)
                <div style="text-align:center;">{{ \Illuminate\Support\Str::limit($p->nama_pasar, 12) }}</div>
                @endforeach
            </div>

            @php
                $komparasiData = \Illuminate\Support\Facades\DB::table('harga_harians')
                    ->where('status','published')
                    ->orderBy('nama_barang')
                    ->get()
                    ->groupBy('nama_barang');
            @endphp

            @forelse($komparasiData->take(20) as $namaBarang => $rows)
            @php $byPasar = $rows->keyBy('pasar_id'); @endphp
            <div style="display:grid;grid-template-columns:2fr repeat({{ count($semua_pasar) }},1fr);padding:12px 20px;border-bottom:1px solid #e8f5ee;background:white;align-items:center;transition:background 0.15s;"
                onmouseover="this.style.background='#f5fdf7'" onmouseout="this.style.background='white'">
                <div style="font-weight:600;color:var(--text);font-size:13px;">{{ $namaBarang }}</div>
                @foreach($semua_pasar as $p)
                @php
                    $row = $byPasar[$p->id] ?? null;
                    $hargaList = $rows->pluck('harga_hari_ini');
                    $minH = $hargaList->min();
                    $maxH = $hargaList->max();
                @endphp
                <div style="text-align:center;">
                    @if($row)
                    @php
                        $isMin = $row->harga_hari_ini == $minH;
                        $isMax = $row->harga_hari_ini == $maxH && $hargaList->count() > 1;
                        $bg = $isMin ? '#f0fdf4' : ($isMax ? '#fef2f2' : 'transparent');
                        $cl = $isMin ? '#16a34a' : ($isMax ? '#dc2626' : 'var(--text)');
                    @endphp
                    <span style="font-size:12px;font-weight:700;color:{{ $cl }};background:{{ $bg }};padding:3px 8px;border-radius:7px;display:inline-block;">
                        Rp {{ number_format($row->harga_hari_ini, 0, ',', '.') }}
                    </span>
                    @else
                    <span style="font-size:11px;color:#ccc;">—</span>
                    @endif
                </div>
                @endforeach
            </div>
            @empty
            <div style="padding:48px;text-align:center;background:white;">
                <p style="color:var(--sub);">Belum ada data komparasi.</p>
            </div>
            @endforelse
        </div>
        <p style="font-size:11px;color:var(--sub);margin-top:8px;text-align:right;">
            <span style="background:#f0fdf4;color:#16a34a;padding:2px 8px;border-radius:5px;font-weight:700;">Hijau</span> = Termurah &nbsp;
            <span style="background:#fef2f2;color:#dc2626;padding:2px 8px;border-radius:5px;font-weight:700;">Merah</span> = Termahal
        </p>
    </div>

</main>

{{-- ════ MODAL UPDATE HARGA ════ --}}
{{-- Modal update harga dihapus - diganti alur antrian --}}

{{-- ════ MODAL LAPORAN ════ --}}
<div class="modal-overlay" id="laporanModal">
    <div class="modal-box" style="width:480px;padding:32px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
            <div style="font-size:18px;font-weight:800;color:var(--text);">
                <i class="fas fa-file-arrow-down" style="color:var(--gd);margin-right:8px;"></i>Unduh Laporan
            </div>
            <button onclick="closeLaporanModal()" style="background:#f0f0f0;border:none;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;color:#666;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="/admin/laporan" method="GET">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:var(--sub);display:block;margin-bottom:6px;">Bulan</label>
                    <select name="bulan" class="form-inp">
                        @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ date('n')==$m?'selected':'' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:var(--sub);display:block;margin-bottom:6px;">Tahun</label>
                    <select name="tahun" class="form-inp">
                        @for($y=date('Y');$y>=date('Y')-5;$y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div style="margin-bottom:24px;">
                <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:var(--sub);display:block;margin-bottom:6px;">Kategori</label>
                <select name="kategori" class="form-inp">
                    <option value="semua">Semua Kategori</option>
                    <option value="pokok">Barang Pokok</option>
                    <option value="subsidi">Barang Subsidi</option>
                    <option value="penting">Barang Penting</option>
                </select>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeLaporanModal()" class="btn-ghost">Batal</button>
                <button type="submit" name="tipe" value="excel" class="btn-green" style="background:linear-gradient(135deg,#15803d,#4ade80);">
                    <i class="fas fa-file-excel" style="margin-right:6px;"></i>Excel
                </button>
                <button type="submit" name="tipe" value="pdf" class="btn-green" style="background:linear-gradient(135deg,#991b1b,#f87171);">
                    <i class="fas fa-file-pdf" style="margin-right:6px;"></i>PDF
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ════ MODAL LOGOUT ════ --}}
<div class="modal-overlay" id="logoutModal">
    <div class="modal-box" style="width:360px;padding:36px;text-align:center;">
        <div style="width:56px;height:56px;background:#fef2f2;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="fas fa-sign-out-alt" style="font-size:24px;color:#dc2626;"></i>
        </div>
        <div style="font-size:18px;font-weight:800;color:var(--text);margin-bottom:8px;">Konfirmasi Keluar</div>
        <div style="font-size:13px;color:var(--sub);margin-bottom:28px;">Apakah Anda yakin ingin keluar dari sistem?</div>
        <div style="display:flex;gap:10px;justify-content:center;">
            <button onclick="closeLogoutModal()" class="btn-ghost" style="flex:1;">Batal</button>
            <button onclick="confirmLogout()" style="flex:1;background:linear-gradient(135deg,#991b1b,#ef4444);color:white;border:none;border-radius:11px;padding:11px;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">
                <i class="fas fa-sign-out-alt" style="margin-right:6px;"></i>Keluar
            </button>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>

<script>
// ══════════════════════════════════════════
//  DASHBOARD ADMIN — JAVASCRIPT
// ══════════════════════════════════════════

// ── 1. Panel Navigation ──
function showPanel(id) {
    ['panelDashboard','panelRekomendasi','panelKomparasi'].forEach(function(p) {
        var el = document.getElementById(p);
        if (el) el.style.display = 'none';
    });
    var target = document.getElementById(id);
    if (target) target.style.display = 'block';
    document.querySelectorAll('.nav-item').forEach(function(i) { i.classList.remove('active'); });
}

function backToDashboard() {
    showPanel('panelDashboard');
    var n = document.getElementById('navDashboard');
    if (n) n.classList.add('active');
}

function openRekomendasiPanel() {
    showPanel('panelRekomendasi');
    var n = document.getElementById('navRekomendasi');
    if (n) n.classList.add('active');
}

function openKomparasiPanel() {
    showPanel('panelKomparasi');
    var n = document.getElementById('navKomparasi');
    if (n) n.classList.add('active');
}

// ── 2. Pasar Dropdown ──
function togglePasar() {
    var dd = document.getElementById('pasarDropdown');
    var ar = document.getElementById('pasarArrow');
    if (!dd) return;
    var isOpen = dd.classList.contains('open');
    dd.classList.toggle('open');
    if (ar) ar.style.transform = !isOpen ? 'rotate(180deg)' : 'rotate(0)';
}

// ── 3. Search Tabel Harga ──
function filterTabel(q) {
    var rows = document.querySelectorAll('.harga-row');
    var count = 0;
    rows.forEach(function(r) {
        var show = !q || r.dataset.nama.includes(q.toLowerCase());
        r.style.display = show ? '' : 'none';
        if (show) count++;
    });
    var el = document.getElementById('tabelCount');
    if (el) el.textContent = count + ' data';
}

// ── 4. Modal Update Harga ──
function openUpdateModal(id, nama, kemarin, hari) {
    var el_id   = document.getElementById('modal_id');
    var el_nama = document.getElementById('modal_nama_barang');
    var el_kem  = null; // kolom harga_kemarin dihapus
    var el_hari = document.getElementById('modal_harga_hari_ini');
    var modal   = document.getElementById('updateModal');
    if (el_id)   el_id.value             = id;
    if (el_nama) el_nama.textContent     = nama;
    if (el_kem)  el_kem.value            = kemarin;
    if (el_hari) el_hari.value           = hari;
    if (modal)   modal.classList.add('open');
}

function closeUpdateModal() {
    var modal = document.getElementById('updateModal');
    if (modal) modal.classList.remove('open');
}

// ── 5. Modal Laporan ──
function openLaporanModal(e) {
    if (e) e.preventDefault();
    var modal = document.getElementById('laporanModal');
    if (modal) modal.classList.add('open');
}

function closeLaporanModal() {
    var modal = document.getElementById('laporanModal');
    if (modal) modal.classList.remove('open');
}

// ── 6. Modal Logout ──
function showLogoutModal(e) {
    if (e) e.preventDefault();
    var modal = document.getElementById('logoutModal');
    if (modal) modal.classList.add('open');
}

function closeLogoutModal() {
    var modal = document.getElementById('logoutModal');
    if (modal) modal.classList.remove('open');
}

function confirmLogout() {
    var form = document.getElementById('logout-form');
    if (form) form.submit();
}

// ── 7. Filter Rekomendasi ──
function filterRek(kat) {
    document.querySelectorAll('#rekContainer .rek-card').forEach(function(c) {
        c.style.display = (!kat || c.dataset.kategori === kat) ? '' : 'none';
    });
    ['rekAll','rekPokok','rekSubsidi','rekPenting'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) { el.style.background = 'white'; el.style.color = 'var(--gd)'; el.style.border = '1.5px solid var(--border)'; }
    });
    var map = {'': 'rekAll', 'pokok': 'rekPokok', 'subsidi': 'rekSubsidi', 'penting': 'rekPenting'};
    var btn = document.getElementById(map[kat]);
    if (btn) { btn.style.background = 'var(--gdd)'; btn.style.color = 'white'; btn.style.border = '1.5px solid var(--gdd)'; }
}

function shareRekomendasi() {
    var cards = Array.from(document.querySelectorAll('#rekContainer .rek-card'));
    var lines = cards.slice(0, 15).map(function(c) {
        var nama  = c.querySelector('div[style*="font-weight:700"]');
        var harga = c.querySelector('div[style*="font-size:20px"]');
        return '• ' + (nama ? nama.textContent.trim() : '') + ': ' + (harga ? harga.textContent.trim() : '');
    });
    var txt = '*Rekomendasi Harga Optimal - SIPHP Parepare*\n\n' + lines.join('\n') + '\n\n_SIPHP Kota Parepare_';
    window.open('https://wa.me/?text=' + encodeURIComponent(txt));
}

// ── 8. Tutup modal klik backdrop + ESC ──
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.modal-overlay').forEach(function(m) {
        m.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('open');
        });
    });
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay').forEach(function(m) {
            m.classList.remove('open');
        });
    }
});
</script>
</body>
</html>
