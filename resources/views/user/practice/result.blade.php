@extends('layouts.user.index')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-[#0B1120] via-[#0E162B] to-[#0B1A2E] py-10 px-6">
    <div class="max-w-3xl mx-auto">

        <!-- RESULT SUMMARY CARD -->
        <div class="backdrop-blur-xl bg-white/5 border border-white/10 
                    rounded-3xl shadow-2xl p-10 hover:border-white/20 transition">

            <h2 class="text-3xl font-bold text-[#00C2FF] mb-6 flex items-center gap-2">
                <i class="fa-solid fa-chart-line"></i>
                Your Result
            </h2>

            <p class="text-gray-300 text-lg mb-3">
                Score:
                <strong class="text-[#00C2FF] text-xl">{{ $attempt->score }}%</strong>
            </p>

            <p class="text-gray-300 text-lg mb-3">
                Correct Answers:
                <strong class="text-green-400">{{ $attempt->correct_answers }}</strong>
                /
                <span class="text-gray-400">{{ $attempt->total_questions }}</span>
            </p>

            <p class="text-gray-300 text-lg">
                Time Taken:
                <strong class="text-yellow-300">{{ gmdate('i:s', $attempt->time_taken) }}</strong>
            </p>
        </div>

        <h3 class="text-2xl font-semibold mt-10 mb-4 text-[#00C2FF]">
            Your Answers
        </h3>

        <!-- ANSWERS LIST -->
        <div class="space-y-5">
            @foreach($attempt->answers as $ans)
                <div class="backdrop-blur-xl p-6 rounded-2xl border shadow-lg 
                    {{ $ans->is_correct 
                        ? 'bg-green-500/10 border-green-400/30' 
                        : 'bg-red-500/10 border-red-400/30' }}">

                    <p class="text-gray-200 text-lg mb-2">
                        <strong class="text-[#00C2FF]">Q:</strong>
                        {{ $ans->question->question_text }}

                    </p>

                    <p class="text-gray-300">
                        <strong class="text-blue-300">Your Answer:</strong>
                        <span class="{{ $ans->is_correct ? 'text-green-400' : 'text-red-400' }}">
                            {{ strtoupper($ans->selected_option) }}
                        </span>
                    </p>

                    <p class="text-gray-300 mt-1">
                        <strong class="text-yellow-300">Correct Answer:</strong>
                        <span class="text-green-400">
                            {{ strtoupper($ans->question->correct_option) }}
                        </span>
                    </p>

                </div>
            @endforeach
        </div>

    </div>
</div>

@endsection
