@extends('layouts.trainer')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-100 to-blue-100 py-10">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-center text-purple-800 mb-10">My Courses</h1>

        @if($courses->isEmpty())
        <div class="bg-white p-10 rounded-lg shadow-md text-center text-gray-700">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">You haven’t created any courses yet!</h2>
            <p class="text-gray-600 mb-4">Start sharing your knowledge by creating a new course or explore existing courses for inspiration.</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('courses.create') }}"
                    class="inline-block px-6 py-2 bg-purple-600 text-white font-semibold rounded hover:bg-purple-700 transition">
                    Create a Course
                </a>
                <a href="{{ route('courses.index') }}"
                    class="inline-block px-6 py-2 bg-gray-200 text-gray-800 font-semibold rounded hover:bg-gray-300 transition">
                    Browse Courses
                </a>
            </div>
        </div>

        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($courses as $course)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:scale-105">
                {{-- <div class="h-40 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $course->image) }}')">--}}
                <div class="h-40 bg-cover bg-center">
                </div>
                <div class="p-5 flex flex-col justify-between h-40">
                    <h2 class="text-lg font-bold text-purple-700">{{ $course->title }}</h2>
                    <a href="{{ route('lessons.alllesson', $course->id) }}"
                        class="mt-4 px-4 py-2 text-center bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-full font-semibold hover:from-purple-600 hover:to-indigo-600 transition">
                        Open
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<script>
    fetch('/courses/all')
        .then(res => res.json())
        .then(data => {
            console.log(data);
            const list = document.getElementById('courses-list');
            list.innerHTML = data.data.map(course => `
            <div class="mb-3">
                <h4>${course.title}</h4>
                <a href="/courses/view/${course.id}" class="btn btn-primary">View Course</a>
            </div>
        `).join('');
        });
</script>
@endsection