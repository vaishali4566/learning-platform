<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">📈 Training Performance Overview</h2>
    <canvas id="trainerChart" height="120"></canvas>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('trainerChart').getContext('2d');

        // Fake data for testing
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                        label: 'Completed Courses',
                        data: [12, 19, 8, 15, 22, 30, 25],
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,0.15)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Active Students',
                        data: [8, 12, 10, 18, 20, 28, 24],
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22,163,74,0.15)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                        }
                    }
                }
            }
        });
    });
</script>