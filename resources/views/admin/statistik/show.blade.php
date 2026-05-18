<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik — {{ $pasar->nama_pasar }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        body { background: #f0faf4; min-height: 100vh; padding: 32px; }
        :root { --g:#d0f0c0;--gd:#2d6a4f;--gdd:#1e3a2f;--border:#d1e8d8;--text:#1a3a2a;--sub:#5a8a6a; }
        .inp-field { padding:10px 14px;border-radius:11px;font-size:13px;border:1.5px solid var(--border);background:#f8fdf9;color:var(--text);outline:none;transition:border-color 0.2s;width:100%;font-family:'Plus Jakarta Sans',sans-serif; }
        .inp-field:focus { border-color:var(--gd); }
        .stat-mini { background:white;border:1.5px solid var(--border);border-radius:14px;padding:16px 20px;text-align:center; }
        .btn-chart { border:1.5px solid var(--border);background:white;color:var(--sub);border-radius:9px;padding:7px 14px;font-size:12px;font-weight:700;cursor:pointer;transition:all 0.2s; }
        .btn-chart.active,.btn-chart:hover { background:var(--gd);color:white;border-color:var(--gd); }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
<div style="max-width:1100px;margin:0 auto;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:5px;">
                <a href="{{ route('admin.statistik.pilihKategori', $pasar->id) }}" style="background:#d0f0c0;border:none;color:#1e3a2f;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:13px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 style="font-size:22px;font-weight:800;color:var(--text);">
                    Statistik <span style="color:var(--gd);">{{ ucfirst($kategori) }}</span>
                </h1>
            </div>
            <p style="font-size:13px;color:var(--sub);margin-left:42px;">
                <i class="fas fa-map-marker-alt" style="color:#ef4444;margin-right:5px;"></i>
                {{ $pasar->nama_pasar }} &nbsp;·&nbsp;
                <span id="displayBarang" style="color:var(--gd);font-weight:700;"></span>
            </p>
        </div>
        <div style="display:flex;gap:8px;">
            <button onclick="shareGrafik()" style="background:white;border:1.5px solid var(--border);color:var(--gd);border-radius:10px;padding:9px 16px;font-weight:700;font-size:12px;cursor:pointer;display:flex;align-items:center;gap:6px;">
                <i class="fas fa-share-nodes"></i> Bagikan
            </button>
            <button onclick="downloadChart()" style="background:var(--g);color:var(--gdd);border:none;border-radius:10px;padding:9px 16px;font-weight:700;font-size:12px;cursor:pointer;display:flex;align-items:center;gap:6px;">
                <i class="fas fa-download"></i> Unduh Grafik
            </button>
        </div>
    </div>

    {{-- Filter Panel --}}
    <div style="background:white;border:1.5px solid var(--border);border-radius:18px;padding:20px 24px;margin-bottom:20px;display:grid;grid-template-columns:2fr 1fr 1fr;gap:16px;align-items:end;">
        <div>
            <label style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--sub);display:block;margin-bottom:6px;">Komoditas</label>
            <select id="selectBarang" onchange="loadChartData()" class="inp-field">
                @foreach($daftar_barang as $barang)
                <option value="{{ $barang }}">{{ $barang }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--sub);display:block;margin-bottom:6px;">Bulan</label>
            <select id="selectBulan" onchange="loadChartData()" class="inp-field">
                @for($m=1;$m<=12;$m++)
                <option value="{{ str_pad($m,2,'0',STR_PAD_LEFT) }}" {{ date('m')==$m?'selected':'' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
                @endfor
            </select>
        </div>
        <div>
            <label style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--sub);display:block;margin-bottom:6px;">Tahun</label>
            <select id="selectTahun" onchange="loadChartData()" class="inp-field">
                <option value="2025" {{ date('Y')=='2025'?'selected':'' }}>2025</option>
                <option value="2026" {{ date('Y')=='2026'?'selected':'' }}>2026</option>
            </select>
        </div>
    </div>

    {{-- Stat Mini Cards --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;" id="statCards">
        <div class="stat-mini">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:var(--sub);margin-bottom:6px;">Harga Terendah</div>
            <div id="statMin" style="font-size:20px;font-weight:800;color:#16a34a;">—</div>
        </div>
        <div class="stat-mini">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:var(--sub);margin-bottom:6px;">Harga Tertinggi</div>
            <div id="statMax" style="font-size:20px;font-weight:800;color:#dc2626;">—</div>
        </div>
        <div class="stat-mini">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:var(--sub);margin-bottom:6px;">Rata-Rata</div>
            <div id="statAvg" style="font-size:20px;font-weight:800;color:var(--gd);">—</div>
        </div>
        <div class="stat-mini">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;color:var(--sub);margin-bottom:6px;">Tren</div>
            <div id="statTrend" style="font-size:20px;font-weight:800;color:var(--text);">—</div>
        </div>
    </div>

    {{-- Grafik --}}
    <div style="background:white;border:1.5px solid var(--border);border-radius:20px;padding:28px;box-shadow:0 4px 20px rgba(45,106,79,0.07);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
            <div>
                <div style="font-size:15px;font-weight:800;color:var(--text);">Grafik Pergerakan Harga</div>
                <div id="labelBulan" style="font-size:12px;color:var(--sub);margin-top:2px;">Memuat data...</div>
            </div>
            <div style="display:flex;gap:6px;">
                <button class="btn-chart active" id="btnLine" onclick="switchChart('line')">
                    <i class="fas fa-chart-line" style="margin-right:5px;"></i>Line
                </button>
                <button class="btn-chart" id="btnBar" onclick="switchChart('bar')">
                    <i class="fas fa-chart-bar" style="margin-right:5px;"></i>Bar
                </button>
            </div>
        </div>

        {{-- Loading --}}
        <div id="chartLoading" style="height:400px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;">
            <div style="width:40px;height:40px;border:3px solid var(--g);border-top-color:var(--gd);border-radius:50%;animation:spin 0.8s linear infinite;"></div>
            <span style="font-size:13px;color:var(--sub);">Memuat grafik...</span>
        </div>
        <div style="height:400px;display:none;" id="chartWrap">
            <canvas id="realtimeChart"></canvas>
        </div>

        <div id="noData" style="height:400px;display:none;align-items:center;justify-content:center;flex-direction:column;gap:10px;">
            <i class="fas fa-chart-area" style="font-size:3rem;color:#c6ebd4;"></i>
            <p style="color:var(--sub);font-weight:600;">Belum ada data untuk periode ini.</p>
        </div>
    </div>
</div>



<script>
let myChart, currentType = 'line', chartData = null;

async function loadChartData() {
    const barang = document.getElementById('selectBarang').value;
    const bulan  = document.getElementById('selectBulan').value;
    const tahun  = document.getElementById('selectTahun').value;
    document.getElementById('displayBarang').textContent = barang;

    document.getElementById('chartLoading').style.display = 'flex';
    document.getElementById('chartWrap').style.display = 'none';
    document.getElementById('noData').style.display = 'none';

    try {
        @if(auth()->user()->role === 'admin_master')
        const url = `/admin/statistik/api/{{ $pasar->id }}/{{ $kategori }}/${encodeURIComponent(barang)}?bulan=${bulan}&tahun=${tahun}`;
        @else
        const url = `/admin-pasar/statistik/api/{{ $kategori }}/${encodeURIComponent(barang)}?bulan=${bulan}&tahun=${tahun}`;
        @endif
        const res  = await fetch(url);
        const data = await res.json();
        document.getElementById('labelBulan').textContent = data.bulan_nama || '';
        chartData = data;

        if (!data.prices || data.prices.length === 0) {
            document.getElementById('chartLoading').style.display = 'none';
            document.getElementById('noData').style.display = 'flex';
            return;
        }

        // Stat cards
        const prices = data.prices.filter(Boolean);
        const fmt = v => 'Rp ' + Math.round(v).toLocaleString('id-ID');
        document.getElementById('statMin').textContent  = fmt(Math.min(...prices));
        document.getElementById('statMax').textContent  = fmt(Math.max(...prices));
        document.getElementById('statAvg').textContent  = fmt(prices.reduce((a,b)=>a+b,0)/prices.length);
        const tren = prices[prices.length-1] > prices[0];
        document.getElementById('statTrend').innerHTML  = tren
            ? '<i class="fas fa-arrow-trend-up" style="color:#dc2626;"></i> Naik'
            : '<i class="fas fa-arrow-trend-down" style="color:#16a34a;"></i> Turun';

        renderChart(data);
    } catch(e) {
        console.error(e);
        document.getElementById('chartLoading').style.display = 'none';
    }
}

function renderChart(data) {
    document.getElementById('chartLoading').style.display = 'none';
    document.getElementById('chartWrap').style.display = 'block';
    const ctx = document.getElementById('realtimeChart').getContext('2d');
    if (myChart) myChart.destroy();

    const isLine = currentType === 'line';
    myChart = new Chart(ctx, {
        type: currentType,
        data: {
            labels: data.labels,
            datasets: [{
                label: document.getElementById('selectBarang').value,
                data: data.prices,
                borderColor: '#2d6a4f',
                backgroundColor: isLine ? 'rgba(208,240,192,0.25)' : 'rgba(208,240,192,0.7)',
                borderWidth: isLine ? 3 : 1,
                fill: isLine,
                tension: 0.35,
                pointRadius: isLine ? 5 : 0,
                pointBackgroundColor: '#2d6a4f',
                pointHoverRadius: 8,
                spanGaps: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e3a2f',
                    titleColor: '#d0f0c0',
                    bodyColor: 'white',
                    padding: 12,
                    borderColor: '#2d6a4f',
                    borderWidth: 1,
                    callbacks: {
                        label: ctx => ' Rp ' + Math.round(ctx.parsed.y).toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    grid: { color: 'rgba(209,232,216,0.4)' },
                    ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID'), font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });
}

function switchChart(type) {
    currentType = type;
    document.getElementById('btnLine').classList.toggle('active', type==='line');
    document.getElementById('btnBar').classList.toggle('active', type==='bar');
    if (chartData) renderChart(chartData);
}

function downloadChart() {
    if (!myChart) return;
    const a = document.createElement('a');
    a.href = myChart.toBase64Image();
    a.download = `grafik-harga-${document.getElementById('selectBarang').value}.png`;
    a.click();
}

function shareGrafik() {
    const txt = `📊 Statistik Harga *${document.getElementById('selectBarang').value}*\n📍 {{ $pasar->nama_pasar }}\n🔗 ${window.location.href}`;
    if (navigator.share) {
        navigator.share({ title: 'Statistik SIPHP', text: txt, url: window.location.href });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Tautan berhasil disalin!');
    }
}

document.addEventListener('DOMContentLoaded', loadChartData);
</script>
</body>
</html>
