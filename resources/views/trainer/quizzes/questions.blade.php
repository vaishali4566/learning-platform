@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-white p-6">
    <div class="max-w-6xl mx-auto bg-[#1B2238]/90 rounded-2xl shadow-2xl p-8 backdrop-blur-lg border border-[#2C3658]">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 border-b border-[#2F3A5C] pb-4">
            <h2 class="text-2xl font-bold text-[#A9C7FF] tracking-wide">
                Manage Quiz Questions — {{ $quiz->title ?? 'Quiz' }}
            </h2>
            <button id="addQuestionBtn" 
                class="bg-[#2F82DB] hover:bg-[#3D9BFF] px-5 py-2 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                + Add Question
            </button>
        </div>

        <!-- Questions List -->
        <div id="questionList" class="space-y-6">
            @forelse ($quiz->questions as $question)
                <div class="bg-[#12182B] border border-[#2C3B5B] rounded-xl p-6 hover:shadow-lg transition duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-[#E6EDF7] mb-2 leading-snug">
                                {{ $loop->iteration }}. {{ $question->question_text }}
                            </h3>
                            <p class="text-sm text-[#9CA8C7] mb-4">Marks: {{ $question->marks }}</p>
                            
                            <ul class="space-y-2">
                                @foreach ($question->options as $index => $option)
                                    <li class="flex items-center">
                                        <span class="mr-2 font-semibold text-sm 
                                            {{ $index === $question->correct_option ? 'text-green-400' : 'text-gray-400' }}">
                                            {{ chr(65 + $index) }}.
                                        </span>
                                        <span 
                                            class="{{ $index === $question->correct_option ? 'text-green-400 font-medium' : 'text-gray-300' }}">
                                            {{ $option }}
                                        </span>
                                        @if ($index === $question->correct_option)
                                            <span class="ml-3 text-xs text-green-400 bg-green-900/30 px-2 py-0.5 rounded-full">
                                                Correct Answer
                                            </span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="flex space-x-3">
                            <button 
                                class="editBtn text-yellow-400 hover:text-yellow-300 transition duration-200 px-2 py-1 border border-yellow-500/30 rounded-md hover:bg-yellow-500/10"
                                data-id="{{ $question->id }}">
                                Edit
                            </button>
                            <button 
                                class="deleteBtn text-red-400 hover:text-red-300 transition duration-200 px-2 py-1 border border-red-500/30 rounded-md hover:bg-red-500/10"
                                data-id="{{ $question->id }}">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 py-10 text-lg italic">No questions added yet.</p>
            @endforelse
        </div>

        <!-- Finalize Quiz -->
        <div class="text-center mt-10">
            <button id="finalizeBtn"
                data-id="{{ $quiz->id }}"
                class="bg-green-600 hover:bg-green-700 text-white px-8 py-2.5 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                Finalize Quiz
            </button>
        </div>
    </div>

    <!-- Add Question Modal -->
    <div id="addQuestionModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">
        <div class="bg-[#1C2541] w-full max-w-lg rounded-xl p-6 shadow-2xl border border-[#2C3658]">
            <h3 class="text-xl font-semibold text-[#A9C7FF] mb-5 border-b border-[#2F3A5C] pb-3">Add New Question</h3>
            
            <form id="addQuestionForm" data-quiz-id="{{ $quiz->id }}">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 text-sm text-gray-300">Question Text</label>
                    <textarea name="question_text" rows="3" required 
                        class="w-full rounded-lg bg-[#12182B] text-white p-2 focus:outline-none focus:ring-2 focus:ring-[#2F82DB]"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 text-sm text-gray-300">Marks</label>
                    <input type="number" name="marks" min="1" required 
                        class="w-full rounded-lg bg-[#12182B] text-white p-2 focus:outline-none focus:ring-2 focus:ring-[#2F82DB]">
                </div>

                <div class="mb-5">
                    <label class="block mb-1 text-sm text-gray-300">Options (4)</label>
                    @for ($i = 0; $i < 4; $i++)
                        <input type="text" name="options[]" placeholder="Option {{ $i + 1 }}" required 
                            class="w-full mb-2 rounded-lg bg-[#12182B] text-white p-2 focus:outline-none focus:ring-2 focus:ring-[#2F82DB]">
                    @endfor
                </div>

                <div class="mb-6">
                    <label class="block mb-1 text-sm text-gray-300">Correct Option</label>
                    <select name="correct_option" required 
                        class="w-full rounded-lg bg-[#12182B] text-white p-2 focus:outline-none focus:ring-2 focus:ring-[#2F82DB]">
                        <option value="">-- Select Correct Option --</option>
                        <option value="0">Option 1</option>
                        <option value="1">Option 2</option>
                        <option value="2">Option 3</option>
                        <option value="3">Option 4</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" id="cancelBtn"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg transition duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-[#2F82DB] hover:bg-[#3D9BFF] text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                        Save Question
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS Section -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('addQuestionModal');
    const openBtn = document.getElementById('addQuestionBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const form = document.getElementById('addQuestionForm');

    // Open modal
    openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
    cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));

    // Handle form submit (AJAX)
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const quizId = form.dataset.quizId;
        const formData = new FormData(form);

        const response = await fetch(`/trainer/quizzes/${quizId}/questions`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        });
        const result = await response.json();

        if (result.success) {
            alert('Question added successfully!');
            location.reload();
        } else {
            alert('Failed to add question.');
        }
    });

    // Delete question
    document.querySelectorAll('.deleteBtn').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Are you sure you want to delete this question?')) return;
            const id = btn.dataset.id;
            const res = await fetch(`/trainer/questions/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const result = await res.json();
            if (result.success) location.reload();
        });
    });

    // Finalize quiz
    document.getElementById('finalizeBtn').addEventListener('click', async (e) => {
        const id = e.target.dataset.id;
        const res = await fetch(`/trainer/quizzes/${id}/finalize`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        const result = await res.json();
        if (result.success) alert(result.success);
        else alert('Error finalizing quiz.');
    });
});
</script>
@endsection
