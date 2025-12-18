@extends('layouts.user.index')

@section('content')
<div class="h-full bg-gray-50 dark:bg-gradient-to-br dark:from-[#0B1120] dark:via-[#0E162B] dark:to-[#0B1A2E] text-gray-800 dark:text-[#E6EDF7] px-6 py-12 flex justify-center items-center">

   <div class="w-full max-w-3xl 2xl:max-w-5xl bg-white dark:bg-[#1C2541]/90 backdrop-blur-md border dark:border-[#1B243D] rounded-2xl shadow-[0_0_25px_rgba(0,194,255,0.05)] p-4 md:p-10 animate-fade-in">
        <!-- Header -->
        <div class="flex items-center justify-between mb-10 animate-fade-in">
            <h1 class="text-2xl font-semibold capitalize tracking-wide">
                {{ $quiz->title }} – Results
            </h1>
            <a href="{{ route('user.quizzes.index') }}" 
            class="text-gray-500 hover:text-gray-400 dark:text-[#A8B3CF] dark:hover:text-[#E6EDF7] transition text-sm font-medium">
            <i class="bi bi-arrow-left"></i> Back to Quizzes
            </a>
        </div>

        <!-- Score Summary -->
        <div class="relative dark:bg-[#0E1625]/80 border border-gray-200 dark:border-[#26304D] rounded-2xl shadow-sm backdrop-blur-lg p-10 mb-12 text-center">
            {{-- <div class="absolute inset-0 bg-gradient-to-r from-[#00C2FF]/10 to-[#2F82DB]/10 rounded-2xl blur-xl"></div> --}}

            <div class="relative z-10">
                <h2 class="text-2xl font-semibold mb-3">Performance Summary</h2>

                <!-- Score -->
                <div class="flex justify-center items-end gap-2 mb-4">
                    <p class="text-6xl font-extrabold bg-[#00c2ff] dark:bg-gradient-to-r dark:from-[#00C2FF] bg-clip-text text-transparent">
                        {{ $score }}
                    </p>
                    <p class="text-2xl text-gray-400 dark:text-[#A8B3CF] font-semibold">/ {{ $totalMarks }}</p>
                </div>

                <!-- Progress Bar -->
                @php
                    $percent = ($totalMarks > 0) ? round(($score / $totalMarks) * 100) : 0;
                @endphp
                @if($percent>0)
                    <div class="w-full bg-gray-200 dark:bg-[#1C2541] h-3 rounded-full overflow-hidden mb-4">
                        <div class="h-3 bg-[#00c2ff] dark:bg-gradient-to-r dark:from-[#00C2FF]" style="width: {{ $percent }}%;"></div>
                    </div>
                @endif

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
                <div class="mt-6 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('user.quizzes.show', $quiz->id) }}"
                    class="px-6 py-2.5 bg-[#00c2ff] text-xs md:text-sm lg:text-base whitespace-nowrap dark:bg-gradient-to-r dark:from-[#00C2FF] dark:to-[#2F82DB] text-white rounded-lg shadow-sm hover:opacity-80 transition">
                    <i class="bi bi-arrow-repeat"></i> Retake Quiz
                    </a>
                    <a href="{{ route('user.quizzes.index') }}"
                    class="px-6 py-2.5 bg-[#141C33] text-xs md:text-sm lg:text-base whitespace-nowrap border border-[#2F82DB]/40 text-[#E6EDF7] rounded-lg hover:opacity-70 dark:hover:bg-[#2F82DB]/10 transition">
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

                <div class="relative dark:bg-[#0E1625]/80 border border-gray-200 dark:border-[#26304D] rounded-2xl rounded-l-lg shadow-sm p-6 transition duration-300">
                    <!-- Left Accent -->
                    <div class="absolute left-0 top-0 h-full w-1 rounded-2xl
                        @if($isCorrect) bg-green-400 @elseif($userAnswer) bg-red-400 @else bg-gray-500 @endif"></div>

                    <!-- Question -->
                    <div class="flex items-center justify-between mb-3 pl-3">
                        <h3 class="text-lg font-semibold leading-snug">
                            <span class="text-[#00C2FF]">Q{{ $index + 1 }}.</span> {{ $question->question }}
                        </h3>

                        @if($isCorrect)
                            <span class="text-green-600 font-semibold flex items-center gap-1">
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
                                @if($isCorrectOption)
                                    bg-green-200/20 dark:bg-green-900/30 border-green-300 dark:border-green-500/50
                                @elseif($isUserSelected && !$isCorrectOption)
                                    bg-red-200/30 dark:bg-red-900/30 border-red-300 dark:border-red-500/50
                                @else
                                  border-gray-200 dark:border-[#26304D]
                                @endif
                            ">
                                <span class="text-gray-600 dark:text-[#A8B3CF] @if($isCorrectOption) font-semibold text-green-500  @endif">
                                    {{ $optionText }}
                                </span>

                                <div class="flex items-center gap-3 text-sm">
                                    @if($isUserSelected && !$isCorrectOption)
                                        <span class="text-red-500 dark:text-red-400"><i class="bi bi-person-check"></i> Your Answer</span>
                                    @endif
                                    @if($isCorrectOption)
                                        <span class="text-green-500"><i class="bi bi-check2"></i> Correct</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
   </div>
</div>
@endsection
