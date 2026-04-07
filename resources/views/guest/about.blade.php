@extends('layouts.app')

@section('title', 'Kedanku Coffee - About')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/about.css') }}">
@endpush

@section('content')
<section class="about-hero">
    <div class="about-hero-content">
        <h1>Tentang Kedanku Coffee</h1>
        <p>Premium Coffee Experience in Balige</p>
    </div>
</section>

<section class="about-main">
    <div class="about-container">

        @if($profile)

            <!-- OWNER -->
            <div class="about-owner">

                <div class="owner-image">
                    @if($profile->owner_photo)
                        <img src="{{ asset('storage/'.$profile->owner_photo) }}" 
                             alt="Foto {{ $profile->owner_name }}">
                    @endif
                </div>

                <div class="owner-info">
                    <h2>Pemilik Kedanku Coffee</h2>
                    <h3>{{ $profile->owner_name }}</h3>
                    <p>{{ $profile->owner_description }}</p>
                </div>

            </div>

            <!-- SEJARAH -->
            <div class="about-block">
                <h2>Sejarah Kedanku Coffee</h2>
                <p>{{ $profile->history }}</p>
            </div>

            <!-- NARASI -->
            <div class="about-block">
                <h2>Pengalaman Ngopi</h2>
                <p>{{ $profile->experience }}</p>
            </div>

            <!-- LOKASI -->
            <div class="about-block location-block">
                <h2>Lokasi Kami</h2>
                <p>📍 {{ $profile->location_name }}</p>

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
                <p>Informasi kedai belum tersedia.</p>
            </div>

        @endif

    </div>
</section>
@endsection