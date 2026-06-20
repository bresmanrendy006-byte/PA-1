// dashboard.js
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('revenueChart');

    // Jaga-jaga kalau elemen <canvas id="revenueChart"> belum ada di halaman
    if (!canvas) {
        return;
    }

    const ctx = canvas.getContext('2d');

    // Membuat efek gradient untuk background grafik
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(139, 94, 60, 0.2)');
    gradient.addColorStop(1, 'rgba(139, 94, 60, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'], // Sesuaikan dengan data dinamis nanti
            datasets: [{
                label: 'Pendapatan',
                data: [150000, 220000, 180000, 242000, 110000, 374000, 300000], // Contoh data
                borderColor: '#8b5e3c',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4, // Membuat garis melengkung (smooth)
                pointRadius: 4,
                pointBackgroundColor: '#8b5e3c',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false } // Sembunyikan label legend agar minimalis
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        callback: function (value) {
                            return 'Rp ' + value.toLocaleString();
                        },
                        color: '#999',
                        font: { size: 10 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#999', font: { size: 10 } }
                }
            }
        }
    });
});