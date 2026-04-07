/**
 * popuphapus.js
 * Reusable Delete Confirmation Popup
 *
 * CARA PAKAI DI BLADE:
 * Tambahkan class "btn-hapus" dan data attributes berikut ke tombol hapus:
 *
 *   <button class="btn-hapus"
 *       data-url="{{ route('admin.xxx.destroy', $item->id) }}"
 *       data-method="DELETE"
 *       data-title="Hapus Item"
 *       data-message="Yakin ingin menghapus item ini?"
 *       data-item-name="{{ $item->name }}">
 *       Hapus
 *   </button>
 *
 * JANGAN lupa load file ini di layout admin (sekali saja),
 * dan pastikan ada <meta name="csrf-token"> di <head>.
 */

class PopupHapus {
    constructor() {
        this.activePopup = null;
        this.initialize();
    }

    /**
     * Pasang event listener ke semua tombol hapus
     */
    initialize() {
        document.addEventListener('click', (e) => {
            const button = e.target.closest('.btn-hapus, [data-hapus="true"]');
            if (!button) return;

            e.preventDefault();

            const config = {
                url:         button.getAttribute('data-url') || button.closest('form')?.action || '',
                method:      button.getAttribute('data-method') || 'DELETE',
                title:       button.getAttribute('data-title') || 'Konfirmasi Hapus',
                message:     button.getAttribute('data-message') || 'Apakah Anda yakin ingin menghapus data ini?',
                warning:     button.getAttribute('data-warning') || 'Data yang dihapus tidak dapat dikembalikan!',
                confirmText: button.getAttribute('data-confirm-text') || 'Ya, Hapus',
                cancelText:  button.getAttribute('data-cancel-text') || 'Batal',
                itemName:    button.getAttribute('data-item-name') || '',
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

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        const overlay = document.createElement('div');
        overlay.className = 'popup-overlay';

        overlay.innerHTML = `
            <div class="popup-box">
                <h3>⚠️ ${config.title}</h3>
                <p class="popup-message">
                    ${config.message}
                    ${config.itemName
                        ? `<br><strong class="popup-item-name">"${config.itemName}"</strong>`
                        : ''}
                </p>
                <p class="popup-warning">${config.warning}</p>
                <div class="popup-btn">
                    <form method="POST" action="${config.url}" style="display:inline;">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="${config.method}">
                        <button type="submit" class="btn-confirm">${config.confirmText}</button>
                    </form>
                    <button type="button" class="btn-cancel">${config.cancelText}</button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);
        this.activePopup = overlay;

        // Tampilkan dengan display flex agar centering aktif
        overlay.style.display = 'flex';

        overlay.querySelector('.btn-cancel').addEventListener('click', () => this.closePopup());
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
    window.popupHapus = new PopupHapus();
});