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
            <!-- Slide 1 -->
            <div class="swiper-slide">
                <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Coffee Shop Interior">
            </div>
            <!-- Slide 2 -->
            <div class="swiper-slide">
                <img src="https://images.unsplash.com/photo-1525193612562-0ec53b0e5d7c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Coffee Bar">
            </div>
            <!-- Slide 3 -->
            <div class="swiper-slide">
                <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?ixlib=rb-4.0.3&auto=format&fit=crop&w=2047&q=80" alt="Coffee Shop Outdoor">
            </div>
            <!-- Slide 4 -->
            <div class="swiper-slide">
                <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Coffee Shop Night">
            </div>
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
            <a href="#" class="home-order-now-button">
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