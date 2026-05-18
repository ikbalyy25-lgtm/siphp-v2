{{-- uses Storage facade --}}
<section id="informasi-ritel" class="relative w-full py-16 md:py-20 font-sans overflow-hidden"
    style="background: linear-gradient(135deg, #0d1b2a 0%, #1a3a2a 60%, #0d2b1a 100%);">

    <div class="absolute inset-0 opacity-10"
        style="background-image: radial-gradient(circle at 20% 50%, #d0f0c0 0%, transparent 50%), radial-gradient(circle at 80% 20%, #4ade80 0%, transparent 40%);"></div>

    <div class="relative z-10 container mx-auto px-4">

        {{-- Header --}}
        <div class="text-center mb-10">
            <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-3"
                style="background: rgba(208,240,192,0.15); color: #d0f0c0; border: 1px solid rgba(208,240,192,0.3);">
                Direktori Toko
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold uppercase" style="color: #d0f0c0;">
                Informasi Ritel
            </h2>
            <p class="text-gray-400 text-sm mt-2">Temukan toko retail modern di Kota Parepare</p>
        </div>

        @if($retails->count() > 0)
        {{-- Slider Container --}}
        <div class="relative" id="retailSlider">
            <div class="overflow-hidden" id="retailTrack">
                <div class="flex gap-5 transition-transform duration-500 ease-in-out" id="retailCards" style="transform: translateX(0);">
                    @foreach($retails as $i => $retail)
                    <div class="flex-shrink-0 w-72 md:w-80 rounded-2xl overflow-hidden shadow-2xl group"
                        style="background: linear-gradient(160deg, #1e3a2f 0%, #0d2b1a 100%); border: 1px solid rgba(208,240,192,0.15);">

                        {{-- Gambar --}}
                        <div style="width:100%; height:176px; overflow:hidden; position:relative; background:rgba(208,240,192,0.08); flex-shrink:0;">
                            @if($retail->gambar)
                                <img src="{{ url('storage/' . $retail->gambar) }}"
                                    style="width:100%; height:100%; object-fit:cover; display:block; transition: transform 0.5s;"
                                    onmouseover="this.style.transform='scale(1.08)'"
                                    onmouseout="this.style.transform='scale(1)'"
                                    onerror="this.style.display='none'; document.getElementById('noimg_{{ $retail->id }}').style.display='flex';">
                                <div id="noimg_{{ $retail->id }}" style="display:none; width:100%; height:100%; align-items:center; justify-content:center;">
                                    <i class="fas fa-store" style="font-size:2rem; color:rgba(208,240,192,0.3);"></i>
                                </div>
                            @else
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-store" style="font-size:2rem; color:rgba(208,240,192,0.3);"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(13,27,26,0.8) 0%, transparent 60%);"></div>
                            <div class="absolute bottom-3 left-4 right-4">
                                <h3 class="font-extrabold text-base uppercase truncate" style="color: #d0f0c0;">
                                    {{ $retail->nama_toko }}
                                </h3>
                            </div>
                            <div class="absolute top-3 right-3">
                                <span class="text-xs font-bold px-2 py-1 rounded-full"
                                    style="background: rgba(208,240,192,0.2); color: #d0f0c0; backdrop-filter: blur(4px);">
                                    {{ $retail->kategori }}
                                </span>
                            </div>
                        </div>

                        {{-- Konten --}}
                        <div class="p-4 space-y-2.5">
                            <div class="flex items-start gap-2.5 text-sm">
                                <i class="fas fa-map-marker-alt mt-0.5 flex-shrink-0" style="color: #d0f0c0;"></i>
                                <span class="text-gray-300 leading-snug text-xs">{{ \Illuminate\Support\Str::limit($retail->alamat, 45) }}</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-sm">
                                <i class="fas fa-phone flex-shrink-0" style="color: #d0f0c0;"></i>
                                <span class="text-gray-300 text-xs">{{ $retail->kontak }}</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-sm">
                                <i class="fas fa-clock flex-shrink-0" style="color: #d0f0c0;"></i>
                                <span class="text-gray-300 text-xs">{{ $retail->jam_buka }}</span>
                            </div>
                        </div>

                        <div class="px-4 pb-4">
                            <a href="{{ $retail->link_maps }}" target="_blank"
                                class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-bold transition duration-300 hover:opacity-90"
                                style="background: linear-gradient(135deg, #d0f0c0, #4ade80); color: #0d2b1a;">
                                <i class="fas fa-directions text-xs"></i> Kunjungi
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Navigasi --}}
            @if($retails->count() > 4)
            <button id="prevBtn" onclick="slideRetail(-1)"
                class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-10 h-10 rounded-full flex items-center justify-center shadow-lg z-10 transition"
                style="background: #d0f0c0; color: #0d2b1a;">
                <i class="fas fa-chevron-left text-sm"></i>
            </button>
            <button id="nextBtn" onclick="slideRetail(1)"
                class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-10 h-10 rounded-full flex items-center justify-center shadow-lg z-10 transition"
                style="background: #d0f0c0; color: #0d2b1a;">
                <i class="fas fa-chevron-right text-sm"></i>
            </button>
            @endif
        </div>

        {{-- Dots indikator --}}
        @if($retails->count() > 4)
        <div class="flex justify-center gap-2 mt-6" id="retailDots"></div>
        @endif

        @else
        <div class="text-center py-16">
            <i class="fas fa-store text-5xl mb-4" style="color: rgba(208,240,192,0.3);"></i>
            <p class="text-gray-400">Belum ada informasi ritel saat ini.</p>
        </div>
        @endif
    </div>
</section>

<script>
(function() {
    const cards = document.getElementById('retailCards');
    if (!cards) return;
    const total = {{ $retails->count() }};
    const perView = window.innerWidth >= 768 ? 4 : 1;
    const cardW = (window.innerWidth >= 768 ? 320 : 288) + 20;
    let current = 0;
    const maxSlide = Math.max(0, total - perView);

    function updateDots() {
        const dots = document.getElementById('retailDots');
        if (!dots) return;
        const pages = Math.ceil(total / perView);
        dots.innerHTML = '';
        for (let i = 0; i < pages; i++) {
            const d = document.createElement('button');
            d.className = 'w-2 h-2 rounded-full transition-all duration-300';
            d.style.background = i === Math.floor(current / perView) ? '#d0f0c0' : 'rgba(208,240,192,0.3)';
            d.style.width = i === Math.floor(current / perView) ? '24px' : '8px';
            d.onclick = () => { current = i * perView; slide(); };
            dots.appendChild(d);
        }
    }

    function slide() {
        current = Math.max(0, Math.min(current, maxSlide));
        cards.style.transform = `translateX(-${current * cardW}px)`;
        updateDots();
    }

    window.slideRetail = function(dir) {
        current += dir * perView;
        slide();
    };

    updateDots();
})();
</script>
