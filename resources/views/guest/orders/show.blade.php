@extends('layouts.app')

@section('title','Detail Pesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/order-detail.css') }}">
@endpush

@section('content')

<div class="order-container">

    <h1 class="order-title">Detail Pesanan</h1>

    @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    {{-- ORDER INFO --}}
    <div class="order-info">
        <p><strong>ID Pesanan :</strong> #{{ $order->id }}</p>
        <p>
            <strong>Status :</strong>
            @if($order->status == 'pending')
                <span class="status pending">Menunggu Konfirmasi</span>
            @elseif($order->status == 'confirmed')
                <span class="status confirmed">Pesanan Dikonfirmasi</span>
            @elseif($order->status == 'rejected')
                <span class="status rejected">Pesanan Ditolak</span>
            @endif
        </p>
    </div>

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
                        <td>Rp {{ number_format($item->price,0,',','.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->price * $item->quantity,0,',','.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- TOTAL --}}
    <div class="order-total">
        <h3>
            Total Pembayaran
            <span>Rp {{ number_format($order->total_price,0,',','.') }}</span>
        </h3>
    </div>

    {{-- ACTIONS --}}
    <div class="order-actions">
        <a href="{{ route('orders.index') }}" class="btn-back">Kembali</a>

        {{-- BATAL PESANAN --}}
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
                Batal Pesanan
            </button>
        @endif

        {{-- HAPUS PESANAN --}}
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
                Hapus Pesanan
            </button>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('plugins/js/popuphapus.js') }}"></script>
@endpush