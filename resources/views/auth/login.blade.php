@extends('layouts.app')

@section('title', 'Login - Kedanku Coffee')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/auth.css') }}">
@endpush

@section('content')

<section class="auth-section">
    <div class="auth-container">
        <h2>Login</h2>
        <form>
            <input type="email" placeholder="Email" required>
            <input type="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <p class="auth-switch">
            Belum punya akun? 
            <a href="{{ route('register') }}">Daftar di sini</a>
        </p>
    </div>
</section>

@endsection