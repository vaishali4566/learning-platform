@extends('layouts.trainer')

@section('title', 'Trainer Dashboard')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Welcome to Trainer Dashboard</h1>
    <p class="text-gray-600 mb-4">Manage your courses, students, and quizzes efficiently from one place.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded-lg shadow-sm">
            <h2 class="text-blue-700 font-semibold flex items-center gap-2">
                <i data-lucide="book-open-text"></i> Courses
            </h2>
            <p class="text-gray-600 text-sm">Manage and view all available courses.</p>
        </div>

        <div class="bg-green-50 border-l-4 border-green-600 p-4 rounded-lg shadow-sm">
            <h2 class="text-green-700 font-semibold flex items-center gap-2">
                <i data-lucide="users"></i> Students
            </h2>
            <p class="text-gray-600 text-sm">View enrolled students and progress.</p>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-600 p-4 rounded-lg shadow-sm">
            <h2 class="text-yellow-700 font-semibold flex items-center gap-2">
                <i data-lucide="chart-line"></i> Reports
            </h2>
            <p class="text-gray-600 text-sm">Track performance and analytics.</p>
        </div>
    </div>
</div>
@endsection