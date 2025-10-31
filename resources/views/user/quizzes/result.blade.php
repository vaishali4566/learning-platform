@extends('layouts.user.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7] px-6 py-12">

    <!-- Header -->
    <div class="flex items-center justify-between mb-10 animate-fade-in">
        <h1 class="text-2xl font-extrabold tracking-wide ">
            {{ $quiz->title }} – Results
        </h1>
        <a href="{{ route('user.quizzes.index') }}" 
           class="text-[#A8B3CF] hover:text-[#E6EDF7] transition text-sm font-medium">
           <i class="bi bi-arrow-left"></i> Back to Quizzes
        </a>
    </div>

    <!-- Score Summary -->
    <div class="relative bg-[#0E1625]/90 border border-[#26304D] rounded-2xl shadow-2xl backdrop-blur-lg p-10 mb-12 text-center">
        <div class="absolute inset-0 bg-gradient-to-r from-[#00C2FF]/10 to-[#2F82DB]/10 rounded-2xl blur-xl"></div>

        <div class="relative z-10">
            <h2 class="text-2xl font-semibold mb-3">Performance Summary</h2>

            <!-- Score -->
            <div class="flex justify-center items-end gap-2 mb-4">
                <p class="text-6xl font-extrabold bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] bg-clip-text text-transparent">
                    {{ $score }}
                </p>
                <p class="text-2xl text-[#A8B3CF] font-semibold">/ {{ $totalMarks }}</p>
            </div>

            <!-- Progress Bar -->
            @php
                $percent = ($totalMarks > 0) ? round(($score / $totalMarks) * 100) : 0;
            @endphp
            <div class="w-full bg-[#1C2541] h-3 rounded-full overflow-hidden mb-4">
                <div class="h-3 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB]" style="width: {{ $percent }}%;"></div>
            </div>

            <!-- Feedback -->
            <div class="text-sm font-medium">
                @if ($score == $totalMarks)
                    <p class="text-green-400 flex items-center justify-center gap-2">
                        <i class="bi bi-trophy-fill"></i> Excellent! Perfect Score
                    </p>
                @elseif ($score > ($totalMarks / 2))
                    <p class="text-blue-400 flex items-center justify-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Great Work! You Passed
                    </p>
                @else
                    <p class="text-red-400 flex items-center justify-center gap-2">
                        <i class="bi bi-x-circle-fill"></i> Keep Practicing! You’ll Improve
                    </p>
                @endif
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex justify-center gap-4">
                <a href="{{ route('user.quizzes.show', $quiz->id) }}"
                   class="px-6 py-2.5 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white rounded-lg shadow-lg hover:scale-[1.05] transition">
                   <i class="bi bi-arrow-repeat"></i> Retake Quiz
                </a>
                <a href="{{ route('user.quizzes.index') }}"
                   class="px-6 py-2.5 bg-[#141C33] border border-[#2F82DB]/40 text-[#E6EDF7] rounded-lg hover:bg-[#2F82DB]/10 transition">
                   <i class="bi bi-house-door"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Questions Review -->
    <div class="space-y-8">
        @foreach($quiz->questions as $index => $question)
            @php
                $userAnswer = $answers[$question->id] ?? null;
                $isCorrect = $userAnswer?->is_correct;
            @endphp

            <div class="relative bg-[#0E1625]/80 border border-[#26304D] rounded-2xl shadow-lg p-6 hover:shadow-[#00C2FF]/20 transition duration-300">
                <!-- Left Accent -->
                <div class="absolute left-0 top-0 h-full w-1 rounded-l-2xl 
                    @if($isCorrect) bg-green-400 @elseif($userAnswer) bg-red-400 @else bg-gray-500 @endif"></div>

                <!-- Question -->
                <div class="flex items-center justify-between mb-3 pl-3">
                    <h3 class="text-lg font-semibold leading-snug">
                        <span class="text-[#00C2FF]">Q{{ $index + 1 }}.</span> {{ $question->question }}
                    </h3>

                    @if($isCorrect)
                        <span class="text-green-400 font-semibold flex items-center gap-1">
                            <i class="bi bi-check-circle"></i> Correct
                        </span>
                    @elseif($userAnswer)
                        <span class="text-red-400 font-semibold flex items-center gap-1">
                            <i class="bi bi-x-circle"></i> Incorrect
                        </span>
                    @else
                        <span class="text-gray-400 italic flex items-center gap-1">
                            <i class="bi bi-dash-circle"></i> Not Answered
                        </span>
                    @endif
                </div>

                <!-- Options -->
                <ul class="space-y-2 mt-3 pl-3">
                    @foreach($question->options as $optionIndex => $optionText)
                        @php
                            $isUserSelected = $userAnswer && $userAnswer->selected_option == $optionIndex;
                            $isCorrectOption = $question->correct_option == $optionIndex;
                        @endphp

                        <li class="px-4 py-2 rounded-lg border flex items-center justify-between
                            @if($isUserSelected && $isCorrectOption)
                                bg-green-900/30 border-green-500/50
                            @elseif($isUserSelected && !$isCorrectOption)
                                bg-red-900/30 border-red-500/50
                            @elseif($isCorrectOption)
                                bg-blue-900/30 border-blue-500/50
                            @else
                                border-[#26304D]
                            @endif
                        ">
                            <span class="text-[#A8B3CF] @if($isUserSelected) font-semibold text-[#E6EDF7] @endif">
                                {{ $optionText }}
                            </span>

                            <div class="flex items-center gap-2 text-sm">
                                @if($isUserSelected)
                                    <span class="text-[#00C2FF]"><i class="bi bi-person-check"></i> Your Answer</span>
                                @endif
                                @if($isCorrectOption)
                                    <span class="text-green-400"><i class="bi bi-check2"></i> Correct</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
@endsection
