@extends('layouts.trainer')

@section('content')
<div class="relative min-h-screen flex items-center justify-center px-6 py-10 overflow-hidden 
            bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33]">

    <!-- Animated Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-[#00C2FF]/10 via-transparent to-[#2F82DB]/5 animate-gradient-slow"></div>

    <!-- Create Quiz Card -->
    <div class="relative w-full max-w-2xl bg-[#0E1426]/80 backdrop-blur-xl rounded-2xl shadow-2xl 
                border border-[#2F82DB]/20 p-8 z-10">

        <h1 class="text-2xl font-semibold text-center text-white mb-8 tracking-wide">
            Create New Quiz
        </h1>

        <form id="create-quiz-form" action="{{ route('trainer.quizzes.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Lesson ID -->
            <div>
                <label class="block text-sm font-medium text-[#AAB3C7] mb-1">Lesson ID</label>
                <input type="number" name="lesson_id" placeholder="Enter lesson ID"
                    class="w-full p-3 text-sm bg-[#101727] border border-[#1E2A45] rounded-lg 
                           text-white placeholder-gray-500 focus:outline-none 
                           focus:ring-2 focus:ring-[#00C2FF]">
                <p class="text-red-500 text-xs mt-1 hidden" id="lesson-error"></p>
            </div>

            <!-- Quiz Title -->
            <div>
                <label class="block text-sm font-medium text-[#AAB3C7] mb-1">Quiz Title</label>
                <input type="text" name="title" placeholder="Enter quiz title"
                    class="w-full p-3 text-sm bg-[#101727] border border-[#1E2A45] rounded-lg 
                           text-white placeholder-gray-500 focus:outline-none 
                           focus:ring-2 focus:ring-[#00C2FF]">
                <p class="text-red-500 text-xs mt-1 hidden" id="title-error"></p>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-[#AAB3C7] mb-1">Description</label>
                <textarea name="description" rows="4" placeholder="Enter quiz description"
                    class="w-full p-3 text-sm bg-[#101727] border border-[#1E2A45] rounded-lg 
                           text-white placeholder-gray-500 focus:outline-none 
                           focus:ring-2 focus:ring-[#00C2FF]"></textarea>
                <p class="text-red-500 text-xs mt-1 hidden" id="description-error"></p>
            </div>

            <div class="pt-2">
                <p class="text-gray-400 text-xs">Total Marks: <span class="text-white">0</span> (updates after adding questions)</p>
                <p class="text-gray-400 text-xs">Passing Marks: <span class="text-white">0</span> (33% of total)</p>
            </div>

            <button type="submit" 
                class="w-full bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white font-medium 
                       py-3 rounded-lg hover:shadow-[0_0_15px_#00C2FF70] hover:scale-[1.02] 
                       transition-all duration-300">
                Create Quiz
            </button>
        </form>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('#create-quiz-form').on('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        $('#lesson-error, #title-error, #description-error').text('').hide();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    window.location.href = '/trainer/quizzes/' + response.quiz_id + '/edit';
                } else {
                    alert('Quiz created! Redirecting...');
                    window.location.href = response.redirect_url || '/trainer/quizzes';
                }
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if(errors.lesson_id) $('#lesson-error').text(errors.lesson_id[0]).show();
                    if(errors.title) $('#title-error').text(errors.title[0]).show();
                    if(errors.description) $('#description-error').text(errors.description[0]).show();
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        });
    });
});
</script>
@endsection
