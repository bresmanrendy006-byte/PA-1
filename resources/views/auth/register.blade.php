@extends('layouts.app')

@section('title', 'Register - Kedanku Coffee')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/auth.css') }}">
@endpush

@section('content')

<section class="auth-section">
    <div class="auth-container">
        <h2>Register</h2>
        <form>
            <input type="text" placeholder="Nama Lengkap" required>
            <input type="email" placeholder="Email" required>
            <input type="password" placeholder="Password" required>
            <button type="submit">Daftar</button>
        </form>

        <p class="auth-switch">
            Sudah punya akun? 
            <a href="{{ route('login') }}">Login di sini</a>
        </p>
    </div>
</section>

@endsection