@extends('layouts.app')

@section('title', 'Kedanku Coffee - Home')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/home.css') }}">
@endpush

@section('content')
<section class="home-section">

    <div class="swiper home-swiper">
        <div class="swiper-wrapper">
            @forelse($images as $img)
                <div class="swiper-slide">
                    <img src="{{ asset('storage/' . $img->image) }}"
                         alt="Kedanku Coffee Balige - Foto {{ $loop->iteration }}">
                </div>
            @empty
                <div class="swiper-slide">
                    <img src="{{ asset('plugins/img/default-hero.jpg') }}"
                         alt="Kedanku Coffee - Balige">
                </div>
            @endforelse
        </div>

        <div class="home-overlay"></div>

        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-pagination"></div>
    </div>

    <div class="home-content">

        <div class="home-store-name">
            <h1>Kedanku <span>Coffee</span></h1>
            <div class="subtitle">
                <i class="fas fa-map-marker-alt"></i> BALIGE • Premium Coffee
            </div>
        </div>

        <div class="home-order-btn">
            {{--
                FIX: tag <a> tidak boleh dibuka di dalam @auth dan ditutup di luar.
                Tentukan href dulu, baru render tag <a> sekali.
            --}}
            @auth
                @php $orderUrl = route('guest.menu') @endphp
            @else
                @php $orderUrl = route('login') @endphp
            @endauth

            <a href="{{ $orderUrl }}" class="home-order-now-button">
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