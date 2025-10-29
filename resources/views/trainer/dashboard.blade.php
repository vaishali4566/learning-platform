@extends('layouts.trainer.index')

@section('title', 'Trainer Dashboard')

@section('content')
<div class="bg-[#101727] min-h-screen p-6 rounded-lg">
    <h1 class="text-2xl font-bold text-[#E6EDF7] mb-4">
        Welcome back, {{ Auth::user()->name ?? 'Trainer' }}
    </h1>
    <p class="text-[#8A93A8] mb-8">
        Manage your courses, students, and analytics efficiently from one place.
    </p>

    <!-- === Dashboard Cards === -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div
            class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#00C2FF] p-4 rounded-lg shadow-md hover:shadow-[#00C2FF]/30 transition-all duration-300 transform hover:-translate-y-1">
            <h2 class="text-[#E6EDF7] font-semibold flex items-center gap-2">
                <i data-lucide="book-open-text" class="w-5 h-5 text-[#00C2FF]"></i> Courses
            </h2>
            <p class="text-[#8A93A8] text-sm">Manage and view all available courses.</p>
        </div>

        <div
            class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#16a34a] p-4 rounded-lg shadow-md hover:shadow-[#16a34a]/30 transition-all duration-300 transform hover:-translate-y-1">
            <h2 class="text-[#E6EDF7] font-semibold flex items-center gap-2">
                <i data-lucide="users" class="w-5 h-5 text-[#16a34a]"></i> Students
            </h2>
            <p class="text-[#8A93A8] text-sm">View enrolled students and progress.</p>
        </div>

        <div
            class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#facc15] p-4 rounded-lg shadow-md hover:shadow-[#facc15]/30 transition-all duration-300 transform hover:-translate-y-1">
            <h2 class="text-[#E6EDF7] font-semibold flex items-center gap-2">
                <i data-lucide="chart-line" class="w-5 h-5 text-[#facc15]"></i> Reports
            </h2>
            <p class="text-[#8A93A8] text-sm">Track performance and analytics.</p>
        </div>
    </div>

    <!-- === Charts Section === -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 📈 Line Chart -->
        <div
            class="bg-gradient-to-br from-[#1C2541] to-[#11182A] rounded-xl shadow-xl border border-[#26304D] p-6 transition-all duration-300 hover:shadow-[#00C2FF]/20">
            <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
                <i data-lucide="trending-up" class="w-5 h-5 text-[#00C2FF]"></i>
                Training Performance Overview
            </h2>
            <div class="h-64">
                <canvas id="trainerChart"></canvas>
            </div>
        </div>

        <!-- 🥧 Pie Chart -->
        <div
            class="bg-gradient-to-br from-[#1C2541] to-[#11182A] rounded-xl shadow-xl border border-[#26304D] p-6 transition-all duration-300 hover:shadow-[#00C2FF]/20">
            <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
                <i data-lucide="pie-chart" class="w-5 h-5 text-[#00C2FF]"></i>
                Course Category Distribution
            </h2>
            <div class="h-64">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- === New Dashboard Sections === -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-10">

        <!-- 🔥 Recent Courses -->
        <div
            class="lg:col-span-2 bg-gradient-to-br from-[#1C2541] to-[#11182A] rounded-xl shadow-xl border border-[#26304D] p-6">
            <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
                <i data-lucide="book" class="w-5 h-5 text-[#00C2FF]"></i>
                Recent Courses
            </h2>

            <div class="space-y-4">
                @foreach (['React Masterclass', 'Data Visualization Basics', 'AI for Beginners'] as $course)
                <div
                    class="flex justify-between items-center bg-[#101727]/40 rounded-lg px-4 py-3 hover:bg-[#101727]/70 transition-all duration-200">
                    <div>
                        <p class="text-[#E6EDF7] font-medium">{{ $course }}</p>
                        <p class="text-[#8A93A8] text-sm">Updated {{ rand(1, 7) }} days ago</p>
                    </div>
                    <button class="text-[#00C2FF] text-sm font-medium hover:underline">View</button>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 🧑‍🎓 Top Students -->
        <div
            class="bg-gradient-to-br from-[#1C2541] to-[#11182A] rounded-xl shadow-xl border border-[#26304D] p-6">
            <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
                <i data-lucide="award" class="w-5 h-5 text-[#facc15]"></i>
                Top Students
            </h2>

            <div class="space-y-4">
                @foreach ([
                ['name' => 'Riya Sharma', 'score' => '98%'],
                ['name' => 'Aman Verma', 'score' => '95%'],
                ['name' => 'Sneha Patel', 'score' => '92%'],
                ] as $student)
                <div
                    class="flex justify-between items-center bg-[#101727]/40 rounded-lg px-4 py-3 hover:bg-[#101727]/70 transition-all duration-200">
                    <p class="text-[#E6EDF7] font-medium">{{ $student['name'] }}</p>
                    <span class="text-[#16a34a] font-semibold">{{ $student['score'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 🗓️ Upcoming Schedule / Events -->
    <div
        class="bg-gradient-to-br from-[#1C2541] to-[#11182A] rounded-xl shadow-xl border border-[#26304D] p-6 mt-10">
        <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
            <i data-lucide="calendar-days" class="w-5 h-5 text-[#00C2FF]"></i>
            Upcoming Training Schedule
        </h2>

        <ul class="divide-y divide-[#26304D]/70">
            @foreach ([
            ['date' => 'Oct 30, 2025', 'event' => 'React Hooks Deep Dive'],
            ['date' => 'Nov 2, 2025', 'event' => 'Data Science Workshop'],
            ['date' => 'Nov 8, 2025', 'event' => 'AI for Trainers Webinar']
            ] as $schedule)
            <li class="py-3 flex justify-between items-center">
                <span class="text-[#E6EDF7]">{{ $schedule['event'] }}</span>
                <span class="text-[#8A93A8] text-sm">{{ $schedule['date'] }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</div>

<!-- === Chart.js === -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        lucide.createIcons();

        // === Line Chart ===
        const ctxLine = document.getElementById('trainerChart').getContext('2d');
        const gradientBlue = ctxLine.createLinearGradient(0, 0, 0, 400);
        gradientBlue.addColorStop(0, "rgba(0,194,255,0.35)");
        gradientBlue.addColorStop(1, "rgba(0,194,255,0)");
        const gradientGreen = ctxLine.createLinearGradient(0, 0, 0, 400);
        gradientGreen.addColorStop(0, "rgba(22,163,74,0.35)");
        gradientGreen.addColorStop(1, "rgba(22,163,74,0)");

        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                        label: 'Completed Courses',
                        data: [12, 19, 8, 15, 22, 30, 25],
                        borderColor: '#00C2FF',
                        backgroundColor: gradientBlue,
                        borderWidth: 2.5,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#00C2FF',
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#E6EDF7',
                    },
                    {
                        label: 'Active Students',
                        data: [8, 12, 10, 18, 20, 28, 24],
                        borderColor: '#16a34a',
                        backgroundColor: gradientGreen,
                        borderWidth: 2.5,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#16a34a',
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#E6EDF7',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#E6EDF7',
                            font: {
                                size: 13
                            },
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(16,23,39,0.9)',
                        titleColor: '#E6EDF7',
                        bodyColor: '#E6EDF7',
                        borderColor: '#00C2FF',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: true,
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#8A93A8'
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.05)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#8A93A8'
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.05)'
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                }
            }
        });

        // === Pie Chart ===
        const ctxPie = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Web Dev', 'Data Science', 'AI & ML', 'Design', 'Marketing'],
                datasets: [{
                    label: 'Category Share',
                    data: [25, 20, 15, 25, 15],
                    backgroundColor: [
                        'rgba(0,194,255,0.9)',
                        'rgba(58,110,165,0.9)',
                        'rgba(22,163,74,0.9)',
                        'rgba(249,115,22,0.9)',
                        'rgba(147,51,234,0.9)'
                    ],
                    borderColor: '#101727',
                    borderWidth: 3,
                    hoverOffset: 10,
                }]
            },
            options: {
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#E6EDF7',
                            boxWidth: 18,
                            padding: 20,
                            font: {
                                family: 'Inter, sans-serif',
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(16,23,39,0.95)',
                        titleColor: '#E6EDF7',
                        bodyColor: '#E6EDF7',
                        borderColor: '#00C2FF',
                        borderWidth: 1,
                        callbacks: {
                            label: (tooltipItem) => `${tooltipItem.label}: ${tooltipItem.raw}%`
                        }
                    }
                }
            }
        });
    });
</script>
@endsection