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

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="profile-card">

    @if($profile)

        {{-- FOTO --}}
        @if($profile->owner_photo)
            <div class="owner-preview">
                <img src="{{ asset('storage/'.$profile->owner_photo) }}" width="150" style="border-radius:10px;">
            </div>
            <br>
        @endif

        <table class="profile-table">
            <tr>
                <th>Nama Pemilik</th>
                <td>{{ $profile->owner_name }}</td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{{ $profile->owner_description }}</td>
            </tr>
            <tr>
                <th>Sejarah Kedai</th>
                <td>{{ $profile->history }}</td>
            </tr>
            <tr>
                <th>Pengalaman Ngopi</th>
                <td>{{ $profile->experience }}</td>
            </tr>
            <tr>
                <th>Lokasi</th>
                <td>{{ $profile->location_name }}</td>
            </tr>
            <tr>
                <th>Latitude</th>
                <td>{{ $profile->latitude }}</td>
            </tr>
            <tr>
                <th>Longitude</th>
                <td>{{ $profile->longitude }}</td>
            </tr>
        </table>

        <br>

        <a href="{{ route('admin.profile.form') }}" class="save-btn">
            Edit Profile
        </a>

    @else

        <p>Profile belum ada. Silakan tambah profile baru.</p>

        <a href="{{ route('admin.profile.form') }}" class="save-btn">
            Tambah Profile
        </a>

    @endif

</div>

@endsection