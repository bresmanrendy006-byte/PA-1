@extends('layouts.app')

@section('title', 'Detail Pesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/order-detail.css') }}">
@endpush

@section('content')
<div class="page-white">
<div class="order-container">

    <h1 class="order-title">Detail Pesanan</h1>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

{{-- ORDER INFO --}}
<div class="order-info">
    <p><strong>ID Pesanan :</strong> #{{ $order->id }}</p>
    <p><strong>Tanggal :</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
    <p>
        <strong>Status :</strong>
        @if($order->status == 'pending')
            <span class="status pending">
                <i class="fas fa-clock"></i> Menunggu Konfirmasi
            </span>
        @elseif($order->status == 'confirmed')
            <span class="status confirmed">
                <i class="fas fa-check-circle"></i> Pesanan Dikonfirmasi
            </span>
        @elseif($order->status == 'rejected')
            <span class="status rejected">
                <i class="fas fa-times-circle"></i> Pesanan Ditolak
            </span>
        @endif
    </p>
</div>

{{-- INFO KONFIRMASI --}}
@if($order->status == 'confirmed')
    <div class="confirmed-info">
        <i class="fas fa-store"></i>
        <div>
            <strong>Pesanan kamu sudah dikonfirmasi!</strong>
            <p>Tunjukkan nomor pesanan <strong>#{{ $order->id }}</strong> ke kasir dan lakukan pembayaran cash.</p>
        </div>
    </div>
@endif

{{-- ORDER TABLE --}}
<div class="order-table">
    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td class="menu-name">{{ $item->menu->name }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- TOTAL --}}
<div class="order-total">
    <h3>
        Total Pembayaran
        <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
    </h3>
</div>

{{-- ACTIONS --}}
<div class="order-actions">

    <a href="{{ route('orders.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    {{-- EDIT PESANAN — hanya saat pending --}}
    @if($order->status == 'pending')
        <form id="formEditPesanan" action="{{ route('orders.editToCart', $order->id) }}" method="POST">
            @csrf
            <button type="button"
                    class="btn-edit-order btn-confirm-popup"
                    data-form="formEditPesanan"
                    data-icon="✏️"
                    data-title="Konfirmasi Edit Pesanan"
                    data-message="Pesanan akan dipindahkan kembali ke keranjang.<br>Apakah Anda yakin ingin mengedit pesanan ini?"
                    data-confirm-text="Ya, Edit"
                    data-cancel-text="Batal">
                <i class="fas fa-edit"></i> Edit Pesanan
            </button>
        </form>
    @endif

    {{-- BATAL PESANAN — hanya saat pending --}}
    @if($order->status == 'pending')
        <button type="button"
                class="btn-remove btn-hapus"
                data-hapus="true"
                data-url="{{ route('orders.cancel', $order->id) }}"
                data-method="DELETE"
                data-title="Konfirmasi Pembatalan"
                data-message="Apakah Anda yakin ingin membatalkan pesanan ini?"
                data-warning="Pesanan yang dibatalkan tidak dapat dikembalikan!"
                data-confirm-text="Ya, Batalkan"
                data-cancel-text="Batal"
                data-item-name="Pesanan #{{ $order->id }}">
            <i class="fas fa-times"></i> Batal Pesanan
        </button>
    @endif

    {{-- HAPUS PESANAN — hanya saat rejected --}}
    @if($order->status == 'rejected')
        <button type="button"
                class="btn-delete btn-hapus"
                data-hapus="true"
                data-url="{{ route('orders.destroy', $order->id) }}"
                data-method="DELETE"
                data-title="Konfirmasi Hapus"
                data-message="Apakah Anda yakin ingin menghapus pesanan ini?"
                data-warning="Pesanan yang dihapus tidak dapat dikembalikan!"
                data-confirm-text="Ya, Hapus"
                data-cancel-text="Batal"
                data-item-name="Pesanan #{{ $order->id }}">
            <i class="fas fa-trash"></i> Hapus Pesanan
        </button>
    @endif

</div>
</div>
@endsection