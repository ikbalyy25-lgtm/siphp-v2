<section id="fitur-unggulan" class="relative w-full min-h-screen bg-cover bg-center"
    style="background-image: url('{{ asset('img/backgroud2.png') }}');">

    <div class="absolute inset-0 bg-black/10"></div>

    <div class="relative z-10 container mx-auto px-6 lg:px-20 pt-16 md:pt-24 pb-10">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div class="flex justify-center order-2 lg:order-1">
                <img src="{{ asset('img/3gambarkartu.png') }}"
                    class="w-full max-w-sm md:max-w-[600px] rounded-xl shadow-xl">
            </div>

            <div class="text-white font-poppins text-center lg:text-left order-1 lg:order-2">
                <h1 class="text-xl md:text-2xl lg:text-3xl font-extrabold leading-snug drop-shadow-lg">
                    DENGAN MENYEDIAKAN <br>
                    INFORMASI UNTUK 5 PASAR <br>
                    DI KOTA PAREPARE
                </h1>
            </div>
        </div>

        <h2 class="text-center text-2xl md:text-3xl font-bold mt-16 md:mt-20 mb-8 drop-shadow-lg" style="color:#d0f0c0;">
            Harga Barang Komoditas
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 mt-4">

            <div onclick="openMarketModal('pokok')" onmouseenter="showHoverPopup(event,'pokok')" onmouseleave="hideHoverPopup()"
                class="group block relative h-40 rounded-3xl overflow-hidden shadow-lg bg-cover bg-center cursor-pointer transform hover:scale-105 transition duration-300"
                style="background-image: url('{{ asset('img/barangpokok.png') }}');">
                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/50 transition duration-300"></div>
                <div class="relative p-5 text-white flex flex-col justify-end h-full">
                    <p class="text-sm opacity-80 mb-1">01</p>
                    <h3 class="text-xl font-semibold">Barang Pokok</h3>
                    <p class="text-sm opacity-80 group-hover:opacity-100">Klik untuk pilih pasar</p>
                </div>
            </div>

            <div onclick="openMarketModal('subsidi')" onmouseenter="showHoverPopup(event,'subsidi')" onmouseleave="hideHoverPopup()"
                class="group block relative h-40 rounded-3xl overflow-hidden shadow-lg bg-cover bg-center cursor-pointer transform hover:scale-105 transition duration-300"
                style="background-image: url('{{ asset('img/barangsubsidi.png') }}');">
                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/50 transition duration-300"></div>
                <div class="relative p-5 text-white flex flex-col justify-end h-full">
                    <p class="text-sm opacity-80 mb-1">02</p>
                    <h3 class="text-xl font-semibold">Barang Subsidi</h3>
                    <p class="text-sm opacity-80 group-hover:opacity-100">Klik untuk pilih pasar</p>
                </div>
            </div>

            <div onclick="openMarketModal('penting')" onmouseenter="showHoverPopup(event,'penting')" onmouseleave="hideHoverPopup()"
                class="group block relative h-40 rounded-3xl overflow-hidden shadow-lg bg-cover bg-center cursor-pointer transform hover:scale-105 transition duration-300"
                style="background-image: url('{{ asset('img/barangpenting.png') }}');">
                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/50 transition duration-300"></div>
                <div class="relative p-5 text-white flex flex-col justify-end h-full">
                    <p class="text-sm opacity-80 mb-1">03</p>
                    <h3 class="text-xl font-semibold">Barang Penting</h3>
                    <p class="text-sm opacity-80 group-hover:opacity-100">Klik untuk pilih pasar</p>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL PILIH PASAR - Eye Catching --}}
    <div id="marketModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="fixed inset-0 transition-opacity" onclick="closeMarketModal()"
            style="background: rgba(13,27,26,0.85); backdrop-filter: blur(6px);"></div>

        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="relative w-full max-w-md transform transition-all"
                style="background: linear-gradient(160deg, #1e3a2f 0%, #0d2b1a 100%);
                       border-radius: 24px;
                       border: 1px solid rgba(208,240,192,0.25);
                       box-shadow: 0 32px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(208,240,192,0.1);">

                {{-- Header Modal --}}
                <div class="px-6 pt-6 pb-4" style="border-bottom: 1px solid rgba(208,240,192,0.1);">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                style="background: rgba(208,240,192,0.15);">
                                <i class="fas fa-store text-sm" style="color: #d0f0c0;"></i>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-lg" style="color: #d0f0c0;">Pilih Pasar</h3>
                                <p class="text-xs text-gray-400">Kategori: <span id="selectedCategoryName"
                                    class="font-semibold capitalize" style="color: #4ade80;"></span></p>
                            </div>
                        </div>
                        <button onclick="closeMarketModal()"
                            class="w-8 h-8 rounded-lg flex items-center justify-center transition hover:opacity-80"
                            style="background: rgba(208,240,192,0.1); color: #d0f0c0;">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Daftar Pasar --}}
                <div class="px-5 py-4 grid grid-cols-1 gap-2.5">
                    @php
                    $daftarPasar = [
                        1 => ['nama' => 'Pasar Lakessi',          'icon' => 'fa-store',       'desc' => 'Pasar terbesar di Parepare'],
                        2 => ['nama' => 'Pasar Senggol',          'icon' => 'fa-shopping-bag', 'desc' => 'Pusat kuliner & kebutuhan harian'],
                        3 => ['nama' => 'Pasar Labukkang',        'icon' => 'fa-building',    'desc' => 'Kawasan perdagangan Labukkang'],
                        4 => ['nama' => 'Pasar Sumpang Minangae', 'icon' => 'fa-map-pin',     'desc' => 'Pasar wilayah Sumpang Minangae'],
                        5 => ['nama' => 'Pasar Wekkee', 'icon' => 'fa-landmark', 'desc' => 'Pasar tradisional Wekkee'],
                    ];
                    @endphp

                    @foreach($daftarPasar as $id => $pasar)
                    <button onclick="goToPricePage({{ $id }})"
                        class="pasar-btn-new w-full text-left px-4 py-3.5 rounded-xl flex items-center gap-4 transition duration-200 group"
                        style="background: rgba(208,240,192,0.05); border: 1px solid rgba(208,240,192,0.12);">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 transition duration-200"
                            style="background: rgba(208,240,192,0.1);" id="pasarIcon{{ $id }}">
                            <i class="fas {{ $pasar['icon'] }} text-sm" style="color: #d0f0c0;"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-sm text-white truncate">{{ $pasar['nama'] }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $pasar['desc'] }}</p>
                        </div>
                        <i class="fas fa-arrow-right text-xs text-gray-600 group-hover:text-green-400 transition"></i>
                    </button>
                    @endforeach
                </div>

                {{-- Footer Modal --}}
                <div class="px-5 pb-5">
                    <button onclick="closeMarketModal()"
                        class="w-full py-2.5 rounded-xl text-sm font-semibold transition"
                        style="background: rgba(208,240,192,0.08); color: #9ca3af; border: 1px solid rgba(208,240,192,0.1);">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Popup Hover Harga Terkini --}}
<div id="hoverPopup" class="pointer-events-none hidden"
    style="position: fixed; z-index: 99999; transition: opacity 0.15s;
           background: white; border-radius: 16px;
           box-shadow: 0 12px 40px rgba(0,0,0,0.25);
           min-width: 240px; padding: 16px;
           border-top: 4px solid #d0f0c0;
           transform: translateY(-8px);">
    <div class="flex items-center gap-2 mb-3">
        <span style="background:#d0f0c0; border-radius:8px; padding:4px 8px;" class="text-xs font-bold text-green-800" id="popupKategori"></span>
        <span class="text-xs text-gray-400">Harga Terbaru</span>
    </div>
    <div id="popupContent" class="space-y-2 text-sm"></div>
</div>

<style>
.pasar-btn:hover { background-color: #d0f0c0 !important; border-color: #2d6a4f !important; color: #2d6a4f !important; }
.pasar-btn-new:hover { background: rgba(208,240,192,0.15) !important; border-color: rgba(208,240,192,0.4) !important; }
.pasar-btn-new:hover .fa-arrow-right { color: #4ade80 !important; }
</style>

<script>
const hargaData = @json($hargaTerkini ?? []);
let currentKategori = '';

// ============ MODAL PASAR ============
function openMarketModal(kategori) {
    currentKategori = kategori;
    document.getElementById('selectedCategoryName').textContent = kategori;
    const modal = document.getElementById('marketModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeMarketModal() {
    document.getElementById('marketModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function goToPricePage(pasarId) {
    if (!currentKategori) return;
    const url = "{{ url('/info-harga') }}/" + currentKategori + "/" + pasarId;
    window.location.href = url;
}

// Tutup modal dengan ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeMarketModal();
});

// ============ HOVER POPUP ============
function showHoverPopup(event, kategori) {
    const popup = document.getElementById('hoverPopup');
    const content = document.getElementById('popupContent');
    const label = document.getElementById('popupKategori');
    label.textContent = kategori.charAt(0).toUpperCase() + kategori.slice(1);

    const items = (hargaData[kategori] || []).slice(0, 3);
    if (items.length === 0) {
        content.innerHTML = '<p class="text-gray-400 text-xs">Belum ada data harga.</p>';
    } else {
        content.innerHTML = items.map(item => `
            <div class="flex justify-between items-center py-1 border-b border-gray-100 last:border-0">
                <span class="font-medium text-gray-700 capitalize">${item.nama_barang}</span>
                <span class="font-bold" style="color:#2d6a4f;">Rp ${Number(item.harga_hari_ini).toLocaleString('id-ID')}</span>
            </div>
        `).join('');
    }

    popup.classList.remove('hidden');
    movePopup(event);
}

function movePopup(event) {
    const popup = document.getElementById('hoverPopup');
    const pw = popup.offsetWidth || 240;
    const ph = popup.offsetHeight || 120;
    const margin = 16;
    let x = event.clientX + margin;
    let y = event.clientY - ph - margin; // selalu di ATAS kursor

    // jika tidak muat di atas, tampilkan di bawah
    if (y < 10) y = event.clientY + margin;
    // jika tidak muat di kanan, geser ke kiri
    if (x + pw > window.innerWidth - 10) x = event.clientX - pw - margin;

    popup.style.left = x + 'px';
    popup.style.top = y + 'px';
}

function hideHoverPopup() {
    document.getElementById('hoverPopup').classList.add('hidden');
}

document.querySelectorAll('[onmouseenter]').forEach(el => {
    el.addEventListener('mousemove', movePopup);
});
</script>
