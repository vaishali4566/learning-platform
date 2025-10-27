@extends('layouts.user')

@section('title', 'User Dashboard')

@section('content')
<div class="bg-[#101727] min-h-screen p-6 rounded-lg">
    <h1 class="text-2xl font-bold text-[#E6EDF7] mb-4">Welcome back, {{ Auth::user()->name ?? 'Trainer' }}</h1>
    <p class="text-[#8A93A8] mb-8">
        Here’s a quick summary of your teaching activity and student engagement today.
    </p>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#00C2FF] p-5 rounded-lg shadow-md hover:shadow-[#00C2FF]/30 transition-all duration-300 hover:-translate-y-1">
            <h2 class="text-[#E6EDF7] font-semibold flex items-center gap-2">
                <i data-lucide="book-open" class="w-5 h-5 text-[#00C2FF]"></i> Total Courses
            </h2>
            <p class="text-3xl font-bold text-[#E6EDF7] mt-3">12</p>
            <p class="text-[#8A93A8] text-sm">Active & Published</p>
        </div>

        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#16a34a] p-5 rounded-lg shadow-md hover:shadow-[#16a34a]/30 transition-all duration-300 hover:-translate-y-1">
            <h2 class="text-[#E6EDF7] font-semibold flex items-center gap-2">
                <i data-lucide="users" class="w-5 h-5 text-[#16a34a]"></i> Enrolled Students
            </h2>
            <p class="text-3xl font-bold text-[#E6EDF7] mt-3">458</p>
            <p class="text-[#8A93A8] text-sm">Across all courses</p>
        </div>

        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#facc15] p-5 rounded-lg shadow-md hover:shadow-[#facc15]/30 transition-all duration-300 hover:-translate-y-1">
            <h2 class="text-[#E6EDF7] font-semibold flex items-center gap-2">
                <i data-lucide="star" class="w-5 h-5 text-[#facc15]"></i> Avg. Rating
            </h2>
            <p class="text-3xl font-bold text-[#E6EDF7] mt-3">4.8</p>
            <p class="text-[#8A93A8] text-sm">Based on student reviews</p>
        </div>

        <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border-l-4 border-[#a855f7] p-5 rounded-lg shadow-md hover:shadow-[#a855f7]/30 transition-all duration-300 hover:-translate-y-1">
            <h2 class="text-[#E6EDF7] font-semibold flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="w-5 h-5 text-[#a855f7]"></i> Earnings
            </h2>
            <p class="text-3xl font-bold text-[#E6EDF7] mt-3">₹12,350</p>
            <p class="text-[#8A93A8] text-sm">This month</p>
        </div>
    </div>

    <!-- Recent Courses -->
    <div class="mb-10">
        <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
            <i data-lucide="book" class="w-5 h-5 text-[#00C2FF]"></i> Your Recent Courses
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['title' => 'Full Stack Web Development', 'students' => 210, 'status' => 'Active', 'color' => '#16a34a'],
                ['title' => 'JavaScript for Beginners', 'students' => 112, 'status' => 'Active', 'color' => '#16a34a'],
                ['title' => 'MongoDB Advanced Queries', 'students' => 89, 'status' => 'Draft', 'color' => '#facc15'],
            ] as $course)
                <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] p-5 rounded-lg shadow-md hover:shadow-[#00C2FF]/20 transition-all">
                    <h3 class="font-semibold text-[#E6EDF7] mb-2">{{ $course['title'] }}</h3>
                    <p class="text-sm text-[#8A93A8] mb-3">Enrolled: {{ $course['students'] }} students</p>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium" style="color: {{ $course['color'] }}">{{ $course['status'] }}</span>
                        <a href="#" class="text-[#00C2FF] hover:underline">Manage →</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Feedback -->
    <div class="mb-10">
        <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
            <i data-lucide="message-square" class="w-5 h-5 text-[#00C2FF]"></i> Recent Feedback
        </h2>
        <div class="space-y-4">
            <div class="bg-[#1C2541]/70 p-4 rounded-lg border border-[#26304D]">
                <p class="text-[#E6EDF7] italic">"Loved the way concepts were explained clearly!"</p>
                <div class="flex justify-between mt-2 text-sm text-[#8A93A8]">
                    <span>— Priya S.</span>
                    <span>⭐ 5.0</span>
                </div>
            </div>
            <div class="bg-[#1C2541]/70 p-4 rounded-lg border border-[#26304D]">
                <p class="text-[#E6EDF7] italic">"Could use more advanced examples in the MongoDB course."</p>
                <div class="flex justify-between mt-2 text-sm text-[#8A93A8]">
                    <span>— Rohit M.</span>
                    <span>⭐ 4.0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Tasks -->
    <div>
        <h2 class="text-lg font-semibold text-[#E6EDF7] mb-4 flex items-center gap-2">
            <i data-lucide="calendar-days" class="w-5 h-5 text-[#00C2FF]"></i> Upcoming Tasks
        </h2>
        <ul class="space-y-3 text-[#E6EDF7]">
            <li class="flex justify-between bg-[#1C2541]/70 p-4 rounded-md border border-[#26304D] hover:shadow-[#00C2FF]/10 transition-all">
                <span>Upload next week’s lesson for "React Mastery"</span>
                <span class="text-sm text-[#8A93A8]">Due: Oct 29</span>
            </li>
            <li class="flex justify-between bg-[#1C2541]/70 p-4 rounded-md border border-[#26304D] hover:shadow-[#00C2FF]/10 transition-all">
                <span>Review quiz submissions (JavaScript)</span>
                <span class="text-sm text-[#8A93A8]">Due: Oct 30</span>
            </li>
            <li class="flex justify-between bg-[#1C2541]/70 p-4 rounded-md border border-[#26304D] hover:shadow-[#00C2FF]/10 transition-all">
                <span>Check assignment reports</span>
                <span class="text-sm text-[#8A93A8]">Due: Nov 1</span>
            </li>
        </ul>
    </div>
</div>
@endsection
