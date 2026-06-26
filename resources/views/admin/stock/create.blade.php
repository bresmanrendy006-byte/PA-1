@extends('layouts.admin')

@section('title', 'Tambah Stok')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/manajemenstok.css') }}">
@endpush

@section('content')

<div class="stock-container">

    <h1 class="stock-title">Tambah Stok Menu</h1>

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
                <label>Pilih Menu</label>
                <select name="menu_id" required>
                    <option value="" disabled selected>-- Pilih Menu --</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}" {{ old('menu_id') == $menu->id ? 'selected' : '' }}>
                            {{ $menu->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Jumlah Stok (Porsi)</label>
                <input
                    type="number"
                    name="quantity"
                    value="{{ old('quantity') }}"
                    placeholder="Contoh: 50"
                    min="0"
                    required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-add">Simpan</button>
                <a href="{{ route('admin.stock.index') }}" class="btn-back">Kembali</a>
            </div>

        </form>
    </div>

</div>

@endsection