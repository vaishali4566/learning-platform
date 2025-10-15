@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10">

    {{-- Quiz Info --}}
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-2xl font-bold mb-4">{{ $quiz->title }}</h2>
        <p class="mb-2"><strong>Description:</strong> {{ $quiz->description ?? 'No description' }}</p>
        <p class="mb-2"><strong>Total Marks:</strong> <span id="total-marks">{{ $quiz->total_marks }}</span></p>
        <p class="mb-4"><strong>Passing Marks:</strong> <span id="passing-marks">{{ $quiz->passing_marks }}</span></p>
        <button id="finalize-btn" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Finalize Quiz</button>
    </div>

    {{-- Add Question Form --}}
    <div class="bg-white p-6 rounded shadow mb-6">
        <h3 class="text-xl font-semibold mb-4">Add Question</h3>
        <form id="add-question-form">
            @csrf
            <input type="hidden" id="quizId" value="{{ $quiz->id }}">

            <div class="mb-2">
                <label class="font-semibold">Question Text</label>
                <textarea name="question_text" id="question_text" class="w-full border p-2 rounded" rows="2"></textarea>
            </div>

            <div class="mb-2">
                <label class="font-semibold">Marks</label>
                <input type="number" name="marks" id="marks" class="w-full border p-2 rounded">
            </div>

            <div class="grid grid-cols-2 gap-2 mb-2">
                <div>
                    <label class="font-semibold">Option 1</label>
                    <input type="text" name="options[0]" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="font-semibold">Option 2</label>
                    <input type="text" name="options[1]" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="font-semibold">Option 3</label>
                    <input type="text" name="options[2]" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="font-semibold">Option 4</label>
                    <input type="text" name="options[3]" class="w-full border p-2 rounded">
                </div>
            </div>

            <div class="mb-4">
                <label class="font-semibold">Correct Option</label>
                <select name="correct_option" id="correct_option" class="w-full border p-2 rounded">
                    <option value="0">Option 1</option>
                    <option value="1">Option 2</option>
                    <option value="2">Option 3</option>
                    <option value="3">Option 4</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Question</button>
        </form>
    </div>

    {{-- List of Questions --}}
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-xl font-semibold mb-4">Questions</h3>
        <ul id="questions-list">
            @foreach($quiz->questions as $question)
            <li data-id="{{ $question->id }}" class="border-b py-2 flex justify-between items-center">
                <span>{{ $question->question_text }} ({{ $question->marks }} marks)</span>
                <button class="delete-btn px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
            </li>
            @endforeach
        </ul>
    </div>

</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {

    // Add question via AJAX
    $('#add-question-form').submit(function(e) {
        e.preventDefault();

        let quizId = $('#quizId').val();
        let data = $(this).serialize();

        $.ajax({
            url: '/trainer/quizzes/' + quizId + '/questions', // route for storeQuestion
            method: 'POST',
            data: data,
            success: function(res) {
                alert(res.success ? res.success : 'Question added!');
                // Reload questions list (simplest way)
                location.reload();
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let msg = Object.values(errors).flat().join("\n");
                    alert(msg);
                }
            }
        });
    });

    // Delete question via AJAX
    $('.delete-btn').click(function() {
        if(!confirm('Are you sure you want to delete this question?')) return;
        let li = $(this).closest('li');
        let questionId = li.data('id');

        $.ajax({
            url: '/trainer/quizzes/questions/' + questionId,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                alert(res.success ? res.success : 'Deleted!');
                location.reload();
            }
        });
    });

    // Finalize Quiz
    $('#finalize-btn').click(function() {
        let quizId = $('#quizId').val();
        $.ajax({
            url: '/trainer/quizzes/' + quizId + '/finalize',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                alert(res.success ? res.success : 'Quiz finalized!');
                location.reload();
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    alert(xhr.responseJSON.errors.quiz[0]);
                }
            }
        });
    });

});
</script>
@endsection
