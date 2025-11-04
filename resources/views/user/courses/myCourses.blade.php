@extends('layouts.user.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7] px-6 py-10">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8 animate-fade-in">
        <h1 class="text-3xl font-bold tracking-wide">
            Available Courses
        </h1>
    </div>

    <!-- Course List -->
    @if($courses->isEmpty())
    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center mt-20 text-center animate-fade-in-up">
        <div class="bg-[#0E1625]/80 border border-[#26304D] rounded-2xl shadow-lg p-10 max-w-md">
            <h2 class="text-2xl font-semibold text-[#E6EDF7] mb-2">No Available Courses</h2>
            <p class="text-[#8A93A8] mb-6">
                You haven’t purchased any courses yet. Explore and start your learning journey today!
            </p>
            <a href="{{ route('user.courses.index') }}"
                class="inline-block px-6 py-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white font-semibold rounded-xl shadow-md hover:shadow-[#00C2FF]/40 hover:translate-y-[-1px] transition-all duration-300">
                Browse More
            </a>
        </div>
    </div>

    @else
    <div
        class="bg-[#0E1625]/80 backdrop-blur-md border border-[#26304D] rounded-2xl shadow-lg divide-y divide-[#26304D] animate-fade-in-up">
        @foreach($courses as $course)
        <div
            class="flex items-center justify-between p-4 hover:bg-[#1A233A]/70 transition-all duration-300 ease-in-out relative group">

            <!-- Left: Thumbnail + Info -->
            <div class="flex items-center gap-4">
                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Course Image"
                    class="w-20 h-20 object-cover rounded-lg border border-[#26304D] shadow-md">
                <div>
                    <h3 class="text-lg font-semibold text-[#E6EDF7] line-clamp-1">
                        {{ $course->title }}
                    </h3>
                    <p class="text-sm text-[#8A93A8] line-clamp-1">
                        {{ Str::limit($course->description ?? 'No description available.', 70) }}
                    </p>
                    @if(isset($course->price))
                    <span class="text-[#00C2FF] text-sm font-medium mt-1 block">
                        ₹{{ $course->price }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Right: Buttons -->
            <div class="flex items-center gap-3">
                <a href="{{ route('user.courses.view', $course->id) }}"
                    class="px-4 py-2 bg-[#1C2541]/70 border border-[#00C2FF]/20 text-[#E6EDF7] text-sm font-medium rounded-lg hover:bg-[#1C2541]/90 hover:border-[#00C2FF]/40 hover:scale-[1.02] transition-all duration-300">
                    Open
                </a>
                
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<style>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.5s ease forwards;
    }
</style>
@endsection
