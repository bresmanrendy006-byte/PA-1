// File: public/plugins/js/popupdelete.js
// Fungsi untuk konfirmasi hapus dengan popup

console.log("File popupdelete.js berhasil di-load"); // Untuk debugging

let formHapus = null;

// Fungsi untuk membuka popup
function bukaPopup(form) {
    console.log("bukaPopup dipanggil", form); // Untuk debugging
    formHapus = form;
    const popup = document.getElementById("popupHapus");
    if (popup) {
        popup.style.display = "flex";
        console.log("Popup ditampilkan"); // Untuk debugging
    } else {
        console.error("Popup dengan id 'popupHapus' tidak ditemukan");
    }
    return false;
}

// Fungsi untuk menutup popup
function tutupPopup() {
    console.log("tutupPopup dipanggil"); // Untuk debugging
    const popup = document.getElementById("popupHapus");
    if (popup) {
        popup.style.display = "none";
        console.log("Popup ditutup"); // Untuk debugging
    }
    formHapus = null;
}

// Event listener untuk tombol konfirmasi hapus
document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM sudah siap"); // Untuk debugging
    
    const btnConfirm = document.getElementById("btnConfirmHapus");
    if (btnConfirm) {
        console.log("Tombol confirm ditemukan"); // Untuk debugging
        btnConfirm.onclick = function() {
            console.log("Tombol confirm diklik"); // Untuk debugging
            if (formHapus) {
                console.log("Mengirim form..."); // Untuk debugging
                formHapus.submit();
            } else {
                console.error("formHapus bernilai null");
            }
        };
    } else {
        console.error("Tombol dengan id 'btnConfirmHapus' tidak ditemukan");
    }
    
    // Tutup popup jika klik di luar area popup
    const popup = document.getElementById("popupHapus");
    if (popup) {
        popup.onclick = function(e) {
            if (e.target === this) {
                console.log("Klik di luar popup"); // Untuk debugging
                tutupPopup();
            }
        };
    } else {
        console.error("Popup dengan id 'popupHapus' tidak ditemukan saat DOM ready");
    }
});