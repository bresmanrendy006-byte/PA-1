// Cart Management System
class CartManager {
    constructor() {
        this.init();
    }

    init() {
        this.initializeForms();
        this.initializeQuantityInputs();
    }

    initializeForms() {
        const updateForms = document.querySelectorAll('.update-form');
        updateForms.forEach(form => {
            form.addEventListener('submit', (e) => this.handleUpdate(e));
        });
    }

    initializeQuantityInputs() {
        const quantityInputs = document.querySelectorAll('.quantity-input');
        quantityInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                const form = input.closest('.update-form');
                if (form) {
                    if (input.value < 1) input.value = 1;
                    form.dispatchEvent(new Event('submit'));
                }
            });
        });
    }

    // Header standar untuk semua request AJAX
    getHeaders() {
        return {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',  // ← wajib agar Laravel kenali sebagai AJAX
            'Accept': 'application/json',
        };
    }

    async handleUpdate(event) {
        event.preventDefault();

        const form = event.currentTarget;
        const cartId = form.dataset.id;
        const quantity = form.querySelector('input[name="quantity"]').value;
        const updateButton = form.querySelector('.btn-update');

        this.setLoading(updateButton, true);

        try {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('quantity', quantity);

            const response = await fetch(`/cart/update/${cartId}`, {
                method: 'POST',
                headers: this.getHeaders(),
                body: formData,
            });

            const data = await response.json();

            if (data.success) {
                this.updateSubtotal(cartId, data.subtotal);
                this.showAlert('success', data.message);
                if (window.updateCartCount) window.updateCartCount();
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showAlert('danger', data.message || 'Gagal memperbarui keranjang.');
            }

        } catch (error) {
            console.error('Update error:', error);
            this.showAlert('danger', 'Terjadi kesalahan saat memperbarui keranjang.');
        } finally {
            this.setLoading(updateButton, false);
        }
    }

    updateSubtotal(cartId, subtotal) {
        const el = document.querySelector(`.subtotal-${cartId}`);
        if (el) {
            el.textContent = this.formatRupiah(subtotal);
            el.style.backgroundColor = '#f0f0f0';
            setTimeout(() => el.style.backgroundColor = '', 500);
        }
    }

    formatRupiah(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    showAlert(type, message) {
        document.querySelectorAll('.alert').forEach(a => a.remove());

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;

        const container = document.querySelector('.cart-container');
        const header = document.querySelector('.cart-header');
        if (container && header) {
            container.insertBefore(alertDiv, header.nextSibling);
        }

        setTimeout(() => {
            alertDiv.style.transition = 'opacity 0.3s';
            alertDiv.style.opacity = '0';
            setTimeout(() => alertDiv.remove(), 300);
        }, 3000);
    }

    setLoading(button, isLoading) {
        if (!button) return;
        if (isLoading) {
            button.disabled = true;
            button.originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        } else {
            button.disabled = false;
            if (button.originalText) button.innerHTML = button.originalText;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new CartManager();
});