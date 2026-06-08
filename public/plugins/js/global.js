/**
 * global.js
 * Fungsi global yang dipakai di semua halaman
 * Load di app.blade.php agar tersedia di semua halaman
 */

// ==============================
// UPDATE CART COUNT
// ==============================

/**
 * Update angka cart di navbar secara langsung
 * Dipanggil setelah AJAX berhasil, contoh: window.updateCartCount(5)
 */
window.updateCartCount = function (count) {
    const el = document.getElementById('cartCount');
    if (!el) return;

    el.textContent = count;

    // Animasi bounce
    el.classList.add('cart-bounce');
    setTimeout(() => el.classList.remove('cart-bounce'), 400);
};

// CATATAN: Tidak ada auto-fetch saat halaman load
// karena navbar sudah render angka yang benar dari server via Blade:
// {{ Auth::user()->carts()->sum('quantity') ?? 0 }}
// Fungsi updateCartCount hanya dipakai setelah aksi AJAX (tambah/hapus item)