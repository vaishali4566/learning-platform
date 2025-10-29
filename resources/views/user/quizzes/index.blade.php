@extends('layouts.user.index')

@section('title', 'Available Quizzes')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] p-8">
    <h1 class="text-2xl font-bold text-[#E6EDF7] mb-6 flex items-center gap-2">
        <i data-lucide="help-circle" class="w-6 h-6 text-[#00C2FF]"></i> Available Quizzes
    </h1>

    @if($quizzes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($quizzes as $quiz)
                <div class="bg-gradient-to-br from-[#1C2541] to-[#11182A] border border-[#26304D] rounded-lg p-6 shadow-md hover:shadow-[#00C2FF]/20 transition-all duration-300 hover:-translate-y-1">
                    <h2 class="text-xl font-semibold text-[#E6EDF7] mb-2">{{ $quiz->title }}</h2>
                    <p class="text-sm text-[#8A93A8] mb-4">
                        {{ Str::limit($quiz->description ?? 'Test your knowledge and improve your skills!', 80) }}
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-[#8A93A8]">
                            <i data-lucide="clock" class="inline w-4 h-4 mr-1 text-[#00C2FF]"></i>
                            {{ $quiz->duration ?? 10 }} mins
                        </span>
                        <a href="{{ route('user.quizzes.show', $quiz->id) }}"
                           class="px-4 py-2 bg-[#00C2FF] text-[#0A0E19] font-semibold rounded-md hover:bg-[#00E0FF] transition-all duration-300">
                            Attempt Quiz →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center mt-20">
            <i data-lucide="inbox" class="w-16 h-16 text-[#26304D] mx-auto mb-4"></i>
            <p class="text-[#8A93A8] text-lg">No quizzes available right now.</p>
            <p class="text-[#E6EDF7] text-sm mt-1">Please check back later!</p>
        </div>
    @endif
</div>
@endsection
