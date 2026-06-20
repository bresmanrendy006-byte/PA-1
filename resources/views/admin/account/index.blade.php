@extends('layouts.admin')

@section('title', 'Profile Akun')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/user-profile.css') }}">
@endpush

@section('content')

<div class="profile-container">
    <div class="profile-topbar">
        <h1 class="profile-title">Profile Akun</h1>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
    <div class="profile-wrapper">

        {{-- PROFILE INFO --}}
        <div class="profile-card">

            <div class="profile-avatar-big">
                {{ strtoupper(substr($user->username, 0, 1)) }}
            </div>

            <h2 class="profile-username">{{ $user->username }}</h2>
            <p class="profile-email">{{ $user->email }}</p>

            <span class="profile-role-badge {{ $user->role }}">
                {{ ucfirst($user->role) }}
            </span>

        </div>

        {{-- GANTI PASSWORD --}}
        <div class="password-card">

            <h3><i class="fas fa-lock"></i> Ganti Password</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="formGantiPasswordAdmin"
                  action="{{ route('admin.userProfile.changePassword') }}"
                  method="POST">
                @csrf

                <div class="form-group">
                    <label>Password Saat Ini</label>
                    <input type="password" name="current_password"
                           placeholder="Masukkan password saat ini" required>
                </div>

                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password"
                           placeholder="Minimal 6 karakter" required>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                           placeholder="Ulangi password baru" required>
                </div>

                <button type="button"
                    class="btn-confirm-popup btn-save"
                    data-form="formGantiPasswordAdmin"
                    data-icon="🔐"
                    data-title="Konfirmasi Ganti Password"
                    data-message="Apakah Anda yakin ingin mengubah password akun Anda?<br>Pastikan Anda mengingat password baru."
                    data-confirm-text="Ya, Ubah"
                    data-cancel-text="Batal">
                    <i class="fas fa-save"></i> Simpan Password
                </button>

            </form>

        </div>

    </div>

    {{-- Tombol ke Halaman Customer --}}
    <div class="btn-home-wrapper">
        <a href="{{ route('home') }}" class="btn-home">
            <i class="fas fa-home"></i> Ke Halaman Utama
        </a>
    </div>

</div>

@endsection