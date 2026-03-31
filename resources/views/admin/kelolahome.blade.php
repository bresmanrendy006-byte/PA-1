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

<form action="{{ route('admin.home.update',$img->id) }}" method="POST" enctype="multipart/form-data" class="edit-form">
@csrf
@method('PUT')

<label class="file-label">
<input type="file" name="image" required>
<span>Edit</span>
</label>

<button type="submit" class="edit-btn">
<i class="fas fa-sync-alt"></i>
</button>

</form>

<form action="{{ route('admin.home.delete',$img->id) }}" method="POST">
@csrf
@method('DELETE')

<button class="delete-btn">
<i class="fas fa-trash"></i>
</button>

</form>

</div>

</div>

@endforeach

</div>

@endsection