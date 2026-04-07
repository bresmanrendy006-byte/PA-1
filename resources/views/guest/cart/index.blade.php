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
<p>
<small>
<i class="fas fa-pencil-alt"></i>
{{ $cart->notes }}
</small>
</p>
@endif

</div>

</div>
</td>


<td>
Rp {{ number_format($cart->menu->price,0,',','.') }}
</td>


<td>

<form action="{{ route('cart.update',$cart->id) }}"
class="update-form"
method="POST"
data-id="{{ $cart->id }}">

@csrf
@method('PUT')

<input type="number"
name="quantity"
value="{{ $cart->quantity }}"
min="1"
class="quantity-input"
data-id="{{ $cart->id }}">

<button type="submit" class="btn-update">
<i class="fas fa-sync-alt"></i>
Update
</button>

</form>

</td>


<td class="subtotal-{{ $cart->id }}">
Rp {{ number_format($cart->subtotal,0,',','.') }}
</td>


<td>

<form action="{{ route('cart.remove',$cart->id) }}"
class="remove-form"
method="POST"
data-id="{{ $cart->id }}">

@csrf
@method('DELETE')

<button type="submit" class="btn-remove">
<i class="fas fa-trash"></i>
Hapus
</button>

</form>

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
<span><strong>{{ $totalItems }}</strong> item</span>
</div>


<div class="summary-total">
<span>Total Pembayaran:</span>
<span>Rp {{ number_format($total,0,',','.') }}</span>
</div>


<p class="payment-info">
Pembayaran dilakukan langsung di kasir (Cash Only)
</p>


<div class="cart-actions">

<a href="{{ route('orders.index') }}" class="btn-orders">
<i class="fas fa-receipt"></i>
Pesanan Saya
</a>


<a href="{{ route('guest.menu') }}" class="btn-continue">
<i class="fas fa-shopping-bag"></i>
Lanjut Belanja
</a>


<form action="{{ route('cart.clear') }}" method="POST" style="flex:1;">

@csrf
@method('DELETE')

<button type="submit"
class="btn-clear"
onclick="return confirm('Yakin ingin mengosongkan keranjang?')">

<i class="fas fa-trash-alt"></i>
Kosongkan Keranjang

</button>

</form>


<form action="{{ route('orders.checkout') }}" method="POST" style="flex:1;">

@csrf

<button type="submit" class="btn-checkout">
<i class="fas fa-credit-card"></i>
Checkout
</button>

</form>

</div>

</div>

@else


<div class="empty-cart">

<i class="fas fa-shopping-cart"></i>

<h3>Keranjang Belanja Kosong</h3>

<p>
Belum ada item di keranjang Anda.
Yuk, pesan menu favorit Anda!
</p>

<a href="{{ route('guest.menu') }}" class="btn-shop">
<i class="fas fa-coffee"></i>
Mulai Belanja
</a>

</div>


@endif

</div>

@endsection


@push('scripts')
<script src="{{ asset('plugins/js/cart.js') }}"></script>
@endpush