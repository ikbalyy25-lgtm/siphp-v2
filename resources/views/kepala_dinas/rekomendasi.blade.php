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
        .stat-card { background:white; border:1.5px solid var(--border); border-radius:16px; padding:20px 24px; box-shadow:0 2px 8px rgba(45,106,79,0.05); }
        .tab-btn { padding:9px 20px; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; border:1.5px solid var(--border); background:white; color:var(--sub); transition:all 0.2s; text-decoration:none; }
        .tab-btn.active { background:var(--gd); color:white; border-color:var(--gd); }
        .rekomen-card { background:white; border:1.5px solid var(--border); border-radius:14px; overflow:hidden; transition:all 0.2s; margin-bottom:12px; }
        .rekomen-card:hover { box-shadow:0 4px 16px rgba(45,106,79,0.1); transform:translateY(-1px); }
        .rekomen-card.perlu-perhatian { border-color:#fca5a5; }
        .rekomen-header { display:grid; grid-template-columns:2fr 1fr 1fr 1fr 1fr 80px; align-items:center; padding:14px 20px; cursor:pointer; gap:8px; }
        .rekomen-header:hover { background:#f9fffe; }
        .detail-row { padding:0 20px 16px; background:#f9fffe; border-top:1px solid #e8f5ee; }
        .detail-pasar-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:10px; margin-top:12px; }
        .pasar-item { background:white; border:1.5px solid var(--border); border-radius:10px; padding:12px; }
        .pasar-item.tinggi { border-color:#fca5a5; background:#fff5f5; }
        .pasar-item.rendah { border-color:#93c5fd; background:#f0f9ff; }
        .flag-tinggi { background:#fee2e2; color:#dc2626; padding:3px 8px; border-radius:20px; font-size:10px; font-weight:700; }
        .flag-rendah { background:#dbeafe; color:#1d4ed8; padding:3px 8px; border-radius:20px; font-size:10px; font-weight:700; }
        .flag-normal { background:#d1fae5; color:#065f46; padding:3px 8px; border-radius:20px; font-size:10px; font-weight:700; }
        .col-header { font-size:11px; font-weight:700; color:var(--sub); text-transform:uppercase; letter-spacing:1px; }
        .harga-optimal-big { font-size:20px; font-weight:800; color:var(--gd); }
        .perhatian-badge { background:#fee2e2; color:#dc2626; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; display:inline-flex; align-items:center; gap:5px; }
        .empty-state { text-align:center; padding:60px 20px; color:var(--sub); }
        .info-tooltip { position:relative; display:inline-block; cursor:help; }
        .selisih-bar { height:6px; border-radius:3px; background:#e8f5ee; margin-top:4px; overflow:hidden; }
        .selisih-fill { height:100%; border-radius:3px; background:var(--gd); }
        .selisih-fill.warning { background:#f59e0b; }
        .selisih-fill.danger { background:#ef4444; }
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
    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-size:22px;font-weight:800;color:var(--gdd);margin:0;">
                <i class="fas fa-lightbulb" style="color:#f59e0b;margin-right:10px;"></i>Rekomendasi Harga Optimal
            </h1>
            <p style="font-size:13px;color:var(--sub);margin:4px 0 0;">
                Berdasarkan rata-rata harga dari {{ $ringkasan['total_pasar'] }} pasar · Update terakhir: {{ $ringkasan['terakhir_update'] ? \Carbon\Carbon::parse($ringkasan['terakhir_update'])->format('d M Y') : 'Belum ada data' }}
            </p>
        </div>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            <button onclick="shareWA()" style="background:white;border:1.5px solid var(--border);color:var(--gd);border-radius:10px;padding:9px 16px;font-weight:700;font-size:12px;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;display:flex;align-items:center;gap:6px;transition:all 0.2s;">
                <i class="fab fa-whatsapp" style="color:#25d366;font-size:14px;"></i> Bagikan
            </button>
        </div>
    </div>

    {{-- Tab kategori --}}
    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:28px;">
        <a href="?kategori=pokok"   class="tab-btn {{ $kategori=='pokok'?'active':'' }}"><i class="fas fa-shopping-basket" style="margin-right:6px;"></i>Bahan Pokok</a>
        <a href="?kategori=subsidi" class="tab-btn {{ $kategori=='subsidi'?'active':'' }}"><i class="fas fa-tags" style="margin-right:6px;"></i>Subsidi</a>
        <a href="?kategori=penting" class="tab-btn {{ $kategori=='penting'?'active':'' }}"><i class="fas fa-star" style="margin-right:6px;"></i>Barang Penting</a>
    </div>

    {{-- Ringkasan --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:28px;">
        <div class="stat-card">
            <div style="font-size:11px;font-weight:700;color:var(--sub);text-transform:uppercase;letter-spacing:1px;">Total Komoditas</div>
            <div style="font-size:28px;font-weight:800;color:var(--gdd);margin-top:4px;">{{ $ringkasan['total_komoditas'] }}</div>
        </div>
        <div class="stat-card" style="border-color:{{ $ringkasan['perlu_perhatian']>0 ? '#fca5a5' : 'var(--border)' }}">
            <div style="font-size:11px;font-weight:700;color:var(--sub);text-transform:uppercase;letter-spacing:1px;">Perlu Perhatian</div>
            <div style="font-size:28px;font-weight:800;color:{{ $ringkasan['perlu_perhatian']>0 ? '#dc2626' : 'var(--gdd)' }};margin-top:4px;">{{ $ringkasan['perlu_perhatian'] }}</div>
            <div style="font-size:11px;color:var(--sub);">disparitas harga &gt; 15%</div>
        </div>
        <div class="stat-card">
            <div style="font-size:11px;font-weight:700;color:var(--sub);text-transform:uppercase;letter-spacing:1px;">Dari Pasar</div>
            <div style="font-size:28px;font-weight:800;color:var(--gdd);margin-top:4px;">{{ $ringkasan['total_pasar'] }}</div>
            <div style="font-size:11px;color:var(--sub);">pasar aktif</div>
        </div>
    </div>

    {{-- Legenda --}}
    <div style="display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
        <span style="font-size:12px;color:var(--sub);">Status harga per pasar:</span>
        <span class="flag-tinggi"><i class="fas fa-arrow-up"></i> Tinggi (&gt;10% rata-rata)</span>
        <span class="flag-normal"><i class="fas fa-check"></i> Normal</span>
        <span class="flag-rendah"><i class="fas fa-arrow-down"></i> Rendah (&lt;10% rata-rata)</span>
    </div>

    @if($rekomendasi->isEmpty())
    <div class="stat-card">
        <div class="empty-state">
            <i class="fas fa-database" style="font-size:48px;color:#d1d5db;margin-bottom:16px;display:block;"></i>
            <div style="font-size:16px;font-weight:700;color:#374151;">Belum ada data harga</div>
            <div style="font-size:13px;margin-top:8px;">Admin pedagang belum mengirim data harga untuk kategori ini.</div>
        </div>
    </div>
    @else

    {{-- Header kolom --}}
    <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr 80px;padding:8px 20px;margin-bottom:4px;gap:8px;">
        <span class="col-header">Komoditas</span>
        <span class="col-header">Harga Optimal</span>
        <span class="col-header">Rata-rata</span>
        <span class="col-header">Terendah</span>
        <span class="col-header">Tertinggi</span>
        <span class="col-header">Pasar</span>
    </div>

    {{-- Daftar rekomendasi --}}
    @foreach($rekomendasi as $i => $item)
    <div class="rekomen-card {{ $item['perlu_perhatian'] ? 'perlu-perhatian' : '' }}" id="card-{{ $i }}">
        <div class="rekomen-header" onclick="toggleDetail({{ $i }})">
            <div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:14px;font-weight:700;color:#1a3a2a;">{{ $item['nama_barang'] }} @if($item['satuan'] && $item['satuan'] !== '-') <span style="font-weight:500; font-size:12px; color:var(--sub);">/ {{ $item['satuan'] }}</span> @endif</span>
                    @if($item['perlu_perhatian'])
                    <span class="perhatian-badge"><i class="fas fa-exclamation-triangle"></i> Disparitas Tinggi</span>
                    @endif
                </div>
                {{-- Bar disparitas --}}
                <div style="margin-top:6px;display:flex;align-items:center;gap:8px;">
                    <div class="selisih-bar" style="width:100px;">
                        <div class="selisih-fill {{ $item['selisih_persen']>15 ? 'danger' : ($item['selisih_persen']>8 ? 'warning' : '') }}"
                             style="width:{{ min($item['selisih_persen']*3, 100) }}%"></div>
                    </div>
                    <span style="font-size:11px;color:{{ $item['selisih_persen']>15 ? '#dc2626' : ($item['selisih_persen']>8 ? '#f59e0b' : 'var(--sub)') }};font-weight:600;">
                        {{ $item['selisih_persen'] }}% disparitas
                    </span>
                </div>
            </div>
            <div>
                <div class="harga-optimal-big">Rp {{ number_format($item['harga_optimal'],0,',','.') }}</div>
                <div style="font-size:10px;color:var(--sub);">direkomendasikan</div>
            </div>
            <div style="font-size:14px;font-weight:600;color:#374151;">Rp {{ number_format($item['rata_rata'],0,',','.') }}</div>
            <div style="font-size:14px;font-weight:600;color:#16a34a;">Rp {{ number_format($item['harga_min'],0,',','.') }}</div>
            <div style="font-size:14px;font-weight:600;color:{{ $item['perlu_perhatian'] ? '#dc2626' : '#374151' }};">Rp {{ number_format($item['harga_max'],0,',','.') }}</div>
            <div style="display:flex;align-items:center;gap:6px;">
                <span style="font-size:14px;font-weight:700;color:var(--gdd);">{{ $item['jumlah_pasar'] }}</span>
                <i class="fas fa-chevron-down" id="icon-{{ $i }}" style="font-size:11px;color:var(--sub);transition:transform 0.2s;"></i>
            </div>
        </div>

        {{-- Detail per pasar (tersembunyi) --}}
        <div class="detail-row" id="detail-{{ $i }}" style="display:none;">
            <div style="font-size:12px;font-weight:700;color:var(--sub);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">
                Detail Harga Per Pasar
            </div>
            <div class="detail-pasar-grid">
                @foreach($item['detail_pasar'] as $dp)
                <div class="pasar-item {{ $dp['flag'] }}">
                    <div style="font-size:12px;font-weight:700;color:#1a3a2a;">{{ $dp['nama_pasar'] }}</div>
                    <div style="font-size:16px;font-weight:800;color:{{ $dp['flag']=='tinggi' ? '#dc2626' : ($dp['flag']=='rendah' ? '#1d4ed8' : 'var(--gd)') }};margin:4px 0;">
                        Rp {{ number_format($dp['harga'],0,',','.') }}
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span class="flag-{{ $dp['flag'] }}">
                            @if($dp['flag']=='tinggi')<i class="fas fa-arrow-up"></i>@elseif($dp['flag']=='rendah')<i class="fas fa-arrow-down"></i>@else<i class="fas fa-check"></i>@endif
                            {{ $dp['flag'] == 'normal' ? 'Normal' : ucfirst($dp['flag']) }}
                        </span>
                        <span style="font-size:11px;color:var(--sub);">
                            {{ $dp['selisih_pct'] > 0 ? '+' : '' }}{{ $dp['selisih_pct'] }}%
                        </span>
                    </div>
                    <div style="font-size:10px;color:#9ca3af;margin-top:4px;">{{ \Carbon\Carbon::parse($dp['tanggal'])->format('d M Y') }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach

    @endif
</main>

<script>
function toggleDetail(i) {
    const d = document.getElementById('detail-'+i);
    const icon = document.getElementById('icon-'+i);
    const isOpen = d.style.display !== 'none';
    d.style.display = isOpen ? 'none' : 'block';
    icon.style.transform = isOpen ? '' : 'rotate(180deg)';
}

function shareWA() {
    const cards = Array.from(document.querySelectorAll('.rekomen-card'));
    const lines = cards.slice(0, 15).map(c => {
        const nama = c.querySelector('span[style*="font-size:14px"]')?.textContent?.trim() || '';
        const harga = c.querySelector('.harga-optimal-big')?.textContent?.trim() || '';
        return '- ' + nama + ': ' + harga;
    });
    const txt = 'Rekomendasi Harga Optimal SIPHP Parepare\nKategori: {{ ucfirst($kategori) }}\n\n' + lines.join('\n') + '\n\nSumber: SIPHP Kota Parepare';
    window.open('https://wa.me/?text=' + encodeURIComponent(txt));
}
</script>
</body>
</html>
