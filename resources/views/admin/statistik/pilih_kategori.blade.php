<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Harga — {{ $pasar->nama_pasar }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f0faf4; min-height: 100vh; }
        .kat-card {
            position: relative; border-radius: 20px; overflow: hidden;
            cursor: pointer; height: 220px;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 16px rgba(45,106,79,0.1);
            border: 1.5px solid #d1e8d8;
            text-decoration: none; display: block;
        }
        .kat-card:hover { transform: translateY(-7px); box-shadow: 0 20px 40px rgba(45,106,79,0.2); }
        .kat-card:hover img { transform: scale(1.08); }
        .kat-card img { width:100%;height:100%;object-fit:cover;transition:transform 0.5s; }
        .kat-card .ov { position:absolute;inset:0;background:linear-gradient(to top,rgba(15,40,25,0.88) 0%,rgba(15,40,25,0.2) 55%,transparent 100%); }
        .kat-card .cnt { position:absolute;bottom:0;left:0;right:0;padding:20px 22px; }
        .kat-card .arrow { position:absolute;top:14px;right:14px;width:30px;height:30px;border-radius:50%;background:rgba(255,255,255,0.2);border:1px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;color:white;font-size:12px;transition:all 0.2s; }
        .kat-card:hover .arrow { background:#d0f0c0;color:#1e3a2f;transform:rotate(45deg); }
    </style>
</head>
<body>
<div style="max-width:960px;margin:0 auto;padding:40px 24px;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:36px;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:5px;">
                <a href="{{ route('admin.dashboard') }}" style="background:#d0f0c0;border:none;color:#1e3a2f;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:13px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 style="font-size:22px;font-weight:800;color:#1a3a2a;">Statistik Harga</h1>
            </div>
            <p style="font-size:13px;color:#5a8a6a;margin-left:42px;">
                <i class="fas fa-map-marker-alt" style="color:#ef4444;margin-right:5px;"></i>
                {{ $pasar->nama_pasar }} — Pilih kategori untuk melihat grafik
            </p>
        </div>
        <div style="background:#d0f0c0;border-radius:12px;padding:9px 16px;font-size:12px;font-weight:700;color:#1e3a2f;display:flex;align-items:center;gap:6px;">
            <span style="width:7px;height:7px;border-radius:50%;background:#2d6a4f;display:inline-block;"></span>
            Data Real-time
        </div>
    </div>

    {{-- Kartu Kategori --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;">
        @php
        $kategoris = [
            ['id'=>'pokok',   'title'=>'Barang Pokok',   'img'=>'barangpokok.png',   'desc'=>'Tren harga beras, gula, minyak, dll.'],
            ['id'=>'subsidi', 'title'=>'Barang Subsidi', 'img'=>'barangsubsidi.png', 'desc'=>'Tren harga LPG, minyak curah, dll.'],
            ['id'=>'penting', 'title'=>'Barang Penting', 'img'=>'barangpenting.png', 'desc'=>'Tren harga semen, besi, BBM, dll.'],
        ];
        @endphp
        @foreach($kategoris as $kat)
        <a href="{{ route('admin.statistik.grafik', [$pasar->id, $kat['id']]) }}" class="kat-card">
            <img src="{{ asset('img/'.$kat['img']) }}" alt="{{ $kat['title'] }}"
                onerror="this.parentElement.style.background='linear-gradient(135deg,#1e3a2f,#2d6a4f)'">
            <div class="ov"></div>
            <div class="arrow"><i class="fas fa-chart-line"></i></div>
            <div class="cnt">
                <div style="font-size:10px;font-weight:800;color:rgba(208,240,192,0.8);text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Statistik</div>
                <div style="font-size:20px;font-weight:800;color:white;margin-bottom:4px;">{{ $kat['title'] }}</div>
                <div style="font-size:12px;color:rgba(255,255,255,0.65);">{{ $kat['desc'] }}</div>
            </div>
        </a>
        @endforeach
    </div>
</div>
</body>
</html>
