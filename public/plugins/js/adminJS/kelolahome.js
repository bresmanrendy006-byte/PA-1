document.querySelectorAll('.edit-form input[type="file"]').forEach(input => {
    input.addEventListener('change', function () {
        if (!this.files.length) return;

        this.closest('.edit-form').querySelector('.btn-confirm-popup').click();
    });
});