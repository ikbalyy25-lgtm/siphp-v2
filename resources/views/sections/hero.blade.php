<section class="relative w-full min-h-screen bg-cover bg-center font-sans"
    style="background-image: url('{{ asset('img/gambaratas.png') }}');">

    <div class="relative z-10 flex flex-col h-full bg-black/30 md:bg-transparent">

        {{-- NAVBAR --}}
        <nav class="flex justify-between items-center px-6 py-4 md:px-10 relative z-50">

            {{-- Logo sejajar dengan teks SISTEM di bawah --}}
            <div class="flex-shrink-0 ml-6 md:ml-20">
                <img src="{{ asset('img/logo.png') }}" alt="Logo"
                    class="w-30 h-11 drop-shadow-xl">
            </div>

            {{-- Menu + Tombol Masuk kanan --}}
            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center rounded-full shadow-xl gap-1 px-2 py-2"
                    style="background: rgba(13,27,26,0.55); border: 1px solid rgba(208,240,192,0.25); backdrop-filter: blur(12px);">
                    <a href="{{ url('/') }}"
                        class="px-4 py-1.5 rounded-full text-sm font-bold transition"
                        style="background: rgba(208,240,192,0.2); color: #d0f0c0;">
                        Beranda
                    </a>
                    <a href="#fitur-unggulan"
                        class="px-4 py-1.5 rounded-full text-sm font-semibold transition hover:bg-white/10"
                        style="color: rgba(208,240,192,0.8);">
                        SIPHP
                    </a>
                    <a href="{{ route('pengaduan') }}"
                        class="px-4 py-1.5 rounded-full text-sm font-semibold transition hover:bg-white/10"
                        style="color: rgba(208,240,192,0.8);">
                        Pengaduan
                    </a>
                </div>

                <a href="{{ route('login') }}"
                    class="flex items-center gap-2 bg-white/20 hover:bg-white/30 border-2 border-white text-white font-bold px-6 py-2.5 rounded-full shadow-lg text-sm backdrop-blur-sm transition duration-300 hover:scale-105">
                    <i class="fas fa-user text-xs"></i>
                    <span class="tracking-wide">MASUK</span>
                </a>
            </div>
        </nav>

        {{-- HERO CONTENT --}}
        <div class="flex-1 flex items-center px-6 pt-10 md:pt-0 md:px-20 pb-20 md:pb-0">
            <div class="max-w-xl text-white text-center md:text-left mx-auto md:mx-0 md:ml-4">
                <h1 class="text-4xl md:text-6xl font-extrabold leading-tight uppercase mb-4 drop-shadow-lg">
                    Sistem Informasi<br>
                    Penyedia Harga<br>
                    Pasar
                </h1>
                <p class="text-sm md:text-base leading-relaxed mb-8 opacity-95">
                    Mewujudkan digitalisasi pelayanan publik dan mendukung
                    prinsip transparansi data di bidang perdagangan melalui
                    penyediaan informasi harga komoditas pasar yang aktual
                    dan mudah diakses.
                </p>
                <a href="#informasi-ritel"
                    class="inline-block bg-white text-black px-6 py-3 rounded-full font-semibold hover:bg-gray-200 transition shadow-lg">
                    Lihat Ritel
                </a>
            </div>
            <div class="hidden lg:block w-1/3"></div>
        </div>
    </div>
</section>
