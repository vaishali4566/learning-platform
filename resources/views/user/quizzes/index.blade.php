@extends('layouts.user.index')

@section('title', 'Available Quizzes')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0B1120] via-[#0E162B] to-[#0B1A2E] text-gray-200 py-12 px-6">
    <div class="max-w-6xl mx-auto space-y-12">

        {{-- 💡 Available Quizzes --}}
        <div>
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-3xl font-semibold text-[#00C2FF] flex items-center gap-2">
                    <i class="fa-solid fa-circle-question"></i> Available Quizzes
                </h2>
            </div>

            @if($quizzes->isEmpty())
                <div class="text-center py-24 bg-[#101D35] border border-[#1E2B4A] rounded-2xl shadow-lg">
                    <i class="fa-solid fa-inbox text-5xl text-gray-500 mb-4"></i>
                    <p class="text-lg text-gray-400">No quizzes available right now.</p>
                    <p class="text-sm text-gray-500 mt-1">Please check back later!</p>
                </div>
            @else
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($quizzes as $quiz)
                        <div class="group bg-[#101D35] border border-[#1E2B4A] rounded-2xl shadow-lg hover:shadow-[#00C2FF]/20 hover:-translate-y-1 transition-all duration-300 p-6 flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-[#E2E8F0] group-hover:text-[#00C2FF] transition mb-2">
                                    {{ $quiz->title }}
                                </h3>
                                <p class="text-sm text-gray-400 mb-4 line-clamp-3">
                                    {{ Str::limit($quiz->description ?? 'Test your knowledge and improve your skills!', 100) }}
                                </p>

                                <p class="text-sm text-gray-400 mb-4 line-clamp-3">
                                    {{ $quiz->source }}
                                </p>
                            </div>

                            <div class="flex justify-between items-center mt-auto pt-3 border-t border-[#1E2B4A]">
                                <span class="text-sm text-[#8A93A8] flex items-center gap-1">
                                    <i class="fa-regular fa-clock text-[#00C2FF]"></i> 
                                    {{ $quiz->duration ?? 10 }} mins
                                </span>
                                <a href="{{ route('user.quizzes.show', $quiz->id) }}"
                                   class="px-4 py-2 bg-gradient-to-r from-[#2F82DB] to-[#00C2FF] text-white text-sm font-medium rounded-lg shadow-md hover:shadow-[#00C2FF]/30 hover:scale-[1.02] transition-all duration-300">
                                    Attempt Quiz →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
