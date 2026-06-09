<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unduh Laporan — SIPHP</title>
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
        .inp { width:100%; padding:11px 14px; border-radius:11px; font-size:14px; border:1.5px solid var(--border); background:#f8fdf9; color:var(--text); outline:none; transition:border-color 0.2s; font-family:'Plus Jakarta Sans',sans-serif; }
        .inp:focus { border-color:var(--gd); box-shadow:0 0 0 3px rgba(45,106,79,0.1); }
        .form-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:var(--sub); display:block; margin-bottom:7px; }
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
        <a href="{{ route('kepala_dinas.rekomendasi') }}" class="nav-item"><i class="fas fa-lightbulb"></i> Rekomendasi Harga</a>
        <a href="{{ route('kepala_dinas.laporan') }}" class="nav-item active"><i class="fas fa-file-arrow-down"></i> Unduh Laporan</a>
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

    <div style="margin-bottom:28px;">
        <h1 style="font-size:22px;font-weight:800;color:var(--text);">Unduh Laporan Harga</h1>
        <p style="font-size:13px;color:var(--sub);margin-top:2px;">
            Export data harga pasar ke format PDF atau Excel
        </p>
    </div>

    {{-- Form Unduh --}}
    <div style="max-width:600px;">
        <div style="background:white;border:1.5px solid var(--border);border-radius:20px;padding:28px;box-shadow:0 4px 20px rgba(45,106,79,0.07);">

            <form action="{{ route('kepala_dinas.laporan.unduh') }}" method="GET">

                {{-- Bulan & Tahun --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px;">
                    <div>
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="inp">
                            @for($m=1;$m<=12;$m++)
                            <option value="{{ $m }}" {{ date('n')==$m?'selected':'' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="inp">
                            @for($y=date('Y');$y>=date('Y')-5;$y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>



                {{-- Kategori --}}
                <div style="margin-bottom:18px;">
                    <label class="form-label">Kategori Barang</label>
                    <select name="kategori" class="inp">
                        <option value="semua">Semua Kategori</option>
                        <option value="pokok">Barang Pokok</option>
                        <option value="subsidi">Barang Subsidi</option>
                        <option value="penting">Barang Penting</option>
                    </select>
                </div>

                {{-- Pasar --}}
                <div style="margin-bottom:28px;">
                    <label class="form-label">Pasar</label>
                    <select name="pasar_id" class="inp">
                        <option value="">Semua Pasar</option>
                        @foreach($pasars as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_pasar }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol Unduh --}}
                <div style="display:flex;gap:12px;">
                    <button type="submit" name="tipe" value="excel"
                        style="flex:1;background:linear-gradient(135deg,#15803d,#4ade80);color:white;border:none;border-radius:12px;padding:13px;font-weight:700;font-size:13px;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 14px rgba(21,128,61,0.25);">
                        <i class="fas fa-file-excel"></i> Unduh Excel
                    </button>
                    <button type="submit" name="tipe" value="pdf"
                        style="flex:1;background:linear-gradient(135deg,#991b1b,#f87171);color:white;border:none;border-radius:12px;padding:13px;font-weight:700;font-size:13px;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 14px rgba(153,27,27,0.25);">
                        <i class="fas fa-file-pdf"></i> Unduh PDF
                    </button>
                </div>
            </form>
        </div>

        {{-- Info --}}
        <div style="background:#f0faf4;border:1.5px solid var(--border);border-radius:14px;padding:16px 18px;margin-top:16px;display:flex;gap:12px;align-items:flex-start;">
            <i class="fas fa-circle-info" style="color:var(--gd);font-size:16px;margin-top:2px;flex-shrink:0;"></i>
            <div style="font-size:12px;color:var(--sub);line-height:1.7;">
                Laporan berisi data harga barang yang sudah berstatus <strong>Update</strong> dari pasar yang dipilih.
                Pilih <strong>Semua Pasar</strong> untuk mendapatkan laporan gabungan 5 pasar sekaligus.
            </div>
        </div>
    </div>
</main>
</body>
</html>
