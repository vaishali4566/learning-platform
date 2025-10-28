@extends('layouts.trainer')

@section('content')
<div class="relative h-screen flex items-center justify-center px-4 overflow-hidden 
            bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33]">

    <!-- Animated Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-[#00C2FF]/10 via-transparent to-[#2F82DB]/5 
                animate-gradient-slow blur-2xl opacity-40 pointer-events-none"></div>

    <!-- Glassmorphic Container -->
    <div class="relative z-10 w-full max-w-2xl bg-white/10 backdrop-blur-xl rounded-lg shadow-[0_0_25px_rgba(0,194,255,0.08)] 
                border border-white/10 p-6 md:p-8 transition-all duration-500 ease-in-out transform 
                hover:scale-[1.01] hover:shadow-[0_0_35px_rgba(0,194,255,0.15)] overflow-hidden">

        <!-- Flash Messages -->
        <div id="flashMessages" class="mb-3 text-center space-y-1">
            <div id="successMessage" class="hidden bg-[#00C2FF]/10 text-[#00C2FF] border border-[#00C2FF]/30 px-3 py-1.5 rounded text-sm font-medium"></div>
            <div id="errorMessage" class="hidden bg-red-500/10 text-red-400 border border-red-400/30 px-3 py-1.5 rounded text-sm font-medium"></div>
        </div>

        <h2 class="text-2xl font-semibold mb-5 text-[#E6EDF7] text-center tracking-wide">Create a New Course</h2>

        <!-- Course Form -->
        <form method="POST" id="courseForm" class="space-y-3">
            @php
            $inputClass = "mt-1 block w-full rounded-md bg-[#1C2541]/60 text-[#E6EDF7] border border-[#00C2FF]/20 
                           px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#00C2FF]/70 text-sm transition-all duration-200 placeholder-gray-400";
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="trainer_id" class="block text-[#A1A9C4] text-xs mb-1">Trainer ID<span class="text-red-500">*</span></label>
                    <input type="number" name="trainer_id" id="trainer_id" class="{{ $inputClass }}" required>
                    <div id="trainer_idError" class="text-red-500 text-[11px] mt-1 error-message"></div>
                </div>

                <div>
                    <label for="title" class="block text-[#A1A9C4] text-xs mb-1">Title<span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" class="{{ $inputClass }}">
                    <div id="titleError" class="text-red-500 text-[11px] mt-1 error-message"></div>
                </div>
            </div>

            <div>
                <label for="description" class="block text-[#A1A9C4] text-xs mb-1">Description<span class="text-red-500">*</span></label>
                <textarea name="description" id="description" rows="2" class="{{ $inputClass }}"></textarea>
                <div id="descriptionError" class="text-red-500 text-[11px] mt-1 error-message"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="price" class="block text-[#A1A9C4] text-xs mb-1">Price (Rs)<span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="price" id="price" class="{{ $inputClass }}">
                    <div id="priceError" class="text-red-500 text-[11px] mt-1 error-message"></div>
                </div>

                <div>
                    <label for="duration" class="block text-[#A1A9C4] text-xs mb-1">Duration<span class="text-red-500">*</span></label>
                    <input type="text" name="duration" id="duration" class="{{ $inputClass }}">
                    <div id="durationError" class="text-red-500 text-[11px] mt-1 error-message"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="difficulty" class="block text-[#A1A9C4] text-xs mb-1">Difficulty</label>
                    <select name="difficulty" id="difficulty" class="{{ $inputClass }}">
                        <option value="">-- Select --</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-[#A1A9C4] text-xs mb-1">Status</label>
                    <select name="status" id="status" class="{{ $inputClass }}">
                        <option value="">-- Select --</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <input type="checkbox" name="is_online" value="1" class="form-checkbox text-[#00C2FF] border-gray-300 rounded focus:ring-[#00C2FF]">
                <label class="text-[#E6EDF7] text-xs">Is Online?</label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="city" class="block text-[#A1A9C4] text-xs mb-1">City<span class="text-red-500">*</span></label>
                    <input type="text" name="city" id="city" class="{{ $inputClass }}">
                    <div id="cityError" class="text-red-500 text-[11px] mt-1 error-message"></div>
                </div>

                <div>
                    <label for="country" class="block text-[#A1A9C4] text-xs mb-1">Country<span class="text-red-500">*</span></label>
                    <input type="text" name="country" id="country" class="{{ $inputClass }}">
                    <div id="countryError" class="text-red-500 text-[11px] mt-1 error-message"></div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" id="submitButton" disabled
                        class="w-full bg-gradient-to-r from-[#2f82db] to-[#00C2FF] text-white font-medium text-sm py-2 px-3 rounded-md 
                               shadow-md cursor-not-allowed transition-all duration-300 transform hover:scale-[1.01] hover:shadow-[0_0_15px_rgba(0,194,255,0.3)]">
                    Create Course
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* prevent scrolling */
html, body {
    height: 100%;
    overflow: hidden;
}
@keyframes gradient-slow {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
.animate-gradient-slow {
    background-size: 200% 200%;
    animation: gradient-slow 10s ease infinite;
}
input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0px 1000px rgba(30, 64, 175, 0.1) inset !important;
    -webkit-text-fill-color: #e5e7eb !important;
}
</style>
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
                    displaySuccessMessage(data.message);
                    this.reset();
                    clearErrors();
                    toggleSubmitButton();
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

    <script>
        const form = document.getElementById('courseForm');

        const validators = {
            trainer_id: (value) => {
                if (!value.trim()) return "Trainer ID is required.";
                if (!/^\d+$/.test(value)) return "Trainer ID must be a number.";
                return "";
            },
            title: (value) => {
                if (!value.trim()) return "Title is required.";
                if (value.length < 5) return "Title must be at least 5 characters.";
                return "";
            },
            description: (value) => {
                if (!value.trim()) return "Description is required.";
                if (value.length < 10) return "Description must be at least 10 characters.";
                return "";
            },
            price: (value) => {
                if (!value.trim()) return "Price is required.";
                if (isNaN(value) || parseFloat(value) <= 0) return "Price must be a positive number.";
                return "";
            },
            duration: (value) => {
                if (!value.trim()) return "Duration is required.";
                return "";
            },
            city: (value) => {
                if (!value.trim()) return "City is required.";
                return "";
            },
            country: (value) => {
                if (!value.trim()) return "Country is required.";
                return "";
            }
        };

        function validateField(field) {
            const value = field.value;
            const name = field.name;
            const errorDiv = document.getElementById(`${name}Error`);

            if (validators[name]) {
                const error = validators[name](value);
                if (error) {
                    field.classList.add('border-red-500');
                    errorDiv.textContent = error;
                } else {
                    field.classList.remove('border-red-500');
                    errorDiv.textContent = '';
                }
            }
        }

        // Attach validation on input
        Object.keys(validators).forEach((name) => {
            const field = document.querySelector(`[name="${name}"]`);
            if (field) {
                field.addEventListener('input', () => validateField(field));
            }
        });
    </script>

    <script>
        const submitButton = document.getElementById('submitButton');

        function isFormValid() {
            let isValid = true;
            Object.keys(validators).forEach((name) => {
                const field = document.querySelector(`[name="${name}"]`);
                const error = validators[name](field.value);
                if (error) {
                    isValid = false;
                }
            });
            return isValid;
        }

        function toggleSubmitButton() {
            if (isFormValid()) {
                submitButton.disabled = false;
                submitButton.classList.remove('bg-indigo-400', 'cursor-not-allowed');
                submitButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
            } else {
                submitButton.disabled = true;
                submitButton.classList.add('bg-indigo-400', 'cursor-not-allowed');
                submitButton.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
            }
        }

        // Add event listeners to all fields
        Object.keys(validators).forEach((name) => {
            const field = document.querySelector(`[name="${name}"]`);
            if (field) {
                field.addEventListener('input', () => {
                    validateField(field);
                    toggleSubmitButton();
                });
            }
        });

        // Disable button on page load
        document.addEventListener('DOMContentLoaded', () => {
            toggleSubmitButton();
        });
    </script>
    @endsection