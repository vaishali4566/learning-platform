@extends('layouts.trainer.index')

@section('title', 'Trainer Performance Report')

@section('content')
<div class="bg-[#101727] min-h-screen p-6 rounded-lg">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#E6EDF7]">Trainer Performance Report</h1>
        <p class="text-[#8A93A8] text-sm mt-1">
            Overview of your teaching activities and performance analytics
        </p>
    </div>

    <!-- === Stats Cards === -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div
            class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#00C2FF] rounded-lg p-5 shadow-md hover:shadow-[#00C2FF]/30 transition-all duration-300 transform hover:-translate-y-1">
            <h2 class="text-[#8A93A8] text-sm">Total Courses</h2>
            <p class="text-3xl font-semibold text-[#E6EDF7] mt-1">{{ $totalCourses ?? 0 }}</p>
        </div>

        <div
            class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#16a34a] rounded-lg p-5 shadow-md hover:shadow-[#16a34a]/30 transition-all duration-300 transform hover:-translate-y-1">
            <h2 class="text-[#8A93A8] text-sm">Enrolled Students</h2>
            <p class="text-3xl font-semibold text-[#E6EDF7] mt-1">{{ $totalStudents ?? 0 }}</p>
        </div>

        <div
            class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#facc15] rounded-lg p-5 shadow-md hover:shadow-[#facc15]/30 transition-all duration-300 transform hover:-translate-y-1">
            <h2 class="text-[#8A93A8] text-sm">Quizzes Created</h2>
            <p class="text-3xl font-semibold text-[#E6EDF7] mt-1">{{ $totalQuizzes ?? 0 }}</p>
        </div>

        <div
            class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#ef4444] rounded-lg p-5 shadow-md hover:shadow-[#ef4444]/30 transition-all duration-300 transform hover:-translate-y-1">
            <h2 class="text-[#8A93A8] text-sm">Avg Course Rating</h2>
            <p class="text-3xl font-semibold text-[#E6EDF7] mt-1">{{ $averageRating ?? '—' }}</p>
        </div>
    </div>

    <!-- === Charts Section === -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <!-- 📈 Line Chart -->
        <div
            class="bg-gradient-to-br from-[#1C2541] to-[#11182A] rounded-xl shadow-xl border border-[#26304D] p-6 transition-all duration-300 hover:shadow-[#00C2FF]/20">
            <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
                <i data-lucide="trending-up" class="w-5 h-5 text-[#00C2FF]"></i>
                Course Enrollments (Monthly)
            </h2>
            <div class="h-64 flex items-center justify-center">
                <canvas id="enrollmentChart"></canvas>
            </div>
        </div>

        <!-- 🥧 Pie Chart -->
        <div
            class="bg-gradient-to-br from-[#1C2541] to-[#11182A] rounded-xl shadow-xl border border-[#26304D] p-6 transition-all duration-300 hover:shadow-[#00C2FF]/20">
            <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
                <i data-lucide="pie-chart" class="w-5 h-5 text-[#00C2FF]"></i>
                Quiz Performance
            </h2>
            <div class="h-64 flex items-center justify-center">
                <canvas id="quizChart"></canvas>
            </div>
        </div>
    </div>

    <!-- === Course Performance Table === -->
    <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] rounded-xl shadow-xl border border-[#26304D] p-6 mb-10">
        <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
            <i data-lucide="book-open-text" class="w-5 h-5 text-[#00C2FF]"></i>
            Course Performance
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-[#8A93A8]">
                <thead class="border-b border-[#26304D] text-[#A2A9C1]">
                    <tr>
                        <th class="py-3 px-4">Course</th>
                        <th class="py-3 px-4">Enrollments</th>
                        <th class="py-3 px-4">Completion Rate</th>
                        <th class="py-3 px-4">Rating</th>
                        <th class="py-3 px-4">Created On</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses ?? [] as $course)
                        <tr class="hover:bg-[#101727]/60 transition">
                            <td class="py-3 px-4 text-[#E6EDF7]">{{ $course->title ?? 'Untitled' }}</td>
                            <td class="py-3 px-4">{{ $course->enrollments_count ?? 0 }}</td>
                            <td class="py-3 px-4">{{ $course->completion_rate ?? '—' }}</td>
                            <td class="py-3 px-4">{{ $course->average_rating ?? '—' }}</td>
                            <td class="py-3 px-4">{{ isset($course->created_at) ? $course->created_at->format('d M Y') : '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">No courses found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- === Quiz Summary Table === -->
    <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] rounded-xl shadow-xl border border-[#26304D] p-6">
        <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
            <i data-lucide="clipboard-list" class="w-5 h-5 text-[#00C2FF]"></i>
            Quiz Summary
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-[#8A93A8]">
                <thead class="border-b border-[#26304D] text-[#A2A9C1]">
                    <tr>
                        <th class="py-3 px-4">Quiz</th>
                        <th class="py-3 px-4">Attempts</th>
                        <th class="py-3 px-4">Avg Score</th>
                        <th class="py-3 px-4">Pass %</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quizzes ?? [] as $quiz)
                        <tr class="hover:bg-[#101727]/60 transition">
                            <td class="py-3 px-4 text-[#E6EDF7]">{{ $quiz->title ?? 'Untitled' }}</td>
                            <td class="py-3 px-4">{{ $quiz->attempts ?? 0 }}</td>
                            <td class="py-3 px-4">{{ $quiz->average_score ?? '—' }}</td>
                            <td class="py-3 px-4">{{ $quiz->pass_rate ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">No quizzes available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- === Chart.js === -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Your existing scripts remain unchanged
    document.addEventListener("DOMContentLoaded", () => {
        @php
            $chartMonths = isset($months) ? $months : ['Jan', 'Feb', 'Mar', 'Apr', 'May'];
            $chartEnrollments = isset($enrollments) ? $enrollments : [10, 25, 35, 50, 40];
            $chartQuizStats = isset($quizStats) ? $quizStats : [75, 25];
        @endphp

        const ctx1 = document.getElementById('enrollmentChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: @json($chartMonths),
                datasets: [{
                    label: 'Enrollments',
                    data: @json($chartEnrollments),
                    borderColor: '#00C2FF',
                    backgroundColor: 'rgba(0,194,255,0.15)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#00C2FF'
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        ticks: { color: '#8A93A8' },
                        grid: { color: 'rgba(255,255,255,0.05)' }
                    },
                    y: {
                        ticks: { color: '#8A93A8' },
                        grid: { color: 'rgba(255,255,255,0.05)' }
                    }
                }
            }
        });

        const ctx2 = document.getElementById('quizChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Passed', 'Failed'],
                datasets: [{
                    data: @json($chartQuizStats),
                    backgroundColor: ['#00C2FF', '#2F82DB'],
                    borderWidth: 0
                }]
            },
            options: {
                plugins: {
                    legend: {
                        labels: { color: '#8A93A8' }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endsection
