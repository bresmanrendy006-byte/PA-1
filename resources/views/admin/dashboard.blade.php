@extends('layouts.admin')

@section('title','Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/dashboard.css') }}">
@endpush

@section('content')

<div class="dashboard-container">

{{-- GREETING CARD --}}
<div class="greeting-card">

<div class="greeting-text">

<span class="dashboard-label">Dashboard</span>

<h2>
Selamat datang,
<b>{{ Auth::user()->username }}</b>
</h2>

<p class="role-text">
<i class="fas {{ Auth::user()->role == 'owner' ? 'fa-crown' : 'fa-user' }}"></i>
{{ ucfirst(Auth::user()->role) }}
</p>

</div>

<div class="greeting-icon">
<i class="fas fa-mug-hot"></i>
</div>

</div>


{{-- CARD STATISTIK --}}
<div class="dashboard-cards">

<div class="card">

<i class="fas fa-mug-hot"></i>

<div>
<h3>Total Menu</h3>
<p>{{ $totalMenu }}</p>
</div>

</div>


<div class="card">

<i class="fas fa-receipt"></i>

<div>
<h3>Pesanan Hari Ini</h3>
<p>{{ $todayOrders }}</p>
</div>

</div>

</div>

</div>

@endsection