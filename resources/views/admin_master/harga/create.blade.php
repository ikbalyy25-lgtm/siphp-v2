@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
:root { --g:#d0f0c0; --gd:#2d6a4f; --gdd:#1e3a2f; --border:#d1e8d8; --text:#1a3a2a; --sub:#5a8a6a; }
.form-inp {
    width: 100%; padding: 12px 14px; border-radius: 12px; font-size: 14px;
    border: 1.5px solid var(--border); background: #f8fdf9; color: var(--text);
    outline: none; transition: border-color 0.2s, box-shadow 0.2s;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.form-inp:focus { border-color: var(--gd); box-shadow: 0 0 0 3px rgba(45,106,79,0.1); background: white; }
.form-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--sub); display: block; margin-bottom: 7px; }
</style>

<div style="min-height:100vh; background:#f0faf4; padding:32px; font-family:'Plus Jakarta Sans',sans-serif;">
<div style="max-width:640px; margin:0 auto;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:32px;">
        <a href="{{ route('admin_master.harga.index', $kategori) }}"
            style="background:#d0f0c0; color:#1e3a2f; width:36px; height:36px; border-radius:10px;
                   display:inline-flex; align-items:center; justify-content:center;
                   text-decoration:none; font-size:14px; flex-shrink:0;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 style="font-size:22px; font-weight:800; color:var(--text); margin:0;">
                Input Komoditas <span style="color:var(--gd);">{{ ucfirst($kategori) }}</span>
            </h1>
            <p style="font-size:13px; color:var(--sub); margin:3px 0 0;">
                <i class="fas fa-map-marker-alt" style="color:#ef4444; margin-right:5px;"></i>
                {{ $pasar->nama_pasar }}
            </p>
        </div>
    </div>

    {{-- Info Card Kategori --}}
    <div style="background:linear-gradient(135deg,var(--gdd),var(--gd)); border-radius:16px; padding:18px 22px; margin-bottom:24px; display:flex; align-items:center; gap:14px;">
        <div style="width:44px; height:44px; background:rgba(255,255,255,0.15); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;">
            @if($kategori=='pokok') 🛒
            @elseif($kategori=='subsidi') 🏷️
            @else 🏗️
            @endif
        </div>
        <div>
            <div style="font-weight:800; color:white; font-size:15px;">Barang {{ ucfirst($kategori) }}</div>
            <div style="font-size:12px; color:rgba(255,255,255,0.7); margin-top:2px;">
                @if($kategori=='pokok') Beras, gula, minyak, telur, daging, dll.
                @elseif($kategori=='subsidi') LPG 3kg, minyak curah, beras BULOG, dll.
                @else Semen, besi, BBM, pupuk, material bangunan, dll.
                @endif
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div style="background:white; border:1.5px solid var(--border); border-radius:20px; padding:28px; box-shadow:0 4px 20px rgba(45,106,79,0.07);">

        @if($errors->any())
        <div style="background:#fef2f2; border:1px solid #fecaca; border-left:4px solid #ef4444; border-radius:12px; padding:12px 16px; margin-bottom:20px;">
            <div style="font-weight:700; color:#dc2626; font-size:13px; margin-bottom:5px;">Terjadi Kesalahan:</div>
            @foreach($errors->all() as $e)
            <p style="font-size:12px; color:#dc2626; margin:2px 0;">• {{ $e }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('admin_master.harga.store') }}" method="POST">
            @csrf
            <input type="hidden" name="kategori" value="{{ $kategori }}">

            {{-- Nama Barang --}}
            <div style="margin-bottom:20px;">
                <label class="form-label">Nama Barang <span style="color:#ef4444;">*</span></label>
                <div style="position:relative;">
                    <i class="fas fa-box" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#a3c4aa; font-size:13px; pointer-events:none;"></i>
                    <input type="text" name="nama_barang" required autofocus
                        placeholder="Contoh: Beras Medium, Cabai Merah..."
                        value="{{ old('nama_barang') }}"
                        class="form-inp" style="padding-left:40px;"
                        list="suggestBarang">
                    <datalist id="suggestBarang">
                        @if($kategori=='pokok')
                            <option value="Beras Medium"><option value="Beras Premium"><option value="Gula Pasir">
                            <option value="Minyak Goreng Kemasan"><option value="Telur Ayam Ras"><option value="Daging Ayam Ras">
                            <option value="Daging Sapi"><option value="Tepung Terigu"><option value="Ikan Bandeng">
                        @elseif($kategori=='subsidi')
                            <option value="Minyak Goreng Curah"><option value="Beras BULOG"><option value="Gula BUMN">
                            <option value="LPG 3 Kg"><option value="Tepung Terigu Subsidi"><option value="Garam Beryodium">
                        @else
                            <option value="Semen Portland 50kg"><option value="Besi Beton 10mm"><option value="Besi Beton 8mm">
                            <option value="Bensin Pertalite (L)"><option value="Solar (L)"><option value="LPG 12 Kg">
                            <option value="Pupuk Urea 50kg"><option value="Cat Tembok 5kg"><option value="Batu Bata Merah">
                        @endif
                    </datalist>
                </div>
            </div>

            {{-- Tanggal --}}
            <div style="margin-bottom:20px;">
                <label class="form-label">Tanggal Input <span style="color:#ef4444;">*</span></label>
                <div style="position:relative;">
                    <i class="fas fa-calendar" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#a3c4aa; font-size:13px; pointer-events:none;"></i>
                    <input type="date" name="tanggal" required
                        value="{{ date('Y-m-d') }}"
                        class="form-inp" style="padding-left:40px;">
                </div>
            </div>

            {{-- Harga --}}
            <div style="margin-bottom:20px;">
                <label class="form-label">Harga Hari Ini (Rp) <span style="color:#ef4444;">*</span></label>
                <div style="position:relative;">
                    <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#a3c4aa; font-size:12px; font-weight:600; pointer-events:none;">Rp</span>
                    <input type="number" name="harga_hari_ini" required min="0"
                        placeholder="0"
                        value="{{ old('harga_hari_ini') }}"
                        class="form-inp" style="padding-left:36px;"
                        id="hargaHariIni">
                </div>
                <p style="font-size:11px;color:var(--sub);margin-top:5px;">
                    <i class="fas fa-info-circle"></i> Untuk input dari 3 pedagang sekaligus, gunakan panel Admin Pasar.
                </p>
            </div>

            {{-- Tombol --}}
            <div style="display:flex; gap:12px; justify-content:flex-end;">
                <a href="{{ route('admin_master.harga.index', $kategori) }}"
                    style="background:#f0f0f0; color:#666; text-decoration:none; border-radius:12px;
                           padding:12px 22px; font-weight:700; font-size:13px; display:inline-flex; align-items:center; gap:6px;">
                    Batal
                </a>
                <button type="submit"
                    style="background:linear-gradient(135deg,var(--gdd),var(--gd)); color:white; border:none;
                           border-radius:12px; padding:12px 28px; font-weight:700; font-size:13px; cursor:pointer;
                           display:inline-flex; align-items:center; gap:8px; font-family:'Plus Jakarta Sans',sans-serif;
                           box-shadow:0 4px 14px rgba(45,106,79,0.25);"
                    onmouseover="this.style.opacity=0.9" onmouseout="this.style.opacity=1">
                    <i class="fas fa-save"></i> Simpan Data Harga
                </button>
            </div>
        </form>
    </div>
</div>
</div>

<script>
function updateSelisih() {
    const k = parseFloat(document.getElementById('hargaKemarin').value) || 0;
    const h = parseFloat(document.getElementById('hargaHariIni').value) || 0;
    const box  = document.getElementById('selisihBox');
    const icon = document.getElementById('selisihIcon');
    const text = document.getElementById('selisihText');

    if (k > 0 && h > 0) {
        box.style.display = 'flex';
        const diff = h - k;
        const pct  = ((diff / k) * 100).toFixed(1);
        const fmt  = n => 'Rp ' + Math.abs(n).toLocaleString('id-ID');

        if (diff > 0) {
            box.style.background = '#fef2f2'; box.style.borderColor = '#fecaca';
            icon.className = 'fas fa-arrow-up'; icon.style.color = '#dc2626';
            text.style.color = '#dc2626';
            text.textContent = `Naik ${fmt(diff)} (+${pct}%) dari kemarin`;
        } else if (diff < 0) {
            box.style.background = '#f0fdf4'; box.style.borderColor = '#bbf7d0';
            icon.className = 'fas fa-arrow-down'; icon.style.color = '#16a34a';
            text.style.color = '#16a34a';
            text.textContent = `Turun ${fmt(diff)} (-${Math.abs(pct)}%) dari kemarin`;
        } else {
            box.style.background = '#f8fafc'; box.style.borderColor = '#e2e8f0';
            icon.className = 'fas fa-minus'; icon.style.color = '#64748b';
            text.style.color = '#64748b';
            text.textContent = 'Harga tidak berubah dari kemarin';
        }
    } else {
        box.style.display = 'none';
    }
}
</script>
@endsection
