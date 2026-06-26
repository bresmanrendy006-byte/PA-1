@extends('layouts.admin')

@section('title', 'Manajemen Stok')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/manajemenstok.css') }}">
@endpush

@section('content')

<div class="stock-container">
    <div class="stock-header">
        <h1 class="stock-title">Manajemen Stok Menu</h1>
        @if(Auth::user()->isOwner())
            <a href="{{ route('admin.stock.create') }}" class="btn-add">
                + Tambah Stok
            </a>
        @endif
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Tabel Data Stok --}}
    <div class="table-wrapper">
        <table class="stock-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Menu</th>
                    <th>Stok (Porsi)</th>
                    @if(Auth::user()->isOwner())
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $stock)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $stock->menu->name }}</td>
                    <td>
                        @if($stock->quantity <= 5)
                            <span style="color: #e53e3e; font-weight: 600;">
                                {{ $stock->quantity }} ⚠️
                            </span>
                        @else
                            {{ $stock->quantity }}
                        @endif
                    </td>
                    @if(Auth::user()->isOwner())
                    <td class="stock-action">
                        <a href="{{ route('admin.stock.edit', $stock->id) }}" class="btn-edit">
                            Edit
                        </a>
                        <button class="btn-hapus btn-delete"
                            data-url="{{ route('admin.stock.delete', $stock->id) }}"
                            data-item-name="{{ $stock->menu->name }}">
                            Hapus
                        </button>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ Auth::user()->isOwner() ? 4 : 3 }}" class="empty-text">
                        Belum ada stok ditambahkan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Grafik hanya tampil jika ada data --}}
    @if($stocks->count() > 0)
    <div class="dashboard-grid">

        <!-- Kartu 1: Grafik Batang -->
        <div class="chart-card">
            <div class="card-header">
                <h3>Stok per Menu</h3>
                <select><option>Semua Menu</option></select>
            </div>
            <div style="height: 250px;">
                {{-- Data dikirim via data attribute, dibaca oleh manajemenstok.js --}}
                <canvas id="barChart"
                    data-labels="{{ json_encode($stocks->map(fn($s) => $s->menu->name)) }}"
                    data-values="{{ json_encode($stocks->pluck('quantity')) }}">
                </canvas>
            </div>
        </div>

        <!-- Kartu 2: Grafik Donat -->
        <div class="chart-card">
            <div class="card-header">
                <h3>Distribusi Stok (%)</h3>
                <select><option>Semua</option></select>
            </div>
            <div class="donut-container">
                <div style="width: 180px; height: 180px;">
                    <canvas id="donutChart"></canvas>
                </div>
                <ul id="donutLegend" class="custom-legend"></ul>
            </div>
        </div>

    </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('plugins/js/adminJS/manajemenstok.js') }}"></script>
@endpush