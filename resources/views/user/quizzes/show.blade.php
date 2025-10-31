@extends('layouts.user.index')

@section('content')
<div class="max-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7] py-6 px-6 flex justify-center">
    <div class="w-full max-w-8xl bg-[#0E1625]/90 backdrop-blur-lg border border-[#26304D] rounded-md shadow-2xl p-10 animate-fade-in">

        <!-- Quiz Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold mb-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] bg-clip-text text-transparent">
                {{ $quiz->title }}
            </h1>
            <p class="text-[#A8B3CF] text-sm font-medium">Answer all questions carefully before submitting.</p>
        </div>

        <!-- Quiz Form -->
        <form action="{{ route('user.quizzes.submit', $quiz->id) }}" method="POST" class="space-y-8">
            @csrf

            @foreach($quiz->questions as $index => $q)
                <div class="relative group bg-[#141C33]/80 border border-[#26304D] rounded-xl p-6 transition-all duration-300 ease-in-out hover:shadow-lg hover:shadow-[#00C2FF]/10 hover:border-[#00C2FF]/30">
                    
                    <!-- Floating Accent -->
                    <div class="absolute -top-2 -left-2 w-10 h-10 bg-gradient-to-br from-[#00C2FF]/20 to-transparent rounded-tr-2xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>

                    <!-- Question -->
                    <div class="flex items-start gap-3 mb-5">
                        <span class="text-[#00C2FF] font-semibold text-lg">Q{{ $index + 1 }}.</span>
                        <p class="font-medium text-lg leading-relaxed text-[#E6EDF7] flex-1">
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
                                       class="w-4 h-4 text-[#00C2FF] focus:ring-[#00C2FF] border-[#26304D] cursor-pointer">
                                <span class="text-[#A8B3CF] text-base group-hover:text-[#E6EDF7] transition-colors duration-200">
                                    {{ $option }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Submit -->
            <div class="text-center pt-6">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-8 py-2.5 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white font-semibold rounded-lg shadow-lg shadow-[#00C2FF]/30 hover:shadow-[#00C2FF]/50 hover:scale-[1.05] transition-all duration-300">
                    <i class="bi bi-send-fill"></i> Submit Quiz
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
