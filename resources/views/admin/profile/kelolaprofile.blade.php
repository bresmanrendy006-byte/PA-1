@extends('layouts.admin')

@section('title', 'Kelola Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolaprofile.css') }}">
@endpush

@section('content')

<h2 class="page-title">Kelola Profile Kedanku Coffee</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="profile-card">

    @if($profile)
        {{-- HEADER: Foto & Nama --}}
        <div class="profile-header-box">
            <div class="img-circle-wrapper">
                <img src="{{ $profile->owner_photo ? asset('storage/'.$profile->owner_photo) : asset('images/default-avatar.png') }}" alt="Owner">
            </div>
            <div class="profile-main-info">
                <span class="info-label">Pemilik Kedai</span>
                <h3>{{ $profile->owner_name }}</h3>
                <p style="color: #888; margin-top: 5px;">
                    <i class="fas fa-map-marker-alt"></i> {{ $profile->location_name }}
                </p>
            </div>
        </div>

        {{-- GRID: Detail Informasi --}}
        <div class="profile-info-grid">
            <div class="info-box full-width">
                <span class="info-label">Deskripsi Pemilik</span>
                <div class="info-value">{{ $profile->owner_description }}</div>
            </div>

            <div class="info-box">
                <span class="info-label">Sejarah Kedai</span>
                <div class="info-value">{{ $profile->history }}</div>
            </div>

            <div class="info-box">
                <span class="info-label">Pengalaman Ngopi</span>
                <div class="info-value">{{ $profile->experience }}</div>
            </div>

            <div class="info-box">
                <span class="info-label">Koordinat Map (Latitude)</span>
                <div class="info-value">{{ $profile->latitude ?? '-' }}</div>
            </div>

            <div class="info-box">
                <span class="info-label">Koordinat Map (Longitude)</span>
                <div class="info-value">{{ $profile->longitude ?? '-' }}</div>
            </div>
        </div>

        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
            <a href="{{ route('admin.profile.form') }}" class="btn-primary-coffee">
                Edit Profile
            </a>
        </div>

    @else
        {{-- Tampilan jika data kosong --}}
        <div style="text-align: center; padding: 40px 0;">
            <p style="color: #999; margin-bottom: 20px;">Profile belum dikonfigurasi. Silakan tambah profile baru.</p>
            <a href="{{ route('admin.profile.form') }}" class="btn-primary-coffee">
                + Tambah Profile Baru
            </a>
        </div>
    @endif

</div>

@endsection