@extends('layouts.admin')

@section('title', 'Edit Menu')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolamenu.css') }}">
@endpush

@section('content')

<div class="admin-menu-container">

    <h2>Edit Menu</h2>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nama Menu</label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $menu->name) }}"
                pattern="[A-Za-z\s]+"
                title="Nama menu hanya boleh berisi huruf"
                required>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description">{{ old('description', $menu->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Harga</label>
            <input
                type="number"
                name="price"
                value="{{ old('price', $menu->price) }}"
                min="0"
                required>
        </div>

        <div class="form-group">
            <label>Gambar Menu</label>
            @if($menu->image)
                <div class="current-img">
                    <img src="{{ asset('storage/'.$menu->image) }}" style="max-width:120px; border-radius:8px;" alt="{{ $menu->name }}">
                    <small>Gambar saat ini. Upload baru untuk mengganti.</small>
                </div>
            @endif
            <input type="file" name="image" accept="image/jpg,image/jpeg,image/png,image/webp">
        </div>

        <br>

        <button type="submit" class="btn-add">Update Menu</button>
        <a href="{{ route('admin.menu.index') }}" class="btn-edit">Batal</a>

    </form>

</div>

@endsection