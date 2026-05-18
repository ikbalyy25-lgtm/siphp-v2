<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrian Harga — SIPHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family:'Plus Jakarta Sans',sans-serif; box-sizing:border-box; }
        :root { --g:#d0f0c0; --gd:#2d6a4f; --gdd:#1e3a2f; --border:#d1e8d8; --sub:#5a8a6a; }
        body { background:#f0faf4; margin:0; }
        .main { padding:28px 32px; }
        .card { background:white; border:1.5px solid var(--border); border-radius:16px; padding:24px; margin-bottom:20px; box-shadow:0 2px 8px rgba(45,106,79,0.05); }
        .pasar-header { display:flex; align-items:center; gap:12px; padding:14px 20px; background:#f5fdf7; border-radius:12px; margin-bottom:12px; border:1px solid var(--border); }
        .row { display:grid; grid-template-columns:2fr 1fr 1fr 1fr 1fr 1fr 140px; align-items:center; padding:12px 16px; border-bottom:1px solid #e8f5ee; font-size:13px; }
        .row:hover { background:#f9fffe; }
        .row-header { display:grid; grid-template-columns:2fr 1fr 1fr 1fr 1fr 1fr 140px; padding:8px 16px; font-size:11px; font-weight:700; color:var(--sub); text-transform:uppercase; letter-spacing:1px; }
        .btn-approve { background:#22c55e; color:white; border:none; padding:7px 14px; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; transition:all 0.2s; }
        .btn-approve:hover { background:#16a34a; }
        .btn-tolak { background:#fee2e2; color:#dc2626; border:none; padding:7px 14px; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; transition:all 0.2s; }
        .btn-tolak:hover { background:#dc2626; color:white; }
        .badge-pending { background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:600; }
        .alert { padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px; display:flex; align-items:center; gap:10px; }
        .alert-success { background:#d0f0c0; color:#1e3a2f; border:1px solid #b0dca0; }
        .alert-info { background:#dbeafe; color:#1e3a8a; border:1px solid #93c5fd; }
        .empty-state { text-align:center; padding:48px 20px; color:var(--sub); }
    </style>
</head>
<body>
<main class="main">

    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
        <div>
            <h1 style="font-size:22px;font-weight:800;color:var(--gdd);margin:0;">
                <i class="fas fa-inbox" style="color:var(--gd);margin-right:10px;"></i>Antrian Harga Masuk
            </h1>
            <p style="font-size:13px;color:var(--sub);margin:4px 0 0;">
                Data dari admin pedagang menunggu persetujuan Anda
            </p>
        </div>
        @if($totalPending > 0)
        <form action="{{ route('admin.antrian.approveAll') }}" method="POST"
              onsubmit="return confirm('Setujui semua {{ $totalPending }} data sekaligus?')">
            @csrf
            <button type="submit" style="background:var(--gd);color:white;border:none;padding:12px 24px;border-radius:12px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-check-double"></i> Setujui Semua ({{ $totalPending }})
            </button>
        </form>
        @endif
    </div>

    @if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('info'))
    <div class="alert alert-info"><i class="fas fa-info-circle"></i> {{ session('info') }}</div>
    @endif

    @if($totalPending === 0)
    <div class="card">
        <div class="empty-state">
            <i class="fas fa-check-circle" style="font-size:48px;color:#22c55e;margin-bottom:16px;display:block;"></i>
            <div style="font-size:16px;font-weight:700;color:var(--gdd);">Semua data sudah diproses!</div>
            <div style="font-size:13px;margin-top:8px;">Tidak ada antrian harga yang menunggu persetujuan.</div>
        </div>
    </div>
    @else

    {{-- Kelompok per pasar --}}
    @foreach($antrian as $pasarId => $items)
    @php $pasar = $items->first()->pasar; @endphp
    <div class="card">
        <div class="pasar-header">
            <i class="fas fa-store" style="color:var(--gd);font-size:16px;"></i>
            <div>
                <div style="font-size:14px;font-weight:700;color:var(--gdd);">{{ $pasar->nama_pasar }}</div>
                <div style="font-size:12px;color:var(--sub);">{{ $items->count() }} data menunggu</div>
            </div>
            <span class="badge-pending" style="margin-left:auto;">{{ $items->count() }} Pending</span>
        </div>

        <div class="row-header">
            <span>Komoditas</span>
            <span>Kategori</span>
            <span>Pedagang 1</span>
            <span>Pedagang 2</span>
            <span>Pedagang 3</span>
            <span>Rata-rata</span>
            <span>Aksi</span>
        </div>

        @foreach($items as $item)
        @php $inp = $item->inputPedagang; @endphp
        <div class="row">
            <div>
                <div style="font-weight:600;color:#1a3a2a;">{{ $item->nama_barang }}</div>
                <div style="font-size:11px;color:var(--sub);">{{ $item->tanggal->format('d M Y') }} · oleh {{ $inp?->user?->name ?? 'admin' }}</div>
            </div>
            <div><span style="background:#f0faf4;color:var(--gd);padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ ucfirst($item->kategori) }}</span></div>
            <div style="font-weight:500;">Rp {{ $inp ? number_format($inp->harga_pedagang_1,0,',','.') : '-' }}</div>
            <div style="font-weight:500;">Rp {{ $inp ? number_format($inp->harga_pedagang_2,0,',','.') : '-' }}</div>
            <div style="font-weight:500;">Rp {{ $inp ? number_format($inp->harga_pedagang_3,0,',','.') : '-' }}</div>
            <div style="font-weight:700;color:var(--gd);">Rp {{ number_format($item->harga_hari_ini,0,',','.') }}</div>
            <div style="display:flex;gap:6px;">
                <form action="{{ route('admin.antrian.approve', $item->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-approve" title="Setujui"><i class="fas fa-check"></i></button>
                </form>
                <form action="{{ route('admin.antrian.tolak', $item->id) }}" method="POST"
                      onsubmit="return confirm('Tolak data ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-tolak" title="Tolak"><i class="fas fa-times"></i></button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach

    @endif
</main>
</body>
</html>
