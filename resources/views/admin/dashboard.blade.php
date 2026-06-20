@extends('layouts.admin')

@section('title','Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/dashboard.css') }}">
@endpush

@section('content')
<div class="dashboard-container">

    <!-- HEADER & STATS (Tetap seperti sebelumnya) -->
 <!-- Bagian Header Dashboard -->
<div class="dashboard-header">
    <div class="welcome-text">
        <h2>Welcome to Kedanku Coffee</h2>
        <span>Ringkasan performa kedai Anda hari ini.</span>
    </div>

    <div class="header-right">
        <!-- FORM PENCARIAN -->
        <form action="{{ route('admin.orders.index') }}" method="GET" class="search-bar">
            <i class="fas fa-search" style="color: #ccc;"></i>
            <input type="text" name="search" placeholder="Cari ID Pesanan" value="{{ request('search') }}">
            {{-- Form otomatis terkirim saat tekan Enter --}}
        </form>

        <div class="admin-profile">
            <div class="user-avatar-circle" style="width:40px; height:40px; background:#8b5e3c; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold;">
                {{ strtoupper(substr(auth()->user()->username, 0, 2)) }}
            </div>
        </div>
    </div>
</div>

    <!-- STATS CARDS (Tetap) -->
    <div class="stats-grid">
        <!-- ... (Kode stats cards Anda sebelumnya) ... -->
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
                <span class="stat-change">Katalog</span>
            </div>
            <div class="stat-body">
                <h4>Total Menu</h4>
                <h2>{{ $totalMenu }}</h2>
            </div>
            <div class="progress-bar"><div class="progress-fill" style="width: 70%;"></div></div>
        </div>
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <span class="stat-change" style="color: #27ae60;">Aktif</span>
            </div>
            <div class="stat-body">
                <h4>Pesanan Hari Ini</h4>
                <h2>{{ $todayOrders }}</h2>
            </div>
            <div class="progress-bar"><div class="progress-fill" style="width: 45%;"></div></div>
        </div>
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                <span class="stat-change" style="color: #27ae60;">+ Pendapatan</span>
            </div>
            <div class="stat-body">
                <h4>Total Pendapatan</h4>
                <h2>Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h2>
            </div>
            <div class="progress-bar"><div class="progress-fill" style="width: 85%;"></div></div>
        </div>
    </div>

    <!-- RECENT ORDER TABLE -->
    <div class="recent-orders">
        <div class="card-title">
            <h3>Recent Order</h3>
            <a href="#" style="font-size: 12px; color: #8b5e3c; text-decoration: none;">See all</a>
        </div>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Items</th>
                    <th>Date & Time</th>
                    <th>Price</th>
                    <th>Payment</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td class="item-cell">
                        <i class="fas fa-file-invoice" style="color: #8b5e3c; margin-right: 10px;"></i>
                        Order #{{ $order->id }}
                    </td>
                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>Cash</td>
                    <td><span class="status-badge {{ $order->status }}">{{ $order->status }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
{{-- Load Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('plugins/adminJS/dashboard.js') }}"></script>
@endpush