@extends('layouts.user.index')

@section('content')
<div class="relative min-h-screen flex flex-col px-6 py-10 bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33]">
    <!-- Subtle Animated Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-[#00C2FF]/5 via-transparent to-[#2F82DB]/5 animate-gradient-slow blur-xl opacity-30 pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-7xl mx-auto">
        <h1 class="text-4xl font-semibold text-center text-[#E6EDF7] mb-10 tracking-wide drop-shadow-[0_0_4px_rgba(0,194,255,0.2)]">
            Available Courses
        </h1>

        @if($courses->isEmpty())
        <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-xl shadow-[0_0_15px_rgba(0,194,255,0.05)] p-10 text-center text-[#E6EDF7]">
            <h2 class="text-2xl font-semibold mb-4 text-[#00C2FF]">No courses purchased yet</h2>
            <p class="text-[#A1A9C4]">Explore our catalog and find your next learning adventure!</p>
            <a href="{{ route('user.courses.index') }}"
                class="inline-block mt-6 px-6 py-2 bg-gradient-to-r from-[#2F82DB] to-[#00C2FF] text-white font-semibold rounded-md hover:scale-[1.02] hover:shadow-[0_0_10px_rgba(0,194,255,0.2)] transition">
                Browse Courses
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($courses as $course)
            <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-xl shadow-[0_0_15px_rgba(0,194,255,0.05)] overflow-hidden hover:shadow-[0_0_20px_rgba(0,194,255,0.1)] transition transform hover:scale-[1.02]">
                <div class="h-40 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $course->thumbnail) }}')"></div>
                <div class="p-5 flex flex-col justify-between h-42">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-medium text-[#E6EDF7] truncate">{{ $course->title }}</h2>
                    </div>
                    <div class="mt-4 space-y-2">                        
                        <a href="{{ route('user.courses.view', $course->id) }}"
                            class="block w-full text-center bg-[#1C2541]/60 border border-[#00C2FF]/20 text-[#E6EDF7] font-medium rounded-md py-2 hover:bg-[#1C2541]/80 hover:border-[#00C2FF]/40 hover:scale-[1.01] transition">
                            Open
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<style>
    /* Subtle animated gradient */
    @keyframes gradient-slow {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .animate-gradient-slow {
        background-size: 200% 200%;
        animation: gradient-slow 14s ease infinite;
    }

    /* Consistent dark field visuals */
    input,
    textarea,
    select {
        background-color: rgba(28, 37, 65, 0.6) !important;
        color: #E6EDF7 !important;
    }

    input:focus,
    textarea:focus,
    select:focus {
        background-color: rgba(36, 52, 90, 0.9) !important;
        color: #E6EDF7 !important;
        border-color: rgba(0, 194, 255, 0.4) !important;
    }

    select option {
        background-color: #0A0E19;
        color: #E6EDF7;
    }

    input:-webkit-autofill,
    textarea:-webkit-autofill,
    select:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px rgba(28, 37, 65, 0.6) inset !important;
        -webkit-text-fill-color: #E6EDF7 !important;
        caret-color: #E6EDF7;
        transition: background-color 5000s ease-in-out 0s;
    }

    input::selection,
    textarea::selection {
        background-color: rgba(0, 194, 255, 0.3);
        color: #fff;
    }
</style>
@endsection