@extends('layouts.trainer.index')

@section('content')
<div class="relative min-h-screen flex items-center justify-center px-4 py-10 
            bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33] overflow-hidden">

    <!-- Animated Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-[#00C2FF]/10 via-transparent to-[#2F82DB]/5 
                animate-gradient-slow blur-3xl opacity-40"></div>

    <!-- Compact Glassmorphic Container -->
    <div class="relative z-10 w-full max-w-3xl bg-white/10 backdrop-blur-2xl rounded-lg shadow-[0_0_25px_rgba(0,194,255,0.1)] border border-white/10 p-8 
                transition-all duration-700 ease-in-out transform hover:scale-[1.005] hover:shadow-[0_0_35px_rgba(0,194,255,0.2)]">

        <!-- Title -->
        <h2 class="text-xl font-semibold text-center text-[#E6EDF7] mb-6 tracking-wide border-b border-white/10 pb-2">
            Create a New Course
        </h2>

        <!-- Flash Messages -->
        <div id="flashMessages" class="mb-4 space-y-2">
            <div id="successMessage" class="hidden bg-[#00C2FF]/10 border border-[#00C2FF]/40 text-[#00C2FF] px-3 py-2 rounded-md text-center text-sm"></div>
            <div id="errorMessage" class="hidden bg-red-500/10 border border-red-400/40 text-red-400 px-3 py-2 rounded-md text-center text-sm"></div>
        </div>

        <!-- Form -->
        <form method="POST" id="courseForm">
            @php
                $inputClass = "mt-1 block w-full px-2.5 py-1.5 rounded-md bg-[#1C2541]/70 text-[#E6EDF7] placeholder-gray-400 border border-white/10 
                focus:outline-none focus:ring-1 focus:ring-[#00C2FF]/60 text-sm transition-all duration-300";
            @endphp

            <!-- Two-column layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-[#A1A9C4] text-xs mb-1">Trainer ID<span class="text-red-500">*</span></label>
                    <input type="number" name="trainer_id" id="trainer_id" class="{{ $inputClass }}">
                    <div id="trainer_idError" class="text-red-400 text-xs mt-1"></div>
                </div>

                <div>
                    <label class="block text-[#A1A9C4] text-xs mb-1">Title<span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" class="{{ $inputClass }}">
                    <div id="titleError" class="text-red-400 text-xs mt-1"></div>
                </div>

                <div>
                    <label class="block text-[#A1A9C4] text-xs mb-1">Price (₹)<span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="price" id="price" class="{{ $inputClass }}">
                    <div id="priceError" class="text-red-400 text-xs mt-1"></div>
                </div>

                <div>
                    <label class="block text-[#A1A9C4] text-xs mb-1">Duration<span class="text-red-500">*</span></label>
                    <input type="text" name="duration" id="duration" class="{{ $inputClass }}">
                    <div id="durationError" class="text-red-400 text-xs mt-1"></div>
                </div>

                <div>
                    <label class="block text-[#A1A9C4] text-xs mb-1">Difficulty</label>
                    <select name="difficulty" id="difficulty" class="{{ $inputClass }}">
                        <option value="">-- Select --</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[#A1A9C4] text-xs mb-1">Status</label>
                    <select name="status" id="status" class="{{ $inputClass }}">
                        <option value="">-- Select --</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[#A1A9C4] text-xs mb-1">City<span class="text-red-500">*</span></label>
                    <input type="text" name="city" id="city" class="{{ $inputClass }}">
                    <div id="cityError" class="text-red-400 text-xs mt-1"></div>
                </div>

                <div>
                    <label class="block text-[#A1A9C4] text-xs mb-1">Country<span class="text-red-500">*</span></label>
                    <input type="text" name="country" id="country" class="{{ $inputClass }}">
                    <div id="countryError" class="text-red-400 text-xs mt-1"></div>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-5">
                <label class="block text-[#A1A9C4] text-xs mb-1">Description<span class="text-red-500">*</span></label>
                <textarea name="description" id="description" rows="2" class="{{ $inputClass }}"></textarea>
                <div id="descriptionError" class="text-red-400 text-xs mt-1"></div>
            </div>

            <!-- Checkbox -->
            <div class="mt-3 flex items-center space-x-2">
                <input type="checkbox" name="is_online" value="1" class="w-3.5 h-3.5 text-[#00C2FF] bg-transparent border-gray-500 rounded focus:ring-[#00C2FF]">
                <label class="text-xs text-[#E6EDF7]">Is Online?</label>
            </div>

            <!-- Submit -->
            <div class="mt-6">
                <button type="submit" id="submitButton" disabled
                    class="w-full bg-gradient-to-r from-[#2f82db] to-[#00C2FF] text-white font-medium text-xs py-2 rounded-md shadow 
                           transition-all cursor-not-allowed opacity-60 hover:opacity-100 hover:scale-[1.01]">
                    Create Course
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Styles -->
<style>
    select option {
        background-color: #1C2541;
        color: #E6EDF7;
    }

    @keyframes gradient-slow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animate-gradient-slow {
        background-size: 200% 200%;
        animation: gradient-slow 12s ease infinite;
    }
</style>

<!-- JS Validation (unchanged logic) -->
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function clearMessages() {
        document.getElementById('successMessage').classList.add('hidden');
        document.getElementById('errorMessage').classList.add('hidden');
    }

    function displayValidationErrors(errors = {}, message = '') {
        const errorDiv = document.getElementById('errorMessage');
        const successDiv = document.getElementById('successMessage');
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

    function displaySuccessMessage(message = 'Course created successfully!') {
        const successDiv = document.getElementById('successMessage');
        const errorDiv = document.getElementById('errorMessage');
        errorDiv.classList.add('hidden');
        successDiv.textContent = message;
        successDiv.classList.remove('hidden');
    }

    document.getElementById('courseForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        clearMessages();

        const formData = new FormData(this);
        try {
            const response = await fetch("{{ route('trainer.courses.store') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            });
            const data = await response.json();

            if (response.ok) {
                displaySuccessMessage(data.message);
                this.reset();
                toggleSubmitButton();
            } else {
                displayValidationErrors(data.errors || {}, data.message);
            }
        } catch (err) {
            displayValidationErrors({}, 'An unexpected error occurred.');
        }
    });

    const validators = {
        trainer_id: v => !v.trim() ? "Trainer ID is required." : "",
        title: v => !v.trim() ? "Title is required." : v.length < 5 ? "Min 5 characters." : "",
        description: v => !v.trim() ? "Description is required." : v.length < 10 ? "Min 10 characters." : "",
        price: v => !v.trim() ? "Price is required." : parseFloat(v) <= 0 ? "Must be positive." : "",
        duration: v => !v.trim() ? "Duration is required." : "",
        city: v => !v.trim() ? "City is required." : "",
        country: v => !v.trim() ? "Country is required." : ""
    };

    function validateField(field) {
        const error = validators[field.name]?.(field.value);
        const errorDiv = document.getElementById(`${field.name}Error`);
        field.classList.toggle('border-red-500', !!error);
        errorDiv.textContent = error || '';
    }

    function isFormValid() {
        return Object.keys(validators).every(name => !validators[name](document.querySelector(`[name="${name}"]`).value));
    }

    function toggleSubmitButton() {
        const button = document.getElementById('submitButton');
        if (isFormValid()) {
            button.disabled = false;
            button.classList.remove('cursor-not-allowed', 'opacity-60');
        } else {
            button.disabled = true;
            button.classList.add('cursor-not-allowed', 'opacity-60');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        Object.keys(validators).forEach(name => {
            const field = document.querySelector(`[name="${name}"]`);
            if (field) {
                field.addEventListener('input', () => {
                    validateField(field);
                    toggleSubmitButton();
                });
            }
        });
        toggleSubmitButton();
    });
</script>
@endsection
