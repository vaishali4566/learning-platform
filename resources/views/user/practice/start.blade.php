@extends('layouts.user.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0B1120] via-[#0E162B] to-[#0B1A2E] text-gray-200 py-12 px-6">
    <div class="max-w-3xl mx-auto">

        <div class="bg-[#101D35] border border-[#1E2B4A] rounded-2xl shadow-xl 
                    hover:shadow-[#00C2FF]/20 transition-all duration-300 p-8">

            <h2 class="text-xl font-semibold mb-4 text-[#00C2FF] flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i>
                {{ $test->title }}
            </h2>

            <p class="text-gray-300 mb-3 text-md">
                <span class="font-medium text-gray-400">Total Questions:</span>
                <span class="text-[#00C2FF] font-semibold">{{ $test->total_questions }}</span>
            </p>

            <p class="text-gray-300 mb-6 text-md">
                <span class="font-medium text-gray-400">Duration:</span>
                <span class="text-[#00C2FF] font-semibold">10 minutes</span>
            </p>

            <div class="mt-8 text-center">
                <form method="POST" action="{{ route('user.practice.start.attempt', $test->lesson_id) }}">
                    @csrf

                    <button 
                        class="px-10 py-3 bg-[#00C2FF] text-[#0B1120] text-lg 
                               rounded-xl font-medium hover:bg-[#00AEE3] 
                               shadow-lg focus:ring-4 focus:ring-[#00C2FF]/40 
                               transition-all">
                        Start Test
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
