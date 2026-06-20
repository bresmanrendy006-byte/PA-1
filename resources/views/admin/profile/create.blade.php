@extends('layouts.admin')

@section('title', $profile ? 'Edit Profile' : 'Tambah Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolaprofile.css') }}">
@endpush

@section('content')

<h2 class="page-title">{{ $profile ? 'Edit Profile' : 'Tambah Profile' }}</h2>

@if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin:0;">
            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
@endif

<div class="profile-card">

    <form action="{{ route('admin.profile.save') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- SECTION 1: IDENTITAS -->
        <div class="form-section-header">Informasi Dasar</div>
        
        <div class="grid-2-cols">
            <div class="form-group">
                <label>Nama Pemilik <span style="color:red">*</span></label>
                <input
                    type="text"
                    name="owner_name"
                    class="form-control-custom"
                    value="{{ old('owner_name', $profile->owner_name ?? '') }}"
                    pattern="[A-Za-z\s]+"
                    title="Nama pemilik hanya boleh berisi huruf"
                    required
                    placeholder="Contoh: John Doe">
            </div>

            <div class="form-group">
                <label>Foto Pemilik</label>
                <input type="file" name="owner_photo" class="form-control-custom" accept="image/*">
                @if($profile && $profile->owner_photo)
                    <small style="color: #28a745;">Sudah ada foto. Upload lagi untuk mengganti.</small>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi Pemilik <span style="color:red">*</span></label>
            <textarea name="owner_description" class="form-control-custom" rows="3" required placeholder="Ceritakan singkat tentang Anda...">{{ old('owner_description', $profile->owner_description ?? '') }}</textarea>
        </div>

        <!-- SECTION 2: CERITA KEDAI -->
        <div class="form-section-header">Cerita Kedai</div>

        <div class="form-group">
            <label>Sejarah Kedai <span style="color:red">*</span></label>
            <textarea name="history" class="form-control-custom" rows="4" required placeholder="Bagaimana perjalanan Kedanku Coffee dimulai?">{{ old('history', $profile->history ?? '') }}</textarea>
        </div>

        <div class="form-group">
            <label>Pengalaman Ngopi <span style="color:red">*</span></label>
            <textarea name="experience" class="form-control-custom" rows="4" required placeholder="Apa filosofi kopi menurut Anda?">{{ old('experience', $profile->experience ?? '') }}</textarea>
        </div>

        <!-- SECTION 3: LOKASI -->
        <div class="form-section-header">Lokasi & Map</div>

        <div class="form-group">
            <label>Nama Lokasi / Alamat <span style="color:red">*</span></label>
            <input
                type="text"
                name="location_name"
                class="form-control-custom"
                value="{{ old('location_name', $profile->location_name ?? '') }}"
                required
                placeholder="Jl. Raya Coffee No. 123">
        </div>

        <div class="grid-2-cols">
            <div class="form-group">
                <label>Latitude</label>
                <input type="text" name="latitude" class="form-control-custom" 
                       value="{{ old('latitude', $profile->latitude ?? '') }}" placeholder="Contoh: 2.123456">
            </div>
            <div class="form-group">
                <label>Longitude</label>
                <input type="text" name="longitude" class="form-control-custom" 
                       value="{{ old('longitude', $profile->longitude ?? '') }}" placeholder="Contoh: 99.123456">
            </div>
        </div>

        <!-- BUTTONS -->
        <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <button type="submit" class="btn-primary-coffee">Simpan Data</button>
                <a href="{{ route('admin.profile.index') }}" class="btn-link-cancel">Kembali</a>
            </div>

            @if($profile)
                <button type="button" class="btn-outline-danger btn-hapus"
                    data-url="{{ route('admin.profile.destroy') }}"
                    data-method="DELETE"
                    data-title="Hapus Profile"
                    data-message="Apakah Anda yakin ingin menghapus seluruh data profile ini?"
                    data-item-name="Profil Kedai">
                    Hapus Profile
                </button>
            @endif
        </div>

    </form>
</div>

@endsection