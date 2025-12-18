@extends('layouts.user.index')

@section('title', 'Available Quizzes')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gradient-to-br dark:from-[#0B1120] dark:via-[#0E162B] dark:to-[#0B1A2E] text-gray-200 py-12 px-6">
    <div class="lg:px-6 mx-auto space-y-12">

        {{-- 💡 Available Quizzes --}}
        <div>
            <div class="flex items-center justify-between mb-6 2xl:mb-8">
                <h2 class="text-2xl 2xl:text-3xl font-semibold text-[#00C2FF] flex items-baseline sm:items-center gap-2">
                    <i data-lucide="clipboard-list" class="min-w-6 2xl:w-8 min-h-6 2xl:h-8"></i> Available Quizzes
                </h2>
            </div>

            @if($quizzes->isEmpty())
                <div class="text-center py-24 2xl:py-35 bg-white dark:bg-[#101D35] border dark:border-[#1E2B4A] rounded-2xl shadow-lg">
                    <i class="fa-solid fa-inbox text-5xl text-gray-600 dark:text-gray-500 mb-4"></i>
                    <p class="text-base 2xl:text-lg text-gray-600 dark:text-gray-400">No quizzes available right now.</p>
                    <p class="text-xs 2xl:text-sm text-gray-400 dark:text-gray-500 mt-1">Please check back later!</p>
                </div>
            @else
                <div class="grid md:grid-cols-3  2xl:grid-cols-4 gap-4">
                    @foreach($quizzes as $quiz)
                        <div class="group bg-white dark:bg-[#101D35] border dark:border-[#1E2B4A] rounded-2xl shadow-lg transition-all duration-300 p-6 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg 2xl:text-xl font-semibold text-gray-800 dark:text-[#E2E8F0] capitalize transition mb-2">
                                    {{ $quiz->title }}
                                </h3>
                                <p class="text-xs 2xl:text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-3">
                                    {{ Str::limit($quiz->description ?? 'Test your knowledge and improve your skills!', 100) }}
                                </p>

                                <p class="text-xs 2xl:text-sm text-gray-400 mb-2 line-clamp-3">
                                    {{ $quiz->source }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-3 justify-between items-center mt-auto pt-4 border-t border-gray-200 dark:border-[#1E2B4A]">
                                <span class="text-xs 2xl:text-sm text-[#8A93A8] flex items-center gap-1">
                                    <i class="fa-regular fa-clock text-[#00C2FF]"></i> 
                                    {{ $quiz->duration ?? 10 }} mins
                                </span>
                                <a href="{{ route('user.quizzes.show', $quiz->id) }}"
                                   class="px-4 py-2 bg-gradient-to-r from-[#2F82DB] to-[#00C2FF] whitespace-nowrap text-white text-xs 2xl:text-sm font-medium rounded-lg shadow-md hover:opacity-80 transition-all duration-300">
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
