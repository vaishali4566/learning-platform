@extends('layouts.trainer.index')

@section('title', 'Trainer Dashboard')

@section('content')
<div class="bg-[#101727] min-h-screen p-6 rounded-lg">
    <!-- Header Section with Quick Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-[#E6EDF7] mb-2">
                Welcome back, {{ Auth::guard('trainer')->user()->name ?? 'Trainer' }}
            </h1>
            <p class="text-[#8A93A8]">
                {{ now()->format('l, F j, Y') }}
            </p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-4">
            <a href="{{ route('trainer.courses.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-[#00C2FF] text-white rounded-lg hover:bg-[#00A3E0] transition-all">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                New Course
            </a>
            <a href="{{ route('trainer.report') }}" 
               class="inline-flex items-center px-4 py-2 bg-[#1C2541] text-[#E6EDF7] rounded-lg hover:bg-[#26304D] transition-all border border-[#26304D]">
                <i data-lucide="bar-chart-2" class="w-4 h-4 mr-2"></i>
                View Reports
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Active Courses -->
        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#00C2FF] p-6 rounded-lg shadow-md hover:shadow-[#00C2FF]/30 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[#8A93A8] text-sm font-medium">Active Courses</p>
                    <h3 class="text-[#E6EDF7] text-2xl font-bold mt-1">
                        {{ $activeCourses ?? App\Models\Course::where('trainer_id', Auth::guard('trainer')->id())->where('status', 'active')->count() }}
                    </h3>
                </div>
                <div class="bg-[#00C2FF]/10 p-3 rounded-lg">
                    <i data-lucide="book-open" class="w-6 h-6 text-[#00C2FF]"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('trainer.courses.my') }}" class="text-[#00C2FF] text-sm hover:underline inline-flex items-center">
                    View all courses
                    <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Total Students -->
        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#16a34a] p-6 rounded-lg shadow-md hover:shadow-[#16a34a]/30 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[#8A93A8] text-sm font-medium">Total Students</p>
                    <h3 class="text-[#E6EDF7] text-2xl font-bold mt-1">
                        {{ $totalStudents ?? App\Models\Purchase::whereIn('course_id', App\Models\Course::where('trainer_id', Auth::guard('trainer')->id())->pluck('id'))->distinct('user_id')->count() }}
                    </h3>
                </div>
                <div class="bg-[#16a34a]/10 p-3 rounded-lg">
                    <i data-lucide="users" class="w-6 h-6 text-[#16a34a]"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('trainer.students.index') }}" class="text-[#16a34a] text-sm hover:underline inline-flex items-center">
                    Manage students
                    <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#facc15] p-6 rounded-lg shadow-md hover:shadow-[#facc15]/30 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[#8A93A8] text-sm font-medium">Total Revenue</p>
                    <h3 class="text-[#E6EDF7] text-2xl font-bold mt-1">
                        ₹{{ $totalRevenue ?? number_format(App\Models\Payment::whereIn('course_id', App\Models\Course::where('trainer_id', Auth::guard('trainer')->id())->pluck('id'))->sum('amount'), 2) }}
                    </h3>
                </div>
                <div class="bg-[#facc15]/10 p-3 rounded-lg">
                    <i data-lucide="indian-rupee" class="w-6 h-6 text-[#facc15]"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('trainer.report') }}#revenue" class="text-[#facc15] text-sm hover:underline inline-flex items-center">
                    View earnings
                    <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Total Quizzes -->
        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#8B5CF6] p-6 rounded-lg shadow-md hover:shadow-[#8B5CF6]/30 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[#8A93A8] text-sm font-medium">Active Quizzes</p>
                    <h3 class="text-[#E6EDF7] text-2xl font-bold mt-1">
                        {{ $totalQuizzes ?? App\Models\Quiz::whereIn('lesson_id', App\Models\Lesson::whereIn('course_id', App\Models\Course::where('trainer_id', Auth::guard('trainer')->id())->pluck('id'))->pluck('id'))->count() }}
                    </h3>
                </div>
                <div class="bg-[#8B5CF6]/10 p-3 rounded-lg">
                    <i data-lucide="check-circle" class="w-6 h-6 text-[#8B5CF6]"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('trainer.quizzes.index') }}" class="text-[#8B5CF6] text-sm hover:underline inline-flex items-center">
                    View quizzes
                    <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- === Charts Section === -->
<div class="grid grid-cols-1 gap-6">
    <!-- 📈 Line Chart -->
    <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] rounded-xl shadow-xl border border-[#26304D] p-6 transition-all duration-300 hover:shadow-[#00C2FF]/20">
        <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
            <i data-lucide="trending-up" class="w-5 h-5 text-[#00C2FF]"></i>
            Student Enrollment Trends
        </h2>
        <div class="h-64">
            <canvas id="enrollmentChart"></canvas>
        </div>
    </div>
</div>

    
    <!-- Activity Feed & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-10">
        <!-- Recent Course Updates -->
        <div class="lg:col-span-2 bg-gradient-to-br from-[#1C2541] to-[#11182A] rounded-xl shadow-xl border border-[#26304D] p-6">
            <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
                <i data-lucide="activity" class="w-5 h-5 text-[#00C2FF]"></i>
                Recent Course Updates
            </h2>
            <div class="space-y-4">
                @php
                    $courses = App\Models\Course::where('trainer_id', Auth::guard('trainer')->id())
                        ->orderBy('updated_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                @forelse($courses as $course)
                <div class="flex justify-between items-center bg-[#101727]/40 rounded-lg px-4 py-3 hover:bg-[#101727]/70 transition-all duration-200">
                    <div>
                        <p class="text-[#E6EDF7] font-medium">{{ $course->title }}</p>
                        <p class="text-[#8A93A8] text-sm">Last updated {{ $course->updated_at->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('trainer.courses.explore', $course->id) }}" class="text-[#00C2FF] text-sm font-medium hover:underline">View</a>
                </div>
                @empty
                <div class="text-[#8A93A8] text-center py-4">No courses available</div>
                @endforelse
            </div>
        </div>

        <!-- Quick Links & Resources -->
        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] rounded-xl shadow-xl border border-[#26304D] p-6">
            <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
                <i data-lucide="layout-grid" class="w-5 h-5 text-[#00C2FF]"></i>
                Quick Links
            </h2>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('trainer.courses.create') }}" 
                   class="flex items-center gap-3 bg-[#101727]/40 rounded-lg px-4 py-3 hover:bg-[#101727]/70 transition-all duration-200">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-[#00C2FF]"></i>
                    <span class="text-[#E6EDF7]">Create New Course</span>
                </a>
                <a href="{{ route('trainer.quizzes.create') }}" 
                   class="flex items-center gap-3 bg-[#101727]/40 rounded-lg px-4 py-3 hover:bg-[#101727]/70 transition-all duration-200">
                    <i data-lucide="check-square" class="w-5 h-5 text-[#16a34a]"></i>
                    <span class="text-[#E6EDF7]">Add New Quiz</span>
                </a>
                <a href="{{ route('trainer.students.index') }}" 
                   class="flex items-center gap-3 bg-[#101727]/40 rounded-lg px-4 py-3 hover:bg-[#101727]/70 transition-all duration-200">
                    <i data-lucide="users" class="w-5 h-5 text-[#8B5CF6]"></i>
                    <span class="text-[#E6EDF7]">Student Management</span>
                </a>
                <a href="{{ route('trainer.report') }}" 
                   class="flex items-center gap-3 bg-[#101727]/40 rounded-lg px-4 py-3 hover:bg-[#101727]/70 transition-all duration-200">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 text-[#facc15]"></i>
                    <span class="text-[#E6EDF7]">Analytics Dashboard</span>
                </a>
            </div>
        </div>
    </div>

        <!-- Student Performance & Upcoming Content -->
    <div class="grid grid-cols- lg:grid-cols-2 gap-6 mt-10">
        <!-- Recent Student Activity -->
        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] rounded-xl shadow-xl border border-[#26304D] p-6">
            <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
                <i data-lucide="activity" class="w-5 h-5 text-[#16a34a]"></i>
                Recent Student Activity
            </h2>
            <div class="space-y-4">
                @php
                    $recentPurchases = App\Models\Purchase::with(['user', 'course'])
                        ->whereIn('course_id', App\Models\Course::where('trainer_id', Auth::guard('trainer')->id())->pluck('id'))
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                @forelse($recentPurchases as $purchase)
                <div class="flex justify-between items-center bg-[#101727]/40 rounded-lg px-4 py-3 hover:bg-[#101727]/70 transition-all duration-200">
                    <div>
                        <p class="text-[#E6EDF7] font-medium">{{ $purchase->user->name }}</p>
                        <p class="text-[#8A93A8] text-sm">Enrolled in {{ $purchase->course->title }}</p>
                    </div>
                    <span class="text-[#16a34a] text-sm">{{ $purchase->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <div class="text-[#8A93A8] text-center py-4">No recent enrollments</div>
                @endforelse
            </div>
        </div>

        <!-- Course Schedule -->
        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] rounded-xl shadow-xl border border-[#26304D] p-6">
            <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
                <i data-lucide="calendar" class="w-5 h-5 text-[#8B5CF6]"></i>
                Course Schedule
            </h2>
            @php
                $upcomingLessons = App\Models\Lesson::whereIn('course_id', 
                    App\Models\Course::where('trainer_id', Auth::guard('trainer')->id())
                        ->where('status', 'active')
                        ->pluck('id')
                )
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            @endphp
            <div class="divide-y divide-[#26304D]">
                @forelse($upcomingLessons as $lesson)
                <div class="py-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-[#E6EDF7] font-medium">{{ $lesson->title }}</h4>
                            <p class="text-[#8A93A8] text-sm">{{ optional($lesson->course)->title }}</p>
                        </div>
                        <span class="text-[#8B5CF6] text-sm">{{ $lesson->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                @empty
                <div class="text-[#8A93A8] text-center py-4">No upcoming lessons</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- === Chart.js === -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        lucide.createIcons();

        // === Enrollment Chart ===
        const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
        const gradientBlue = enrollmentCtx.createLinearGradient(0, 0, 0, 400);
        gradientBlue.addColorStop(0, "rgba(0,194,255,0.35)");
        gradientBlue.addColorStop(1, "rgba(0,194,255,0)");

        const gradientGreen = enrollmentCtx.createLinearGradient(0, 0, 0, 400);
        gradientGreen.addColorStop(0, "rgba(22,163,74,0.35)");
        gradientGreen.addColorStop(1, "rgba(22,163,74,0)");

        new Chart(enrollmentCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Enrolled Students',
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
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#E6EDF7',
                            font: { size: 13 },
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
                        padding: 10
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#8A93A8' },
                        grid: { color: 'rgba(255,255,255,0.05)' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#8A93A8' },
                        grid: { color: 'rgba(255,255,255,0.05)' }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                }
            }
        });

        
    });
</script>
@endsection