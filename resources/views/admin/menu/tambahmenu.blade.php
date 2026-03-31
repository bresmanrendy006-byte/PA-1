@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolamenu.css') }}">
@endpush

@section('content')

<div class="admin-menu-container">

<h2>Tambah Menu</h2>

<form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">

@csrf

<div class="form-group">
<label>Nama Menu</label>
<input type="text" name="name" required>
</div>

<div class="form-group">
<label>Deskripsi</label>
<textarea name="description"></textarea>
</div>

<div class="form-group">
<label>Harga</label>
<input type="number" name="price" required>
</div>

<div class="form-group">
<label>Gambar Menu</label>
<input type="file" name="image">
</div>

<br>

<button type="submit" class="btn-add">Simpan Menu</button>

<a href="{{ route('admin.menu.index') }}" class="btn-edit">
Kembali
</a>

</form>

</div>

@endsection