@extends('layouts.admin')

@section('title', 'Kelola Home')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolahome.css') }}">
@endpush

@section('content')

<h2 class="page-title">Kelola Foto Carousel Home</h2>

{{-- FLASH MESSAGES --}}
@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        <span>{{ session('info') }}</span>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- FORM UPLOAD --}}
<div class="upload-card">
    <h3>Upload Foto Baru</h3>
    <form action="{{ route('admin.home.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="upload-form">
        @csrf
        <input type="file"
               name="image"
               accept="image/jpeg,image/png,image/webp"
               required>
        <button type="submit" class="upload-btn">Tambah Foto</button>
    </form>
</div>

{{-- GRID GAMBAR --}}
@if($images->isEmpty())
    <p class="text-muted" style="margin-top:1rem;">
        Belum ada foto yang diunggah.
    </p>
@else
    <div class="image-grid">
        @foreach($images as $img)
            <div class="image-card">

                <div class="image-preview">
                    <img src="{{ asset('storage/' . $img->image) }}"
                         alt="Foto Carousel {{ $loop->iteration }}">
                </div>

                <div class="image-actions">

                    {{-- FORM EDIT --}}
                    <form id="formEditFoto{{ $img->id }}"
                          action="{{ route('admin.home.update', $img->id) }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="edit-form">
                        @csrf
                        @method('PUT')

                        <label class="file-label">
                            <input type="file"
                                   name="image"
                                   accept="image/jpeg,image/png,image/webp"
                                   required>
                            <span>Edit</span>
                        </label>

                        <button type="button"
                            class="btn-confirm-popup edit-btn"
                            data-form="formEditFoto{{ $img->id }}"
                            data-icon="🖼️"
                            data-title="Ganti Foto"
                            data-message="Apakah Anda yakin ingin mengganti <strong>Foto #{{ $loop->iteration }}</strong>? Foto lama akan ditimpa."
                            data-confirm-text="Ya, Ganti"
                            data-cancel-text="Batal">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </form>

                    {{-- TOMBOL HAPUS --}}
                    <button class="btn-hapus delete-btn"
                        data-url="{{ route('admin.home.delete', $img->id) }}"
                        data-method="DELETE"
                        data-title="Hapus Foto"
                        data-message="Apakah Anda yakin ingin menghapus foto ini?"
                        data-warning="Foto yang dihapus tidak dapat dikembalikan!"
                        data-item-name="Foto {{ $loop->iteration }}">
                        <i class="fas fa-trash"></i>
                    </button>

                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection


@push('scripts')
<script src="{{ asset('plugins/js/adminJS/kelolahome.js') }}"></script>
@endpush