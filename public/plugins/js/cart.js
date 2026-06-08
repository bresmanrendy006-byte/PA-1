/**
 * cart.js
 * Handle update quantity via AJAX (auto saat input berubah)
 */

document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // ==============================
    // AUTO UPDATE QUANTITY
    // ==============================

    document.querySelectorAll('.quantity-input').forEach(function (input) {
        let debounceTimer;

        input.addEventListener('change', function () {
            // Pastikan minimum 1
            if (parseInt(input.value) < 1) input.value = 1;

            const cartId  = input.dataset.id;
            const url     = input.dataset.url;
            const quantity = input.value;

            // Debounce agar tidak spam request saat klik naik/turun cepat
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                updateQuantity(cartId, url, quantity, input);
            }, 400);
        });
    });


    // ==============================
    // FUNGSI UPDATE QUANTITY
    // ==============================

    async function updateQuantity(cartId, url, quantity, input) {
        // Visual loading
        input.style.opacity = '0.5';

        try {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('quantity', quantity);

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN':     csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept':           'application/json',
                },
                body: formData,
            });

            const data = await response.json();

            if (data.success) {
                // Update subtotal baris ini
                const subtotalEl = document.querySelector(`.subtotal-${cartId}`);
                if (subtotalEl && data.subtotal) {
                    subtotalEl.textContent = formatRupiah(data.subtotal);
                }

                // Update cart count navbar
                if (data.cart_count !== undefined) {
                    window.updateCartCount(data.cart_count);
                }

                // Update total di ringkasan
                refreshTotal();

                if (data.message) {
                    showAlert('success', data.message);
                }
            } else {
                showAlert('danger', data.message || 'Gagal memperbarui jumlah.');
            }

        } catch (err) {
            console.error('Update quantity error:', err);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        } finally {
            input.style.opacity = '1';
        }
    }


    // ==============================
    // HELPERS
    // ==============================

    function formatRupiah(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    function showAlert(type, message) {
        const container = document.getElementById('ajaxFlashMessage');
        if (!container) return;

        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        container.innerHTML = `
            <div class="alert ${alertClass}">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;

        const alertEl = container.querySelector('.alert');
        if (!alertEl) return;

        setTimeout(() => {
            alertEl.style.opacity = '0';
            setTimeout(() => {
                if (alertEl.parentNode) {
                    alertEl.parentNode.removeChild(alertEl);
                }
            }, 300);
        }, 3000);
    }

    /**
     * Hitung ulang total dari semua subtotal yang ada di DOM
     */
    function refreshTotal() {
        let total = 0;
        let totalItems = 0;

        document.querySelectorAll('[class^="subtotal-"]').forEach(function (el) {
            const angka = parseInt(el.textContent.replace(/[^0-9]/g, '')) || 0;
            total += angka;
        });

        document.querySelectorAll('.quantity-input').forEach(function (input) {
            totalItems += parseInt(input.value) || 0;
        });

        const totalPriceEl = document.getElementById('totalPrice');
        const totalItemsEl = document.getElementById('totalItems');

        if (totalPriceEl) totalPriceEl.textContent = formatRupiah(total);
        if (totalItemsEl) totalItemsEl.innerHTML = `<strong>${totalItems}</strong> item`;
    }

});