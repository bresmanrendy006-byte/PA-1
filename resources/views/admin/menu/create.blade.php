@extends('layouts.admin')

@section('title', 'Tambah Menu')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolamenu.css') }}">
@endpush

@section('content')

<div class="admin-menu-container">

    <h2>Tambah Menu</h2>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Nama Menu</label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                pattern="[A-Za-z\s]+"
                title="Nama menu hanya boleh berisi huruf"
                required>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="price" value="{{ old('price') }}" min="0" required>
        </div>

        <div class="form-group">
            <label>Gambar Menu</label>
            <input type="file" name="image" accept="image/jpg,image/jpeg,image/png,image/webp" required>
        </div>

        <br>

        <button type="submit" class="btn-add">Simpan Menu</button>
        <a href="{{ route('admin.menu.index') }}" class="btn-edit">Kembali</a>

    </form>

</div>

@endsection