@extends('layouts.app')

@section('title', 'Register - Kedanku Coffee')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/auth.css') }}">
@endpush

@section('content')

<section class="auth-section">
    <div class="auth-container">

        <h2>Daftar Akun</h2>

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

        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('register.process') }}" method="POST">
            @csrf

            <input
                type="text"
                name="username"
                placeholder="Username"
                value="{{ old('username') }}"
                required>

            <input
                type="email"
                name="email"
                placeholder="Email"
                value="{{ old('email') }}"
                required>

            <input
                type="password"
                name="password"
                placeholder="Password (min. 8 karakter)"
                required>

            {{-- WAJIB ADA — untuk validasi 'confirmed' --}}
            <input
                type="password"
                name="password_confirmation"
                placeholder="Konfirmasi Password"
                required>

            <button type="submit">Daftar</button>

        </form>

        <p class="auth-switch">
            Sudah punya akun?
            <a href="{{ route('login') }}">Login di sini</a>
        </p>

    </div>
</section>

@endsection