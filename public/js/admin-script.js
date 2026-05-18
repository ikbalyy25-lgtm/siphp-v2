// === LOGIKA DROPDOWN PASAR ===
function toggleDropdown() {
    const dropdown = document.getElementById('pasarDropdown');
    const arrow = document.getElementById('arrowIcon');
    dropdown.classList.toggle('hidden');
    arrow.style.transform = dropdown.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
}

// === LOGIKA MODAL LOGOUT ===
const logoutModal = document.getElementById('logoutModal');
const laporanModal = document.getElementById('laporanModal');

function showLogoutModal(event) {
    event.preventDefault();
    logoutModal.classList.remove('hidden');
    setTimeout(() => {
        logoutModal.firstElementChild.classList.add('scale-100');
    }, 10);
}

function closeLogoutModal() {
    logoutModal.firstElementChild.classList.remove('scale-100');
    setTimeout(() => {
        logoutModal.classList.add('hidden');
    }, 200);
}

function confirmLogout() {
    document.getElementById('logout-form').submit();
}

// === LOGIKA MODAL LAPORAN ===
function openLaporanModal(event) {
    event.preventDefault();
    laporanModal.classList.remove('hidden');
    setTimeout(() => {
        laporanModal.firstElementChild.classList.add('scale-100');
    }, 10);
}

function closeLaporanModal() {
    laporanModal.firstElementChild.classList.remove('scale-100');
    setTimeout(() => {
        laporanModal.classList.add('hidden');
    }, 200);
}

// Tutup Modal jika klik di luar
window.onclick = function(event) {
    if (event.target == logoutModal) closeLogoutModal();
    if (event.target == laporanModal) closeLaporanModal();
}

/**
 * Fungsi Modal Rekomendasi Harga
 */
function openModalRekomendasi(nama, optimal, rendah, tinggi, pasar) {
    const modal = document.getElementById('modalRekomendasi');
    const content = modal.querySelector('.transform');
    
    // Isi Data
    document.getElementById('modalNamaBarang').innerText = nama;
    document.getElementById('modalHargaOptimal').innerText = optimal;
    document.getElementById('modalHargaTerendah').innerText = rendah;
    document.getElementById('modalHargaTertinggi').innerText = tinggi;
    document.getElementById('modalJumlahPasar').innerText = pasar;
    
    // Tampilkan Modal
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }, 10);
}

function closeModalRekomendasi() {
    const modal = document.getElementById('modalRekomendasi');
    const content = modal.querySelector('.transform');
    
    content.classList.remove('scale-100');
    content.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

// Tutup modal jika user klik di luar area box putih
window.onclick = function(event) {
    const modal = document.getElementById('modalRekomendasi');
    if (event.target == modal) {
        closeModalRekomendasi();
    }
}


function toggleDropdown(id, arrowId) {
    const dropdown = document.getElementById(id);
    const arrow = document.getElementById(arrowId);
    
    // Toggle class hidden
    dropdown.classList.toggle('hidden');
    
    // Rotate arrow
    if (dropdown.classList.contains('hidden')) {
        arrow.style.transform = 'rotate(0deg)';
    } else {
        arrow.style.transform = 'rotate(180deg)';
    }
}