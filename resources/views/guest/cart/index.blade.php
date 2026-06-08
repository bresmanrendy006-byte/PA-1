@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/cart.css') }}">
@endpush

@section('content')

<div class="cart-container">

    <div class="cart-header">
        <h1>Keranjang Belanja</h1>
        <p>Review pesanan Anda sebelum checkout</p>
    </div>

    {{-- FLASH MESSAGES --}}
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

    <div id="ajaxFlashMessage"></div>

    {{-- NOTIFIKASI PENDING ORDER --}}
    @if($pendingOrder)
        <div class="alert alert-warning">
            <i class="fas fa-clock"></i>
            <div>
                <strong>Pesanan kamu sedang menunggu konfirmasi!</strong>
                <p>Kamu tidak dapat checkout atau belanja sebelum pesanan sebelumnya diproses oleh karyawan.</p>
                <a href="{{ route('orders.show', $pendingOrder->id) }}" class="btn-lihat-pesanan">
                    <i class="fas fa-receipt"></i>
                    Lihat Pesanan #{{ $pendingOrder->id }}
                </a>
            </div>
        </div>
    @endif


    @if($carts->count() > 0)

    <div class="cart-table">
        <table>
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                @foreach($carts as $cart)
                <tr data-cart-id="{{ $cart->id }}">

                    <td>
                        <div class="menu-info">
                            @if($cart->menu->image)
                                <img src="{{ asset('storage/'.$cart->menu->image) }}"
                                     alt="{{ $cart->menu->name }}"
                                     class="menu-image">
                            @else
                                <div class="menu-image">
                                    <i class="fas fa-coffee" style="font-size:24px;color:#cd8c63;"></i>
                                </div>
                            @endif

                            <div class="menu-details">
                                <h3>{{ $cart->menu->name }}</h3>
                                @if($cart->notes)
                                    <p><small><i class="fas fa-pencil-alt"></i> {{ $cart->notes }}</small></p>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td>Rp {{ number_format($cart->menu->price, 0, ',', '.') }}</td>

                    <td>
                        {{-- Input quantity — auto update saat berubah via cart.js --}}
                        <input
                            type="number"
                            class="quantity-input"
                            value="{{ $cart->quantity }}"
                            min="1"
                            data-id="{{ $cart->id }}"
                            data-url="{{ route('cart.update', $cart->id) }}"
                        >
                    </td>

                    <td class="subtotal-{{ $cart->id }}">
                        Rp {{ number_format($cart->subtotal, 0, ',', '.') }}
                    </td>

                    <td>
                        <button
                            class="btn-hapus btn-remove"
                            data-url="{{ route('cart.remove', $cart->id) }}"
                            data-method="DELETE"
                            data-title="Hapus Item"
                            data-message="Yakin ingin menghapus item ini dari keranjang?"
                            data-item-name="{{ $cart->menu->name }}"
                            data-confirm-text="Ya, Hapus"
                            data-warning="Item akan dihapus dari keranjang kamu.">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>

                </tr>
                @endforeach

            </tbody>
        </table>
    </div>


    <div class="cart-summary">

        <h3>Ringkasan Belanja</h3>

        <div class="summary-item">
            <span>Total Item:</span>
            <span id="totalItems"><strong>{{ $totalItems }}</strong> item</span>
        </div>

        <div class="summary-total">
            <span>Total Pembayaran:</span>
            <span id="totalPrice">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>

        <p class="payment-info">
            Pembayaran dilakukan langsung di kasir (Cash Only)
        </p>

        <div class="cart-actions">

            <a href="{{ route('orders.index') }}" class="btn-orders">
                <i class="fas fa-receipt"></i> Pesanan Saya
            </a>

            {{-- Lanjut Belanja — nonaktif jika ada pending order --}}
            @if($pendingOrder)
                <button class="btn-continue btn-disabled" disabled
                        title="Selesaikan pesanan sebelumnya dulu">
                    <i class="fas fa-lock"></i> Lanjut Belanja
                </button>
            @else
                <a href="{{ route('guest.menu') }}" class="btn-continue">
                    <i class="fas fa-shopping-bag"></i> Lanjut Belanja
                </a>
            @endif

            {{-- Kosongkan Keranjang --}}
            <button
                class="btn-hapus btn-clear"
                data-url="{{ route('cart.clear') }}"
                data-method="DELETE"
                data-title="Kosongkan Keranjang"
                data-message="Yakin ingin mengosongkan semua item di keranjang?"
                data-warning="Semua item akan dihapus dari keranjang kamu."
                data-confirm-text="Ya, Kosongkan">
                <i class="fas fa-trash-alt"></i> Kosongkan Keranjang
            </button>

            {{-- Checkout — nonaktif jika ada pending order --}}
            @if($pendingOrder)
                <button class="btn-checkout btn-disabled" disabled
                        title="Selesaikan pesanan sebelumnya dulu">
                    <i class="fas fa-lock"></i> Checkout Tidak Tersedia
                </button>
            @else
                <form action="{{ route('orders.checkout') }}" method="POST" style="flex:1;">
                    @csrf
                    <button type="submit" class="btn-checkout">
                        <i class="fas fa-credit-card"></i> Checkout
                    </button>
                </form>
            @endif

        </div>
    </div>

    @else

    {{-- KERANJANG KOSONG --}}
    <div class="empty-cart">
        <i class="fas fa-shopping-cart"></i>
        <h3>Keranjang Belanja Kosong</h3>
        <p>Belum ada item di keranjang Anda. Yuk, pesan menu favorit Anda!</p>

        @if($pendingOrder)
            <a href="{{ route('orders.show', $pendingOrder->id) }}" class="btn-shop btn-pending">
                <i class="fas fa-clock"></i>
                Lihat Pesanan #{{ $pendingOrder->id }} yang Menunggu
            </a>
        @else
            <a href="{{ route('guest.menu') }}" class="btn-shop">
                <i class="fas fa-coffee"></i> Mulai Belanja
            </a>
        @endif
    </div>

    @endif

</div>

@endsection

@push('scripts')
<script src="{{ asset('plugins/js/cart.js') }}"></script>
@endpush