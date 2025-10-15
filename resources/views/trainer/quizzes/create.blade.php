@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Create New Quiz</h1>

        <form id="create-quiz-form" action="{{ route('trainer.quizzes.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Lesson ID</label>
                <input type="number" name="lesson_id" placeholder="Enter Lesson ID"
                    class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600">
                <p class="text-red-600 text-sm mt-1 hidden" id="lesson-error"></p>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Quiz Title</label>
                <input type="text" name="title" placeholder="Enter Quiz Title"
                    class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600">
                <p class="text-red-600 text-sm mt-1 hidden" id="title-error"></p>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" placeholder="Quiz Description" rows="4"
                    class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
                <p class="text-red-600 text-sm mt-1 hidden" id="description-error"></p>
            </div>

            <p class="text-gray-600">Total Marks: 0 (will update after adding questions)</p>
            <p class="text-gray-600 mb-4">Passing Marks (33%): 0 (will update after adding questions)</p>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-800 to-green-700 text-white font-semibold py-3 rounded-lg hover:opacity-90">
                Create Quiz
            </button>
        </form>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('#create-quiz-form').on('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        $('#lesson-error').text('').hide();
        $('#title-error').text('').hide();
        $('#description-error').text('').hide();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    alert('Quiz created successfully!');
                    // Redirect to edit page to add questions
                    window.location.href = '/trainer/quizzes/' + response.quiz_id + '/edit';
                }
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if(errors.lesson_id) {
                        $('#lesson-error').text(errors.lesson_id[0]).show();
                    }
                    if(errors.title) {
                        $('#title-error').text(errors.title[0]).show();
                    }
                    if(errors.description) {
                        $('#description-error').text(errors.description[0]).show();
                    }
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        });
    });
});
</script>
@endsection
