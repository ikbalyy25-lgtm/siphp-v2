@extends('layouts.app')
@section('content')
<div style="min-height:100vh;background:#f0faf4;padding:32px;font-family:'Plus Jakarta Sans',sans-serif;">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>*{font-family:'Plus Jakarta Sans',sans-serif;box-sizing:border-box;}:root{--g:#d0f0c0;--gd:#2d6a4f;--gdd:#1e3a2f;--border:#d1e8d8;--text:#1a3a2a;--sub:#5a8a6a;}.card-pedagang{background:white;border:1.5px solid var(--border);border-radius:16px;padding:20px 22px;transition:transform 0.2s,box-shadow 0.2s;}.card-pedagang:hover{transform:translateY(-3px);box-shadow:0 10px 28px rgba(45,106,79,0.12);}.inp-field{padding:10px 14px;border-radius:10px;font-size:13px;border:1.5px solid var(--border);background:#f8fdf9;color:var(--text);outline:none;font-family:'Plus Jakarta Sans',sans-serif;}</style>
<div style="max-width:1100px;margin:0 auto;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:5px;">
                <a href="{{ route('admin_master.dashboard') }}" style="background:#d0f0c0;color:#1e3a2f;width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 style="font-size:22px;font-weight:800;color:var(--text);">Data Pedagang</h1>
            </div>
            <p style="font-size:13px;color:var(--sub);margin-left:42px;">
                <i class="fas fa-map-marker-alt" style="color:#ef4444;margin-right:5px;"></i>{{ $pasar->nama_pasar }}
            </p>
        </div>
        <div style="background:var(--g);border-radius:12px;padding:9px 16px;font-size:13px;font-weight:700;color:var(--gdd);">
            <i class="fas fa-users" style="margin-right:6px;"></i>{{ $pedagangs->count() }} Pedagang
        </div>
    </div>

    {{-- Search --}}
    <div style="background:white;border:1.5px solid var(--border);border-radius:14px;padding:14px 18px;margin-bottom:20px;display:flex;gap:12px;align-items:center;">
        <div style="position:relative;flex:1;">
            <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#a3c4aa;font-size:13px;"></i>
            <input type="text" id="searchPedagang" placeholder="Cari nama pedagang..." class="inp-field" style="padding-left:36px;width:100%;" oninput="filterPedagang()">
        </div>
    </div>

    {{-- Grid Kartu --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;" id="pedagangGrid">
        @forelse($pedagangs as $p)
        <div class="card-pedagang pedagang-item" data-nama="{{ strtolower($p->nama_pedagang) }}">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
                <div style="width:48px;height:48px;border-radius:14px;background:var(--g);color:var(--gdd);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;flex-shrink:0;">
                    {{ strtoupper(substr($p->nama_pedagang,0,1)) }}
                </div>
                <div>
                    <div style="font-weight:800;color:var(--text);font-size:15px;">{{ $p->nama_pedagang }}</div>
                    <div style="font-size:11px;color:var(--sub);">Pedagang Aktif</div>
                </div>
            </div>
            <div style="border-top:1px solid #e8f5ee;padding-top:14px;display:flex;justify-content:flex-end;">
                <a href="{{ route('admin_master.pedagang.show', $p->id) }}"
                    style="background:linear-gradient(135deg,var(--gdd),var(--gd));color:white;text-decoration:none;border-radius:10px;padding:9px 18px;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:6px;">
                    <i class="fas fa-eye"></i> Lihat Harga
                </a>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;padding:56px;text-align:center;background:white;border-radius:16px;border:1.5px dashed var(--border);">
            <i class="fas fa-users-slash" style="font-size:2.5rem;color:#c6ebd4;display:block;margin-bottom:12px;"></i>
            <p style="color:var(--sub);font-weight:600;">Belum ada pedagang terdaftar.</p>
        </div>
        @endforelse
    </div>
</div>
<script>
function filterPedagang() {
    const q = document.getElementById('searchPedagang').value.toLowerCase();
    document.querySelectorAll('.pedagang-item').forEach(r => {
        r.style.display = !q || r.dataset.nama.includes(q) ? '' : 'none';
    });
}
</script>
</div>
@endsection
