@extends('layouts.app')

@section('title', 'Login - Kedanku Coffee')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/auth.css') }}">
@endpush

@section('content')

<section class="auth-section">
    <div class="auth-container">

        <h2>Login</h2>

        {{-- Pesan sukses dari register --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan error login --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf

            <input
                type="email"
                name="email"
                placeholder="Email"
                value="{{ old('email') }}"
                required>

            <input
                type="password"
                name="password"
                placeholder="Password"
                required>

            <button type="submit">Login</button>
        </form>

        <p class="auth-switch">
            Belum punya akun?
            <a href="{{ route('register') }}">Daftar di sini</a>
        </p>

    </div>
</section>

@endsection