@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{font-family:'Plus Jakarta Sans',sans-serif;box-sizing:border-box;}
:root{--g:#d0f0c0;--gd:#2d6a4f;--gdd:#1e3a2f;--border:#d1e8d8;--text:#1a3a2a;--sub:#5a8a6a;--bg:#f0faf4;}
.row-item{display:grid;grid-template-columns:2fr 1.1fr 1.3fr 1.3fr 1.3fr;padding:14px 22px;border-bottom:1px solid #e8f5ee;align-items:center;background:white;transition:background 0.15s;}
.row-item:hover{background:#f5fdf7;}
</style>

<div style="min-height:100vh;background:var(--bg);padding:32px;">
<div style="max-width:1000px;margin:0 auto;">

    @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #22c55e;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#15803d;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Header --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <a href="{{ route('admin.pedagang.index') }}" style="background:var(--g);color:var(--gdd);width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 style="font-size:22px;font-weight:800;color:var(--text);">Harga Barang Pedagang</h1>
            </div>
            <div style="display:flex;gap:16px;flex-wrap:wrap;margin-left:42px;">
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:36px;height:36px;border-radius:10px;background:var(--g);color:var(--gdd);display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:800;">
                        {{ strtoupper(substr($pedagang->nama_pedagang,0,1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700;color:var(--text);font-size:14px;">{{ $pedagang->nama_pedagang }}</div>
                        <div style="font-size:11px;color:var(--sub);">Pedagang</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--sub);">
                    <i class="fas fa-map-marker-alt" style="color:#ef4444;"></i>
                    {{ $pasar->nama_pasar }}
                </div>
            </div>
        </div>
        <div style="background:var(--g);border-radius:12px;padding:9px 16px;font-size:12px;font-weight:700;color:var(--gdd);">
            <i class="fas fa-box" style="margin-right:6px;"></i>{{ $harga_pedagang->count() }} data
        </div>
    </div>

    {{-- Tabel --}}
    <div style="border-radius:16px;overflow:hidden;box-shadow:0 2px 14px rgba(45,106,79,0.08);border:1.5px solid var(--border);">
        <div style="display:grid;grid-template-columns:2fr 1.1fr 1.3fr 1.3fr 1.3fr;padding:13px 22px;background:linear-gradient(135deg,var(--gdd),var(--gd));color:var(--g);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;">
            <div>Nama Barang</div>
            <div style="text-align:center;">Tanggal</div>
            <div style="text-align:right;padding-right:8px;">Harga Kemarin</div>
            <div style="text-align:right;padding-right:8px;">Harga Hari Ini</div>
            <div style="text-align:center;">Publikasi</div>
        </div>

        @forelse($harga_pedagang as $h)
        <div class="row-item">
            <div>
                <div style="font-weight:700;color:var(--text);font-size:14px;">{{ $h->nama_barang }}</div>
                <div style="font-size:11px;color:var(--sub);margin-top:2px;">{{ ucfirst($h->kategori) }}</div>
            </div>
            <div style="text-align:center;">
                <span style="background:#f0faf4;color:var(--sub);border:1px solid var(--border);border-radius:7px;padding:4px 9px;font-size:11px;font-weight:600;white-space:nowrap;">
                    {{ \Carbon\Carbon::parse($h->tanggal)->format('d M Y') }}
                </span>
            </div>
            <div style="text-align:right;padding-right:8px;color:#8aaa9a;font-size:13px;">
                Rp {{ number_format($h->harga_kemarin, 0, ',', '.') }}
            </div>
            <div style="text-align:right;padding-right:8px;font-weight:800;color:var(--text);font-size:14px;">
                Rp {{ number_format($h->harga_hari_ini, 0, ',', '.') }}
            </div>
            <div style="text-align:center;">
                @if($h->status=='update')
                    <span style="background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0;border-radius:999px;padding:5px 12px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:5px;">
                        <span style="width:6px;height:6px;border-radius:50%;background:#16a34a;display:inline-block;"></span> Published
                    </span>
                @else
                    <form action="{{ route('admin.pedagang.publish', $h->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:linear-gradient(135deg,var(--gdd),var(--gd));color:white;border:none;border-radius:8px;padding:6px 14px;font-size:11px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:5px;font-family:'Plus Jakarta Sans',sans-serif;">
                            <i class="fas fa-paper-plane" style="font-size:10px;"></i> Publikasikan
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @empty
        <div style="padding:56px;text-align:center;background:white;">
            <i class="fas fa-box-open" style="font-size:2.5rem;color:#c6ebd4;display:block;margin-bottom:12px;"></i>
            <p style="color:var(--sub);font-weight:600;font-size:14px;">Pedagang ini belum menginput harga apapun.</p>
        </div>
        @endforelse
    </div>
</div>
</div>
@endsection
