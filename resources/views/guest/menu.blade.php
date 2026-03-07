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

<!-- MENU GRID -->
<section class="menu-section">
    <div class="menu-container">

        <div class="menu-grid">

            <!-- Card 1 -->
            <div class="menu-card">
                <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80">
                <div class="menu-info">
                    <h3>Espresso</h3>
                    <p>Kopi hitam pekat dengan rasa bold dan aroma kuat.</p>
                    <div class="menu-bottom">
                        <span class="price">Rp 18.000</span>
                        <button class="add-btn">+ Keranjang</button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="menu-card">
                <img src="https://images.unsplash.com/photo-1498804103079-a6351b050096?auto=format&fit=crop&w=800&q=80">
                <div class="menu-info">
                    <h3>Cappuccino</h3>
                    <p>Perpaduan espresso, steamed milk & foam lembut.</p>
                    <div class="menu-bottom">
                        <span class="price">Rp 25.000</span>
                        <button class="add-btn">+ Keranjang</button>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="menu-card">
                <img src="https://images.unsplash.com/photo-1523942839745-7848d79f32d8?auto=format&fit=crop&w=800&q=80">
                <div class="menu-info">
                    <h3>Caramel Latte</h3>
                    <p>Latte creamy dengan sentuhan manis caramel.</p>
                    <div class="menu-bottom">
                        <span class="price">Rp 28.000</span>
                        <button class="add-btn">+ Keranjang</button>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="menu-card">
                <img src="https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=800&q=80">
                <div class="menu-info">
                    <h3>Matcha Latte</h3>
                    <p>Matcha premium dengan susu creamy lembut.</p>
                    <div class="menu-bottom">
                        <span class="price">Rp 27.000</span>
                        <button class="add-btn">+ Keranjang</button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

@endsection