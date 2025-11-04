@extends('layouts.trainer.index')

@section('content')
<div class="relative min-h-screen flex justify-center px-6 py-10 overflow-hidden 
            bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7]">

    <!-- Soft Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-[#1C2541]/20 via-transparent to-[#2F82DB]/5 
                animate-gradient-slow"></div>

    <!-- Main Wrapper -->
    <div class="relative w-full max-w-8xl bg-[#10182F]/70 backdrop-blur-xl rounded-md shadow-2xl 
                p-8 border border-[#2F82DB]/10 z-10 self-start ">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-10">
            <h1 class="text-2xl font-semibold text-white tracking-wide">
                Manage Quizzes
            </h1>
            <a href="{{ route('trainer.quizzes.create') }}"
               class="px-5 py-2 rounded-lg font-medium bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white hover:shadow-[#00C2FF]/40 transition-all">
                + Create Quiz
            </a>
        </div>

        <!-- Quiz List -->
        @if($quizzes->isEmpty())
            <div class="text-center py-16 border border-dashed border-[#2F82DB]/20 rounded-xl">
                <p class="text-gray-400 text-sm">
                    No quizzes found. Click “Create Quiz” to add one.
                </p>
            </div>
        @else
            <div class="space-y-5">
                @foreach($quizzes as $quiz)
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center
                                bg-[#141C33]/70 border border-[#2F82DB]/10 rounded-xl p-6 
                                hover:border-[#2F82DB]/25 hover:bg-[#141C33]/90 
                                transition duration-200 shadow-sm hover:shadow-md">

                        <!-- Left: Quiz Info -->
                        <div class="flex-1">
                            <a href="{{ route('trainer.quizzes.questions', $quiz->id) }}"
                               class="text-lg font-semibold text-[#7AC7FF] hover:text-[#9fd5ff] transition">
                                {{ $quiz->title }}
                            </a>
                            <p class="text-gray-400 text-sm mt-1">
                                {{ $quiz->description ?? 'No description available' }}
                            </p>
                        </div>

                        <!-- Right: Actions -->
                        <div class="flex gap-3 mt-4 sm:mt-0">
                            
                            <button data-id="{{ $quiz->id }}" 
                                    class="delete-quiz-btn px-3 py-1.5 rounded-md bg-[#3A1F2F] text-red-400 hover:bg-[#4A2639] transition-all duration-200">
                                Delete
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('.delete-quiz-btn').click(function() {
        if(!confirm('Are you sure you want to delete this quiz?')) return;

        let quizId = $(this).data('id');
        let row = $(this).closest('div');

        $.ajax({
            url: '/trainer/quizzes/' + quizId,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                row.fadeOut(400, function() { $(this).remove(); });
            },
            error: function() {
                alert('Something went wrong. Please try again.');
            }
        });
    });
});
</script>
@endsection
