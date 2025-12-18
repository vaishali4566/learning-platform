@extends('layouts.user.index')

@section('content')
<div class="h-full bg-gray-50 dark:bg-gradient-to-br dark:from-[#0B1120] dark:via-[#0E162B] dark:to-[#0B1A2E] text-[#E6EDF7] py-10 px-6 flex justify-center items-center">
    <div class="w-full max-w-3xl 2xl:max-w-5xl bg-white dark:bg-[#1C2541]/90 backdrop-blur-md border dark:border-[#1B243D] rounded-2xl shadow-[0_0_25px_rgba(0,194,255,0.05)] p-4 md:p-10 animate-fade-in">

        @if($quiz->questions->count() > 0)
            <!-- Quiz Header -->
            <div class="text-center mb-8 2xl:mb-12">
                <h1 class="text-2xl md:text-3xl 2xl:text-4xl font-bold mb-2 2xl:mb-3 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] bg-clip-text text-transparent tracking-wide capitalize">
                    {{ $quiz->title }}
                </h1>
                <p class="text-gray-500 dark:text-[#A8B3CF] text-xs 2xl:text-sm font-medium">Answer all questions carefully before submitting.</p>
            </div>

            <!-- Quiz Form -->
            <form action="{{ route('user.quizzes.submit', $quiz->id) }}" method="POST" class="space-y-5 md:space-y-7">
                @csrf

                @foreach($quiz->questions as $index => $q)
                    <div class="relative dark:bg-[#131c30]/80 border border-gray-200 dark:border-[#1E2B4A] rounded-xl p-4 md:p-6 transition-all duration-300 flex justify-between items-start">
                        
                        <!-- Question Text -->
                        <div class="flex-1">
                            <div class="flex items-start gap-3 mb-5">
                                <span class="text-[#00C2FF] font-semibold text-base mt-[2px]">Q{{ $index + 1 }}.</span>
                                <p class="font-medium text-sm sm:text-base 2xl:text-lg text-gray-700 dark:text-[#E6EDF7] leading-relaxed flex-1">
                                    {{ $q->question_text }}
                                </p>
                            </div>

                            <!-- Options -->
                            <div class="space-y-3">
                                @foreach($q->options as $optIndex => $option)
                                    <label class="flex items-center gap-3 cursor-pointer relative group  hover:bg-gray-100 hover:dark:bg-[#1C2541] rounded-lg transition duration-150 border-[#00C2FF] pl-3 pr-2 py-2">
                                        <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300"></div>
                                        <input type="radio" 
                                               name="answers[{{ $q->id }}]" 
                                               value="{{ $optIndex }}" 
                                               required
                                               class="w-4 h-4 accent-[#2788d1] dark:accent-[#00C2FF] border dark:border-[#26304D] focus:ring-[#00C2FF] cursor-pointer">
                                        <span class="text-gray-600 dark:text-[#A8B3CF] text-sm 2xl:text-base">
                                            {{ $option }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Source Icon -->
                        @if($q->source)
                        <div 
                            x-data="{ open: false }"
                            class="relative ml-4"
                        >
                            <!-- Icon -->
                            <button 
                                @click="open = !open"
                                @click.outside="open = false"
                                class="text-[#00C2FF] hover:text-[#2F82DB] text-lg"
                            >
                                <i class="fa-solid fa-circle-info"></i>
                            </button>

                            <!-- Tooltip Popover -->
                            <div 
                                x-show="open"
                                class="absolute left-1/2 -translate-x-1/2 mt-1 py-1 z-50 
                                    dark:bg-white dark:text-gray-700 border shadow-sm rounded-lg px-4
                                    bg-[#0E1625] backdrop-blur-md  text-[#E6EDF7] dark:border-[#1E2B4A]
                                    text-sm leading-relaxed"
                                style="display: none;"
                            >
                                <div class="absolute -top-2 left-1/2 -translate-x-1/2 
                                    w-0 h-0 
                                    border-l-8 border-l-transparent
                                    border-r-8 border-r-transparent
                                    border-b-8 dark:border-b-gray-200 border-b-[#0E1625]">
                                </div>

                                {!! nl2br(e($q->source)) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                @endforeach

                <!-- Submit -->
                <div class="text-center pt-0 2xl:pt-4">
                    <button type="submit"
                        class="inline-flex items-center text-sm 2xl:text-base gap-2 px-8 py-2.5 2xl:py-3 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white font-semibold rounded-lg shadow-[0_0_15px_rgba(0,194,255,0.2)] hover:shadow-[0_0_25px_rgba(0,194,255,0.3)] hover:opacity-80 transition-all duration-300">
                        Submit Quiz
                    </button>
                </div>
            </form>

        @else
            <!-- No Questions Available -->
            <div class="flex flex-1 flex-col items-center justify-center 2xl:py-16">
                <div class="w-16 h-16 2xl:w-20 2xl:h-20 bg-white dark:bg-[#101B2E] border dark:border-[#1E2B4A] rounded-full flex items-center justify-center mb-6">
                    <i class="fa-solid fa-circle-question text-[#00C2FF] text-4xl"></i>
                </div>
                <h2 class="text-xl 2xl:text-2xl text-center font-semibold text-gray-800 dark:text-white mb-3">No Questions Available</h2>
                <p class="text-gray-500 dark:text-[#A8B3CF] text-sm 2xl:text-base text-center max-w-md mb-8 leading-relaxed">
                    This quiz currently doesn’t have any questions. Please check back later or return to your course overview.
                </p>
                <a href="{{ route('user.courses.index') }}"
                   class="text-xs 2xl:text-sm inline-flex items-center gap-2 px-6 py-2.5 bg-[#00c2ff] border dark:border-[#1E2B4A] hover:border-[#00C2FF]/40 text-white font-medium rounded-lg hover:shadow-[0_0_15px_rgba(0,194,255,0.15)] hover:opacity-80 transition-all duration-300">
                    <i class="fa-solid fa-arrow-left"></i> Back to Courses
                </a>
            </div>
        @endif

    </div>
</div>

<!-- Source Modal -->
<div id="sourceModal" class="fixed inset-0 hidden bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-[#0E1625] rounded-2xl w-full max-w-lg p-6 relative">
        <button onclick="closeSourceModal()" class="absolute top-4 right-4 text-white text-xl">&times;</button>
        <h3 class="text-xl font-semibold text-[#00C2FF] mb-4">Question Source</h3>
        <div class="max-h-80 overflow-y-auto text-[#E6EDF7] whitespace-pre-wrap">
            <p id="sourceContent"></p>
        </div>
    </div>
</div>

<!-- JS -->
<script>
function openSourceModal(sourceText) {
    document.getElementById('sourceContent').textContent = sourceText;
    document.getElementById('sourceModal').classList.remove('hidden');
}

function closeSourceModal() {
    document.getElementById('sourceModal').classList.add('hidden');
}
</script>
@endsection
