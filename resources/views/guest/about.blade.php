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
        <div class="about-left">
            <h2>Cerita Kami</h2>
            <p>
                Kedanku Coffee hadir untuk menghadirkan pengalaman ngopi premium
                dengan suasana nyaman dan modern di Balige.
            </p>
            <p>
                Kami menggunakan biji kopi pilihan dengan proses roasting terbaik
                untuk memastikan setiap cangkir memiliki cita rasa yang konsisten.
            </p>

            <div class="about-stats">
                <div class="stat-item">
                    <h3>5+</h3>
                    <span>Tahun Pengalaman</span>
                </div>
                <div class="stat-item">
                    <h3>10K+</h3>
                    <span>Pelanggan Puas</span>
                </div>
                <div class="stat-item">
                    <h3>50+</h3>
                    <span>Menu Variasi</span>
                </div>
            </div>
        </div>

        <div class="about-right">
            <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1000&q=80" alt="About Coffee">
        </div>
    </div>
</section>
@endsection