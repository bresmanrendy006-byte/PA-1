/**
 * menu.js
 * Handle AJAX add to cart & update cart count di navbar
 * Bergantung pada global.js untuk fungsi updateCartCount
 */

document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // ==============================
    // ADD TO CART
    // ==============================

    document.querySelectorAll('.add-cart-form').forEach(function (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn       = form.querySelector('.btn-add-cart');
            const savedText = btn.innerHTML;

            // Loading
            btn.disabled  = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN':     csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept':           'application/json',
                    },
                    body: new FormData(form),
                });

                const data = await response.json();

                if (data.success) {
                    // Pakai fungsi global dari global.js
                    window.updateCartCount(data.cart_count);
                    showBtnSuccess(btn, savedText);
                } else {
                    showBtnError(btn, savedText, data.message);
                }

            } catch (err) {
                console.error('Add to cart error:', err);
                showBtnError(btn, savedText, 'Terjadi kesalahan. Coba lagi.');
            }
        });
    });


    // ==============================
    // HELPERS
    // ==============================

    function showBtnSuccess(btn, savedText) {
        btn.innerHTML        = '<i class="fas fa-check"></i> Ditambahkan!';
        btn.style.background = '#2ecc71';
        btn.style.color      = 'white';

        setTimeout(() => {
            btn.innerHTML        = savedText;
            btn.style.background = '';
            btn.style.color      = '';
            btn.disabled         = false;
        }, 1500);
    }

    function showBtnError(btn, savedText, message) {
        btn.innerHTML = savedText;
        btn.disabled  = false;
        alert(message || 'Gagal menambahkan ke keranjang.');
    }

});