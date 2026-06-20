@extends('layouts.admin')

@section('title', 'Tambah Stok')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/manajemenstok.css') }}">
@endpush

@section('content')

<div class="stock-container">

    <h1 class="stock-title">Tambah Stok Bahan Baku</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="stock-form-card">

        <form action="{{ route('admin.stock.store') }}" method="POST">
            @csrf

            <div class="form-group">
            <div class="form-group">
                <label>Nama Bahan</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Kopi Arabika"
                    pattern="[A-Za-z\s]+"
                    title="Nama bahan hanya boleh berisi huruf"
                    required>
            </div>

            <div class="form-group">
                <label>Jumlah Stok</label>
                <input
                    type="number"
                    name="quantity"
                    value="{{ old('quantity') }}"
                    placeholder="Contoh: 100"
                    min="0"
                    required>
            </div>

            <div class="form-group">
                <label>Satuan</label>
                <select name="unit" required>
                    <option value="" disabled selected>Pilih Satuan</option>
                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>kg</option>
                    <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>liter</option>
                    <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>pcs</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-add">Simpan</button>
                <a href="{{ route('admin.stock.index') }}" class="btn-back">Kembali</a>
            </div>

        </form>
    </div>

</div>

@endsection