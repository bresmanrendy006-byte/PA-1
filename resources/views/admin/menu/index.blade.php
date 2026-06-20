@extends('layouts.admin')

@section('title', 'Kelola Menu')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolamenu.css') }}">
@endpush

@section('content')

<div class="admin-menu-container">

    <div class="menu-header">
        <h2>Kelola Menu</h2>
        {{-- Hanya owner yang bisa tambah menu --}}
        @if(Auth::user()->role === 'owner')
            <a href="{{ route('admin.menu.create') }}" class="btn-add">+ Tambah Menu</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-wrapper">
        <table class="menu-table">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Menu</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    @if(Auth::user()->role === 'owner')
                    <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>

            @forelse($menus as $menu)
            <tr>
                <td>
                    @if($menu->image)
                        <img src="{{ asset('storage/'.$menu->image) }}" class="menu-img" alt="{{ $menu->name }}">
                    @else
                        <span class="no-img">Tidak ada</span>
                    @endif
                </td>

                <td class="menu-name">{{ $menu->name }}</td>

                <td class="menu-description">
                    {{ $menu->description ?? '-' }}
                </td>

                <td class="menu-price">{{ $menu->formattedPrice }}</td>

                @if(Auth::user()->role === 'owner')
                <td class="menu-actions">
                    <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn-edit">Edit</a>

                    <button
                        class="btn-hapus btn-delete"
                        data-url="{{ route('admin.menu.delete', $menu->id) }}"
                        data-method="DELETE"
                        data-title="Hapus Menu"
                        data-message="Yakin ingin menghapus menu ini?"
                        data-item-name="{{ $menu->name }}"
                    >
                        Hapus
                    </button>
                </td>
                @endif
            </tr>

            @empty
            <tr>
                {{-- colspan menyesuaikan jumlah kolom yang tampil --}}
                <td colspan="{{ Auth::user()->role === 'owner' ? 5 : 4 }}" class="empty-text">
                    Belum ada menu ditambahkan
                </td>
            </tr>
            @endforelse

            </tbody>
        </table>
    </div>

</div>

@endsection