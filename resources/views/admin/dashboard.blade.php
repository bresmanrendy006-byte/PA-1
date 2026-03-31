@extends('layouts.admin')

@section('title','Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/dashboard.css') }}">
@endpush

@section('content')

<div class="dashboard-container">

<div class="greeting-card">
    <div class="greeting-text">
        <span class="dashboard-label">Dashboard</span>
        <h2>Selamat datang, <b>{{ Auth::user()->username }}</b></h2>
        <p class="role-text">
            <i class="fas {{ Auth::user()->role == 'owner' ? 'fa-crown' : 'fa-user' }}"></i>
            {{ ucfirst(Auth::user()->role) }}
        </p>
    </div>

    <div class="greeting-icon">
        <i class="fas fa-mug-hot"></i>
    </div>
</div>

<div class="dashboard-cards">

    <div class="card">
        <i class="fas fa-mug-hot"></i>
        <div>
            <h3>Total Menu</h3>
            <p>12</p>
        </div>
    </div>

    <div class="card">
        <i class="fas fa-receipt"></i>
        <div>
            <h3>Total Pesanan</h3>
            <p>45</p>
        </div>
    </div>

    @if(Auth::user()->role == 'owner')
    <div class="card">
        <i class="fas fa-users"></i>
        <div>
            <h3>Total Karyawan</h3>
            <p>5</p>
        </div>
    </div>
    @endif

</div>

<div class="stats-row">

    <div class="stats-card">
        <h4>Pesanan Hari Ini</h4>
        <p>8</p>
    </div>

    <div class="stats-card">
        <h4>Pendapatan Bulan Ini</h4>
        <p>Rp 4.2 Jt</p>
    </div>

    <div class="stats-card">
        <h4>Menu Favorit</h4>
        <p>Kedanku Latte</p>
    </div>

</div>

</div>

@endsection