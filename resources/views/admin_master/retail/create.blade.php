@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
:root { --g:#d0f0c0; --gd:#2d6a4f; --gdd:#1e3a2f; --border:#d1e8d8; --text:#1a3a2a; --sub:#5a8a6a; }
.form-inp {
    width: 100%; padding: 11px 14px; border-radius: 11px; font-size: 14px;
    border: 1.5px solid var(--border); background: #f8fdf9; color: var(--text);
    outline: none; transition: border-color 0.2s, box-shadow 0.2s;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.form-inp:focus { border-color: var(--gd); box-shadow: 0 0 0 3px rgba(45,106,79,0.1); }
.form-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--sub); display: block; margin-bottom: 7px; }
.err-msg { font-size: 11px; color: #dc2626; margin-top: 5px; }
</style>

<div style="min-height:100vh; background:#f0faf4; padding:32px; font-family:'Plus Jakarta Sans',sans-serif;">
<div style="max-width:760px; margin:0 auto;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:28px;">
        <a href="{{ route('admin_master.retail.index') }}"
            style="background:#d0f0c0; color:#1e3a2f; width:36px; height:36px; border-radius:10px;
                   display:inline-flex; align-items:center; justify-content:center;
                   text-decoration:none; font-size:14px; flex-shrink:0;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 style="font-size:22px; font-weight:800; color:var(--text); margin:0;">Tambah Ritel Baru</h1>
            <p style="font-size:13px; color:var(--sub); margin:3px 0 0;">Isi formulir untuk menambahkan toko ke portal publik</p>
        </div>
    </div>

    {{-- Error --}}
    @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; border-left:4px solid #ef4444; border-radius:12px; padding:14px 16px; margin-bottom:20px;">
        <div style="font-weight:700; color:#dc2626; font-size:13px; margin-bottom:6px;">Terjadi Kesalahan:</div>
        <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $error)
            <li style="font-size:12px; color:#dc2626;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form Card --}}
    <div style="background:white; border:1.5px solid var(--border); border-radius:20px; padding:32px;
                box-shadow:0 4px 24px rgba(45,106,79,0.08);">

        <form action="{{ route('admin_master.retail.store') }}" method="POST" enctype="multipart/form-data" id="retailForm">
            @csrf

            {{-- Baris 1: Nama Toko + Kategori --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:18px;">
                <div>
                    <label class="form-label">Nama Toko <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_toko" value="{{ old('nama_toko') }}"
                        placeholder="Contoh: Toko Ikbal" required class="form-inp
                        @error('nama_toko') border-red-400 @enderror">
                    @error('nama_toko') <p class="err-msg">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Kategori / Deskripsi <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="kategori" value="{{ old('kategori') }}"
                        placeholder="Contoh: Barang Campuran" required class="form-inp">
                    @error('kategori') <p class="err-msg">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Baris 2: Kontak + Jam Operasional --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:18px;">
                <div>
                    <label class="form-label">No. HP / Kontak <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="kontak" value="{{ old('kontak') }}"
                        placeholder="0812..." required class="form-inp">
                    @error('kontak') <p class="err-msg">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Jam Operasional <span style="color:#ef4444;">*</span></label>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <input type="time" name="jam_buka_mulai" value="{{ old('jam_buka_mulai') }}"
                            required class="form-inp" id="jam_mulai" onchange="updateJam()">
                        <span style="color:var(--sub); font-weight:600;">—</span>
                        <input type="time" name="jam_buka_selesai" value="{{ old('jam_buka_selesai') }}"
                            required class="form-inp" id="jam_selesai" onchange="updateJam()">
                    </div>
                    <input type="hidden" name="jam_buka" id="jam_buka_hidden" value="{{ old('jam_buka') }}">
                    <p style="font-size:11px; color:var(--sub); margin-top:5px;">
                        Format otomatis: <span id="previewJam" style="color:var(--gd); font-weight:700;">07.30 - 22.00</span>
                    </p>
                    @error('jam_buka') <p class="err-msg">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Alamat --}}
            <div style="margin-bottom:18px;">
                <label class="form-label">Alamat Lengkap <span style="color:#ef4444;">*</span></label>
                <textarea name="alamat" rows="3" placeholder="Jl. Mawar No. 21, Kelurahan..."
                    required class="form-inp" style="resize:vertical;">{{ old('alamat') }}</textarea>
                @error('alamat') <p class="err-msg">{{ $message }}</p> @enderror
            </div>

            {{-- Link Maps --}}
            <div style="margin-bottom:18px;">
                <label class="form-label">Link Google Maps <span style="color:#ef4444;">*</span></label>
                <div style="position:relative;">
                    <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#a3c4aa; font-size:14px;">
                        <i class="fas fa-map-location-dot"></i>
                    </span>
                    <input type="url" name="link_maps" value="{{ old('link_maps') }}"
                        placeholder="https://goo.gl/maps/..." required
                        class="form-inp" style="padding-left:40px;">
                </div>
                @error('link_maps') <p class="err-msg">{{ $message }}</p> @enderror
            </div>

            {{-- Upload Foto --}}
            <div style="margin-bottom:28px;">
                <label class="form-label">Foto Toko <span style="color:#ef4444;">*</span></label>
                <div id="uploadArea"
                    onclick="document.getElementById('fotoInput').click()"
                    style="border:2px dashed var(--border); border-radius:14px; padding:32px 20px;
                           text-align:center; cursor:pointer; background:#f8fdf9;
                           transition:border-color 0.2s, background 0.2s;"
                    onmouseover="this.style.borderColor='var(--gd)';this.style.background='#f0faf4';"
                    onmouseout="this.style.borderColor='var(--border)';this.style.background='#f8fdf9';">
                    <div id="uploadPlaceholder">
                        <i class="fas fa-cloud-arrow-up" style="font-size:2.5rem; color:#a3c4aa; display:block; margin-bottom:10px;"></i>
                        <p style="font-size:14px; font-weight:600; color:var(--sub);">Klik untuk upload foto toko</p>
                        <p style="font-size:11px; color:#a3c4aa; margin-top:4px;">Format: JPG, PNG, JPEG (Max: 2MB)</p>
                    </div>
                    <div id="uploadPreview" style="display:none; flex-direction:column; align-items:center; gap:10px;">
                        <div style="width:8px; height:8px; border-radius:50%; background:#22c55e; display:inline-block;"></div>
                        <span id="uploadFileName" style="font-size:13px; font-weight:700; color:var(--gd);"></span>
                        <span style="font-size:11px; color:#22c55e;">Foto berhasil dipilih!</span>
                    </div>
                </div>
                <input type="file" id="fotoInput" name="gambar" accept="image/*"
                    style="display:none;" onchange="previewFoto(this)" required>
                @error('gambar') <p class="err-msg">{{ $message }}</p> @enderror
            </div>

            {{-- Tombol --}}
            <div style="display:flex; justify-content:flex-end; gap:12px;">
                <a href="{{ route('admin_master.retail.index') }}"
                    style="background:#f0f0f0; color:#666; text-decoration:none; border-radius:11px;
                           padding:12px 24px; font-weight:700; font-size:13px;">
                    Batal
                </a>
                <button type="submit"
                    style="background:linear-gradient(135deg,var(--gdd),var(--gd)); color:white;
                           border:none; border-radius:11px; padding:12px 28px; font-weight:700;
                           font-size:13px; cursor:pointer; display:inline-flex; align-items:center;
                           gap:8px; font-family:'Plus Jakarta Sans',sans-serif;
                           box-shadow:0 4px 14px rgba(45,106,79,0.25);
                           transition:opacity 0.2s, transform 0.15s;"
                    onmouseover="this.style.opacity=0.9;this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.opacity=1;this.style.transform='translateY(0)'">
                    <i class="fas fa-store-alt"></i> Simpan Ritel
                </button>
            </div>
        </form>
    </div>
</div>
</div>

<script>
function updateJam() {
    const m = document.getElementById('jam_mulai').value;
    const s = document.getElementById('jam_selesai').value;
    if (m && s) {
        const fmt = t => t.replace(':', '.');
        const val = `${fmt(m)} - ${fmt(s)}`;
        document.getElementById('jam_buka_hidden').value = val;
        document.getElementById('previewJam').textContent = val;
    }
}

function previewFoto(input) {
    if (input.files && input.files[0]) {
        const fname = input.files[0].name;
        document.getElementById('uploadPlaceholder').style.display = 'none';
        document.getElementById('uploadPreview').style.display = 'flex';
        document.getElementById('uploadFileName').textContent = fname;
        document.getElementById('uploadArea').style.borderColor = '#22c55e';
        document.getElementById('uploadArea').style.background = '#f0fdf4';
    }
}
</script>
@endsection
