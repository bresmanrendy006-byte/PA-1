// popuphapus.js - Reusable Delete Confirmation Popup Handler

class PopupHapus {
    constructor() {
        this.activePopup = null;
        this.initialize();
    }

    /**
     * Initialize all delete buttons with popup functionality
     */
    initialize() {
        // Find all elements with class 'btn-hapus' or data-hapus attribute
        const deleteButtons = document.querySelectorAll('.btn-hapus, [data-hapus="true"]');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Get popup configuration from data attributes
                const config = {
                    url: button.getAttribute('data-url') || button.form?.action,
                    method: button.getAttribute('data-method') || 'DELETE',
                    title: button.getAttribute('data-title') || 'Konfirmasi Hapus',
                    message: button.getAttribute('data-message') || 'Apakah Anda yakin ingin menghapus data ini?',
                    warning: button.getAttribute('data-warning') || 'Data yang dihapus tidak dapat dikembalikan!',
                    confirmText: button.getAttribute('data-confirm-text') || 'Ya, Hapus',
                    cancelText: button.getAttribute('data-cancel-text') || 'Batal',
                    itemName: button.getAttribute('data-item-name') || ''
                };
                
                this.showPopup(config);
            });
        });
        
        // Close popup when clicking overlay
        document.addEventListener('click', (e) => {
            if (e.target.classList && e.target.classList.contains('popup-overlay')) {
                this.closePopup();
            }
        });
        
        // Close popup with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activePopup) {
                this.closePopup();
            }
        });
    }

    /**
     * Show delete confirmation popup
     */
    showPopup(config) {
        // Remove existing popup if any
        this.closePopup();
        
        // Create popup container
        const overlay = document.createElement('div');
        overlay.className = 'popup-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
        `;
        
        // Create popup box
        const popupBox = document.createElement('div');
        popupBox.className = 'popup-box';
        popupBox.style.cssText = `
            background: #1a1a1a;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            width: 320px;
            color: white;
            border: 1px solid rgba(255,215,0,.2);
            animation: popupFade .3s ease;
        `;
        
        // Create icon based on type
        const icon = config.type === 'warning' ? '⚠️' : '🗑️';
        
        // Build popup HTML
        popupBox.innerHTML = `
            <h3 style="margin-bottom: 10px; color: #ffd700;">${icon} ${config.title}</h3>
            <p style="margin: 10px 0; color: #ccc;">
                ${config.message}
                ${config.itemName ? `<br><strong style="color: #ffd700;">"${config.itemName}"</strong>` : ''}
            </p>
            <p style="margin: 10px 0; font-size: 14px; color: #ff9999;">${config.warning}</p>
            <div class="popup-btn" style="margin-top: 20px; display: flex; gap: 10px; justify-content: center;">
                <form method="POST" action="${config.url}" style="display: inline;">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                    <input type="hidden" name="_method" value="${config.method}">
                    <button type="submit" class="btn-confirm" style="
                        padding: 8px 15px;
                        border: none;
                        border-radius: 6px;
                        cursor: pointer;
                        font-weight: 500;
                        background: #ffd700;
                        color: #1a1a1a;
                        transition: all 0.2s ease;
                    ">${config.confirmText}</button>
                </form>
                <button type="button" class="btn-cancel" style="
                    padding: 8px 15px;
                    border: none;
                    border-radius: 6px;
                    cursor: pointer;
                    font-weight: 500;
                    background: #333;
                    color: white;
                    transition: all 0.2s ease;
                ">${config.cancelText}</button>
            </div>
        `;
        
        overlay.appendChild(popupBox);
        document.body.appendChild(overlay);
        
        // Store reference to active popup
        this.activePopup = overlay;
        
        // Add event listener to cancel button
        const cancelBtn = popupBox.querySelector('.btn-cancel');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => this.closePopup());
        }
        
        // Add hover effects
        const confirmBtn = popupBox.querySelector('.btn-confirm');
        if (confirmBtn) {
            confirmBtn.addEventListener('mouseenter', () => {
                confirmBtn.style.background = '#ffed4e';
                confirmBtn.style.transform = 'translateY(-1px)';
            });
            confirmBtn.addEventListener('mouseleave', () => {
                confirmBtn.style.background = '#ffd700';
                confirmBtn.style.transform = 'translateY(0)';
            });
        }
        
        if (cancelBtn) {
            cancelBtn.addEventListener('mouseenter', () => {
                cancelBtn.style.background = '#444';
                cancelBtn.style.transform = 'translateY(-1px)';
            });
            cancelBtn.addEventListener('mouseleave', () => {
                cancelBtn.style.background = '#333';
                cancelBtn.style.transform = 'translateY(0)';
            });
        }
        
        // Add animation keyframes if not exists
        if (!document.querySelector('#popup-keyframes')) {
            const style = document.createElement('style');
            style.id = 'popup-keyframes';
            style.textContent = `
                @keyframes popupFade {
                    from {
                        transform: scale(.8);
                        opacity: 0;
                    }
                    to {
                        transform: scale(1);
                        opacity: 1;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }

    /**
     * Close current popup
     */
    closePopup() {
        if (this.activePopup) {
            this.activePopup.remove();
            this.activePopup = null;
        }
    }

    /**
     * Quick method to show delete popup with minimal config
     */
    static confirmDelete(url, itemName = '', options = {}) {
        const popup = new PopupHapus();
        popup.showPopup({
            url: url,
            method: 'DELETE',
            title: options.title || 'Konfirmasi Hapus',
            message: options.message || 'Apakah Anda yakin ingin menghapus data ini?',
            warning: options.warning || 'Data yang dihapus tidak dapat dikembalikan!',
            confirmText: options.confirmText || 'Ya, Hapus',
            cancelText: options.cancelText || 'Batal',
            itemName: itemName
        });
    }
}

// Initialize popup handler when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.popupHapus = new PopupHapus();
});