@extends('layouts.admin')

@section('title', 'Manajemen Stok')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/manajemenstok.css') }}">
@endpush

@section('content')

<div class="stock-container">
    <div class="stock-header">
        <h1 class="stock-title">Manajemen Stok Bahan Baku</h1>
        @if(Auth::user()->role === 'owner')
            <a href="{{ route('admin.stock.create') }}" class="btn-add">
                + Tambah Stok
            </a>
        @endif    
</div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- 1. TABEL DATA STOK --}}
    <div class="table-wrapper">
        <table class="stock-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Bahan</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    @if(Auth::user()->role === 'owner')
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $stock)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $stock->name }}</td>
                    <td>{{ $stock->quantity }}</td>
                    <td>{{ $stock->unit }}</td>
                    @if(Auth::user()->role === 'owner')
                    <td class="stock-action">
                        <a href="{{ route('admin.stock.edit', $stock->id) }}"
                        class="btn-edit">
                        Edit
                        </a>

                        <button class="btn-hapus btn-delete"
                            data-url="{{ route('admin.stock.delete', $stock->id) }}"
                            data-item-name="{{ $stock->name }}">
                            Hapus
                        </button>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="{{ Auth::user()->role === 'owner' ? 5 : 4 }}"
                    class="empty-text">Belum ada stok ditambahkan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 2. GRID GRAFIK (MODEL SEPERTI REFERENSI) --}}
    @if($stocks->count() > 0)
    <div class="dashboard-grid">
        
        <!-- Kartu 1: Grafik Batang (Stok Terbanyak) -->
        <div class="chart-card">
            <div class="card-header">
                <h3>Stok Bahan Baku</h3>
                <select><option>Semua Bahan</option></select>
            </div>
            <div style="height: 250px;">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <!-- Kartu 2: Grafik Donat (Persentase Distribusi) -->
        <div class="chart-card">
            <div class="card-header">
                <h3>Distribusi Stok (%)</h3>
                <select><option>Bulan ini</option></select>
            </div>
            <div class="donut-container">
                <div style="width: 180px; height: 180px;">
                    <canvas id="donutChart"></canvas>
                </div>
                <ul id="donutLegend" class="custom-legend">
                    <!-- Legend akan diisi via JS -->
                </ul>
            </div>
        </div>

    </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($stocks->count() > 0)
        const labels = {!! json_encode($stocks->pluck('name')) !!};
        const dataValues = {!! json_encode($stocks->pluck('quantity')) !!};
        const colors = [
            '#8B5E3C', '#A67B5B', '#BC9A7A', '#D4B996', '#EAD8C0', 
            '#6F4E37', '#5D4037', '#3E2723'
        ];

        // --- 1. BAR CHART CONFIG ---
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah',
                    data: dataValues,
                    backgroundColor: '#8B5E3C',
                    borderRadius: 4,
                    barThickness: 25
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { drawBorder: false, color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // --- 2. DONUT CHART CONFIG ---
        const donutChart = new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: dataValues,
                    backgroundColor: colors,
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // --- 3. GENERATE CUSTOM LEGEND (Samping Donat) ---
        const legendContainer = document.getElementById('donutLegend');
        const total = dataValues.reduce((a, b) => a + b, 0);
        
        labels.forEach((label, i) => {
            if(i < 5) { // Tampilkan top 5 saja di legend agar rapi
                const percentage = Math.round((dataValues[i] / total) * 100);
                const li = document.createElement('li');
                li.className = 'legend-item';
                li.innerHTML = `
                    <div class="legend-color" style="background-color: ${colors[i % colors.length]}"></div>
                    <span class="legend-text">${label}</span>
                    <span class="legend-value">${percentage}%</span>
                `;
                legendContainer.appendChild(li);
            }
        });
    @endif
});
</script>
@endpush