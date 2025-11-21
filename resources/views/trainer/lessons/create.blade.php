@extends('layouts.trainer.index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] text-[#E6EDF7] px-6 py-10">

    <!-- Header -->
    <div class="flex items-center justify-center mb-8 animate-fade-in">
        <h1 class="text-3xl font-bold tracking-wide bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] bg-clip-text text-transparent">
            Create a New Lessonnnnn
        </h1>
    </div>

    <!-- Flash Messages -->
    <div id="flashMessages" class="mb-4 space-y-2 text-center">
        <div id="successMessage" class="hidden bg-green-600/20 border border-green-400 text-green-400 px-4 py-3 rounded"></div>
        <div id="errorMessage" class="hidden bg-red-600/20 border border-red-400 text-red-400 px-4 py-3 rounded"></div>
    </div>

    <!-- Form -->
    <div class="bg-[#0E1625]/80 backdrop-blur-md border border-[#26304D] rounded-2xl shadow-lg p-6 max-w-2xl mx-auto animate-fade-in-up">
        <form method="POST" id="lessonForm" enctype="multipart/form-data">
            @csrf

            {{-- Course ID --}}
            <div class="mb-4">
                <label for="course_id" class="block text-[#A8B3CF] mb-1">Course ID</label>
                <input type="number" name="course_id" id="course_id" class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7] focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF] outline-none transition-all duration-300" readonly>
            </div>

            {{-- Lesson Title --}}
            <div class="mb-4">
                <label for="title" class="block text-[#A8B3CF] mb-1">Title<sup class="text-red-600">*</sup></label>
                <input type="text" name="title" id="title" class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7] focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF] outline-none transition-all duration-300" required>
                <div id="titleError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            {{-- Content Type --}}
            <div class="mb-4">
                <label for="content_type" class="block text-[#A8B3CF] mb-1">Content Type<sup class="text-red-600">*</sup></label>
                <select name="content_type" id="content_type" class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7] focus:border-[#00C2FF] focus:ring-1 focus:ring-[#00C2FF] outline-none transition-all duration-300" required>
                    <option value="">-- Select --</option>
                    <option value="video">Video</option>
                    <option value="text">Text</option>
                    <option value="quiz">Quiz</option>
                    <option value="practice">Practice Test</option>
                </select>
                <div id="content_typeError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            {{-- Video Field --}}
            <div id="videoField" class="mb-4 hidden">
                <label for="video" class="block text-[#A8B3CF] mb-1">Video</label>
                <input type="file" name="video" id="video" class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7]" accept="video/*">
                <div id="videoError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            {{-- Text Field --}}
            <div id="textField" class="mb-4 hidden">
                <label for="text_content" class="block text-[#A8B3CF] mb-1">Text Content</label>
                <textarea name="text_content" id="text_content" rows="4" class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7]"></textarea>
                <div id="text_contentError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            {{-- Order Number --}}
            <div class="mb-4">
                <label for="order_number" class="block text-[#A8B3CF] mb-1">Order Number<sup class="text-red-600">*</sup></label>
                <input type="number" name="order_number" id="order_number" class="w-full bg-[#101727] border border-[#26304D] rounded-lg px-3 py-2 text-[#E6EDF7]" required>
                <div id="order_numberError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div class="flex justify-end">
                <button type="submit" id="submitButton" disabled class="px-6 py-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white rounded-xl shadow hover:shadow-[#00C2FF]/40 transition-all duration-300">
                    Create Lesson
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contentType = document.getElementById('content_type');
        const videoField = document.getElementById('videoField');
        const textField = document.getElementById('textField');

        // Fetch course_id from URL
        const urlParts = window.location.pathname.split('/');
        const courseIdInput = document.getElementById('course_id');
        courseIdInput.value = urlParts.includes('courses') ? urlParts[urlParts.indexOf('courses') + 1] : '';

        function toggleFields() {
            const selected = contentType.value;

            videoField.classList.add('hidden');
            textField.classList.add('hidden');

            if (selected === 'video') {
                videoField.classList.remove('hidden');
            } else if (selected === 'text') {
                textField.classList.remove('hidden');
            }
            // Quiz: no fields required, hide everything
        }

        contentType.addEventListener('change', toggleFields);
        toggleFields();

        // Form submit
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const form = document.getElementById('lessonForm');
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            try {
                const response = await fetch('/lessons', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });
                const data = await response.json();
                if (response.ok) {
                    document.getElementById('successMessage').textContent = data.message;
                    document.getElementById('successMessage').classList.remove('hidden');
                    form.reset();
                    toggleFields();
                } else {
                    document.getElementById('errorMessage').textContent = data.message || 'Please fix errors';
                    document.getElementById('errorMessage').classList.remove('hidden');
                }
            } catch (err) {
                alert('Error creating lesson');
                console.error(err);
            }
        });
    });
</script>
@endsection