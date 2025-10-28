@extends('layouts.guest')

@section('title', 'Learning project')

@section('content')
<!-- Load Bootstrap Icons CDN (works without installing) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="flex flex-col items-center justify-center p-4">

   

    <!-- Login Form -->
    <form id="login-form" action="{{ route('user.login.submit') }}" method="POST" class="form w-full max-w-md">
        @csrf
        <p id="heading">Swagatham User 👋</p>

        <!-- Email -->
        <div class="field">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v0.217l-8 4.8-8-4.8V4zm0 1.383V12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5.383l-7.555 4.533a1 1 0 0 1-1.11 0L0 5.383z" />
            </svg>
            <input autocomplete="off" placeholder="Email" class="input-field" type="email" name="email"
                value="{{ old('email') }}">
        </div>
        <p class="text-red-500 text-sm mt-1 hidden" id="email-error"></p>

        <!-- Password -->
        <div class="field relative">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
            </svg>

            <input placeholder="Password" class="input-field pr-10" type="password" id="password" name="password">

            <!-- Toggle Button: now using Bootstrap Icons -->
            <button type="button" id="toggle-password"
                class="absolute right-3 top-2.5 text-gray-400 hover:text-white bg-transparent border-none p-1">
                <i id="eye-icon" class="bi bi-eye" style="font-size: 18px;"></i>
            </button>
        </div>
        <p class="text-red-500 text-sm mt-1 hidden" id="password-error"></p>

        <!-- Buttons -->
        <div class="btn">
            <button type="submit" class="button1">Login</button>
            <a href="{{ route('user.register') }}" id="register-link" class="button2 text-center">Sign Up</a>
        </div>

        <a href="{{ route('user.password.request') }}" id="forgot-link" class="button3 text-center">Forgot Password?</a>
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
    .form {
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding: 2em;
        background-color: #171717;
        border-radius: 20px;
        width: 100%;
        transition: .3s ease-in-out;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
    }

    .form:hover {
        transform: scale(1.03);
    }

    #heading {
        text-align: center;
        color: white;
        font-size: 1.4em;
        font-weight: 600;
    }

    .field {
        display: flex;
        align-items: center;
        gap: 0.6em;
        padding: 0.7em 1em;
        border-radius: 10px;
        background-color: #222;
        box-shadow: inset 1px 2px 6px rgba(0, 0, 0, 0.7);
    }

    .input-icon {
        height: 1.2em;
        width: 1.2em;
        fill: white;
    }

    .input-field {
        background: none;
        border: none;
        outline: none;
        width: 100%;
        color: #d3d3d3;
        font-size: 0.95em;
    }

    .btn {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 1.5em;
    }

    .button1,
    .button2 {
        padding: 0.6em 1.8em;
        border-radius: 6px;
        border: none;
        outline: none;
        transition: .3s ease-in-out;
        background-color: #252525;
        color: white;
        font-weight: 600;
    }

    .button1:hover,
    .button2:hover {
        background-color: black;
    }

    .button3 {
        display: block;
        margin-top: 1em;
        padding: 0.6em;
        border-radius: 6px;
        background-color: #252525;
        color: white;
        text-decoration: none;
        text-align: center;
        font-weight: 500;
        transition: .3s;
    }

    .button3:hover {
        background-color: red;
    }

    input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 30px #222 inset !important;
        -webkit-text-fill-color: #d3d3d3 !important;
    }

    input:-webkit-autofill:focus,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px #222 inset !important;
        -webkit-text-fill-color: #d3d3d3 !important;
    }

    /* Icon rotate animation */
    .icon-rotate {
        transform: rotate(180deg);
        transition: transform 0.22s ease-in-out;
        display: inline-block;
    }

    /* Small button reset */
    #toggle-password {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        cursor: pointer;
    }

    /* make icon color match hover */
    #toggle-password:hover i {
        color: #fff;
    }
</style>
@endsection
