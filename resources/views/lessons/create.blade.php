<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Lession</title>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-2xl p-8 bg-white rounded shadow-lg">
        <div id="flashMessages" class="mb-4 space-y-2">
            <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-center"></div>
            <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-center"></div>
        </div>

        <h2 class="text-3xl font-bold mb-6 text-gray-800 text-center">Create a New Lesson</h2>

        <!-- Course Form -->
        <form method="POST" id="lessonForm" enctype="multipart/form-data">

            {{-- Input Component --}}
            @php
            $inputClass = "mt-1 pl-1.5 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150";
            @endphp

            <div class="mb-4">
                <label for="course_id" class="block font-medium text-gray-700">Course ID</label>
                <input type="number" name="course_id" id="course_id" class="{{ $inputClass }}">
                <div id="course_idError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div class="mb-4">
                <label for="title" class="block font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" class="{{ $inputClass }}">
                <div id="titleError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div class="mb-4">
                <label for="content_type" class="block font-medium text-gray-700">Content Type</label>
                <select name="content_type" id="content_type" class="{{ $inputClass }}">
                    <option value="">-- Select --</option>
                    <option value="video">video</option>
                    <option value="text">text</option>
                    <option value="quiz">quiz</option>
                </select>
                <div id="content_typeError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div id="videoField" class="mb-4 hidden">
                <label for="video" class="block font-medium text-gray-700">Video</label>
                <input type="file" name="video" id="video" class="{{ $inputClass }}" accept="video/*">
                <div id="videoError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div id="textField" class="mb-4 hidden">
                <label for="text_content" class="block font-medium text-gray-700">Text Content</label>
                <input type="text" name="text_content" id="text_content" class="{{ $inputClass }}">
                <div id="text_contentError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div id="quizField" class="mb-4 hidden">
                <label for="quiz_questions" class="block font-medium text-gray-700">Quiz Questions</label>
                <textarea name="quiz_questions" placeholder="Please add quiz in JSON format" id="quiz_questions" rows="3" class="{{ $inputClass }}"></textarea>
                <div id="quiz_questionsError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div class="mb-4">
                <label for="order_number" class="block font-medium text-gray-700">Order Number</label>
                <input type="number" name="order_number" id="order_number" class="{{ $inputClass }}">
                <div id="order_numberError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div class="mb-6">
                <label for="duration" class="block font-medium text-gray-700">Duration</label>
                <input type="text" name="duration" id="duration" class="{{ $inputClass }}">
                <div id="durationError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded shadow transition duration-150">
                    Create Lesson
                </button>
            </div>
        </form>
    </div>
    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contentType = document.getElementById('content_type');
            const videoField = document.getElementById('videoField');
            const textField = document.getElementById('textField');
            const quizField = document.getElementById('quizField');

            function toggleFields() {
                const selected = contentType.value;

                videoField.classList.add('hidden');
                textField.classList.add('hidden');
                quizField.classList.add('hidden');

                if (selected === 'video') {
                    videoField.classList.remove('hidden');
                } else if (selected === 'text') {
                    textField.classList.remove('hidden');
                } else if (selected === 'quiz') {
                    quizField.classList.remove('hidden');
                }
            }

            contentType.addEventListener('change', toggleFields);

            toggleFields();
        });
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function clearMessages() {
            const successDiv = document.getElementById('successMessage');
            const errorDiv = document.getElementById('errorMessage');
            successDiv.textContent = '';
            successDiv.classList.add('hidden');
            errorDiv.textContent = '';
            errorDiv.classList.add('hidden');
        }

        function clearErrors() {
            document.querySelectorAll('.error-message').forEach(div => div.textContent = '');
            document.querySelectorAll('input').forEach(input => input.classList.remove('border-red-500'));
            document.querySelectorAll('select').forEach(select => select.classList.remove('border-red-500'));
        }

        function displayValidationErrors(errors = {}, message = '') {
            const errorDiv = document.getElementById('errorMessage');
            const successDiv = document.getElementById('successMessage');
            successDiv.textContent = '';
            successDiv.classList.add('hidden');

            errorDiv.textContent = message || 'Please fix the errors below.';
            errorDiv.classList.remove('hidden');

            for (const field in errors) {
                console.log("Fields", field)
                const input = document.getElementById(field);
                const errorFieldDiv = document.getElementById(`${field}Error`);
                if (input) input.classList.add('border-red-500');
                if (errorFieldDiv) errorFieldDiv.textContent = errors[field][0];
            }
        }

        function displaySuccessMessage(message = 'Success!') {
            const successDiv = document.getElementById('successMessage');
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = '';
            errorDiv.classList.add('hidden');
            successDiv.textContent = message;
            successDiv.classList.remove('hidden');
        }

        document.getElementById('lessonForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            clearErrors();

            const formData = new FormData(this);
            console.log("Error", formData)

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
                    displaySuccessMessage('');
                } else {
                    console.log("mydata", data);
                    displayValidationErrors(data.errors || {}, data.message);
                }
            } catch (err) {
                alert('An error occurred while creating lesson');
                console.log(err)
            }
        });
    </script>

</body>

</html>