@extends('layouts.admin.index')

@section('title', 'Admin Dashboard')

@section('content')
<div class="bg-[#101727] min-h-screen p-6 rounded-lg">
    <h1 class="text-2xl font-bold text-[#E6EDF7] mb-4">
        Welcome back, {{ Auth::user()->name ?? 'Admin' }} 👋
    </h1>
    <p class="text-[#8A93A8] mb-8">
        Here’s the platform overview and key statistics.
    </p>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#00C2FF] p-5 rounded-lg shadow-md hover:shadow-[#00C2FF]/30 transition-all duration-300 hover:-translate-y-1">
            <h2 class="text-[#E6EDF7] font-semibold flex items-center gap-2">
                <i data-lucide="users" class="w-5 h-5 text-[#00C2FF]"></i> Total Users
            </h2>
            <p class="text-3xl font-bold text-[#E6EDF7] mt-3">{{ \App\Models\User::count() }}</p>
            <p class="text-[#8A93A8] text-sm">Registered students</p>
        </div>

        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#16a34a] p-5 rounded-lg shadow-md hover:shadow-[#16a34a]/30 transition-all duration-300 hover:-translate-y-1">
            <h2 class="text-[#E6EDF7] font-semibold flex items-center gap-2">
                <i data-lucide="user-check" class="w-5 h-5 text-[#16a34a]"></i> Total Trainers
            </h2>
            <p class="text-3xl font-bold text-[#E6EDF7] mt-3">{{ \App\Models\Trainer::count() }}</p>
            <p class="text-[#8A93A8] text-sm">Approved instructors</p>
        </div>

        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#facc15] p-5 rounded-lg shadow-md hover:shadow-[#facc15]/30 transition-all duration-300 hover:-translate-y-1">
            <h2 class="text-[#E6EDF7] font-semibold flex items-center gap-2">
                <i data-lucide="book-open" class="w-5 h-5 text-[#facc15]"></i> Total Courses
            </h2>
            <p class="text-3xl font-bold text-[#E6EDF7] mt-3">{{ \App\Models\Course::count() }}</p>
            <p class="text-[#8A93A8] text-sm">Published on platform</p>
        </div>

        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#a855f7] p-5 rounded-lg shadow-md hover:shadow-[#a855f7]/30 transition-all duration-300 hover:-translate-y-1">
            <h2 class="text-[#E6EDF7] font-semibold flex items-center gap-2">
                <i data-lucide="credit-card" class="w-5 h-5 text-[#a855f7]"></i> Total Revenue
            </h2>
            <p class="text-3xl font-bold text-[#E6EDF7] mt-3">
                ₹{{ number_format(\App\Models\Payment::sum('amount'), 2) }}
            </p>
            <p class="text-[#8A93A8] text-sm">All-time earnings</p>
        </div>
    </div>

    <!-- Analytics Chart -->
    <div class="bg-[#1C2541]/70 p-6 rounded-lg border border-[#26304D] mb-10 shadow-md">
        <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
            <i data-lucide="bar-chart-3" class="w-5 h-5 text-[#00C2FF]"></i> Weekly Platform Activity
        </h2>
        <div class="h-64">
            <canvas id="adminChart"></canvas>
        </div>
    </div>

    <!-- Quick Management Links -->
    <div class="mb-10">
        <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
            <i data-lucide="layout-dashboard" class="w-5 h-5 text-[#00C2FF]"></i> Quick Access
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="#" class="group bg-gradient-to-br from-[#1C2541] to-[#11182A] p-6 rounded-lg border border-[#26304D] hover:shadow-[#00C2FF]/30 transition-all hover:-translate-y-1">
                <i data-lucide="users" class="w-7 h-7 text-[#00C2FF] mb-3"></i>
                <h3 class="text-lg font-semibold text-[#E6EDF7]">Manage Users</h3>
                <p class="text-sm text-[#8A93A8] mt-1">View and control student accounts.</p>
            </a>

            <a href="#" class="group bg-gradient-to-br from-[#1C2541] to-[#11182A] p-6 rounded-lg border border-[#26304D] hover:shadow-[#16a34a]/30 transition-all hover:-translate-y-1">
                <i data-lucide="user-check" class="w-7 h-7 text-[#16a34a] mb-3"></i>
                <h3 class="text-lg font-semibold text-[#E6EDF7]">Manage Trainers</h3>
                <p class="text-sm text-[#8A93A8] mt-1">Approve and monitor trainers.</p>
            </a>

            <a href="#" class="group bg-gradient-to-br from-[#1C2541] to-[#11182A] p-6 rounded-lg border border-[#26304D] hover:shadow-[#facc15]/30 transition-all hover:-translate-y-1">
                <i data-lucide="book-open" class="w-7 h-7 text-[#facc15] mb-3"></i>
                <h3 class="text-lg font-semibold text-[#E6EDF7]">View Courses</h3>
                <p class="text-sm text-[#8A93A8] mt-1">Track and moderate uploaded courses.</p>
            </a>

            <a href="#" class="group bg-gradient-to-br from-[#1C2541] to-[#11182A] p-6 rounded-lg border border-[#26304D] hover:shadow-[#a855f7]/30 transition-all hover:-translate-y-1">
                <i data-lucide="credit-card" class="w-7 h-7 text-[#a855f7] mb-3"></i>
                <h3 class="text-lg font-semibold text-[#E6EDF7]">Payments</h3>
                <p class="text-sm text-[#8A93A8] mt-1">Verify transactions and payouts.</p>
            </a>

            <a href="#" class="group bg-gradient-to-br from-[#1C2541] to-[#11182A] p-6 rounded-lg border border-[#26304D] hover:shadow-[#00C2FF]/30 transition-all hover:-translate-y-1">
                <i data-lucide="bar-chart-3" class="w-7 h-7 text-[#00C2FF] mb-3"></i>
                <h3 class="text-lg font-semibold text-[#E6EDF7]">Reports</h3>
                <p class="text-sm text-[#8A93A8] mt-1">Generate analytics and insights.</p>
            </a>

            <a href="#" class="group bg-gradient-to-br from-[#1C2541] to-[#11182A] p-6 rounded-lg border border-[#26304D] hover:shadow-[#ef4444]/30 transition-all hover:-translate-y-1">
                <i data-lucide="settings" class="w-7 h-7 text-[#ef4444] mb-3"></i>
                <h3 class="text-lg font-semibold text-[#E6EDF7]">Platform Settings</h3>
                <p class="text-sm text-[#8A93A8] mt-1">Adjust global configurations.</p>
            </a>
        </div>
    </div>

    <!-- Recent Activities -->
    <div>
        <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
            <i data-lucide="activity" class="w-5 h-5 text-[#00C2FF]"></i> Recent Admin Activities
        </h2>
        <ul class="space-y-3 text-[#E6EDF7]">
            <li class="flex justify-between bg-[#1C2541]/70 p-4 rounded-md border border-[#26304D] hover:shadow-[#00C2FF]/10 transition-all">
                <span>New trainer <strong>Ravi Sharma</strong> approved.</span>
                <span class="text-sm text-[#8A93A8]">5 min ago</span>
            </li>
            <li class="flex justify-between bg-[#1C2541]/70 p-4 rounded-md border border-[#26304D] hover:shadow-[#00C2FF]/10 transition-all">
                <span>Course <strong>React Mastery</strong> published.</span>
                <span class="text-sm text-[#8A93A8]">1 hour ago</span>
            </li>
            <li class="flex justify-between bg-[#1C2541]/70 p-4 rounded-md border border-[#26304D] hover:shadow-[#00C2FF]/10 transition-all">
                <span>Payment of ₹499 received from <strong>Priya</strong>.</span>
                <span class="text-sm text-[#8A93A8]">Today</span>
            </li>
        </ul>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('adminChart').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(0, 194, 255, 0.5)');
        gradient.addColorStop(1, 'rgba(0, 194, 255, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'User Registrations',
                    data: [5, 8, 4, 9, 6, 12, 10],
                    borderColor: '#00C2FF',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    tension: 0.4,
                    pointBackgroundColor: '#00E0FF',
                    pointBorderColor: '#00E0FF',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#E6EDF7',
                            boxWidth: 0
                        }
                    },
                    tooltip: {
                        backgroundColor: '#11182A',
                        titleColor: '#E6EDF7',
                        bodyColor: '#E6EDF7',
                        borderColor: '#00C2FF',
                        borderWidth: 1
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
                }
            }
        });
    });
</script>
@endsection