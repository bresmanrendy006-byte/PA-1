/**
 * popup_confirm.js
 * Reusable Confirmation Popup
 *
 * CARA PAKAI DI BLADE:
 * Tambahkan id="formGantiPassword" ke form, lalu:
 *
 *   <button type="button"
 *       class="btn-confirm-popup"
 *       data-form="formGantiPassword"
 *       data-title="Konfirmasi Ganti Password"
 *       data-message="Apakah Anda yakin ingin mengubah password akun Anda?<br>Pastikan Anda mengingat password baru."
 *       data-icon="🔐"
 *       data-confirm-text="Ya, Ubah"
 *       data-cancel-text="Batal">
 *       <i class="fas fa-save"></i> Simpan Password
 *   </button>
 *
 * JANGAN lupa load file ini di layout (layouts.app atau layouts.admin),
 * dan pastikan CSS .confirm-overlay sudah ada di style.css.
 */

class PopupConfirm {
    constructor() {
        this.activePopup = null;
        this.initialize();
    }

    /**
     * Pasang event listener ke semua tombol trigger
     */
    initialize() {
        document.addEventListener('click', (e) => {
            const button = e.target.closest('.btn-confirm-popup');
            if (!button) return;

            e.preventDefault();

            const formId = button.getAttribute('data-form');
            const form   = formId ? document.getElementById(formId) : null;

            // Validasi form native browser dulu sebelum popup tampil
            if (form && !form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const config = {
                form:        form,
                icon:        button.getAttribute('data-icon')         || '❓',
                title:       button.getAttribute('data-title')        || 'Konfirmasi',
                message:     button.getAttribute('data-message')      || 'Apakah Anda yakin?',
                confirmText: button.getAttribute('data-confirm-text') || 'Ya',
                cancelText:  button.getAttribute('data-cancel-text')  || 'Batal',
            };

            this.showPopup(config);
        });

        // Tutup dengan klik overlay
        document.addEventListener('click', (e) => {
            if (this.activePopup && e.target === this.activePopup) {
                this.closePopup();
            }
        });

        // Tutup dengan tombol Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activePopup) {
                this.closePopup();
            }
        });
    }

    /**
     * Tampilkan popup konfirmasi
     */
    showPopup(config) {
        this.closePopup();

        const overlay = document.createElement('div');
        overlay.className = 'confirm-overlay active';

        overlay.innerHTML = `
            <div class="confirm-box">
                <div class="confirm-icon">${config.icon}</div>
                <h3>${config.title}</h3>
                <p>${config.message}</p>
                <div class="confirm-actions">
                    <button type="button" class="btn-tidak">${config.cancelText}</button>
                    <button type="button" class="btn-ya">${config.confirmText}</button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);
        this.activePopup = overlay;

        // Tombol Ya → submit form lalu tutup popup
        overlay.querySelector('.btn-ya').addEventListener('click', () => {
            this.closePopup();
            if (config.form) config.form.submit();
        });

        // Tombol Batal → tutup popup
        overlay.querySelector('.btn-tidak').addEventListener('click', () => {
            this.closePopup();
        });
    }

    /**
     * Tutup popup aktif
     */
    closePopup() {
        if (this.activePopup) {
            this.activePopup.remove();
            this.activePopup = null;
        }
    }
}

// Inisialisasi satu instance global saat DOM siap
document.addEventListener('DOMContentLoaded', () => {
    window.popupConfirm = new PopupConfirm();
});