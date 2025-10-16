<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Course</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-2xl p-8 bg-white rounded shadow-lg">
        <div id="flashMessages" class="mb-4 space-y-2">
            <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-center"></div>
            <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-center"></div>
        </div>

        <h2 class="text-3xl font-bold mb-6 text-gray-800 text-center">Create a New Course</h2>

        <!-- Course Form -->
        <form method="POST" id="courseForm">

            {{-- Input Component --}}
            @php
            $inputClass = "mt-1 pl-1.5 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150";
            @endphp

            <div class="mb-4">
                <label for="trainer_id" class="block font-medium text-gray-700">Trainer ID</label>
                <input type="number" name="trainer_id" id="trainer_id" class="{{ $inputClass }}">
                <div id="trainer_idError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div class="mb-4">
                <label for="title" class="block font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" class="{{ $inputClass }}">
                <div id="titleError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div class="mb-4">
                <label for="description" class="block font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" class="{{ $inputClass }}"></textarea>
                <div id="descriptionError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div class="mb-4">
                <label for="price" class="block font-medium text-gray-700">Price ($)</label>
                <input type="number" step="0.01" name="price" id="price" class="{{ $inputClass }}">
                <div id="priceError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div class="mb-4">
                <label for="duration" class="block font-medium text-gray-700">Duration</label>
                <input type="text" name="duration" id="duration" class="{{ $inputClass }}">
                <div id="durationError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div class="mb-4">
                <label for="difficulty" class="block font-medium text-gray-700">Difficulty</label>
                <select name="difficulty" id="difficulty" class="{{ $inputClass }}">
                    <option value="">-- Select --</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_online" value="1" class="form-checkbox text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="ml-2 text-gray-700">Is Online?</span>
                </label>
            </div>

            <div class="mb-4">
                <label for="status" class="block font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="{{ $inputClass }}">
                    <option value="">-- Select --</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="city" class="block font-medium text-gray-700">City</label>
                <input type="text" name="city" id="city" class="{{ $inputClass }}">
                <div id="cityError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div class="mb-6">
                <label for="country" class="block font-medium text-gray-700">Country</label>
                <input type="text" name="country" id="country" class="{{ $inputClass }}">
                <div id="countryError" class="text-red-600 text-sm mt-1 error-message"></div>
            </div>

            <div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded shadow transition duration-150">
                    Create Course
                </button>
            </div>
        </form>
    </div>
    <!-- Scripts -->
    <script>
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
        }

        function displayValidationErrors(errors = {}, message = '') {
            const errorDiv = document.getElementById('errorMessage');
            const successDiv = document.getElementById('successMessage');
            successDiv.textContent = '';
            successDiv.classList.add('hidden');

            errorDiv.textContent = message || 'Please fix the errors below.';
            errorDiv.classList.remove('hidden');

            for (const field in errors) {
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

        document.getElementById('courseForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            clearErrors();

            const formData = new FormData(this);
            console.log("Error", formData)

            try {
                const response = await fetch('/courses', {
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
                alert('An error occurred while creating course');
                console.log(err)
            }
        });
    </script>

</body>

</html>