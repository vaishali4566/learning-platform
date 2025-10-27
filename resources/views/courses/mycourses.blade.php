@extends('layouts.app')

@section('content')
<div class="relative min-h-screen flex flex-col items-center justify-center p-6 bg-cover bg-center"
     style="background-image: url('{{ asset('images/image.png') }}');">

    <!-- Black overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>

    <!-- Content -->
    <div class="relative z-10 w-full max-w-6xl">
        <h1 class="text-4xl font-bold text-white text-center mb-10 drop-shadow-lg">My Courses</h1>

        @if($courses->isEmpty())
            <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl shadow-lg p-10 text-center">
                <h2 class="text-2xl font-semibold text-gray-800 mb-3">No Courses Purchased </h2>
                <p class="text-gray-600 mb-6">Explore our catalog and start learning something new today!</p>
                <a href="{{ route('courses.index') }}"
                   class="inline-block px-6 py-3 bg-gradient-to-r from-blue-800 to-green-700 text-white font-semibold rounded-lg hover:opacity-90 transition">
                    Browse Courses
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl shadow-md hover:shadow-2xl overflow-hidden transition transform hover:scale-105">
                        <!-- Course Image -->
                        <div class="h-40 bg-cover bg-center"
                             style="background-image: url('{{ asset('storage/' . $course->image) }}');">
                        </div>

                        <!-- Course Details -->
                        <div class="p-5 flex flex-col justify-between h-40">
                            <h2 class="text-lg font-semibold text-gray-800 line-clamp-2">{{ $course->title }}</h2>
                            <a href="{{ route('lessons.alllesson', $course->id) }}"
                               class="mt-4 px-4 py-2 text-center bg-gradient-to-r from-blue-800 to-green-700 text-white rounded-lg font-medium hover:opacity-90 transition">
                                Open Course
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
