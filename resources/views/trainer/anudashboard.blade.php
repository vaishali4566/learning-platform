@extends('layouts.trainer')

@section('title', 'Trainer Dashboard')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Welcome {{ $name }}, to Dashboard</h1>
    <p class="text-gray-600 mb-4">Manage your courses, students, and quizzes efficiently from one place.</p>

    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded-lg shadow-sm">
            <h2 class="text-blue-700 font-semibold flex items-center gap-2">
                <i data-lucide="book-open-text" class="w-5 h-5"></i> Courses
            </h2>
            <p class="text-gray-600 text-sm">Manage and view all available courses.</p>
        </div>

        <div class="bg-green-50 border-l-4 border-green-600 p-4 rounded-lg shadow-sm">
            <h2 class="text-green-700 font-semibold flex items-center gap-2">
                <i data-lucide="users" class="w-5 h-5"></i> Students
            </h2>
            <p class="text-gray-600 text-sm">View enrolled students and progress.</p>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-600 p-4 rounded-lg shadow-sm">
            <h2 class="text-yellow-700 font-semibold flex items-center gap-2">
                <i data-lucide="chart-line" class="w-5 h-5"></i> Reports
            </h2>
            <p class="text-gray-600 text-sm">Track performance and analytics.</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 📈 Line Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="trending-up" class="w-5 h-5 text-blue-600"></i>
                Training Performance Overview
            </h2>
            <div class="h-64">
                <canvas id="trainerChart"></canvas>
            </div>
        </div>

        <!-- 🥧 Pie Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="pie-chart" class="w-5 h-5 text-pink-600"></i>
                Course Category Distribution
            </h2>
            <div class="h-64">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        lucide.createIcons();

        // === Line Chart (Performance Overview) ===
        const ctxLine = document.getElementById('trainerChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                        label: 'Completed Courses',
                        data: [12, 19, 8, 15, 22, 30, 25],
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,0.15)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    },
                    {
                        label: 'Active Students',
                        data: [8, 12, 10, 18, 20, 28, 24],
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22,163,74,0.15)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    }
                }
            }
        });

        // === Pie Chart (Course Categories) ===
        const ctxPie = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Web Dev', 'Data Science', 'AI & ML', 'Design', 'Marketing'],
                datasets: [{
                    label: 'Category Share',
                    data: [25, 20, 15, 25, 15],
                    backgroundColor: [
                        'rgba(37,99,235,0.7)', // Blue
                        'rgba(22,163,74,0.7)', // Green
                        'rgba(249,115,22,0.7)', // Orange
                        'rgba(236,72,153,0.7)', // Pink
                        'rgba(147,51,234,0.7)' // Purple
                    ],
                    borderColor: [
                        '#2563eb', '#16a34a', '#f97316', '#ec4899', '#9333ea'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 20,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection