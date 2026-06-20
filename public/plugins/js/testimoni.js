function editTestimoni(id) {
    document.getElementById('lihat' + id).style.display = 'none';
    document.getElementById('edit' + id).style.display = 'block';
}

function batalEdit(id) {
    document.getElementById('lihat' + id).style.display = 'flex';
    document.getElementById('edit' + id).style.display = 'none';
}

function previewImage(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
        preview.src = '';
    }
}

setTimeout(function() {
    document.querySelectorAll('.alert:not(.alert-info)').forEach(function(alert) {
        alert.style.transition = 'opacity 0.3s';
        alert.style.opacity = '0';
        setTimeout(function() { alert.remove(); }, 300);
    });
}, 3000);