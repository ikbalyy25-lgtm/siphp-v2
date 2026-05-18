<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komparasi Harga Pasar — SIPHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        :root {
            --g: #d0f0c0;
            --gd: #2d6a4f;
            --gdd: #1e3a2f;
            --border: #d1e8d8;
            --sub: #5a8a6a;
            --text: #1a3a2a;
        }
        body { background: #f0faf4; margin: 0; min-height: 100vh; }
        .main { padding: 28px 32px; max-width: 1200px; margin: 0 auto; }
        .card { background: white; border: 1.5px solid var(--border); border-radius: 20px; padding: 28px; box-shadow: 0 4px 20px rgba(45,106,79,0.05); transition: transform 0.2s; }
        .tab-btn { padding: 10px 22px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; border: 1.5px solid var(--border); background: white; color: var(--sub); transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .tab-btn.active { background: var(--gd); color: white; border-color: var(--gd); box-shadow: 0 4px 12px rgba(45,106,79,0.15); }
        .tab-btn:hover:not(.active) { background: #f5fdf7; color: var(--text); border-color: var(--gd); }
        .form-select { width: 100%; padding: 12px 16px; border-radius: 12px; font-size: 14px; font-weight: 600; border: 1.5px solid var(--border); background: #f8fdf9; color: var(--text); outline: none; transition: border-color 0.2s, box-shadow 0.2s; cursor: pointer; -webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%232d6a4f' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; background-size: 16px; }
        .form-select:focus { border-color: var(--gd); box-shadow: 0 0 0 3px rgba(45,106,79,0.1); background-color: white; }
        .komparasi-table-header { display: grid; grid-template-columns: 2fr 1fr 1fr 1.2fr; padding: 14px 24px; background: linear-gradient(135deg, var(--gdd), var(--gd)); color: var(--g); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; border-radius: 16px 16px 0 0; }
        .komparasi-table-row { display: grid; grid-template-columns: 2fr 1fr 1fr 1.2fr; padding: 16px 24px; border-bottom: 1px solid #e8f5ee; align-items: center; background: white; transition: background 0.15s; }
        .komparasi-table-row:hover { background: #f5fdf7; }
        .komparasi-table-row:last-child { border-radius: 0 0 16px 16px; border-bottom: none; }
        .disparity-badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
        .disparity-high { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
        .disparity-low { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .disparity-normal { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    </style>
</head>
<body>
<main class="main">

    {{-- Back Header --}}
    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" 
               class="bg-white border-2 border-[#d1e8d8] text-[#1e3a2f] hover:bg-[#e8f5ee] w-11 h-11 rounded-xl flex items-center justify-center text-sm transition-all duration-200 shadow-sm"
               title="Kembali ke Dashboard">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-[#1e3a2f] m-0 flex items-center gap-3">
                    <i class="fas fa-scale-balanced text-[#2d6a4f]"></i> Komparasi Harga Antar Pasar
                </h1>
                <p class="text-xs text-emerald-700/70 font-semibold mt-1">
                    Bandingkan disparitas dan tren harga komoditas secara real-time di Parepare
                </p>
            </div>
        </div>

        {{-- Kategori Selector Tabs --}}
        <div class="flex gap-2 flex-wrap">
            <a href="?kategori=pokok" class="tab-btn {{ $kategori=='pokok'?'active':'' }}">
                <i class="fas fa-shopping-basket"></i> Bahan Pokok
            </a>
            <a href="?kategori=subsidi" class="tab-btn {{ $kategori=='subsidi'?'active':'' }}">
                <i class="fas fa-tags"></i> Subsidi
            </a>
            <a href="?kategori=penting" class="tab-btn {{ $kategori=='penting'?'active':'' }}">
                <i class="fas fa-star"></i> Barang Penting
            </a>
        </div>
    </div>

    @if($komoditasList->isEmpty())
    <div class="card p-12 text-center">
        <i class="fas fa-database text-5xl text-emerald-200 mb-4 block"></i>
        <h3 class="text-lg font-bold text-[#1e3a2f]">Tidak Ada Data</h3>
        <p class="text-sm text-emerald-700/60 mt-2">Belum ada data komoditas yang terbit untuk kategori ini.</p>
    </div>
    @else

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Left Side: Selector Card --}}
        <div class="card lg:col-span-1 flex flex-col justify-between h-full">
            <div>
                <span class="text-[10px] font-extrabold tracking-widest text-[#9ab8a8] uppercase block mb-2">Pilih Komoditas</span>
                <h3 class="text-base font-extrabold text-[#1e3a2f] mb-4">Analisis Komparasi</h3>
                <p class="text-xs text-emerald-700/70 leading-relaxed mb-6">
                    Pilih komoditas di bawah ini untuk melihat perbandingan tren harga grafis dan tingkat disparitas harga antar pasar terdaftar.
                </p>

                <div class="relative mb-6">
                    <label class="text-[10px] font-extrabold uppercase tracking-wide text-emerald-700/60 block mb-2">Komoditas Tersedia</label>
                    <select id="commoditySelect" class="form-select" onchange="changeCommodity(this.value)">
                        @foreach($komoditasList as $item)
                        <option value="{{ $item }}" {{ $namaBarang == $item ? 'selected' : '' }}>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Stat Box --}}
            <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100/70 flex items-center justify-center text-[#2d6a4f]">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-emerald-700/60 uppercase">Komoditas Aktif</div>
                        <div class="text-sm font-extrabold text-[#1a3a2a] truncate max-w-[180px]">{{ $namaBarang }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side: Chart Card --}}
        <div class="card lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-extrabold text-[#1e3a2f]">Grafik Tren Harga Antar Pasar</h3>
                    <p class="text-xs text-emerald-700/60 mt-1">Timeline perubahan harga berdasarkan tanggal input</p>
                </div>
                <span class="bg-emerald-100/60 text-emerald-800 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">Published Data</span>
            </div>
            
            <div class="relative w-full h-[320px]">
                <canvas id="priceComparisonChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Details Table --}}
    <div class="mb-8">
        <h3 class="text-base font-extrabold text-[#1e3a2f] mb-4 flex items-center gap-2">
            <i class="fas fa-list-check text-emerald-700"></i> Detail Harga Terkini Per Pasar
        </h3>
        
        <div class="border border-emerald-100/60 rounded-2xl overflow-hidden shadow-sm">
            <div class="komparasi-table-header">
                <span>Nama Pasar</span>
                <span>Harga</span>
                <span>Selisih vs Rata-rata</span>
                <span>Terakhir Update</span>
            </div>

            <div id="tableBody">
                {{-- Diisi secara dinamis dengan JS berdasarkan data --}}
            </div>
        </div>
    </div>

    @endif
</main>

<script>
    // Sinkronisasi data ke JavaScript
    const rawData = @json($data);
    const selectedCommodity = "{{ $namaBarang }}";
    const selectedKategori = "{{ $kategori }}";

    function changeCommodity(commodity) {
        window.location.href = `?kategori=${selectedKategori}&barang=${encodeURIComponent(commodity)}`;
    }

    document.addEventListener("DOMContentLoaded", function() {
        if (rawData.length === 0) return;

        // --- 1. PROSES DATA GRAFIK ---
        // Dapatkan semua pasar unik
        const markets = [...new Set(rawData.map(item => item.nama_pasar))];
        // Dapatkan semua tanggal unik dan urutkan secara kronologis (asc)
        const dates = [...new Set(rawData.map(item => item.tanggal))].sort();

        const marketColors = [
            '#2d6a4f', // Pasar Lakessi (Green Forest)
            '#f59e0b', // Pasar Senggol (Amber)
            '#3b82f6', // Pasar Labukkang (Blue)
            '#8b5cf6', // Pasar Sumpang Minangae (Purple)
            '#ec4899'  // Pasar Wekkee (Pink)
        ];

        const datasets = markets.map((market, index) => {
            const color = marketColors[index % marketColors.length];
            const priceData = dates.map(date => {
                const record = rawData.find(item => item.nama_pasar === market && item.tanggal === date);
                return record ? Number(record.harga_hari_ini) : null;
            });

            return {
                label: market,
                data: priceData,
                borderColor: color,
                backgroundColor: color + '0a',
                borderWidth: 3,
                tension: 0.3,
                pointBackgroundColor: color,
                pointBorderColor: '#ffffff',
                pointBorderWidth: 1.5,
                pointRadius: 4,
                pointHoverRadius: 6,
                spanGaps: true
            };
        });

        // Format tanggal ke format lokal Indonesia (d M Y)
        const formatDateLabel = (dateStr) => {
            const d = new Date(dateStr);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
        };

        const formattedLabels = dates.map(formatDateLabel);

        // --- 2. RENDER GRAFIK ---
        const ctx = document.getElementById('priceComparisonChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: formattedLabels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            font: { family: 'Plus Jakarta Sans', weight: '700', size: 11 },
                            color: '#1a3a2a',
                            padding: 16
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e3a2f',
                        titleFont: { family: 'Plus Jakarta Sans', weight: '800', size: 12 },
                        bodyFont: { family: 'Plus Jakarta Sans', weight: '600', size: 12 },
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', weight: '600', size: 10 },
                            color: '#5a8a6a'
                        }
                    },
                    y: {
                        grid: { color: '#e8f5ee' },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', weight: '600', size: 10 },
                            color: '#5a8a6a',
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });

        // --- 3. PROSES & RENDER TABEL HARGA TERBARU ---
        // Hitung rata-rata harga terbaru per pasar
        const latestPrices = [];
        markets.forEach(market => {
            const marketData = rawData.filter(item => item.nama_pasar === market);
            if (marketData.length > 0) {
                // Ambil data dengan tanggal terbaru (karena rawData diurutkan asc, item terakhir adalah yang terbaru)
                const latest = marketData[marketData.length - 1];
                latestPrices.push(latest);
            }
        });

        // Hitung rata-rata global dari harga terbaru ini
        const avgPrice = latestPrices.reduce((sum, item) => sum + Number(item.harga_hari_ini), 0) / latestPrices.length;

        const tableBody = document.getElementById('tableBody');
        if (latestPrices.length === 0) {
            tableBody.innerHTML = `
                <div class="p-8 text-center bg-white text-emerald-700/60 font-semibold">
                    Tidak ada data harga terbaru.
                </div>`;
            return;
        }

        latestPrices.forEach(item => {
            const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(item.harga_hari_ini);
            const formattedDate = formatDateLabel(item.tanggal);

            // Persentase selisih vs rata-rata
            const diffPct = avgPrice > 0 ? ((item.harga_hari_ini - avgPrice) / avgPrice) * 100 : 0;
            const diffText = diffPct > 0 ? `+${diffPct.toFixed(1)}%` : `${diffPct.toFixed(1)}%`;

            let badgeClass = 'disparity-normal';
            let icon = 'fa-check';
            let statusText = 'Normal';

            if (diffPct > 10) {
                badgeClass = 'disparity-high';
                icon = 'fa-arrow-up-long';
                statusText = `Tinggi (${diffText})`;
            } else if (diffPct < -10) {
                badgeClass = 'disparity-low';
                icon = 'fa-arrow-down-long';
                statusText = `Rendah (${diffText})`;
            } else {
                statusText = `Normal (${diffText})`;
            }

            const row = document.createElement('div');
            row.className = 'komparasi-table-row';
            row.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-[#2d6a4f]">
                        <i class="fas fa-store text-xs"></i>
                    </div>
                    <span class="font-extrabold text-[#1a3a2a] text-sm">${item.nama_pasar}</span>
                </div>
                <span class="font-extrabold text-[#2d6a4f] text-sm">${formattedPrice}</span>
                <div>
                    <span class="disparity-badge ${badgeClass}">
                        <i class="fas ${icon}"></i> ${statusText}
                    </span>
                </div>
                <span class="text-xs text-emerald-700/60 font-bold">${formattedDate}</span>
            `;
            tableBody.appendChild(row);
        });
    });
</script>
</body>
</html>
