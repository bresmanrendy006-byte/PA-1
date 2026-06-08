@extends('layouts.admin')

@section('title', 'Profile Akun')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/user-profile.css') }}">
@endpush

@section('content')

<div class="profile-container">

    <h1 class="profile-title">Profile Akun</h1>

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

            <form action="{{ route('admin.userProfile.changePassword') }}" method="POST">
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

                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Simpan Password
                </button>

            </form>

        </div>

    </div>

</div>

@endsection