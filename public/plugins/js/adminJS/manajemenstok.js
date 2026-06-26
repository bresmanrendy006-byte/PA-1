document.addEventListener('DOMContentLoaded', function () {

    const chartCanvas = document.getElementById('barChart');
    const donutCanvas = document.getElementById('donutChart');

    if (!chartCanvas || !donutCanvas) return;

    const labels     = JSON.parse(chartCanvas.dataset.labels);
    const dataValues = JSON.parse(chartCanvas.dataset.values);

    const colors = [
        '#8B5E3C', '#A67B5B', '#BC9A7A', '#D4B996', '#EAD8C0',
        '#6F4E37', '#5D4037', '#3E2723'
    ];

    // Bar Chart
    new Chart(chartCanvas, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Stok (Porsi)',
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
                y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Donut Chart
    new Chart(donutCanvas, {
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

    // Custom Legend
    const legendContainer = document.getElementById('donutLegend');
    const total = dataValues.reduce((a, b) => a + b, 0);

    labels.forEach((label, i) => {
        if (i < 5) {
            const percentage = total > 0 ? Math.round((dataValues[i] / total) * 100) : 0;
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

});