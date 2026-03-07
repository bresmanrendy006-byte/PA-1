@extends('layouts.app')

@section('title', 'Kedanku Coffee - Testimoni')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/testimoni.css') }}">
@endpush

@section('content')

<section class="testimoni-hero">
    <div class="testimoni-hero-content">
        <h1>Testimoni Pelanggan</h1>
        <p>Apa kata mereka tentang Kedanku Coffee?</p>
    </div>
</section>

<section class="testimoni-section">
    <div class="testimoni-container">
        <div class="testimoni-grid">

            <div class="testimoni-card">
                <p>"Tempatnya nyaman banget dan kopinya juara!"</p>
                <div class="customer">
                    <strong>Andi</strong>
                    <span>Mahasiswa</span>
                </div>
            </div>

            <div class="testimoni-card">
                <p>"Latte nya creamy dan nggak bikin eneg. Recommended!"</p>
                <div class="customer">
                    <strong>Siska</strong>
                    <span>Pelanggan Setia</span>
                </div>
            </div>

            <div class="testimoni-card">
                <p>"Suasana malamnya estetik banget buat nongkrong."</p>
                <div class="customer">
                    <strong>Rizky</strong>
                    <span>Freelancer</span>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection