/* public/js/home-script.js */

let currentCategory = '';

// Fungsi Buka Modal
function openMarketModal(kategori) {
    currentCategory = kategori;

    // Update teks di dalam modal agar sesuai kategori yang diklik
    // Mengganti tanda strip (-) dengan spasi jika ada
    const categoryName = kategori.replace('-', ' ');
    document.getElementById('selectedCategoryName').innerText = categoryName;

    // Tampilkan Modal
    document.getElementById('marketModal').classList.remove('hidden');
}

// Fungsi Tutup Modal
function closeMarketModal() {
    document.getElementById('marketModal').classList.add('hidden');
}

// Fungsi Redirect ke Halaman Harga
function goToPricePage(pasarId) {
    // Arahkan ke Route Laravel
    window.location.href = "/info-harga/" + currentCategory + "/" + pasarId;
}

// Menutup modal jika user klik di luar area modal (backdrop)
window.onclick = function(event) {
    const modal = document.getElementById('marketModal');
    if (event.target == modal) {
        closeMarketModal();
    }
}