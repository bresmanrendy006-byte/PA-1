@extends('layouts.app')

@section('title', 'Kedanku Coffee - Home')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/home.css') }}">
@endpush

@section('content')
<section class="home-section">
    <!-- Swiper Carousel -->
    <div class="swiper home-swiper">
    <div class="swiper-wrapper">

        @forelse($images as $img)
            <div class="swiper-slide">
                <img src="{{ asset('storage/'.$img->image) }}" 
                    alt="{{ $img->caption ?? 'Kedanku Coffee - Balige' }}">
            </div>
        @empty
            <div class="swiper-slide">
                <img src="{{ asset('plugins/img/default-hero.jpg') }}" 
                    alt="Kedanku Coffee - Balige">
            </div>
        @endforelse
</div>
        
        <!-- Overlay -->
        <div class="home-overlay"></div>
        
        <!-- Navigation -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        
        <!-- Pagination -->
        <div class="swiper-pagination"></div>
    </div>

    <!-- Content -->
    <div class="home-content">
        <!-- Nama Toko di Kiri -->
        <div class="home-store-name">
            <h1>
                Kedanku <span>Coffee</span>
            </h1>
            <div class="subtitle">
                <i class="fas fa-map-marker-alt"></i> BALIGE • Premium Coffee
            </div>
        </div>

        <!-- Tombol Pesan di Kanan -->
        <div class="home-order-btn">
            @auth
                <a href="{{ route('guest.menu') }}" class="home-order-now-button">
            @else
                <a href="{{ route('login') }}" class="home-order-now-button">
            @endauth
                <i class="fas fa-mug-hot home-coffee-icon"></i>
                <span>Pesan Sekarang</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('plugins/js/home.js') }}"></script>
@endpush