@extends('layouts.user.index')

@section('title', 'Student Dashboard')

@section('content')
<div class="bg-gray-50 text-gray-800 dark:bg-[#101727] min-h-screen p-6 px-4 sm:p-8">
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-[#E6EDF7] mb-2">
        Welcome back, {{ Auth::user()->name ?? 'Student' }} 👋
    </h1>
    <p class="text-gray-600 dark:text-[#8A93A8] mb-8">
        Here’s your learning summary and progress insights.
    </p>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="bg-white dark:bg-transparent dark:bg-gradient-to-br dark:from-[#1C2541] dark:to-[#11182A] border-l-4 border-[#00C2FF] p-5 rounded-lg shadow-md transition-all duration-300">
            <h2 class="text-gray-800 dark:text-[#E6EDF7] font-semibold flex items-center justify-between w-full gap-2">
               Courses Enrolled <i data-lucide="book-open" class="w-6 h-6 text-[#00C2FF]"></i> 
            </h2>
            <p class="text-3xl font-semibold text-gray-800 dark:text-[#E6EDF7] mt-1">5</p>
            <p class="text-[#8A93A8] text-sm mt-2">Active learning paths</p>          
        </div>

        <div class="bg-white dark:bg-transparent dark:bg-gradient-to-br dark:from-[#1C2541] dark:to-[#11182A] border-l-4 border-[#16a34a] p-5 rounded-lg shadow-md transition-all duration-300">
            <h2 class="text-gray-800 dark:text-[#E6EDF7] font-semibold flex items-center justify-between w-full gap-2">
               Completed Courses <i data-lucide="check-circle" class="w-6 h-6 text-[#16a34a]"></i> 
            </h2>
            <p class="text-3xl font-semibold text-gray-800 dark:text-[#E6EDF7] mt-1">2</p>
            <p class="text-[#8A93A8] text-sm mt-2">Keep it up!</p>
        </div>

        <div class="bg-white dark:bg-transparent dark:bg-gradient-to-br dark:from-[#1C2541] dark:to-[#11182A] border-l-4 border-[#facc15] p-5 rounded-lg shadow-md transition-all duration-300">
            <h2 class="text-gray-800 dark:text-[#E6EDF7] font-semibold flex items-center gap-2 w-full justify-between">
               Course Progress <i data-lucide="activity" class="w-6 h-6 text-[#facc15]"></i>
            </h2>
            <p class="text-3xl font-semibold text-gray-800 dark:text-[#E6EDF7] mt-1">76%</p>
            <p class="text-[#8A93A8] text-sm mt-2">Overall completion</p>
        </div>

        <div class="bg-white dark:bg-transparent dark:bg-gradient-to-br dark:from-[#1C2541] dark:to-[#11182A] border-l-4 border-[#a855f7] p-5 rounded-lg shadow-md transition-all duration-300">
            <h2 class="text-gray-800 dark:text-[#E6EDF7] font-semibold flex w-full justify-between items-center gap-2">
              Certificates <i data-lucide="trophy" class="w-6 h-6 text-[#a855f7]"></i> 
            </h2>
            <p class="text-3xl font-semibold text-gray-800 dark:text-[#E6EDF7] mt-1">3</p>
            <p class="text-[#8A93A8] text-sm mt-2">Earned so far</p>
        </div>
    </div>

    <!-- Progress Chart -->
    <div class="bg-white dark:bg-[#1C2541]/70 p-6 rounded-lg border border-gray-200 dark:border-[#26304D] mb-10 shadow-md">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-[#E6EDF7] mb-4 flex items-center gap-2">
            <i data-lucide="bar-chart-3" class="w-5 h-5 text-[#00C2FF]"></i> Weekly Study Activity
        </h2>
        <div class="h-64">
            <canvas id="studentChart"></canvas>
        </div>
    </div>

    <!-- Recently Accessed Courses -->
    <div class="mb-10">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-[#E6EDF7] mb-4 flex items-center gap-2">
            <i data-lucide="book" class="w-5 h-5 text-[#00C2FF]"></i> Recently Accessed Courses
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach([
            ['title' => 'React Basics', 'progress' => '45%', 'status' => 'In Progress', 'color' => '#00C2FF'],
            ['title' => 'Advanced JavaScript', 'progress' => '80%', 'status' => 'Almost Done', 'color' => '#16a34a'],
            ['title' => 'Node.js Fundamentals', 'progress' => '20%', 'status' => 'New', 'color' => '#facc15'],
            ] as $course)
            <div class="bg-white dark:bg-gradient-to-br dark:from-[#1C2541] dark:to-[#11182A] p-5 rounded-lg shadow-md hover:shadow-[#00C2FF]/20 transition-all">
                <h3 class="font-semibold text-gray-800 dark:text-[#E6EDF7] mb-2">{{ $course['title'] }}</h3>
                <p class="text-sm text-[#8A93A8] mb-3">Progress: {{ $course['progress'] }}</p>
                <div class="flex items-center justify-between text-sm">
                    <span class="font-medium" style="color: {{ $course['color'] }}">{{ $course['status'] }}</span>
                    <a href="#" class="text-[#00C2FF] hover:underline">Continue →</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Upcoming Lessons -->
    <div>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-[#E6EDF7] mb-4 flex items-center gap-2">
            <i data-lucide="calendar-days" class="w-5 h-5 text-[#00C2FF]"></i> Upcoming Lessons
        </h2>
        <ul class="space-y-3 text-[#E6EDF7]">
            <li class="flex justify-between bg-white dark:bg-[#1C2541]/70 p-4 rounded-md border border-gray-200 dark:border-[#26304D] text-gray-600 dark:text-[#E6EDF7] hover:shadow-[#00C2FF]/10">
                <span>New module: “React State Management”</span>
                <span class="text-sm text-[#8A93A8]">Due: Oct 29</span>
            </li>
            <li class="flex justify-between bg-white dark:bg-[#1C2541]/70 p-4 rounded-md border border-gray-200 dark:border-[#26304D] text-gray-600 dark:text-[#E6EDF7] hover:shadow-[#00C2FF]/10">
                <span>Quiz: JavaScript Closures</span>
                <span class="text-sm text-[#8A93A8]">Due: Oct 30</span>
            </li>
            <li class="flex justify-between bg-white dark:bg-[#1C2541]/70 p-4 rounded-md border border-gray-200 dark:border-[#26304D] text-gray-600 dark:text-[#E6EDF7] hover:shadow-[#00C2FF]/10">
                <span>Assignment Review: Node.js Basics</span>
                <span class="text-sm text-[#8A93A8]">Due: Nov 1</span>
            </li>
        </ul>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('studentChart').getContext('2d');

        // Create a gradient for the chart line
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(0, 194, 255, 0.5)');
        gradient.addColorStop(1, 'rgba(0, 194, 255, 0)');

        const isDark = document.documentElement.classList.contains("dark");

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Study Hours',
                    data: [2, 3, 1, 4, 2, 5, 3],
                    borderColor: '#00C2FF',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    tension: 0.4,
                    pointBackgroundColor: '#00E0FF',
                    pointBorderColor: '#00E0FF',
                    pointHoverRadius: 6,
                    pointRadius: 4,
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
                            color: isDark ? "#cdcdcd" : "#8A93A8",
                            boxWidth: 0
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#11182A',
                        titleColor: '#E6EDF7',
                        bodyColor: '#E6EDF7',
                        borderColor: '#00C2FF',
                        borderWidth: 1,
                        boxPadding: 6
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