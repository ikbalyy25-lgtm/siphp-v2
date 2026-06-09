@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
* { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
:root { --g:#d0f0c0; --gd:#2d6a4f; --gdd:#1e3a2f; --border:#d1e8d8; --text:#1a3a2a; --sub:#5a8a6a; --bg:#f0faf4; }
body { background: var(--bg); margin: 0; }
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

<div style="padding:32px; min-height:100vh;">
    <div style="max-width:1120px;margin:0 auto;">
        
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:5px;">
                    <a href="{{ route('admin.dashboard') }}" style="background:var(--g);color:var(--gdd);width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;flex-shrink:0;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 style="font-size:24px;font-weight:800;color:var(--gdd);margin:0;">
                        <i class="fas fa-microchip" style="color:var(--gd);margin-right:10px;"></i>Rekomendasi Harga Optimal
                    </h1>
                </div>
                <p style="font-size:14px;color:var(--sub);margin:6px 0 0; margin-left: 42px;">
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
        </div>

    </div>
</div>

<script>
let chartInstance = null;
const csrfToken = '{{ csrf_token() }}';
const endpoint = '{{ route("admin.rekomendasi.analyze") }}'; 
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

        // Populate metrics
        document.getElementById('resMae').textContent = data.metrics.mae.toString().replace('.', ',');
        document.getElementById('resMse').textContent = data.metrics.mse.toString().replace('.', ',');
        document.getElementById('resRmse').textContent = data.metrics.rmse.toString().replace('.', ',');
        
        document.getElementById('resHarga').textContent = formatRupiah(data.predicted_price);
        document.getElementById('resTanggal').textContent = 'Per ' + formatTanggalIndo(data.next_date);

        // Render Chart
        renderChart(data.chart.historical_dates, data.chart.historical_prices, data.chart.test_dates, data.chart.test_predictions, data.next_date, data.predicted_price);
        
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

    // Menggabungkan data aktual (historis + testing) menjadi satu garis "Aktual"
    // Tapi karena testing dates adalah bagian akhir dari historis dates, 
    // kita akan memplot: 
    // - Garis Aktual (biru) untuk semua histDates
    // - Garis Prediksi Testing (orange) mulai dari tanggal test pertama sampai akhir
    // - Titik Prediksi Besok (hijau)

    // Buat array prediksi testing yang sejalan dengan histDates (null untuk yang bukan testing)
    const testDataAligned = histDates.map(date => {
        const idx = testDates.indexOf(date);
        return idx !== -1 ? testPrices[idx] : null;
    });

    // Tambahkan data besok
    const allDates = [...histDates, nextDate];
    const actualData = [...histPrices, null];
    const testData = [...testDataAligned, null];
    
    // Array khusus untuk titik prediksi besok (hanya isi di index terakhir)
    const nextPredData = new Array(allDates.length).fill(null);
    nextPredData[allDates.length - 1] = nextPrice;
    
    // Hubungkan garis antara prediksi test terakhir dengan titik prediksi besok
    if(testPrices.length > 0) {
        testData[testData.length - 1] = nextPrice; // Biar garis orange lanjut ke titik hijau
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
</script>
@endsection
