@extends('layouts.app')

@section('title','Kedanku Coffee - Menu')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/menu.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/css/notes-dropdown.css') }}">
@endpush

@section('content')

{{-- HERO --}}
<section class="menu-hero">
    <div class="menu-hero-content">
        <h1>Menu Minuman</h1>
        <p>Nikmati pilihan kopi premium & minuman spesial kami</p>
    </div>
</section>

{{-- MENU SECTION --}}
<section class="menu-section">
    <div class="menu-container">
        <div class="menu-grid">

            @forelse($menus as $menu)
            <div class="menu-card">

                {{-- IMAGE --}}
                <img
                    src="{{ $menu->image ? asset('storage/'.$menu->image) : asset('plugins/img/default-menu.jpg') }}"
                    alt="{{ $menu->name }}"
                >

                <div class="menu-info">

                    <h3>{{ $menu->name }}</h3>
                    <p>{{ $menu->description }}</p>

                    <div class="menu-bottom">
                        <span class="price">{{ $menu->formattedPrice }}</span>
                    </div>

                    {{-- ADD TO CART --}}
                    @auth

                        @if($menu->stock && $menu->stock->quantity > 0)

                            <form
                                action="{{ route('cart.add', $menu->id) }}"
                                method="POST"
                                class="add-cart-form"
                            >
                                @csrf

                                <div class="cart-inputs">
                                    <input
                                        type="number"
                                        name="quantity"
                                        value="1"
                                        min="1"
                                        max="{{ $menu->stock->quantity }}"
                                        class="qty-input"
                                    >
                                </div>

                                {{-- CATATAN DROPDOWN MULTIPLE CHOICE --}}
                                <div class="notes-dropdown" data-id="{{ $menu->id }}">

                                    <button type="button" class="notes-toggle">
                                        <i class="fas fa-sliders-h"></i>
                                        <span class="notes-toggle-text">Pilih Catatan</span>
                                        <i class="fas fa-chevron-down notes-chevron"></i>
                                    </button>

                                    <div class="notes-options">
                                        @foreach([
                                            'Less Ice'    => 'fas fa-snowflake',
                                            'Extra Ice'   => 'fas fa-snowflake',
                                            'Less Sugar'  => 'fas fa-cube',
                                            'Extra Sugar' => 'fas fa-cube',
                                            'Hot'         => 'fas fa-fire',
                                            'Cold'        => 'fas fa-glass-whiskey',
                                        ] as $option => $icon)
                                        <label class="notes-option">
                                            <input
                                                type="checkbox"
                                                class="notes-checkbox"
                                                value="{{ $option }}"
                                            >
                                            <i class="{{ $icon }}"></i>
                                            <span>{{ $option }}</span>
                                        </label>
                                        @endforeach
                                    </div>

                                    {{-- Hidden input yang akan diisi oleh JS --}}
                                    <input type="hidden" name="notes" class="notes-value">

                                </div>

                                <button type="submit" class="btn-add-cart">
                                    <i class="fas fa-shopping-cart"></i>
                                    Tambah ke Keranjang
                                </button>

                            </form>

                        @else

                            <button class="btn-add-cart btn-stok-habis" disabled>
                                <i class="fas fa-times-circle"></i>
                                Stok Sedang Habis
                            </button>

                        @endif

                    @else

                        <a href="{{ route('login') }}" class="btn-add-cart">
                            <i class="fas fa-sign-in-alt"></i>
                            Login untuk Pesan
                        </a>

                    @endauth

                </div>
            </div>

            @empty

            <div class="menu-empty">
                <i class="fas fa-mug-hot"></i>
                <p>Menu belum tersedia. Silakan cek kembali nanti.</p>
            </div>

            @endforelse

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="{{ asset('plugins/js/menu.js') }}"></script>
@endpush