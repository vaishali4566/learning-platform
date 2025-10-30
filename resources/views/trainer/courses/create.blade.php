@extends('layouts.trainer.index')
@section('content')
<div class="relative min-h-full flex items-center justify-center px-4 overflow-hidden bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33]">
    <!-- Animated Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-[#00C2FF]/10 via-transparent to-[#2F82DB]/5 animate-gradient-slow blur-2xl opacity-40 pointer-events-none"></div>

    <!-- Glassmorphic Container -->
    <div class="relative z-10 w-full max-w-2xl bg-white/10 backdrop-blur-xl rounded-lg shadow-[0_0_25px_rgba(0,194,255,0.08)] border border-white/10 p-6 md:p-8 transition-all duration-500 ease-in-out transform hover:scale-[1.01] hover:shadow-[0_0_35px_rgba(0,194,255,0.15)] overflow-hidden">

        <!-- Flash Messages -->
        <div id="flashMessages" class="mb-3 text-center space-y-1">
            <div id="successMessage" class="hidden bg-[#00C2FF]/10 text-[#00C2FF] border border-[#00C2FF]/30 px-3 py-1.5 rounded text-sm font-medium"></div>
            <div id="errorMessage" class="hidden bg-red-500/10 text-red-400 border border-red-400/30 px-3 py-1.5 rounded text-sm font-medium"></div>
        </div>

        <h2 class="text-2xl font-semibold mb-5 text-[#E6EDF7] text-center tracking-wide">Create a New Course</h2>

        <!-- Course Form -->
        <form method="POST" id="courseForm" class="space-y-3">
            @csrf
            @php
            $inputClass = "mt-1 block w-full rounded-md bg-[#1C2541]/60 text-[#E6EDF7] border border-[#00C2FF]/20 px-2.5 py-1.5
            focus:outline-none focus:ring-2 focus:ring-[#00C2FF]/70 text-sm transition-all duration-200 placeholder-gray-400";
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="trainer_id" class="block text-[#A1A9C4] text-xs mb-1">Trainer ID<span class="text-red-500">*</span></label>
                    <input
                        type="number"
                        name="trainer_id"
                        id="trainer_id"
                        value="{{ Auth::guard('trainer')->id() }}"
                        class="{{ $inputClass }} bg-[#1C2541]/80 cursor-not-allowed opacity-80"
                        readonly>
                    <div id="trainer_idError" class="text-red-500 text-[11px] mt-1 error-message"></div>
                </div>

                <div>
                    <label for="title" class="block text-[#A1A9C4] text-xs mb-1">Title<span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" class="{{ $inputClass }}" placeholder="Enter course title">
                    <div id="titleError" class="text-red-500 text-[11px] mt-1 error-message"></div>
                </div>
            </div>

            <div>
                <label for="description" class="block text-[#A1A9C4] text-xs mb-1">Description<span class="text-red-500">*</span></label>
                <textarea name="description" id="description" rows="3" class="{{ $inputClass }}" placeholder="Brief description about the course..."></textarea>
                <div id="descriptionError" class="text-red-500 text-[11px] mt-1 error-message"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="price" class="block text-[#A1A9C4] text-xs mb-1">Price (Rs)<span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="price" id="price" class="{{ $inputClass }}" placeholder="Enter price">
                    <div id="priceError" class="text-red-500 text-[11px] mt-1 error-message"></div>
                </div>

                <div>
                    <label for="difficulty" class="block text-[#A1A9C4] text-xs mb-1">Difficulty</label>
                    <select name="difficulty" id="difficulty"
                        class="{{ $inputClass }} appearance-none bg-[#1C2541]/60 hover:bg-[#24345A]/70 focus:bg-[#24345A]/90">
                        <option value="" class="bg-[#0A0E19] text-[#E6EDF7]">-- Select Level --</option>
                        <option value="beginner" class="bg-[#0A0E19] text-[#E6EDF7]">Beginner</option>
                        <option value="intermediate" class="bg-[#0A0E19] text-[#E6EDF7]">Intermediate</option>
                        <option value="advanced" class="bg-[#0A0E19] text-[#E6EDF7]">Advanced</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <input type="checkbox" name="is_online" value="1" class="form-checkbox text-[#00C2FF] border-gray-300 rounded focus:ring-[#00C2FF]">
                <label class="text-[#E6EDF7] text-xs">This is an Online Course</label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="city" class="block text-[#A1A9C4] text-xs mb-1">City<span class="text-red-500">*</span></label>
                    <input type="text" name="city" id="city" class="{{ $inputClass }}" placeholder="Enter city">
                    <div id="cityError" class="text-red-500 text-[11px] mt-1 error-message"></div>
                </div>

                <div>
                    <label for="country" class="block text-[#A1A9C4] text-xs mb-1">Country<span class="text-red-500">*</span></label>
                    <input type="text" name="country" id="country" class="{{ $inputClass }}" placeholder="Enter country">
                    <div id="countryError" class="text-red-500 text-[11px] mt-1 error-message"></div>
                </div>
            </div>

            <div class="pt-3">
                <button type="submit" id="submitButton" disabled
                    class="w-full bg-gradient-to-r from-[#2f82db] to-[#00C2FF] text-white font-medium text-sm py-2 px-3 rounded-md 
                           shadow-md cursor-not-allowed opacity-70 transition-all duration-300 transform hover:scale-[1.01] hover:shadow-[0_0_15px_rgba(0,194,255,0.3)]">
                    Create Course
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    html,
    body {
        height: 100%;
        overflow: hidden;
    }

    @keyframes gradient-slow {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .animate-gradient-slow {
        background-size: 200% 200%;
        animation: gradient-slow 10s ease infinite;
    }

    input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px rgba(30, 64, 175, 0.1) inset !important;
        -webkit-text-fill-color: #e5e7eb !important;
    }

    select {
        cursor: pointer;
    }

    /* Ensure consistent dark background and text color on focus/selection/autofill */
    input,
    textarea,
    select {
        background-color: rgba(28, 37, 65, 0.6) !important;
        color: #E6EDF7 !important;
    }

    input:focus,
    textarea:focus,
    select:focus {
        background-color: rgba(36, 52, 90, 0.9) !important;
        color: #E6EDF7 !important;
        border-color: rgba(0, 194, 255, 0.6) !important;
    }

    select option {
        background-color: #0A0E19;
        color: #E6EDF7;
    }

    /* Chrome Autofill Fix */
    input:-webkit-autofill,
    textarea:-webkit-autofill,
    select:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px rgba(28, 37, 65, 0.6) inset !important;
        -webkit-text-fill-color: #E6EDF7 !important;
        caret-color: #E6EDF7;
        transition: background-color 5000s ease-in-out 0s;
    }

    /* When selecting text inside field */
    input::selection,
    textarea::selection {
        background-color: rgba(0, 194, 255, 0.3);
        color: #fff;
    }
</style>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const validators = {
        trainer_id: (v) => !v.trim() ? "Trainer ID is required." : "",
        title: (v) => !v.trim() ? "Title is required." : v.length < 5 ? "Title must be at least 5 characters." : "",
        description: (v) => !v.trim() ? "Description is required." : v.length < 10 ? "Description must be at least 10 characters." : "",
        price: (v) => !v.trim() ? "Price is required." : (isNaN(v) || parseFloat(v) <= 0) ? "Price must be positive." : "",
        city: (v) => !v.trim() ? "City is required." : "",
        country: (v) => !v.trim() ? "Country is required." : ""
    };

    function validateField(field) {
        const name = field.name;
        const error = validators[name]?.(field.value) || "";
        const errDiv = document.getElementById(`${name}Error`);
        if (error) {
            field.classList.add('border-red-500');
            errDiv.textContent = error;
        } else {
            field.classList.remove('border-red-500');
            errDiv.textContent = '';
        }
    }

    function isFormValid() {
        return Object.keys(validators).every(name => {
            const field = document.querySelector(`[name="${name}"]`);
            return field && !validators[name](field.value);
        });
    }

    const submitButton = document.getElementById('submitButton');

    function toggleSubmitButton() {
        if (isFormValid()) {
            submitButton.disabled = false;
            submitButton.classList.remove('cursor-not-allowed', 'opacity-70');
        } else {
            submitButton.disabled = true;
            submitButton.classList.add('cursor-not-allowed', 'opacity-70');
        }
    }

    Object.keys(validators).forEach(name => {
        const field = document.querySelector(`[name="${name}"]`);
        if (field) {
            field.addEventListener('input', () => {
                validateField(field);
                toggleSubmitButton();
            });
        }
    });

    document.addEventListener('DOMContentLoaded', toggleSubmitButton);

    // Submit handler
    document.getElementById('courseForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        if (!isFormValid()) return;
        const formData = new FormData(this);

        try {
            const res = await fetch("{{ route('trainer.courses.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            const data = await res.json();
            if (res.ok) {
                document.getElementById('successMessage').textContent = data.message || 'Course created successfully!';
                document.getElementById('successMessage').classList.remove('hidden');
                this.reset();
                toggleSubmitButton();
            } else {
                document.getElementById('errorMessage').textContent = data.message || 'Please fix the errors.';
                document.getElementById('errorMessage').classList.remove('hidden');
            }
        } catch (err) {
            console.error(err);
            alert('An unexpected error occurred.');
        }
    });
</script>
@endsection