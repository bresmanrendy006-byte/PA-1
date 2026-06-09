@extends('layouts.admin')

@section('title','Kelola Home')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolahome.css') }}">
@endpush

@section('content')

<h2 class="page-title">Kelola Foto Carousel Home</h2>

<div class="upload-card">
    <h3>Upload Foto Baru</h3>

    <form action="{{ route('admin.home.store') }}" method="POST" enctype="multipart/form-data" class="upload-form">
        @csrf
        <input type="file" name="image" required>
        <button type="submit" class="upload-btn">Tambah Foto</button>
    </form>
</div>

<div class="image-grid">

@foreach($images as $img)

<div class="image-card">

    <div class="image-preview">
        <img src="{{ asset('storage/'.$img->image) }}">
    </div>

    <div class="image-actions">

        <form id="formEditFoto{{ $img->id }}"
              action="{{ route('admin.home.update', $img->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="edit-form">
            @csrf
            @method('PUT')

            <label class="file-label">
                <input type="file" name="image" required>
                <span>Edit</span>
            </label>

            <button type="button"
                class="btn-confirm-popup edit-btn"
                data-form="formEditFoto{{ $img->id }}"
                data-icon="🖼️"
                data-title="Ganti Foto"
                data-message="Apakah Anda yakin ingin mengganti <strong>Foto #{{ $img->id }}</strong>? Foto lama akan ditimpa."
                data-confirm-text="Ya, Ganti"
                data-cancel-text="Batal">
                <i class="fas fa-sync-alt"></i>
            </button>

        </form>

        <button class="btn-hapus delete-btn"
            data-url="{{ route('admin.home.delete', $img->id) }}"
            data-method="DELETE"
            data-title="Hapus Foto"
            data-message="Apakah Anda yakin ingin menghapus foto ini?"
            data-warning="Foto yang dihapus tidak dapat dikembalikan!"
            data-item-name="Foto #{{ $img->id }}">
            <i class="fas fa-trash"></i>
        </button>

    </div>

</div>

@endforeach

</div>
@push('scripts')
<script>
    document.querySelectorAll('.edit-form input[type="file"]').forEach(input => {
        input.addEventListener('change', function () {
            if (!this.files.length) return;

            // Trigger klik tombol konfirmasi di form yang sama
            this.closest('.edit-form').querySelector('.btn-confirm-popup').click();
        });
    });
</script>
@endpush
@endsection