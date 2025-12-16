@extends('layouts.user.index')

@section('title', 'Practice Test History')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0B1120] via-[#0E162B] to-[#0B1A2E] text-gray-200 py-12 px-6">
    <div class="max-w-6xl mx-auto space-y-10">

        {{-- 🔥 Header --}}
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-semibold text-[#00C2FF] flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left"></i> Practice Test History
            </h2>
        </div>

        @forelse($attempts as $attempt)
            <div class="bg-[#101D35] border border-[#1E2B4A] rounded-2xl shadow-lg p-6 space-y-4">

                {{-- Title & Date --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <h3 class="text-xl font-semibold text-[#E2E8F0]">
                        {{ $attempt->test->title }}
                    </h3>
                    <span class="text-sm text-gray-400">
                        <i class="fa-regular fa-calendar text-[#00C2FF]"></i>
                        {{ $attempt->completed_at->format('d M Y, h:i A') }}
                    </span>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div class="bg-[#0B1730] rounded-xl p-3 text-center border border-[#1E2B4A]">
                        <p class="text-gray-400">Total</p>
                        <p class="text-lg font-semibold">{{ $attempt->total_questions }}</p>
                    </div>

                    <div class="bg-[#0B1730] rounded-xl p-3 text-center border border-[#1E2B4A]">
                        <p class="text-gray-400">Correct</p>
                        <p class="text-lg font-semibold text-green-400">
                            {{ $attempt->correct_answers }}
                        </p>
                    </div>

                    <div class="bg-[#0B1730] rounded-xl p-3 text-center border border-[#1E2B4A]">
                        <p class="text-gray-400">Wrong</p>
                        <p class="text-lg font-semibold text-red-400">
                            {{ $attempt->total_questions - $attempt->correct_answers }}
                        </p>
                    </div>

                    <div class="bg-[#0B1730] rounded-xl p-3 text-center border border-[#1E2B4A]">
                        <p class="text-gray-400">Score</p>
                        <p class="text-lg font-semibold text-[#00C2FF]">
                            {{ $attempt->score }}%
                        </p>
                    </div>
                </div>

                {{-- Toggle Button --}}
                <button
                    onclick="toggleDetails({{ $attempt->id }})"
                    class="mt-3 px-4 py-2 bg-gradient-to-r from-[#2F82DB] to-[#00C2FF] text-white text-sm font-medium rounded-lg shadow-md hover:shadow-[#00C2FF]/30 hover:scale-[1.02] transition-all duration-300">
                    View Details
                </button>

                {{-- DETAILS --}}
                <div id="attempt-{{ $attempt->id }}" class="hidden mt-6 space-y-4">
                    @foreach($attempt->answers as $answer)
                        <div class="bg-[#0B1730] border border-[#1E2B4A] rounded-xl p-5 space-y-2">

                            <p class="font-medium text-[#E2E8F0]">
                                <span class="text-[#00C2FF]">Q:</span>
                                {{ $answer->question->question_text }}
                            </p>

                            <p class="text-sm">
                                <strong class="text-gray-400">Your Answer:</strong>
                                <span class="ml-1 font-semibold">
                                    {{ strtoupper($answer->selected_option) }}
                                </span>
                            </p>

                            <p class="text-sm">
                                <strong class="text-gray-400">Correct Answer:</strong>
                                <span class="ml-1 font-semibold text-green-400">
                                    {{ strtoupper($answer->question->correct_option) }}
                                </span>
                            </p>

                            <div>
                                @if($answer->is_correct)
                                    <span class="inline-flex items-center gap-1 text-xs px-3 py-1 rounded-full bg-green-500/10 text-green-400 border border-green-500/20">
                                        <i class="fa-solid fa-check"></i> Correct
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs px-3 py-1 rounded-full bg-red-500/10 text-red-400 border border-red-500/20">
                                        <i class="fa-solid fa-xmark"></i> Wrong
                                    </span>
                                @endif
                            </div>

                            <p class="text-sm text-gray-400 border-t border-[#1E2B4A] pt-3">
                                <strong class="text-[#8A93A8]">Explanation:</strong>
                                {{ $answer->question->explanation }}
                            </p>
                        </div>
                    @endforeach
                </div>

            </div>
        @empty
            <div class="text-center py-24 bg-[#101D35] border border-[#1E2B4A] rounded-2xl shadow-lg">
                <i class="fa-solid fa-inbox text-5xl text-gray-500 mb-4"></i>
                <p class="text-lg text-gray-400">No completed attempts found.</p>
                <p class="text-sm text-gray-500 mt-1">Attempt some quizzes to see history here.</p>
            </div>
        @endforelse

    </div>
</div>

<script>
function toggleDetails(id) {
    const el = document.getElementById('attempt-' + id);
    el.classList.toggle('hidden');
}
</script>
@endsection
