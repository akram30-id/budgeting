const ctx = document.getElementById('treasuryChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [{
            label: 'Income',
            data: [10, 25, 40, 30, 50, 60, 80],
            borderColor: '#4c869a',
            backgroundColor: 'rgba(76,134,154,0.1)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                display: false
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
