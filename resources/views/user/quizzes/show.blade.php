@extends('layouts.user.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7] py-10 px-6 flex justify-center items-center">
    <div class="w-full max-w-5xl bg-[#0E1625]/90 backdrop-blur-md border border-[#1B243D] rounded-2xl shadow-[0_0_25px_rgba(0,194,255,0.05)] p-10 animate-fade-in">

        @if($quiz->questions->count() > 0)
            <!-- Quiz Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold mb-3 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] bg-clip-text text-transparent tracking-wide">
                    {{ $quiz->title }}
                </h1>
                <p class="text-[#A8B3CF] text-sm font-medium">Answer all questions carefully before submitting.</p>
            </div>

            <!-- Quiz Form -->
            <form action="{{ route('user.quizzes.submit', $quiz->id) }}" method="POST" class="space-y-8">
                @csrf

                @foreach($quiz->questions as $index => $q)
                    <div class="relative bg-[#111A2E]/80 border border-[#1E2B4A] rounded-xl p-6 hover:border-[#00C2FF]/30 transition-all duration-300 flex justify-between items-start">
                        
                        <!-- Question Text -->
                        <div class="flex-1">
                            <div class="flex items-start gap-3 mb-5">
                                <span class="text-[#00C2FF] font-semibold text-base mt-[2px]">Q{{ $index + 1 }}.</span>
                                <p class="font-medium text-lg text-[#E6EDF7] leading-relaxed flex-1">
                                    {{ $q->question_text }}
                                </p>
                            </div>

                            <!-- Options -->
                            <div class="space-y-3">
                                @foreach($q->options as $optIndex => $option)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="radio" 
                                               name="answers[{{ $q->id }}]" 
                                               value="{{ $optIndex }}" 
                                               required
                                               class="w-4 h-4 accent-[#00C2FF] border border-[#26304D] focus:ring-[#00C2FF] cursor-pointer">
                                        <span class="text-[#A8B3CF] text-base group-hover:text-white transition-colors duration-150">
                                            {{ $option }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Source Icon -->
                        @if($q->source)
                            <button type="button" class="ml-4 text-[#00C2FF] hover:text-[#2F82DB] text-lg" 
                                    onclick="openSourceModal(`{!! addslashes($q->source) !!}`)">
                                <i class="fa-solid fa-circle-info"></i>
                            </button>
                        @endif
                    </div>
                @endforeach

                <!-- Submit -->
                <div class="text-center pt-6">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white font-semibold rounded-lg shadow-[0_0_15px_rgba(0,194,255,0.2)] hover:shadow-[0_0_25px_rgba(0,194,255,0.3)] hover:scale-[1.03] transition-all duration-300">
                        <i class="bi bi-send-fill text-lg"></i> Submit Quiz
                    </button>
                </div>
            </form>

        @else
            <!-- No Questions Available -->
            <div class="flex flex-col items-center justify-center py-24">
                <div class="w-24 h-24 bg-[#101B2E] border border-[#1E2B4A] rounded-full flex items-center justify-center mb-6">
                    <i class="fa-solid fa-circle-question text-[#00C2FF] text-4xl"></i>
                </div>
                <h2 class="text-2xl font-semibold text-white mb-3">No Questions Available</h2>
                <p class="text-[#A8B3CF] text-center max-w-md mb-8 leading-relaxed">
                    This quiz currently doesn’t have any questions. Please check back later or return to your course overview.
                </p>
                <a href="{{ route('user.courses.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#101B2E] border border-[#1E2B4A] hover:border-[#00C2FF]/40 text-white font-medium rounded-lg hover:shadow-[0_0_15px_rgba(0,194,255,0.15)] hover:scale-[1.03] transition-all duration-300">
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
