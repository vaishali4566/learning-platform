@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">All Quizzes</h1>
            <a href="{{ route('trainer.quizzes.create') }}"
               class="bg-gradient-to-r from-blue-800 to-green-700 text-white font-semibold px-4 py-2 rounded-lg hover:opacity-90">
                Create New Quiz
            </a>
        </div>

        @if($quizzes->isEmpty())
            <p class="text-gray-600">No quizzes found. Click "Create New Quiz" to add one.</p>
        @else
            <ul class="space-y-4">
                @foreach($quizzes as $quiz)
                    <li class="flex justify-between items-center bg-gray-50 p-4 rounded-lg shadow-sm hover:shadow-md transition">
                        <span class="font-semibold text-gray-700">{{ $quiz->title }}</span>
                        <div class="flex gap-2">
                            <a href="{{ route('trainer.quizzes.edit', $quiz->id) }}"
                               class="text-white bg-blue-600 px-3 py-1 rounded-lg hover:bg-blue-700">
                                Edit
                            </a>
                            <button data-id="{{ $quiz->id }}" class="delete-quiz-btn text-white bg-red-600 px-3 py-1 rounded-lg hover:bg-red-700">
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
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Quiz deleted successfully!');
                row.remove();
            },
            error: function() {
                alert('Something went wrong. Please try again.');
            }
        });
    });
});
</script>
@endsection
