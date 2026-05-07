@extends('layouts.admin')

@section('title', $profile ? 'Edit Profile' : 'Tambah Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolaprofile.css') }}">
@endpush

@section('content')

<h2 class="page-title">
    {{ $profile ? 'Edit Profile' : 'Tambah Profile' }}
</h2>

{{-- ERROR VALIDASI --}}
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="profile-card">

    {{-- FORM UTAMA --}}
    <form action="{{ route('admin.profile.save') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- FOTO PEMILIK --}}
        <div class="form-group">
            <label>Foto Pemilik</label>

            @if($profile && $profile->owner_photo)
                <div class="current-img">
                    <img src="{{ asset('storage/'.$profile->owner_photo) }}"
                         style="width:120px;border-radius:8px;">
                    <small>Foto saat ini. Upload baru untuk mengganti.</small>
                </div>
            @endif

            <input type="file" name="owner_photo"
                   accept="image/jpg,image/jpeg,image/png,image/webp">
        </div>

        {{-- NAMA PEMILIK --}}
        <div class="form-group">
            <label>Nama Pemilik <span class="required">*</span></label>
            <input type="text" name="owner_name"
                   value="{{ old('owner_name', $profile->owner_name ?? '') }}"
                   required>
        </div>

        {{-- DESKRIPSI --}}
        <div class="form-group">
            <label>Deskripsi Pemilik <span class="required">*</span></label>
            <textarea name="owner_description" required>{{ old('owner_description', $profile->owner_description ?? '') }}</textarea>
        </div>

        {{-- SEJARAH --}}
        <div class="form-group">
            <label>Sejarah Kedai <span class="required">*</span></label>
            <textarea name="history" required>{{ old('history', $profile->history ?? '') }}</textarea>
        </div>

        {{-- PENGALAMAN --}}
        <div class="form-group">
            <label>Pengalaman Ngopi <span class="required">*</span></label>
            <textarea name="experience" required>{{ old('experience', $profile->experience ?? '') }}</textarea>
        </div>

        {{-- LOKASI --}}
        <div class="form-group">
            <label>Nama Lokasi <span class="required">*</span></label>
            <input type="text" name="location_name"
                   value="{{ old('location_name', $profile->location_name ?? '') }}"
                   required>
        </div>

        {{-- LATITUDE & LONGITUDE --}}
        <div class="location-row">
            <div class="form-group">
                <label>Latitude</label>
                <input type="text" name="latitude"
                       value="{{ old('latitude', $profile->latitude ?? '') }}"
                       placeholder="Contoh: 2.1234567">
            </div>
            <div class="form-group">
                <label>Longitude</label>
                <input type="text" name="longitude"
                       value="{{ old('longitude', $profile->longitude ?? '') }}"
                       placeholder="Contoh: 99.1234567">
            </div>
        </div>

        <div style="display:flex;gap:10px;margin-top:10px;">
            <button type="submit" class="save-btn">Simpan</button>
            <a href="{{ route('admin.profile.index') }}" class="cancel-btn">Kembali</a>
        </div>

    </form>



    {{-- HAPUS PROFILE — pakai popuphapus.js --}}
    @if($profile)
        <div style="margin-top:15px;">
            <button
                class="btn-hapus delete-btn"
                data-url="{{ route('admin.profile.destroy') }}"
                data-method="DELETE"
                data-title="Hapus Profile"
                data-message="Yakin ingin menghapus seluruh profile?"
                data-warning="Semua data profile akan hilang dan tidak dapat dikembalikan!"
                data-confirm-text="Ya, Hapus"
                data-item-name="Profile Kedanku Coffee">
                Hapus Profile
            </button>
        </div>
    @endif

</div>

@endsection