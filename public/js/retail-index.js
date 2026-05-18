/**
 * retail-index.js
 * Script untuk menangani Modal Konfirmasi Hapus Ritel
 */

// Variabel global untuk menyimpan form yang akan disubmit
let formYangAkanDihapus = null;

// Ambil elemen DOM
const modal = document.getElementById('modalKonfirmasi');
const modalContent = document.getElementById('modalContent');

// --- FUNGSI GLOBAL (Dipanggil via onclick di HTML) ---

function tampilkanModal(button) {
    // 1. Simpan form dari tombol yang diklik
    formYangAkanDihapus = button.closest('form');

    // 2. Tampilkan modal
    if (modal && modalContent) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Animasi zoom in (sedikit delay agar transisi CSS jalan)
        setTimeout(() => {
            modalContent.classList.remove('scale-90');
            modalContent.classList.add('scale-100');
        }, 10);
    }
}

function tutupModal() {
    if (modal && modalContent) {
        // Animasi zoom out
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-90');

        // Sembunyikan modal setelah durasi transisi (300ms)
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            formYangAkanDihapus = null; // Reset variable
        }, 300);
    }
}

// --- EVENT LISTENERS (Dijalankan saat halaman selesai dimuat) ---

document.addEventListener('DOMContentLoaded', function() {
    
    // Listener untuk tombol "Ya, Hapus" di dalam modal
    const btnYa = document.getElementById('btnYaHapus');
    
    if (btnYa) {
        btnYa.addEventListener('click', function() {
            if (formYangAkanDihapus) {
                formYangAkanDihapus.submit(); // Submit form ke server
            }
        });
    }

    // Listener untuk menutup modal jika user klik area gelap (backdrop)
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            tutupModal();
        }
    });
});