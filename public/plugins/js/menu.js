/**
 * menu.js
 * Handle AJAX add to cart, notes dropdown multiple choice,
 * dan update cart count di navbar
 * Bergantung pada global.js untuk fungsi updateCartCount
 */

document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // ==============================
    // NOTES DROPDOWN
    // ==============================

    // Grup yang saling eksklusif
    const exclusiveGroups = [
        ['Less Ice', 'Extra Ice'],        
        ['Less Sugar', 'Extra Sugar'],    
        ['Hot', 'Cold'],                  
        ['Hot', 'Less Ice', 'Extra Ice'], 
    ];

    document.querySelectorAll('.notes-dropdown').forEach(function (dropdown) {

        const toggle      = dropdown.querySelector('.notes-toggle');
        const options     = dropdown.querySelector('.notes-options');
        const chevron     = dropdown.querySelector('.notes-chevron');
        const toggleText  = dropdown.querySelector('.notes-toggle-text');
        const hiddenInput = dropdown.querySelector('.notes-value');
        const checkboxes  = dropdown.querySelectorAll('.notes-checkbox');

        // Buka/tutup dropdown
        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = options.classList.contains('open');

            // Tutup semua dropdown lain dulu
            document.querySelectorAll('.notes-options.open').forEach(el => {
                el.classList.remove('open');
                el.closest('.notes-dropdown')
                  .querySelector('.notes-chevron')
                  .style.transform = '';
            });

            if (!isOpen) {
                options.classList.add('open');
                chevron.style.transform = 'rotate(180deg)';
            }
        });

        // Update hidden input & teks tombol saat checkbox berubah
        checkboxes.forEach(function (cb) {
            cb.addEventListener('change', function () {

                // Jika dicentang, uncheck semua yang segrup
                if (this.checked) {
                    // Cari SEMUA grup yang mengandung nilai ini (bukan hanya yang pertama)
                    exclusiveGroups
                        .filter(g => g.includes(this.value))
                        .forEach(group => {
                            group.forEach(val => {
                                if (val !== this.value) {
                                    const sibling = Array.from(checkboxes)
                                        .find(c => c.value === val);
                                    if (sibling) sibling.checked = false;
                                }
                            });
                        });
                }

                // Update hidden input & label
                const selected = Array.from(checkboxes)
                    .filter(c => c.checked)
                    .map(c => c.value);

                hiddenInput.value = selected.join(', ');

                if (selected.length === 0) {
                    toggleText.textContent = 'Pilih Catatan';
                } else if (selected.length === 1) {
                    toggleText.textContent = selected[0];
                } else {
                    toggleText.textContent = selected.length + ' catatan dipilih';
                }
            });
        });
    });

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function () {
        document.querySelectorAll('.notes-options.open').forEach(el => {
            el.classList.remove('open');
            el.closest('.notes-dropdown')
              .querySelector('.notes-chevron')
              .style.transform = '';
        });
    });


    // ==============================
    // ADD TO CART (AJAX)
    // ==============================

    document.querySelectorAll('.add-cart-form').forEach(function (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn       = form.querySelector('.btn-add-cart');
            const savedText = btn.innerHTML;

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
                    // Update cart count navbar via global.js
                    window.updateCartCount(data.cart_count);

                    // Reset dropdown setelah berhasil
                    const dropdown = form.querySelector('.notes-dropdown');
                    if (dropdown) {
                        dropdown.querySelectorAll('.notes-checkbox')
                            .forEach(cb => cb.checked = false);
                        dropdown.querySelector('.notes-value').value = '';
                        dropdown.querySelector('.notes-toggle-text').textContent = 'Pilih Catatan';
                    }

                    // Reset quantity ke 1
                    const qtyInput = form.querySelector('.qty-input');
                    if (qtyInput) qtyInput.value = 1;

                    // Feedback tombol
                    btn.innerHTML        = '<i class="fas fa-check"></i> Ditambahkan!';
                    btn.style.background = '#2ecc71';
                    btn.style.color      = 'white';

                    setTimeout(() => {
                        btn.innerHTML        = savedText;
                        btn.style.background = '';
                        btn.style.color      = '';
                        btn.disabled         = false;
                    }, 1500);

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
    // HELPER
    // ==============================

    function showBtnError(btn, savedText, message) {
        btn.innerHTML = savedText;
        btn.disabled  = false;
        alert(message || 'Gagal menambahkan ke keranjang.');
    }

});