@extends('layouts.user.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7] py-12 px-6 flex justify-center">
    <div class="w-full max-w-3xl bg-[#0E1625]/90 backdrop-blur-md border border-[#26304D] rounded-2xl shadow-2xl p-8 animate-fade-in">

        <!-- Quiz Title -->
        <h1 class="text-3xl font-bold mb-6 text-center bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] bg-clip-text text-transparent">
            {{ $quiz->title }}
        </h1>

        <!-- Quiz Form -->
        <form action="{{ route('user.quizzes.submit', $quiz->id) }}" method="POST" class="space-y-8">
            @csrf

            @foreach($quiz->questions as $index => $q)
                <div class="bg-[#141C33]/70 border border-[#26304D] rounded-xl p-6 hover:shadow-lg hover:shadow-[#00C2FF]/10 transition-all duration-300 ease-in-out">
                    <!-- Question -->
                    <p class="font-semibold text-lg mb-4">
                        <span class="text-[#00C2FF]">Q{{ $index + 1 }}.</span> {{ $q->question_text }}
                    </p>

                    <!-- Options -->
                    <div class="space-y-3">
                        @foreach($q->options as $optIndex => $option)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $optIndex }}" required
                                    class="w-4 h-4 text-[#00C2FF] focus:ring-[#00C2FF] border-[#26304D]">
                                <span class="text-[#A8B3CF] group-hover:text-[#E6EDF7] transition-colors duration-300">
                                    {{ $option }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Submit Button -->
            <div class="text-center pt-4">
                <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white font-semibold rounded-lg shadow-lg shadow-[#00C2FF]/30 hover:shadow-[#00C2FF]/50 hover:scale-[1.05] transition-all duration-300">
                    Submit Quiz
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
