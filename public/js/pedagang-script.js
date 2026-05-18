// public/js/pedagang-script.js

const modal = document.getElementById('inputModal');
const modalContent = document.getElementById('modalContent');
const inputKategori = document.getElementById('inputKategori');
const labelKategori = document.getElementById('modalKategoriLabel');

function openModal(kategori) {
    // Set kategori di form
    inputKategori.value = kategori;
    labelKategori.innerText = kategori;

    // Tampilkan Modal
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal() {
    // Efek tutup
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}