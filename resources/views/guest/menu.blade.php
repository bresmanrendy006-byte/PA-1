@extends('layouts.app')

@section('title', 'Kedanku Coffee - Menu')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/menu.css') }}">
@endpush

@section('content')

<!-- HERO -->
<section class="menu-hero">
    <div class="menu-hero-content">
        <h1>Menu Minuman</h1>
        <p>Nikmati pilihan kopi premium & minuman spesial kami</p>
    </div>
</section>

<section class="menu-section">
<div class="menu-container">

<div class="menu-grid">

@foreach($menus as $menu)

<div class="menu-card">

    {{-- IMAGE --}}
    @if($menu->image)
        <img src="{{ asset('storage/'.$menu->image) }}">
    @endif

    <div class="menu-info">

        <h3>{{ $menu->name }}</h3>

        <p>{{ $menu->description }}</p>

        <div class="menu-bottom">
            <span class="price">
                Rp {{ number_format($menu->price,0,',','.') }}
            </span>
        </div>

        {{-- ADD TO CART --}}
        @auth
        <form action="{{ route('cart.add',$menu->id) }}" method="POST" class="add-cart-form">
            @csrf

            <div class="cart-inputs">

                <input type="number"
                       name="quantity"
                       value="1"
                       min="1"
                       class="qty-input">

                <input type="text"
                       name="notes"
                       placeholder="Catatan (optional)"
                       class="notes-input">

            </div>

            <button type="submit" class="btn-add-cart">
                <i class="fas fa-shopping-cart"></i>
                Tambah ke Keranjang
            </button>

        </form>
        @else
            <a href="{{ route('login') }}" class="btn-add-cart">
                Login untuk Pesan
            </a>
        @endauth

    </div>

</div>

@endforeach

</div>
</div>
</section>

@endsection