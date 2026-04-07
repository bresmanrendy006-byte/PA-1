@extends('layouts.admin')

@section('title', 'Manajemen Stok')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/manajemenstok.css') }}">
@endpush

@section('content')

<div class="stock-container">

    <div class="stock-header">
        <h1 class="stock-title">Manajemen Stok Bahan Baku</h1>
        <a href="{{ route('admin.stock.create') }}" class="btn-add">+ Tambah Stok</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-wrapper">
        <table class="stock-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Bahan</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                @forelse($stocks as $stock)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $stock->name }}</td>
                    <td>{{ $stock->quantity }}</td>
                    <td>{{ $stock->unit }}</td>
                    <td class="stock-action">

                        <a href="{{ route('admin.stock.edit', $stock->id) }}" class="btn-edit">
                            Edit
                        </a>

                        <button
                            class="btn-hapus btn-delete"
                            data-url="{{ route('admin.stock.delete', $stock->id) }}"
                            data-method="DELETE"
                            data-title="Hapus Stok"
                            data-message="Yakin ingin menghapus bahan ini?"
                            data-item-name="{{ $stock->name }}">
                            Hapus
                        </button>

                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="5" class="empty-text">Belum ada stok ditambahkan</td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

</div>

@endsection