document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // BAGIAN 1: LOGIKA JAM OPERASIONAL
    // ==========================================
    const inputBuka = document.getElementById('input_buka');
    const inputTutup = document.getElementById('input_tutup');
    const finalInput = document.getElementById('final_jam_buka');

    function updateJam() {
        let buka = inputBuka.value;
        let tutup = inputTutup.value;

        if (buka && tutup) {
            // Ganti titik dua (:) menjadi titik (.) sesuai format database
            buka = buka.replace(':', '.');
            tutup = tutup.replace(':', '.');
            // Gabungkan nilai
            finalInput.value = `${buka} - ${tutup}`;
        }
    }

    if(inputBuka && inputTutup) {
        inputBuka.addEventListener('change', updateJam);
        inputTutup.addEventListener('change', updateJam);
    }

    // ==========================================
    // BAGIAN 2: LOGIKA UPLOAD FOTO & VALIDASI
    // ==========================================
    const fileInput = document.getElementById('gambarInput');
    const uploadBox = document.getElementById('uploadBox');
    const uploadDefault = document.getElementById('uploadDefault');
    const uploadSuccess = document.getElementById('uploadSuccess');
    const fileNameDisplay = document.getElementById('fileName');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (file) {
                // 1. Validasi Ukuran File (Max 2MB = 2 * 1024 * 1024 bytes)
                const maxSize = 2 * 1024 * 1024;
                
                if (file.size > maxSize) {
                    alert("Ukuran file terlalu besar! Maksimal 2MB.");
                    this.value = ""; // Reset input agar user harus pilih ulang
                    
                    // Kembalikan tampilan ke default (Merah error)
                    tampilkanStateDefault(true); 
                    return;
                }

                // 2. Jika File Valid: Tampilkan Nama File & Ubah Jadi Hijau
                fileNameDisplay.textContent = file.name;
                tampilkanStateSukses();
            
            } else {
                // 3. Jika user membatalkan pemilihan file (Cancel di dialog file)
                tampilkanStateDefault(false);
            }
        });
    }

    // Fungsi Helper: Menampilkan Tampilan Sukses (Hijau)
    function tampilkanStateSukses() {
        uploadDefault.classList.add('hidden');
        uploadSuccess.classList.remove('hidden');
        uploadSuccess.classList.add('flex');
        
        // Ubah border & background jadi hijau
        uploadBox.classList.remove('border-gray-300', 'bg-gray-50', 'border-red-500', 'bg-red-50');
        uploadBox.classList.add('border-green-500', 'bg-green-50');
    }

    // Fungsi Helper: Menampilkan Tampilan Default/Error
    function tampilkanStateDefault(isError) {
        uploadDefault.classList.remove('hidden');
        uploadSuccess.classList.add('hidden');
        uploadSuccess.classList.remove('flex');

        if (isError) {
            // Tampilan Merah (Error)
            uploadBox.classList.remove('border-green-500', 'bg-green-50', 'border-gray-300', 'bg-gray-50');
            uploadBox.classList.add('border-red-500', 'bg-red-50');
        } else {
            // Tampilan Abu-abu (Normal)
            uploadBox.classList.remove('border-green-500', 'bg-green-50', 'border-red-500', 'bg-red-50');
            uploadBox.classList.add('border-gray-300', 'bg-gray-50');
        }
    }
});