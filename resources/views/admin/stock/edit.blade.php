@extends('layouts.admin')

@section('title', 'Edit Stok')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/manajemenstok.css') }}">
@endpush

@section('content')

<div class="stock-container">

    <h1 class="stock-title">Edit Stok Bahan Baku</h1>

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

        <form action="{{ route('admin.stock.update', $stock->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Bahan</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $stock->name) }}"
                    placeholder="Contoh: Kopi Arabika"
                    required>
            </div>

            <div class="form-group">
                <label>Jumlah Stok</label>
                <input
                    type="number"
                    name="quantity"
                    value="{{ old('quantity', $stock->quantity) }}"
                    placeholder="Contoh: 100"
                    min="0"
                    required>
            </div>

            <div class="form-group">
                <label>Satuan</label>
                <input
                    type="text"
                    name="unit"
                    value="{{ old('unit', $stock->unit) }}"
                    placeholder="Contoh: kg / liter / pcs"
                    required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-add">Update</button>
                <a href="{{ route('admin.stock.index') }}" class="btn-back">Kembali</a>
            </div>

        </form>
    </div>

</div>

@endsection