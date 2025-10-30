@extends('layouts.trainer.index')

@section('content')
<div class="relative min-h-screen flex justify-center px-6 py-10 overflow-hidden 
            bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33]">

    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-[#00C2FF]/10 via-transparent to-[#2F82DB]/5 
                animate-gradient-slow"></div>

    <!-- Main Container -->
    <div class="relative w-full max-w-5xl bg-[#0E1426]/80 backdrop-blur-xl rounded-2xl shadow-2xl 
                p-8 border border-[#2F82DB]/20 z-10 self-start mt-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-center justify-between mb-8">
            <h1 class="text-2xl font-semibold text-white tracking-wide mb-4 sm:mb-0">
                Manage Quizzes
            </h1>
            <a href="{{ route('trainer.quizzes.create') }}"
               class="bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] hover:opacity-90 text-white 
                      font-medium text-sm px-4 py-2 rounded-lg transition duration-200 shadow-md">
                + Create Quiz
            </a>
        </div>

        <!-- Quiz List -->
        @if($quizzes->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-400 text-sm">
                    No quizzes found. Click “Create Quiz” to add one.
                </p>
            </div>
        @else
            <ul class="space-y-4">
                @foreach($quizzes as $quiz)
                    <li class="flex flex-col sm:flex-row justify-between items-start sm:items-center 
                               bg-[#141C33]/70 border border-[#00C2FF]/10 rounded-xl p-5 
                               hover:shadow-lg hover:border-[#00C2FF]/30 transition duration-200">

                        <div>
                            <!-- ✅ Clickable Quiz Title -->
                            <a href="{{ route('trainer.quizzes.questions', $quiz->id) }}"
                               class="text-lg font-semibold text-[#2eceff] underline hover:text-[#00C2FF] h transition">
                                {{ $quiz->title }}
                            </a>

                            <p class="text-gray-400 text-xs mt-1">
                                {{ $quiz->description ?? 'No description available' }}
                            </p>
                        </div>

                        <div class="flex gap-3 mt-4 sm:mt-0">
                            <a href="{{ route('trainer.quizzes.edit', $quiz->id) }}"
                               class="px-3 py-1.5 bg-gradient-to-r from-[#2F82DB] to-[#00C2FF] 
                                      text-white text-sm rounded-md font-medium hover:opacity-90 transition">
                                Edit
                            </a>
                            <button data-id="{{ $quiz->id }}" 
                                    class="delete-quiz-btn px-3 py-1.5 bg-red-600 text-white 
                                           text-sm rounded-md font-medium hover:bg-red-700 transition">
                                Delete
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
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
        let row = $(this).closest('li');

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
