@extends('layouts.app')

@section('content')
<div class="min-h-screen font-sans py-10 px-4 md:px-8" style="background: linear-gradient(135deg, #f0fff4 0%, #e8f8e8 100%);">

    {{-- Header --}}
    <div class="max-w-4xl mx-auto mb-6">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-semibold mb-5 transition hover:opacity-80"
            style="color: #2d6a4f;">
            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Beranda
        </a>

        <div class="rounded-2xl p-5 md:p-6 shadow-lg mb-2"
            style="background: linear-gradient(135deg, #1e3a2f, #0d2b1a); border: 1px solid rgba(208,240,192,0.2);">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <span class="inline-block text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest mb-2"
                        style="background: rgba(208,240,192,0.15); color: #d0f0c0; border: 1px solid rgba(208,240,192,0.3);">
                        {{ ucfirst($kategori) }}
                    </span>
                    <h1 class="text-xl md:text-2xl font-extrabold" style="color: #d0f0c0;">
                        Harga Barang {{ ucfirst($kategori) }}
                    </h1>
                    <div class="flex items-center gap-2 mt-1">
                        <i class="fas fa-map-marker-alt text-sm" style="color: #4ade80;"></i>
                        <span class="text-gray-300 text-sm font-medium">{{ $pasar->nama_pasar }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ now()->translatedFormat('d F Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Search + Info --}}
    <div class="max-w-4xl mx-auto mb-4 flex flex-col md:flex-row gap-3 items-start md:items-center justify-between">
        <div class="relative w-full md:w-72">
            <div class="absolute left-0 top-0 bottom-0 w-10 flex items-center justify-center pointer-events-none"
                style="color:#4ade80;">
                <i class="fas fa-search text-sm"></i>
            </div>
            <input type="text" id="searchInput" placeholder="Cari nama barang..."
                class="w-full text-sm focus:outline-none transition"
                style="padding: 10px 14px 10px 38px;
                       border-radius: 12px;
                       border: 1.5px solid #d0f0c0;
                       background: white;
                       line-height: 1.4;"
                oninput="filterTable()">
        </div>
        <span id="infoCount" class="text-xs text-gray-500 whitespace-nowrap"></span>
    </div>

    {{-- Tabel --}}
    <div class="max-w-4xl mx-auto">
        <div class="rounded-2xl overflow-hidden shadow-xl" style="border: 1px solid rgba(208,240,192,0.4);">
            <table class="w-full text-left" id="hargaTable">
                <thead>
                    <tr style="background: linear-gradient(135deg, #1e3a2f, #2d6a4f);">
                        <th class="px-4 py-3.5 text-center text-xs font-bold uppercase tracking-wider w-10" style="color: #d0f0c0;">No</th>
                        <th class="px-4 py-3.5 text-xs font-bold uppercase tracking-wider" style="color: #d0f0c0;">Nama Barang</th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold uppercase tracking-wider whitespace-nowrap" style="color: #d0f0c0;">Tanggal</th>
                        <th class="px-4 py-3.5 text-right text-xs font-bold uppercase tracking-wider whitespace-nowrap" style="color: #d0f0c0;">Harga (Rp)</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($data_harga as $index => $item)
                    <tr class="harga-row border-b transition duration-150 hover:bg-green-50"
                        style="border-color: rgba(208,240,192,0.3);"
                        data-nama="{{ strtolower($item->nama_barang) }}">
                        <td class="px-4 py-3 text-center text-xs font-bold text-gray-400 row-no">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                    style="background: rgba(208,240,192,0.3);">
                                    <i class="fas fa-box text-xs" style="color: #2d6a4f;"></i>
                                </div>
                                <span class="font-semibold text-gray-800 capitalize text-sm">{{ $item->nama_barang }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center text-xs text-gray-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="inline-block font-extrabold text-sm px-3 py-1 rounded-lg"
                                style="background: rgba(208,240,192,0.3); color: #1a5c35;">
                                Rp {{ number_format($item->harga_hari_ini, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-16 text-center text-gray-400">
                            <i class="fas fa-box-open text-3xl mb-3 opacity-30 block"></i>
                            <p class="text-sm">Belum ada data harga saat ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between mt-5 px-1" id="paginationBar">
            <span id="pageInfo" class="text-xs text-gray-500"></span>
            <div class="flex gap-2">
                <button id="prevPage" onclick="changePage(-1)"
                    class="px-4 py-2 rounded-xl text-xs font-semibold transition disabled:opacity-40"
                    style="background: #1e3a2f; color: #d0f0c0;">
                    <i class="fas fa-chevron-left mr-1"></i> Prev
                </button>
                <button id="nextPage" onclick="changePage(1)"
                    class="px-4 py-2 rounded-xl text-xs font-semibold transition disabled:opacity-40"
                    style="background: #1e3a2f; color: #d0f0c0;">
                    Next <i class="fas fa-chevron-right ml-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const PER_PAGE = 10;
let currentPage = 1;
let filteredRows = [];

function getAllRows() {
    return Array.from(document.querySelectorAll('.harga-row'));
}

function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase().trim();
    const all = getAllRows();
    filteredRows = all.filter(r => !q || r.dataset.nama.includes(q));
    currentPage = 1;
    renderPage();
}

function renderPage() {
    const all = getAllRows();
    all.forEach(r => r.style.display = 'none');

    const start = (currentPage - 1) * PER_PAGE;
    const end = start + PER_PAGE;
    const pageRows = filteredRows.slice(start, end);
    pageRows.forEach((r, i) => {
        r.style.display = '';
        r.querySelector('.row-no').textContent = start + i + 1;
    });

    const total = filteredRows.length;
    const totalPages = Math.ceil(total / PER_PAGE);
    document.getElementById('pageInfo').textContent =
        total === 0 ? 'Tidak ada hasil' :
        `Menampilkan ${start + 1}–${Math.min(end, total)} dari ${total} barang`;

    document.getElementById('infoCount').textContent =
        total > 0 ? `${total} barang ditemukan` : '';

    document.getElementById('prevPage').disabled = currentPage <= 1;
    document.getElementById('nextPage').disabled = currentPage >= totalPages;
    document.getElementById('paginationBar').style.display = total <= PER_PAGE && currentPage === 1 && !document.getElementById('searchInput').value ? 'none' : 'flex';
}

function changePage(dir) {
    const total = filteredRows.length;
    const totalPages = Math.ceil(total / PER_PAGE);
    currentPage = Math.max(1, Math.min(currentPage + dir, totalPages));
    renderPage();
    window.scrollTo({ top: 200, behavior: 'smooth' });
}

// Init
filteredRows = getAllRows();
renderPage();
</script>
@endsection
