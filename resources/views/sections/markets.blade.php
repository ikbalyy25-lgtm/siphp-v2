<footer class="w-full font-sans" style="background:#0d1b2a;">
    <div class="w-full h-0.5" style="background:linear-gradient(90deg,#d0f0c0,#4ade80,#22c55e);"></div>

    <div class="max-w-5xl mx-auto px-6 py-6">

        {{-- Baris utama --}}
        <div class="flex flex-wrap gap-y-6 gap-x-10 justify-between">

            {{-- Brand --}}
            <div style="min-width:180px; max-width:220px;">
                <div class="flex items-center gap-2 mb-2">
                    <img src="{{ asset('img/logo.png') }}" class="w-7 h-7 flex-shrink-0">
                    <span class="font-extrabold text-sm tracking-wide" style="color:#d0f0c0;">SIPHP</span>
                </div>
                <p class="text-xs leading-relaxed" style="color:#6b7280;">
                    Sistem Informasi Penyedia Harga Pasar Kota Parepare.
                </p>
            </div>

            {{-- Navigasi --}}
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:#d0f0c0;">Navigasi</p>
                <ul class="space-y-1 text-xs" style="color:#6b7280;">
                    <li><a href="{{ url('/') }}"           class="hover:text-white transition">Beranda</a></li>
                    <li><a href="#informasi-ritel"          class="hover:text-white transition">Informasi Ritel</a></li>
                    <li><a href="{{ route('pengaduan') }}"  class="hover:text-white transition">Pengaduan</a></li>
                    <li><a href="{{ route('login') }}"      class="hover:text-white transition">Masuk</a></li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:#d0f0c0;">Kontak</p>
                <ul class="space-y-1 text-xs" style="color:#6b7280;">
                    <li>(0421) 21427</li>
                    <li>dinas.perdagangan@parepare.go.id</li>
                    <li>Jl. Bau Massepe No.54, Parepare</li>
                </ul>
            </div>

            {{-- Sosmed --}}
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:#d0f0c0;">Ikuti Kami</p>
                <div class="flex gap-2">
                    <a href="#" title="Facebook"
                        class="hover:opacity-80 transition"
                        style="width:28px;height:28px;border-radius:7px;background:#1877f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-brands fa-facebook" style="color:white;font-size:12px;"></i>
                    </a>
                    <a href="#" title="X / Twitter"
                        class="hover:opacity-80 transition"
                        style="width:28px;height:28px;border-radius:7px;background:#000000;border:1.5px solid #ffffff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <span style="color:white;font-size:13px;font-weight:900;line-height:1;font-family:sans-serif;">𝕏</span>
                    </a>
                    <a href="#" title="Instagram"
                        class="hover:opacity-80 transition"
                        style="width:28px;height:28px;border-radius:7px;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-brands fa-instagram" style="color:white;font-size:12px;"></i>
                    </a>
                    <a href="#" title="YouTube"
                        class="hover:opacity-80 transition"
                        style="width:28px;height:28px;border-radius:7px;background:#ff0000;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-brands fa-youtube" style="color:white;font-size:12px;"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-1 mt-5 pt-4 text-xs"
            style="border-top:1px solid rgba(208,240,192,0.1); color:#4b5563; margin-top:28px; padding-top:18px;">
            <span>&copy; 2026 <span style="color:#d0f0c0;" class="font-semibold">SIPHP</span> — MAROA TEAM. Hak cipta dilindungi.</span>
            <span class="flex items-center gap-1.5 text-green-400">
                <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                Sistem Aktif
            </span>
        </div>
    </div>

    <div class="w-full h-0.5" style="background:linear-gradient(90deg,#22c55e,#d0f0c0,#4ade80);"></div>
</footer>
