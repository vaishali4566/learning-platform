@extends('layouts.guest')

@section('title', 'Globussoft E-Learning')

@section('content')
<!-- Load Bootstrap Icons CDN (works without installing) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="flex flex-col items-center justify-center p-2 sm:p-4">

    <!-- Login Form -->
    <form id="login-form" action="{{ route('user.login.submit') }}" method="POST" class="login-card py-8 px-4 sm:p-8 form w-full max-w-md 2xl:max-w-lg">
        @csrf
        {{-- <p id="heading">Swagatham User 👋</p> --}}

        <!-- Heading -->
        <div class="text-center mb-6">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-[#E6EDF7]">
                Welcome Back 👋
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1 mb-3">
                Login to continue learning
            </p>
        </div>

        <!-- Email -->
        <div class="input-group">
            <i class="bi bi-envelope input-icon sm:text-base"></i>
            <input placeholder="Email address" class="input text-xs sm:text-sm" type="email" name="email"
                value="{{ old('email') }}"  required autofocus>
        </div>
        <p id="email-error" class="error-text text-xs sm:text-sm hidden"></p>

        <!-- Password -->
        <div class="input-group relative">
            <i class="bi bi-lock input-icon sm:text-base"></i>
            <input placeholder="Password" class="input pr-10 text-xs sm:text-sm" type="password" id="password" name="password" required>

            <!-- Toggle Button: now using Bootstrap Icons -->
            <button type="button" id="toggle-password" class="eye-btn">
                <i id="eye-icon" class="bi bi-eye"></i>
            </button>
        </div>
        <p id="password-error" class="error-text text-xs sm:text-sm hidden"></p>

        <!-- Remember + Forgot -->
        <div class="flex items-center justify-between mt-3 text-xs sm:text-sm">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="remember" class="remember-checkbox">
                <span class="text-gray-600 dark:text-gray-400">Remember me</span>
            </label>

            <a href="{{ route('user.password.request') }}" class="link">
                Forgot password?
            </a>
        </div>

        <!-- Actions -->
        <button type="submit" class="btn-primary text-sm sm:text-base w-full mt-6">
            Login
        </button>

        <div class="flex justify-center items-center mt-6 text-xs sm:text-sm">
            <span class="text-gray-600 dark:text-gray-400">
                Don't have an account?
                <a href="{{ route('user.register') }}" class="link font-medium">
                    Sign Up
                </a>
            </span>
        </div>
    </form>
</div>

<!-- jQuery (kept as you used it) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {

        // Password toggle using Bootstrap Icons
        $('#toggle-password').on('click', function () {
            const passwordField = $('#password');
            const icon = $('#eye-icon');
            const isPassword = passwordField.attr('type') === 'password';

            // Toggle input type
            passwordField.attr('type', isPassword ? 'text' : 'password');

            // Animate: add rotate class, then swap icon
            icon.addClass('icon-rotate');
            setTimeout(() => {
                if (isPassword) {
                    // show "eye-slash" (password visible => show slash)
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    // show "eye" (password hidden => normal eye)
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
                // remove rotate after animation completes
                setTimeout(() => icon.removeClass('icon-rotate'), 220);
            }, 100);
        });

        // Role toggle
        $('.role-toggle').on('click', function () {
            const role = $(this).data('role');
            const form = $('#login-form');

            if (role === 'user') {
                form.attr('action', "{{ route('user.login.submit') }}");
                $('#register-link').attr('href', "{{ route('user.register') }}");
                $('#forgot-link').attr('href', "{{ route('user.password.request') }}");
            } else {
                form.attr('action', "{{ route('trainer.login.submit') }}");
                $('#register-link').attr('href', "{{ route('trainer.register') }}");
                $('#forgot-link').attr('href', "{{ route('trainer.password.request') }}");
            }

            $('.role-toggle').each(function () {
                if ($(this).data('role') === role) {
                    $(this).removeClass('bg-gray-900').addClass('bg-gray-700');
                } else {
                    $(this).removeClass('bg-gray-700').addClass('bg-gray-900');
                }
            });

            $('#heading').text('Swagatham ' + (role === 'user' ? 'User' : 'Trainer') + ' 👋');
        });

        // AJAX Login
        $('#login-form').on('submit', function (e) {
            e.preventDefault();
            $('#email-error, #password-error').hide();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success) window.location.href = response.redirect;
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        if (errors.email) $('#email-error').text(errors.email[0]).show();
                        if (errors.password) $('#password-error').text(errors.password[0]).show();
                    } else {
                        $('#password-error').text('Something went wrong. Please try again.').show();
                    }
                }
            });
        });
    });
</script>

<style>
    /* CARD */
    .login-card {
        background: #ffffff;
        border-radius: 14px;
        /* padding: 2rem; */
        border: 1px solid #e5e7eb;
        box-shadow: 0 6px 25px rgba(0,0,0,0.05);
    }

    .dark .login-card {
        background: #161c33;
        border-color: #26304d;
        box-shadow: 0 10px 30px rgba(0,0,0,0.35);
    }

    /* INPUT GROUP */
    .input-group {
        position: relative;
        margin-bottom: 0.75rem;
    }

    .input {
        width: 100%;
        padding: 0.65rem 0.75rem 0.65rem 2.5rem;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        /* font-size: 0.9rem; */
        background: #f9fafb;
        color: #111827;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .input:focus {
        border-color: #00C2FF;
        /* box-shadow: 0 0 0 2px rgba(0,194,255,0.2); */
    }
    .dark .input:focus {
        border-color: #3ab2d7bf;
        /* box-shadow: 0 0 0 2px rgba(0,194,255,0.2); */
    }

    .dark .input {
        background: #0e1625;
        border-color: #374151;
        color: #e5e7eb;
    }

    /* ICONS */
    .input-icon {
        position: absolute;
        top: 50%;
        left: 0.75rem;
        transform: translateY(-50%);
        color: #9ca3af;
        /* font-size: 1rem; */
    }

    /* PASSWORD EYE */
    .eye-btn {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #9ca3af;
    }

    .eye-btn:hover {
        color: #00C2FF;
    }

    /* BUTTON */
    .btn-primary {
        background: linear-gradient(90deg, #2f82db, #00C2FF);
        color: white;
        padding: 0.65rem;
        border-radius: 8px;
        font-weight: 600;
        transition: opacity 0.2s;
    }

    .btn-primary:hover {
        opacity: 0.9;
    }

    /* LINKS */
    .link {
        color: #00C2FF;
        transition: opacity 0.2s;
    }

    .link:hover {
        opacity: 0.8;
        text-decoration: underline
    }

    /* ERRORS */
    .error-text {
        /* font-size: 0.85rem; */
        color: #dc2626;
        margin-top: 0.25rem;
        text-align: left;
    }

</style>
@endsection
