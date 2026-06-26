@extends('layouts.admin')

@section('title', 'Edit Stok')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/manajemenstok.css') }}">
@endpush

@section('content')

<div class="stock-container">

    <h1 class="stock-title">Edit Stok Menu</h1>

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

            {{-- Nama menu hanya ditampilkan, tidak bisa diubah --}}
            <div class="form-group">
                <label>Menu</label>
                <input
                    type="text"
                    value="{{ $stock->menu->name }}"
                    disabled
                    style="background-color: #f5f5f5; cursor: not-allowed;">
            </div>

            <div class="form-group">
                <label>Jumlah Stok (Porsi)</label>
                <input
                    type="number"
                    name="quantity"
                    value="{{ old('quantity', $stock->quantity) }}"
                    placeholder="Contoh: 50"
                    min="0"
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