<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Harga — SIPHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        /* New Styles for AI Recommendation */
        .card { background: white; border: 1.5px solid var(--border); border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(45,106,79,0.06); margin-bottom: 24px; }
        .inp { width: 100%; padding: 12px 16px; border-radius: 12px; font-size: 14px; border: 1.5px solid var(--border); background: #f8fdf9; color: var(--text); outline: none; transition: all 0.2s; font-weight: 500; }
        .inp:focus { border-color: var(--gd); box-shadow: 0 0 0 4px rgba(45,106,79,0.1); }
        .btn-primary { background: linear-gradient(135deg, var(--gdd), var(--gd)); color: white; border: none; border-radius: 12px; padding: 12px 24px; font-weight: 700; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; justify-content: center; width: 100%; }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(45,106,79,0.2); }
        .btn-primary:disabled { opacity: 0.7; cursor: not-allowed; transform: none; box-shadow: none; }
        .metric-box { padding: 16px; border-radius: 12px; border: 1.5px solid var(--border); background: #fcfdfc; display: flex; flex-direction: column; gap: 6px; }
        .metric-title { font-size: 12px; color: var(--sub); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .metric-value { font-size: 24px; font-weight: 800; color: var(--gd); }
        .loader { display: none; width: 20px; height: 20px; border: 3px solid rgba(255,255,255,0.3); border-radius: 50%; border-top-color: white; animation: spin 1s ease-in-out infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .result-section { display: none; animation: fadeIn 0.4s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        @media (min-width: 1024px) {
            .grid-main { grid-template-columns: 320px 1fr !important; }
        }
        @media (max-width: 1023px) {
            .grid-main { grid-template-columns: 1fr !important; }
            .metrics-container { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        }
        @media (max-width: 768px) {
            .grid-top { grid-template-columns: 1fr !important; }
            .metrics-container { grid-template-columns: 1fr; }
        }
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
    <div style="max-width:1120px;margin:0 auto;">
        
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
            <div>
                <h1 style="font-size:24px;font-weight:800;color:var(--gdd);margin:0;">
                    <i class="fas fa-lightbulb" style="color:#f59e0b;margin-right:10px;"></i>Rekomendasi Harga Optimal
                </h1>
                <p style="font-size:14px;color:var(--sub);margin:6px 0 0;">
                    Analisis harga optimal menggunakan algoritma <b style="color:var(--gd);">XGBoost</b> dengan pembagian data 80:20.
                </p>
            </div>
            <div style="background:white;border:1.5px solid var(--border);border-radius:12px;padding:10px 16px;display:flex;align-items:center;gap:12px;box-shadow:0 2px 8px rgba(45,106,79,0.05);">
                <i class="fas fa-database" style="color:#f59e0b;font-size:18px;"></i>
                <div>
                    <div style="font-size:11px;font-weight:700;color:var(--sub);text-transform:uppercase;">Total Komoditas</div>
                    <div style="font-size:15px;font-weight:800;color:var(--text);">{{ $ringkasan['total_komoditas'] }} Barang</div>
                </div>
            </div>
        </div>

        <div class="card grid-top" style="display:grid;grid-template-columns:1fr 1fr 200px;gap:16px;align-items:end;background:url('{{ asset('img/pattern.png') }}') right bottom no-repeat;background-size:cover;background-color:white;">
            <div>
                <label style="font-size:12px;font-weight:700;color:var(--sub);display:block;margin-bottom:8px;">KATEGORI</label>
                <select id="kategoriSelect" class="inp" onchange="window.location.href='?kategori='+this.value">
                    <option value="pokok" {{ $kategori == 'pokok' ? 'selected' : '' }}>Barang Kebutuhan Pokok</option>
                    <option value="subsidi" {{ $kategori == 'subsidi' ? 'selected' : '' }}>Barang Bersubsidi</option>
                    <option value="penting" {{ $kategori == 'penting' ? 'selected' : '' }}>Barang Penting Lainnya</option>
                </select>
            </div>
            <div>
                <label style="font-size:12px;font-weight:700;color:var(--sub);display:block;margin-bottom:8px;">KOMODITAS</label>
                <select id="komoditasSelect" class="inp">
                    <option value="">-- Pilih Barang --</option>
                    @foreach($komoditasList as $k)
                        <option value="{{ $k }}">{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button id="btnAnalyze" class="btn-primary" onclick="analyzeData()">
                    <span id="btnText"><i class="fas fa-wand-magic-sparkles"></i> Analisis Harga</span>
                    <div id="btnLoader" class="loader"></div>
                </button>
            </div>
        </div>

        <div id="errorAlert" style="display:none;background:#fef2f2;border:1.5px solid #fecaca;color:#dc2626;padding:16px;border-radius:12px;margin-bottom:24px;font-weight:600;font-size:14px;align-items:center;gap:10px;">
            <i class="fas fa-exclamation-triangle"></i> <span id="errorText"></span>
        </div>

        <div id="resultSection" class="result-section">
            <div class="grid-main" style="display:grid;grid-template-columns:320px 1fr;gap:24px;">
                
                {{-- Panel Kiri: Metrik & Prediksi --}}
                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div class="card" style="margin-bottom:0;background:linear-gradient(135deg,var(--gdd),var(--gd));color:white;border:none;box-shadow:0 8px 24px rgba(45,106,79,0.25);">
                        <div style="font-size:12px;font-weight:700;color:var(--g);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">Harga Optimal</div>
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                            <i class="fas fa-tag" style="font-size:28px;color:var(--g);"></i>
                            <div>
                                <div id="resHarga" style="font-size:32px;font-weight:800;line-height:1.2;">Rp 0</div>
                                <div id="resTanggal" style="font-size:13px;color:#a8d5ba;margin-top:2px;">-</div>
                            </div>
                        </div>
                        <div style="background:rgba(255,255,255,0.1);padding:10px 14px;border-radius:10px;font-size:12px;line-height:1.5;">
                            Nilai ini murni hasil evaluasi Machine Learning berdasarkan tren historis dan konsensus pasar hari ini.
                        </div>
                    </div>

                    <div class="card" style="margin-bottom:0;flex:1;">
                        <div style="font-size:14px;font-weight:800;color:var(--gdd);margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                            <i class="fas fa-chart-pie" style="color:var(--gd);"></i> Evaluasi Model (20% Test)
                        </div>
                        <div class="metrics-container" style="display:flex;flex-direction:column;gap:12px;">
                            <!-- Akurasi Panel -->
                            <div class="metric-box" style="background:linear-gradient(135deg, #f0faf4, #e8f5ee);border-color:var(--gd);">
                                <div class="metric-title" style="color:var(--gd);">Tingkat Akurasi Prediksi</div>
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px;">
                                    <div id="resAccuracy" class="metric-value" style="font-size:28px;">0%</div>
                                    <div id="resStatus" style="padding:4px 12px;background:var(--gd);color:white;border-radius:20px;font-size:11px;font-weight:700;">-</div>
                                </div>
                            </div>
                            
                            <!-- Grid for MAE & RMSE -->
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                <div class="metric-box" style="padding:12px;">
                                    <div class="metric-title" style="font-size:10px;">MAE (Selisih Error)</div>
                                    <div id="resMae" class="metric-value" style="font-size:16px;">0</div>
                                </div>
                                <div class="metric-box" style="padding:12px;">
                                    <div class="metric-title" style="font-size:10px;">RMSE (Selisih Error)</div>
                                    <div id="resRmse" class="metric-value" style="font-size:16px;">0</div>
                                </div>
                            </div>
                            
                            <!-- MSE -->
                            <div class="metric-box" style="padding:12px;">
                                <div class="metric-title" style="font-size:10px;">MSE (Mean Squared Error)</div>
                                <div id="resMse" class="metric-value" style="font-size:16px;">0</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel Kanan: Grafik --}}
                <div class="card" style="margin-bottom:0;display:flex;flex-direction:column;">
                    <div style="font-size:16px;font-weight:800;color:var(--gdd);margin-bottom:20px;display:flex;align-items:center;gap:8px;">
                        <i class="fas fa-chart-line" style="color:var(--gd);"></i> Visualisasi Tren & Rekomendasi
                    </div>
                    <div style="position:relative;flex:1;min-height:400px;width:100%;">
                        <canvas id="predictionChart"></canvas>
                    </div>
                </div>

            </div>

            {{-- Panel Bawah: Tambahan Grafik Volatilitas dan Disparitas --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(400px, 1fr));gap:24px;margin-top:24px;">
                
                {{-- Grafik Volatilitas Harian --}}
                <div class="card" style="margin-bottom:0;display:flex;flex-direction:column;">
                    <div style="font-size:16px;font-weight:800;color:var(--gdd);margin-bottom:20px;display:flex;align-items:center;gap:8px;">
                        <i class="fas fa-water" style="color:var(--gd);"></i> Volatilitas Harian
                    </div>
                    <div style="position:relative;flex:1;min-height:300px;width:100%;">
                        <canvas id="volatilityChart"></canvas>
                    </div>
                </div>

                {{-- Grafik Disparitas Antar Pasar --}}
                <div class="card" style="margin-bottom:0;display:flex;flex-direction:column;">
                    <div style="font-size:16px;font-weight:800;color:var(--gdd);margin-bottom:20px;display:flex;align-items:center;gap:8px;">
                        <i class="fas fa-scale-unbalanced" style="color:var(--gd);"></i> Disparitas Antar Pasar
                    </div>
                    <div style="position:relative;flex:1;min-height:300px;width:100%;">
                        <canvas id="disparityChart"></canvas>
                    </div>
                </div>
            </div>

        </div>

    </div>
</main>

<script>
let chartInstance = null;
let volChartInstance = null;
let dispChartInstance = null;
const csrfToken = '{{ csrf_token() }}';
const endpoint = '{{ route("kepala_dinas.rekomendasi.analyze") }}'; 
const currentCategory = '{{ $kategori }}';

function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
}

function showLoader(show) {
    const btn = document.getElementById('btnAnalyze');
    const txt = document.getElementById('btnText');
    const ldr = document.getElementById('btnLoader');
    btn.disabled = show;
    if(show) {
        txt.style.display = 'none';
        ldr.style.display = 'block';
    } else {
        txt.style.display = 'inline-flex';
        ldr.style.display = 'none';
    }
}

function showError(msg) {
    const err = document.getElementById('errorAlert');
    const txt = document.getElementById('errorText');
    if(msg) {
        txt.textContent = msg;
        err.style.display = 'flex';
        document.getElementById('resultSection').style.display = 'none';
    } else {
        err.style.display = 'none';
    }
}

function formatTanggalIndo(dateStr) {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateStr).toLocaleDateString('id-ID', options);
}

async function analyzeData() {
    const komoditas = document.getElementById('komoditasSelect').value;
    if(!komoditas) {
        showError('Pilih komoditas terlebih dahulu.');
        return;
    }
    
    showError('');
    showLoader(true);
    document.getElementById('resultSection').style.display = 'none';

    try {
        const formData = new FormData();
        formData.append('nama_barang', komoditas);
        formData.append('kategori', currentCategory);

        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });

        const data = await response.json();
        
        if(!data.success) {
            showError(data.message || 'Terjadi kesalahan saat mengambil analisis.');
            return;
        }

        let acc = 100;
        if(data.metrics.mae > 0) {
            // Karena MAE sekarang berada di skala Log, nilainya secara otomatis merepresentasikan
            // Percentage Error (MAPE) dalam bentuk desimal (misal 0.0048 = 0.48%)
            acc = Math.max(0, 100 - (data.metrics.mae * 100));
        }
        document.getElementById('resAccuracy').textContent = acc.toFixed(2) + '%';
        
        let statusEl = document.getElementById('resStatus');
        if(acc >= 95) {
            statusEl.textContent = 'Sangat Baik';
            statusEl.style.background = '#059669';
        } else if(acc >= 85) {
            statusEl.textContent = 'Cukup Baik';
            statusEl.style.background = '#d97706';
        } else {
            statusEl.textContent = 'Kurang';
            statusEl.style.background = '#dc2626';
        }

        document.getElementById('resMae').textContent = data.metrics.mae.toString().replace('.', ',');
        document.getElementById('resMse').textContent = data.metrics.mse.toString().replace('.', ',');
        document.getElementById('resRmse').textContent = data.metrics.rmse.toString().replace('.', ',');
        
        document.getElementById('resHarga').textContent = formatRupiah(data.predicted_price);
        document.getElementById('resTanggal').textContent = 'Per ' + formatTanggalIndo(data.next_date);

        renderChart(data.chart.historical_dates, data.chart.historical_prices, data.chart.test_dates, data.chart.test_predictions, data.next_date, data.predicted_price);
        renderVolatilityChart(data.chart.historical_dates, data.chart.historical_prices, data.chart.historical_min, data.chart.historical_max, data.next_date, data.predicted_price);
        renderDisparityChart(data.disparitas.data, data.predicted_price);
        
        document.getElementById('resultSection').style.display = 'block';

    } catch (e) {
        showError('Kesalahan jaringan: ' + e.message);
    } finally {
        showLoader(false);
    }
}

function renderChart(histDates, histPrices, testDates, testPrices, nextDate, nextPrice) {
    const ctx = document.getElementById('predictionChart').getContext('2d');
    if(chartInstance) chartInstance.destroy();

    const testDataAligned = histDates.map(date => {
        const idx = testDates.indexOf(date);
        return idx !== -1 ? testPrices[idx] : null;
    });

    const allDates = [...histDates, nextDate];
    const actualData = [...histPrices, null];
    const testData = [...testDataAligned, null];
    
    const nextPredData = new Array(allDates.length).fill(null);
    nextPredData[allDates.length - 1] = nextPrice;
    
    if(testPrices.length > 0) {
        testData[testData.length - 1] = nextPrice;
    }

    chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: allDates,
            datasets: [
                {
                    label: 'Harga Aktual',
                    data: actualData,
                    borderColor: '#2d6a4f',
                    backgroundColor: 'rgba(45,106,79,0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#2d6a4f',
                    pointRadius: 3,
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Rekomendasi Harga XGBoost (Test)',
                    data: testData,
                    borderColor: '#f59e0b',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointRadius: 0,
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Rekomendasi Hari Ini',
                    data: nextPredData,
                    backgroundColor: '#16a34a',
                    borderColor: '#16a34a',
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    showLine: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        font: { family: 'Plus Jakarta Sans', size: 12 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(30,58,47,0.9)',
                    titleFont: { family: 'Plus Jakarta Sans', size: 13 },
                    bodyFont: { family: 'Plus Jakarta Sans', size: 13 },
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: false,
                    grid: { color: '#e8f5ee' },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
}

function renderVolatilityChart(histDates, histPrices, histMin, histMax, nextDate, nextPrice) {
    const ctx = document.getElementById('volatilityChart').getContext('2d');
    if(volChartInstance) volChartInstance.destroy();

    const allDates = [...histDates, nextDate];
    const avgData = [...histPrices, null];
    const minData = [...histMin, null];
    const maxData = [...histMax, null];

    volChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: allDates,
            datasets: [
                {
                    label: 'Harga Minimum',
                    data: minData,
                    borderColor: 'rgba(45, 106, 79, 0.4)',
                    borderWidth: 1.5,
                    borderDash: [4, 4],
                    backgroundColor: 'transparent',
                    pointRadius: 0,
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Harga Maksimum',
                    data: maxData,
                    borderColor: 'rgba(45, 106, 79, 0.4)',
                    borderWidth: 1.5,
                    borderDash: [4, 4],
                    backgroundColor: 'rgba(45, 106, 79, 0.12)', // Tema Hijau SIPHP
                    pointRadius: 0,
                    fill: '-1',
                    tension: 0.3
                },
                {
                    label: 'Rata-rata Harian',
                    data: avgData,
                    borderColor: '#2d6a4f',
                    borderWidth: 2,
                    pointBackgroundColor: '#2d6a4f',
                    pointRadius: 3,
                    fill: false,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: '#e8f5ee' }, ticks: { callback: function(value) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(value); } } }
            }
        }
    });
}

function renderDisparityChart(disparitasData, recommendedPrice) {
    const ctx = document.getElementById('disparityChart').getContext('2d');
    if(dispChartInstance) dispChartInstance.destroy();

    const labels = disparitasData.map(d => d.nama_pasar.replace(/^Pasar\s+/i, ''));
    const dataPrices = disparitasData.map(d => parseFloat(d.harga_hari_ini));
    const recData = new Array(labels.length).fill(recommendedPrice);

    const insideBarLabelPlugin = {
        id: 'insideBarLabel',
        afterDatasetsDraw(chart) {
            const { ctx, data } = chart;
            const meta = chart.getDatasetMeta(1);
            if(!meta.hidden) {
                ctx.save();
                ctx.font = 'bold 12px "Plus Jakarta Sans"';
                ctx.fillStyle = 'rgba(255, 255, 255, 0.95)';
                ctx.textAlign = 'left';
                ctx.textBaseline = 'middle';
                meta.data.forEach((bar, index) => {
                    const label = data.labels[index];
                    ctx.save();
                    ctx.translate(bar.x, bar.base - 15);
                    ctx.rotate(-Math.PI / 2);
                    ctx.fillText(label, 0, 3);
                    ctx.restore();
                });
                ctx.restore();
            }
        }
    };

    dispChartInstance = new Chart(ctx, {
        type: 'bar',
        plugins: [insideBarLabelPlugin],
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'line',
                    label: 'Rekomendasi XGBoost',
                    data: recData,
                    borderColor: '#16a34a',
                    borderWidth: 2, 
                    borderDash: [5, 5],
                    pointRadius: 0,
                    fill: false
                },
                {
                    type: 'bar',
                    label: 'Harga Aktual per Pasar',
                    data: dataPrices,
                    backgroundColor: 'rgba(45, 106, 79, 0.6)', // Tema Hijau SIPHP
                    borderRadius: 6,
                    barPercentage: 0.6,
                    maxBarThickness: 45
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: { 
                    grid: { display: false },
                    ticks: { display: false } // Nama pasar sudah di dalam diagram batang
                },
                y: { grid: { color: '#e8f5ee' }, ticks: { callback: function(value) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(value); } } }
            }
        }
    });
}
</script>
</body>
</html>
