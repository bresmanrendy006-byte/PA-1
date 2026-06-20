@extends('layouts.app')

@section('title', 'Profile Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/user-profile.css') }}">
@endpush

@section('content')

<div class="profile-page">
    <div class="profile-wrapper">

        {{-- PROFILE CARD --}}
        <div class="profile-card">

            <div class="profile-avatar-big">
                {{-- strtoupper di Blade, bukan raw PHP echo --}}
                {{ strtoupper(mb_substr($user->username, 0, 1)) }}
            </div>

            <h2 class="profile-username">{{ $user->username }}</h2>
            <p class="profile-email">{{ $user->email }}</p>

            <span class="profile-role-badge">
                {{ ucfirst($user->role) }}
            </span>

        </div>

        {{-- GANTI PASSWORD --}}
        <div class="password-card">

            <h3><i class="fas fa-lock"></i> Ganti Password</h3>

            {{-- Flash success --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error per-field, bukan global dump --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 
                novalidate: matikan HTML5 validation bawaan browser,
                biar error handling sepenuhnya lewat server (konsisten).
                Kalau mau pakai client-side, hapus novalidate & aktifkan minlength.
            --}}
            <form id="formGantiPassword"
                  action="{{ route('profile.changePassword') }}"
                  method="POST"
                  novalidate>
                @csrf

                {{-- Password Saat Ini --}}
                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        autocomplete="current-password"
                        placeholder="Masukkan password saat ini"
                        required>
                    {{-- Error spesifik field ini --}}
                    @error('current_password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password Baru --}}
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                        minlength="8"
                        maxlength="100"
                        required>
                    @error('password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="Ulangi password baru"
                        minlength="8"
                        maxlength="100"
                        required>
                    {{-- 
                        'password_confirmation' tidak punya error sendiri dari Laravel,
                        errornya muncul di field 'password' dengan pesan "tidak cocok".
                        Jadi tidak perlu @error di sini.
                    --}}
                </div>

                <button
                    type="button"
                    class="btn-confirm-popup btn-save"
                    data-form="formGantiPassword"
                    data-icon="🔐"
                    data-title="Konfirmasi Ganti Password"
                    data-message="Apakah Anda yakin ingin mengubah password?<br>Pastikan Anda mengingat password baru."
                    data-confirm-text="Ya, Ubah"
                    data-cancel-text="Batal">
                    <i class="fas fa-save"></i> Simpan Password
                </button>

            </form>

        </div>

    </div>
</div>

@endsection