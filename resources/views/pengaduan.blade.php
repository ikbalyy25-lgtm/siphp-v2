@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen bg-cover bg-center" style="background-image: url('{{ asset('img/bgpengaduan.png') }}')">

    <div class="flex w-full min-h-screen">

        {{-- SIDE INFORMATION --}}
        <div class="hidden md:flex w-5/12 flex-col justify-center px-14 lg:px-20 py-16"
            style="background: rgba(13,27,26,0.85); backdrop-filter: blur(8px);">

            <div class="flex items-center gap-3 mb-6">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-10 h-10">
                <span class="font-extrabold text-lg" style="color: #d0f0c0;">SIPHP</span>
            </div>

            <h1 class="text-4xl lg:text-5xl font-extrabold mb-4 leading-tight" style="color: #d0f0c0;">
                Pengaduan
            </h1>
            <p class="text-gray-300 text-sm leading-relaxed mb-10">
                Laporkan hal terkait pasar di Kota Parepare! Suaramu wajib didengar demi pelayanan yang lebih baik.
            </p>

            <div class="space-y-5">
                <div class="flex items-start gap-4">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                        style="background: rgba(208,240,192,0.15);">
                        <i class="fas fa-phone text-sm" style="color: #d0f0c0;"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-0.5">Phone</p>
                        <p class="text-white text-sm font-medium">(0421) 21427</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                        style="background: rgba(208,240,192,0.15);">
                        <i class="fas fa-globe text-sm" style="color: #d0f0c0;"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-0.5">Website</p>
                        <p class="text-white text-sm font-medium">parepare.go.id</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                        style="background: rgba(208,240,192,0.15);">
                        <i class="fas fa-envelope text-sm" style="color: #d0f0c0;"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-0.5">Email</p>
                        <p class="text-white text-sm font-medium">dinas.perdagangan@parepare.go.id</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                        style="background: rgba(208,240,192,0.15);">
                        <i class="fas fa-map-marker-alt text-sm" style="color: #d0f0c0;"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-0.5">Alamat</p>
                        <p class="text-white text-sm font-medium">Jl. Bau Massepe No. 54, Kota Parepare, Sulawesi Selatan</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM --}}
        <div class="flex-1 flex items-center justify-center px-6 md:px-12 py-16">
            <div class="w-full max-w-lg rounded-2xl p-8 shadow-2xl"
                style="background: rgba(255,255,255,0.95); backdrop-filter: blur(12px);">

                <h2 class="text-xl font-extrabold mb-1" style="color: #1e3a2f;">Kirim Pengaduan</h2>
                <p class="text-gray-500 text-xs mb-6">Isi formulir di bawah dengan informasi yang benar.</p>

                @if(session('success'))
                    <div class="mb-4 px-4 py-3 rounded-xl text-sm font-medium" style="background: rgba(208,240,192,0.4); color: #1a5c35; border: 1px solid #d0f0c0;">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('pengaduan.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nama</label>
                        <input type="text" name="nama" placeholder="Masukkan nama lengkap..."
                            class="w-full px-4 py-3 rounded-xl text-sm border focus:outline-none focus:ring-2 transition"
                            style="border-color: #d0f0c0; focus: ring-color: #2d6a4f;"
                            value="{{ old('nama') }}">
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pasar + Nomor Telepon --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Pasar</label>
                            <select name="pasar"
                                class="w-full px-4 py-3 rounded-xl text-sm border focus:outline-none focus:ring-2 transition cursor-pointer"
                                style="border-color: #d0f0c0;">
                                <option value="" disabled selected>Pilih pasar</option>
                                <option value="Pasar Lakessi" {{ old('pasar') == 'Pasar Lakessi' ? 'selected' : '' }}>Pasar Lakessi</option>
                                <option value="Pasar Senggol" {{ old('pasar') == 'Pasar Senggol' ? 'selected' : '' }}>Pasar Senggol</option>
                                <option value="Pasar Labukkang" {{ old('pasar') == 'Pasar Labukkang' ? 'selected' : '' }}>Pasar Labukkang</option>
                                <option value="Pasar Sumpang Minangae" {{ old('pasar') == 'Pasar Sumpang Minangae' ? 'selected' : '' }}>Pasar Sumpang Minangae</option>
                                <option value="Pasar Wekkee" {{ old('pasar') == 'Pasar Wekkee' ? 'selected' : '' }}>Pasar Wekke'e</option>
                            </select>
                            @error('pasar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-3 rounded-xl text-sm border focus:outline-none focus:ring-2 transition"
                                style="border-color: #d0f0c0;"
                                value="{{ old('nomor_telepon') }}">
                            @error('nomor_telepon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Kategori Pengaduan</label>
                        <select name="kategori"
                            class="w-full px-4 py-3 rounded-xl text-sm border focus:outline-none focus:ring-2 transition cursor-pointer"
                            style="border-color: #d0f0c0;">
                            <option value="" disabled selected>Pilih jenis pengaduan</option>
                            <option value="pasar" {{ old('kategori') == 'pasar' ? 'selected' : '' }}>Pasar</option>
                            <option value="kebersihan" {{ old('kategori') == 'kebersihan' ? 'selected' : '' }}>Kebersihan</option>
                            <option value="los kosong" {{ old('kategori') == 'los kosong' ? 'selected' : '' }}>Los Kosong</option>
                            <option value="harga" {{ old('kategori') == 'harga' ? 'selected' : '' }}>Harga</option>
                            <option value="sistem" {{ old('kategori') == 'sistem' ? 'selected' : '' }}>Sistem</option>
                        </select>
                        @error('kategori')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pesan --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Pesan</label>
                        <textarea name="pesan" placeholder="Tuliskan pengaduan Anda secara jelas..."
                            class="w-full px-4 py-3 rounded-xl text-sm border focus:outline-none focus:ring-2 transition h-28 resize-none"
                            style="border-color: #d0f0c0;">{{ old('pesan') }}</textarea>
                        @error('pesan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full py-3 rounded-xl font-bold text-sm shadow-lg transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #1e3a2f, #2d6a4f); color: #d0f0c0;">
                        <i class="fas fa-paper-plane mr-2"></i> KIRIM PENGADUAN
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
