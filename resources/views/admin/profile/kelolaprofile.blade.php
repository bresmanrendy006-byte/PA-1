@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolaprofile.css') }}">
@endpush

@section('content')

<h2>Kelola Profile Kedanku Coffee</h2>

@if(session('success'))
<p style="color:green">{{ session('success') }}</p>
@endif

<div class="profile-card">

@if($profile)

<h3>Foto Pemilik</h3>

@if($profile->owner_photo)
<img src="{{ asset('storage/'.$profile->owner_photo) }}" width="150">
@endif

<hr>

<h3>Nama Pemilik</h3>
<p>{{ $profile->owner_name }}</p>

<h3>Deskripsi Pemilik</h3>
<p>{{ $profile->owner_description }}</p>

<hr>

<h3>Sejarah Kedai</h3>
<p>{{ $profile->history }}</p>

<hr>

<h3>Pengalaman Ngopi</h3>
<p>{{ $profile->experience }}</p>

<hr>

<h3>Lokasi</h3>
<p>{{ $profile->location_name }}</p>
<p>Latitude : {{ $profile->latitude }}</p>
<p>Longitude : {{ $profile->longitude }}</p>

<br>

<a href="{{ route('admin.profile.form') }}" class="btn btn-primary">
Edit Profile
</a>

@else

<p>Profile belum ada. Silakan tambah profile baru.</p>

<a href="{{ route('admin.profile.form') }}" class="btn btn-success">
Tambah Profile
</a>

@endif

</div>

@endsection