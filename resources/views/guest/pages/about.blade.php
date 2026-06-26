@extends('layouts.app')

@section('title', 'Kedanku Coffee - About')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/about.css') }}">
@endpush

@section('content')

<section class="about-hero">
    <div>
        <h1>Profile Kedanku Coffee</h1>
        <p>Premium Coffee Experience in Balige</p>
    </div>
</section>

<section class="about-main">
    <div class="about-container">

        @if($profile)

            {{-- STATS --}}
            <div class="about-stats">
                <div class="about-stat">
                    <div class="about-stat-num">2025</div>
                    <div class="about-stat-label">Berdiri sejak</div>
                </div>
                <div class="about-stat">
                    <div class="about-stat-num">20</div>
                    <div class="about-stat-label">Menu minuman</div>
                </div>
                <div class="about-stat">
                    <div class="about-stat-num">4.5★</div>
                    <div class="about-stat-label">Rating pelanggan</div>
                </div>
            </div>

            {{-- OWNER --}}
            <div class="about-owner">
                <div class="owner-image">
                    @if($profile->owner_photo)
                        <img src="{{ asset('storage/'.$profile->owner_photo) }}"
                             alt="Foto {{ $profile->owner_name }}">
                    @else
                        <div class="owner-avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                <div class="owner-info">
                    <span class="owner-badge">Pemilik</span>
                    <h2>{{ $profile->owner_name }}</h2>
                    <p>{{ $profile->owner_description }}</p>
                </div>
            </div>

            {{-- SEJARAH & PENGALAMAN --}}
            <div class="about-grid">
                <div class="about-card">
                    <div class="about-card-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>Sejarah Kedanku Coffee</h3>
                    <p>{{ $profile->history }}</p>
                </div>
                <div class="about-card">
                    <div class="about-card-icon">
                        <i class="fas fa-mug-hot"></i>
                    </div>
                    <h3>Pengalaman Ngopi</h3>
                    <p>{{ $profile->experience }}</p>
                </div>
            </div>

            {{-- LOKASI --}}
            <div class="about-location">
                <h3><i class="fas fa-map-pin"></i> Lokasi Kami</h3>
                <p class="location-addr">{{ $profile->location_name }}</p>

                @if($profile->latitude && $profile->longitude)
                    <iframe
                        title="Lokasi Kedanku Coffee"
                        src="https://maps.google.com/maps?q={{ $profile->latitude }},{{ $profile->longitude }}&z=15&output=embed"
                        loading="lazy">
                    </iframe>
                @endif
            </div>

        @else
            <div class="about-empty">
                <i class="fas fa-store-slash"></i>
                <p>Informasi kedai belum tersedia.</p>
            </div>
        @endif

    </div>
</section>

@endsection