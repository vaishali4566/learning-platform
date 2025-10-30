@extends('layouts.trainer.index')

@section('content')
<div class="relative min-h-screen flex items-center justify-center px-6 py-10 overflow-hidden 
            bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-gray-100">

    <!-- Gradient overlay animation -->
    <div class="absolute inset-0 bg-gradient-to-t from-[#00C2FF]/10 via-transparent to-[#2F82DB]/5 
                animate-gradient-slow"></div>

    <div class="relative w-full max-w-5xl bg-[#12182B]/80 backdrop-blur-xl border border-white/10 
                rounded-2xl shadow-2xl p-8 space-y-8">

        {{-- Quiz Info --}}
        <div class="bg-[#1C2440]/80 rounded-xl p-6 border border-white/10 shadow-inner">
            <h2 class="text-2xl font-bold mb-3 text-blue-400">{{ $quiz->title }}</h2>
            <p class="text-gray-300 mb-2"><span class="font-semibold text-gray-100">Description:</span> {{ $quiz->description ?? 'No description' }}</p>
            <p class="text-gray-300 mb-1"><span class="font-semibold text-gray-100">Total Marks:</span> <span id="total-marks">{{ $quiz->total_marks }}</span></p>
            <p class="text-gray-300 mb-4"><span class="font-semibold text-gray-100">Passing Marks:</span> <span id="passing-marks">{{ $quiz->passing_marks }}</span></p>
            
            <button id="finalize-btn" 
                class="px-6 py-2 bg-gradient-to-r from-green-700 to-emerald-600 text-white font-semibold rounded-lg shadow hover:opacity-90 transition">
                Finalize Quiz
            </button>
        </div>

        {{-- Add Question Form --}}
        <div class="bg-[#1C2440]/80 rounded-xl p-6 border border-white/10">
            <h3 class="text-xl font-semibold mb-4 text-blue-400">Add New Question</h3>
            <form id="add-question-form" class="space-y-4">
                @csrf
                <input type="hidden" id="quizId" value="{{ $quiz->id }}">

                <div>
                    <label class="font-medium text-gray-200">Question Text</label>
                    <textarea name="question_text" id="question_text" class="w-full p-3 rounded-lg bg-[#0E1426] border border-white/10 focus:ring-2 focus:ring-blue-600 text-gray-100" rows="2"></textarea>
                </div>

                <div>
                    <label class="font-medium text-gray-200">Marks</label>
                    <input type="number" name="marks" id="marks" class="w-full p-3 rounded-lg bg-[#0E1426] border border-white/10 focus:ring-2 focus:ring-blue-600 text-gray-100">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    @for($i = 0; $i < 4; $i++)
                        <div>
                            <label class="font-medium text-gray-200">Option {{ $i + 1 }}</label>
                            <input type="text" name="options[{{ $i }}]" class="w-full p-3 rounded-lg bg-[#0E1426] border border-white/10 focus:ring-2 focus:ring-blue-600 text-gray-100">
                        </div>
                    @endfor
                </div>

                <div>
                    <label class="font-medium text-gray-200">Correct Option</label>
                    <select name="correct_option" id="correct_option" class="w-full p-3 rounded-lg bg-[#0E1426] border border-white/10 focus:ring-2 focus:ring-blue-600 text-gray-100">
                        <option value="0">Option 1</option>
                        <option value="1">Option 2</option>
                        <option value="2">Option 3</option>
                        <option value="3">Option 4</option>
                    </select>
                </div>

                <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-700 to-cyan-600 text-white font-semibold py-3 rounded-lg hover:opacity-90 transition">
                    Add Question
                </button>
            </form>
        </div>

        {{-- List of Questions --}}
        <div class="bg-[#1C2440]/80 rounded-xl p-6 border border-white/10">
            <h3 class="text-xl font-semibold mb-4 text-blue-400">All Questions</h3>
            <ul id="questions-list" class="space-y-2">
                @forelse($quiz->questions as $question)
                <li data-id="{{ $question->id }}" class="flex justify-between items-center bg-[#0E1426]/60 border border-white/10 rounded-lg p-3 hover:bg-[#16203A] transition">
                    <div>
                        <p class="font-medium text-gray-100">{{ $question->question_text }}</p>
                        <span class="text-sm text-gray-400">{{ $question->marks }} marks</span>
                    </div>
                    <button class="delete-btn px-3 py-1.5 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-md hover:opacity-90 transition">
                        Delete
                    </button>
                </li>
                @empty
                <li class="text-gray-400 italic">No questions added yet.</li>
                @endforelse
            </ul>
        </div>

    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {

    // 🔹 Add Question (AJAX without reload)
    $('#add-question-form').on('submit', function(e) {
        e.preventDefault();
        let quizId = $('#quizId').val();

        $.ajax({
            url: '/trainer/quizzes/' + quizId + '/questions',
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    const q = res.question;

                    // Append new question instantly
                    $('#questions-list').append(`
                        <li data-id="${q.id}" class="flex justify-between items-center bg-[#0E1426]/60 border border-white/10 rounded-lg p-3 hover:bg-[#16203A] transition">
                            <div>
                                <p class="font-medium text-gray-100">${q.text}</p>
                                <span class="text-sm text-gray-400">${q.marks} marks</span>
                            </div>
                            <button class="delete-btn px-3 py-1.5 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-md hover:opacity-90 transition">
                                Delete
                            </button>
                        </li>
                    `);

                    // Reset form
                    $('#add-question-form')[0].reset();

                    // Update marks display
                    let total = parseInt($('#total-marks').text() || 0);
                    $('#total-marks').text(total + parseInt(q.marks));

                    alert('✅ Question added successfully!');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let msg = Object.values(xhr.responseJSON.errors).flat().join("\n");
                    alert(msg);
                } else {
                    alert('❌ An unexpected error occurred.');
                }
            }
        });
    });


    // 🔹 Delete Question (Dynamic)
    $(document).on('click', '.delete-btn', function() {
        if (!confirm('Delete this question?')) return;

        let li = $(this).closest('li');
        let questionId = li.data('id');

        $.ajax({
            url: '/trainer/quizzes/questions/' + questionId,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                if (res.success) {
                    li.remove();
                    alert('🗑️ Question deleted successfully.');

                    // Optionally update total marks
                    let current = parseInt($('#total-marks').text() || 0);
                    let removedMarks = parseInt(li.find('span').text()) || 0;
                    $('#total-marks').text(Math.max(current - removedMarks, 0));
                }
            },
            error: function() {
                alert('❌ Error deleting question.');
            }
        });
    });


    // 🔹 Finalize Quiz
    $('#finalize-btn').click(function() {
        let quizId = $('#quizId').val();

        $.ajax({
            url: '/trainer/quizzes/' + quizId + '/finalize',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                alert(res.success || '✅ Quiz finalized successfully!');
                location.reload(); // reload to refresh passing marks
            },
            error: function(xhr) {
                if (xhr.status === 422 && xhr.responseJSON.errors.quiz) {
                    alert(xhr.responseJSON.errors.quiz[0]);
                } else {
                    alert('❌ Something went wrong.');
                }
            }
        });
    });

});
</script>

@endsection
