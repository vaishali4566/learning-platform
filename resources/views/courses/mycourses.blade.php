@extends('layouts.user.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] py-10 px-6">
    <div class="max-w-7xl mx-auto">
        <!-- Page Title -->
        <h1 class="text-2xl md:text-3xl font-semibold text-center text-[#E6EDF7] mb-10">
            My Courses
        </h1>

        @if($courses->isEmpty())
            <!-- No Courses Message -->
            <div class="bg-[#10182C]/70 border border-[#1F2A44] backdrop-blur-md p-10 rounded-2xl shadow-lg text-center text-[#B8C1D8]">
                <h2 class="text-xl font-medium text-[#00C2FF] mb-3">No courses purchased yet</h2>
                <p class="text-[#AAB3C7] text-sm mb-6">
                    You haven’t purchased any courses yet. Browse our catalog and start learning today.
                </p>
                <a href="{{ route('courses.index') }}"
                   class="inline-block px-5 py-2.5 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white text-sm font-medium rounded-full shadow-md hover:shadow-[0_0_10px_#00C2FF70] transition-all duration-300">
                    Browse Courses
                </a>
            </div>
        @else
            <!-- Course Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($courses as $course)
                    <div
                        class="group bg-[#10182C]/80 border border-[#1F2A44] backdrop-blur-md rounded-2xl overflow-hidden shadow-md hover:shadow-[0_0_15px_#00C2FF40] transition-all duration-300 hover:-translate-y-1">
                        
                        <!-- Course Thumbnail -->
                        <div class="relative h-40 overflow-hidden">
                            <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                alt="{{ $course->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0E19]/80 via-transparent to-transparent"></div>
                        </div>

                        <!-- Course Info -->
                        <div class="p-4 flex flex-col justify-between">
                            <div>
                                <h2 class="text-base font-medium text-[#E6EDF7] leading-snug line-clamp-2">
                                    {{ $course->title }}
                                </h2>
                            </div>

                            <!-- Button -->
                            <div class="mt-4">
                                <a href="{{ route('lessons.alllesson', $course->id) }}"
                                   class="block text-center w-full px-4 py-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] rounded-full text-sm font-medium text-white shadow-md hover:shadow-[0_0_10px_#00C2FF70] transition-all duration-300">
                                    Open Course
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
