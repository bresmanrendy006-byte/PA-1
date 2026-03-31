@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolamenu.css') }}">
@endpush

@section('content')

<div class="admin-menu-container">

<h2>Edit Menu</h2>

<form action="{{ route('admin.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Nama Menu</label>
        <input type="text" name="name" value="{{ old('name', $menu->name) }}" required>
    </div>

    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="description">{{ old('description', $menu->description) }}</textarea>
    </div>

    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="price" value="{{ old('price', $menu->price) }}" required>
    </div>

    <div class="form-group">
        <label>Gambar Menu</label>
        @if($menu->image)
            <div><img src="{{ asset('storage/'.$menu->image) }}" style="max-width: 120px;" alt="{{ $menu->name }}"></div>
        @endif
        <input type="file" name="image">
    </div>

    <br>

    <button type="submit" class="btn-add">Update Menu</button>
    <a href="{{ route('admin.menu.index') }}" class="btn-edit">Batal</a>

</form>

</div>

@endsection