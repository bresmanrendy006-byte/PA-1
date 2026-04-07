@extends('layouts.admin')

@section('title','Tambah Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolaprofile.css') }}">
@endpush

@section('content')

<h2 class="page-title">
{{ $profile ? 'Edit Profile' : 'Tambah Profile' }}
</h2>

<div class="profile-card">

<form action="{{ route('admin.profile.save') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="form-group">
<label>Foto Pemilik</label>
<input type="file" name="owner_photo">

@if($profile && $profile->owner_photo)
<div class="owner-preview">
<img src="{{ asset('storage/'.$profile->owner_photo) }}">
<form action="{{ route('admin.profile.deletePhoto') }}" method="POST">
    @csrf
    <button type="submit" onclick="return confirm('Yakin hapus foto?')">
        Hapus Foto
    </button>
</form>
</div>
@endif
</div>

<div class="form-group">
<label>Nama Pemilik</label>
<input type="text" name="owner_name" value="{{ $profile->owner_name ?? '' }}">
</div>

<div class="form-group">
<label>Deskripsi Pemilik</label>
<textarea name="owner_description">{{ $profile->owner_description ?? '' }}</textarea>
</div>

<div class="form-group">
<label>Sejarah Kedai</label>
<textarea name="history">{{ $profile->history ?? '' }}</textarea>
</div>

<div class="form-group">
<label>Pengalaman Ngopi</label>
<textarea name="experience">{{ $profile->experience ?? '' }}</textarea>
</div>

<div class="form-group">
<label>Nama Lokasi</label>
<input type="text" name="location_name" value="{{ $profile->location_name ?? '' }}">
</div>

<div class="location-row">

<div class="form-group">
<label>Latitude</label>
<input type="text" name="latitude" value="{{ $profile->latitude ?? '' }}">
</div>

<div class="form-group">
<label>Longitude</label>
<input type="text" name="longitude" value="{{ $profile->longitude ?? '' }}">
</div>

</div>

<button type="submit" class="save-btn">
Simpan
</button>

<a href="{{ route('admin.profile.index') }}" class="cancel-btn">
Kembali
</a>

</form>

<!-- TOMBOL HAPUS PROFILE - HANYA MUNCUL JIKA PROFILE ADA -->
@if($profile)
<form action="{{ route('admin.profile.destroy') }}" method="POST" style="margin-top:15px;">
@csrf
@method('DELETE')

<button type="button" class="delete-btn" style="background-color: #dc3545;" onclick="bukaPopup(this.form)">
Hapus Profile
</button>

</form>
@endif

</div>

<!-- POPUP KONFIRMASI HAPUS -->
<div id="popupHapus" class="popup-overlay">
    <div class="popup-box">
        <h3>⚠️ Konfirmasi Hapus</h3>
        <p>Apakah Anda yakin ingin menghapus profile?<br>Semua data akan hilang dan tidak dapat dikembalikan!</p>
        <div class="popup-btn">
            <button class="btn-confirm" id="btnConfirmHapus">Ya, Hapus</button>
            <button class="btn-cancel" onclick="tutupPopup()">Batal</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('plugins/js/popupdelete.js') }}"></script>
@endpush