<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Harga — SIPHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        :root { --g:#d0f0c0; --gd:#2d6a4f; --gdd:#1e3a2f; --border:#d1e8d8; --text:#1a3a2a; --sub:#5a8a6a; }
        body { background:#f0faf4; margin:0; }
        .sidebar { width:240px; position:fixed; top:0; left:0; bottom:0; background:white; border-right:1.5px solid var(--border); box-shadow:4px 0 16px rgba(45,106,79,0.07); display:flex; flex-direction:column; z-index:40; overflow-y:auto; }
        .nav-item { display:flex; align-items:center; gap:10px; padding:10px 16px; border-radius:10px; margin:2px 10px; font-size:13px; font-weight:600; color:#3a5a4a; text-decoration:none; transition:all 0.18s; }
        .nav-item:hover { background:#f0faf4; color:var(--gdd); }
        .nav-item.active { background:#e8f5ee; color:var(--gdd); border-left:3px solid var(--gd); }
        .nav-item i { width:16px; text-align:center; font-size:13px; }
        .nav-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:1.5px; color:#9ab8a8; padding:0 20px; margin:14px 0 5px; }
        .main { margin-left:240px; padding:28px 32px; min-height:100vh; }
        .card { background:white; border:1.5px solid var(--border); border-radius:16px; padding:28px; box-shadow:0 2px 8px rgba(45,106,79,0.06); }
        .form-group { margin-bottom:20px; }
        label { display:block; font-size:13px; font-weight:600; color:var(--text); margin-bottom:7px; }
        .sub-label { font-size:11px; color:var(--sub); font-weight:400; margin-left:6px; }
        input, select { width:100%; padding:11px 14px; border:1.5px solid var(--border); border-radius:10px; font-size:13px; color:var(--text); outline:none; transition:border 0.2s; background:white; font-family:inherit; }
        input:focus, select:focus { border-color:var(--gd); box-shadow:0 0 0 3px rgba(45,106,79,0.08); }
        .input-prefix { display:flex; align-items:center; border:1.5px solid var(--border); border-radius:10px; overflow:hidden; transition:border 0.2s; }
        .input-prefix:focus-within { border-color:var(--gd); box-shadow:0 0 0 3px rgba(45,106,79,0.08); }
        .input-prefix span { padding:11px 14px; background:#f5fdf7; font-size:13px; font-weight:600; color:var(--sub); border-right:1.5px solid var(--border); white-space:nowrap; }
        .input-prefix input { border:none; border-radius:0; box-shadow:none; }
        .input-prefix input:focus { box-shadow:none; }
        .preview-box { background:#f0faf4; border:2px dashed var(--gd); border-radius:14px; padding:20px; text-align:center; }
        .preview-harga { font-size:28px; font-weight:800; color:var(--gd); }
        .btn-primary { background:var(--gd); color:white; border:none; padding:13px 28px; border-radius:12px; font-size:14px; font-weight:700; cursor:pointer; transition:all 0.2s; width:100%; }
        .btn-primary:hover { background:var(--gdd); transform:translateY(-1px); }
        .btn-secondary { background:white; color:var(--gd); border:1.5px solid var(--gd); padding:11px 24px; border-radius:12px; font-size:13px; font-weight:600; cursor:pointer; transition:all 0.2s; text-decoration:none; display:inline-block; }
        .step-badge { display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; background:var(--gd); color:white; border-radius:50%; font-size:12px; font-weight:700; margin-right:8px; flex-shrink:0; }
        .alert { padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px; display:flex; align-items:center; gap:10px; }
        .alert-success { background:#d0f0c0; color:#1e3a2f; border:1px solid #b0dca0; }
        .alert-error { background:#fee2e2; color:#7f1d1d; border:1px solid #fca5a5; }
    </style>
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar">
    <div style="padding:18px 20px;border-bottom:1px solid #e8f5ee;">
        <div style="font-size:14px;font-weight:800;color:var(--gdd);">SIPHP</div>
        <div style="font-size:11px;color:var(--sub);">{{ $pasar->nama_pasar }}</div>
    </div>
    <nav style="padding:12px 0;flex:1;">
        <div class="nav-label">Menu</div>
        <a href="{{ route('admin_pasar.dashboard') }}" class="nav-item"><i class="fas fa-home"></i> Dashboard</a>
        <div class="nav-label">Input Harga</div>
        <a href="{{ route('admin_pasar.harga.index', 'pokok') }}" class="nav-item {{ $kategori=='pokok'?'active':'' }}"><i class="fas fa-shopping-basket"></i> Bahan Pokok</a>
        <a href="{{ route('admin_pasar.harga.index', 'subsidi') }}" class="nav-item {{ $kategori=='subsidi'?'active':'' }}"><i class="fas fa-tags"></i> Subsidi</a>
        <a href="{{ route('admin_pasar.harga.index', 'penting') }}" class="nav-item {{ $kategori=='penting'?'active':'' }}"><i class="fas fa-star"></i> Barang Penting</a>
    </nav>
    <div style="padding:14px 20px;border-top:1px solid #e8f5ee;">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-item" style="width:100%;border:none;background:none;cursor:pointer;color:#e53e3e;"><i class="fas fa-sign-out-alt"></i> Keluar</button>
        </form>
    </div>
</aside>

<main class="main">
    {{-- Header --}}
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:28px;">
        <a href="{{ route('admin_pasar.harga.index', $kategori) }}" class="btn-secondary" style="padding:9px 16px;"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h1 style="font-size:20px;font-weight:800;color:var(--gdd);margin:0;">Input Harga Komoditas</h1>
            <p style="font-size:12px;color:var(--sub);margin:3px 0 0;">
                Kategori: <b>{{ ucfirst($kategori) }}</b> — {{ $pasar->nama_pasar }}
            </p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">

        {{-- FORM --}}
        <div class="card">
            <h2 style="font-size:16px;font-weight:700;color:var(--gdd);margin:0 0 24px;">
                <i class="fas fa-edit" style="color:var(--gd);margin-right:8px;"></i>Form Input Harga 3 Pedagang
            </h2>

            <form action="{{ route('admin_pasar.harga.store') }}" method="POST" id="formHarga">
                @csrf
                <input type="hidden" name="kategori" value="{{ $kategori }}">

                <div class="form-group">
                    <label>Nama Komoditas</label>
                    <select name="nama_barang" required onchange="updatePreview()">
                        <option value="">-- Pilih Komoditas --</option>
                        @foreach($daftarBarang as $barang)
                        <option value="{{ $barang }}" {{ old('nama_barang') == $barang ? 'selected' : '' }}>{{ $barang }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Pencatatan</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                </div>

                {{-- 3 Input Pedagang --}}
                @foreach([1,2,3] as $n)
                <div class="form-group">
                    <label>
                        <span class="step-badge">{{ $n }}</span>
                        Harga Pedagang {{ $n }}
                        <span class="sub-label">per satuan/kg</span>
                    </label>
                    <div class="input-prefix">
                        <span>Rp</span>
                        <input type="number" name="harga_pedagang_{{ $n }}" id="harga{{ $n }}"
                               value="{{ old('harga_pedagang_'.$n) }}"
                               placeholder="0" min="1" required
                               oninput="updatePreview()">
                    </div>
                </div>
                @endforeach

                <button type="submit" class="btn-primary" style="margin-top:8px;">
                    <i class="fas fa-paper-plane"></i> Kirim ke Admin Master
                </button>
            </form>
        </div>

        {{-- PREVIEW RATA-RATA --}}
        <div>
            <div class="card" style="margin-bottom:16px;">
                <h3 style="font-size:13px;font-weight:700;color:var(--sub);text-transform:uppercase;letter-spacing:1px;margin:0 0 16px;">Preview Rata-rata</h3>
                <div class="preview-box">
                    <div style="font-size:12px;color:var(--sub);margin-bottom:4px;">Harga Rata-rata</div>
                    <div class="preview-harga" id="previewRata">Rp —</div>
                    <div style="font-size:11px;color:var(--sub);margin-top:8px;">= (P1 + P2 + P3) ÷ 3</div>
                </div>
                <div style="margin-top:16px;font-size:12px;color:var(--sub);">
                    <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #e8f5ee;">
                        <span>Pedagang 1</span><span id="p1val" style="font-weight:600;color:var(--text);">—</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #e8f5ee;">
                        <span>Pedagang 2</span><span id="p2val" style="font-weight:600;color:var(--text);">—</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:6px 0;">
                        <span>Pedagang 3</span><span id="p3val" style="font-weight:600;color:var(--text);">—</span>
                    </div>
                </div>
            </div>

            <div class="card" style="background:#fffbf0;border-color:#f6e05e;">
                <div style="font-size:12px;font-weight:700;color:#744210;margin-bottom:8px;"><i class="fas fa-info-circle"></i> Cara Kerja</div>
                <ul style="font-size:12px;color:#92400e;margin:0;padding-left:16px;line-height:1.8;">
                    <li>Input harga dari 3 pedagang berbeda</li>
                    <li>Sistem hitung rata-rata otomatis</li>
                    <li>Data masuk antrian Admin Master</li>
                    <li>Setelah disetujui, harga tampil ke publik</li>
                </ul>
            </div>
        </div>

    </div>
</main>

<script>
function formatRp(n) {
    if (!n || isNaN(n)) return '—';
    return 'Rp ' + parseInt(n).toLocaleString('id-ID');
}
function updatePreview() {
    const h1 = parseFloat(document.getElementById('harga1').value) || 0;
    const h2 = parseFloat(document.getElementById('harga2').value) || 0;
    const h3 = parseFloat(document.getElementById('harga3').value) || 0;
    document.getElementById('p1val').textContent = h1 ? formatRp(h1) : '—';
    document.getElementById('p2val').textContent = h2 ? formatRp(h2) : '—';
    document.getElementById('p3val').textContent = h3 ? formatRp(h3) : '—';
    if (h1 && h2 && h3) {
        const rata = Math.round((h1 + h2 + h3) / 3);
        document.getElementById('previewRata').textContent = formatRp(rata);
        document.getElementById('previewRata').style.color = '#2d6a4f';
    } else {
        document.getElementById('previewRata').textContent = 'Rp —';
    }
}
</script>
</body>
</html>
