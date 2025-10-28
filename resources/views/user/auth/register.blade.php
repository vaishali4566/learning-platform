@extends('layouts.guest')

@section('title', 'Learning Project')

@section('content')
<!-- Load Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="relative flex items-center justify-center p-4 bg-cover bg-center">

    <!-- Registration Card -->
    <div class="relative w-full max-w-md bg-[#171717] rounded-xl shadow-lg p-8 z-10">

        <h2 id="heading" class="text-2xl font-bold text-center text-white mb-6">Swagatham 👋 User</h2>

        <!-- Registration Form -->
        <form id="register-form" action="{{ route('user.register.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Full Name -->
            <div class="field">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3z"/>
                    <path fill-rule="evenodd" d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                </svg>
                <input placeholder="Full Name" class="input-field" type="text" name="name" value="{{ old('name') }}">
            </div>
            <p class="text-red-600 text-sm mt-1 hidden" id="name-error"></p>

            <!-- Email -->
            <div class="field">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" viewBox="0 0 16 16">
                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v0.217l-8 4.8-8-4.8V4zm0 1.383V12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5.383l-7.555 4.533a1 1 0 0 1-1.11 0L0 5.383z" />
                </svg>
                <input placeholder="Email" class="input-field" type="email" name="email" value="{{ old('email') }}">
            </div>
            <p class="text-red-600 text-sm mt-1 hidden" id="email-error"></p>

            <!-- Password -->
            <div class="field relative">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                </svg>
                <input placeholder="Password" class="input-field pr-10" type="password" id="password" name="password">

                <!-- Toggle Button (Bootstrap Icons) -->
                <button type="button" id="toggle-password"
                    class="absolute right-3 top-2.5 text-gray-400 hover:text-white bg-transparent border-none p-1">
                    <i id="eye-icon" class="bi bi-eye" style="font-size: 18px;"></i>
                </button>
            </div>
            <p class="text-red-600 text-sm mt-1 hidden" id="password-error"></p>

            <!-- Confirm Password -->
            <div class="field relative">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                </svg>
                <input placeholder="Confirm Password" class="input-field pr-8" type="password" name="password_confirmation">
            </div>
            <p class="text-red-600 text-sm mt-1 hidden" id="password_confirmation-error"></p>

            <!-- Buttons -->
            <div class="flex justify-center gap-4 mt-4">
                <button type="submit" class="px-6 py-2 bg-blue-700 text-white font-semibold rounded hover:bg-blue-800 transition">Register</button>
                <a href="{{ route('user.login') }}" class="px-6 py-2 bg-gray-700 text-white font-semibold rounded hover:bg-gray-800 transition">Login</a>
            </div>
        </form>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Password toggle with Bootstrap icon animation
    $('#toggle-password').on('click', function () {
        const passwordField = $('#password');
        const icon = $('#eye-icon');
        const isPassword = passwordField.attr('type') === 'password';

        passwordField.attr('type', isPassword ? 'text' : 'password');

        // Add rotation animation
        icon.addClass('icon-rotate');
        setTimeout(() => {
            if (isPassword) {
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
            setTimeout(() => icon.removeClass('icon-rotate'), 220);
        }, 100);
    });

    // AJAX form submit (same as before)
    $('#register-form').on('submit', function(e) {
        e.preventDefault();
        $('#name-error, #email-error, #password-error, #password_confirmation-error').text('').hide();

        let formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) window.location.href = response.redirect;
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if(errors.name) $('#name-error').text(errors.name[0]).show();
                    if(errors.email) $('#email-error').text(errors.email[0]).show();
                    if(errors.password) $('#password-error').text(errors.password[0]).show();
                    if(errors.password_confirmation) $('#password_confirmation-error').text(errors.password_confirmation[0]).show();
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        });
    });
});
</script>

<style>
.field {
    display: flex;
    align-items: center;
    gap: 0.6em;
    padding: 0.7em 1em;
    border-radius: 10px;
    background-color: #222;
    box-shadow: inset 1px 2px 6px rgba(0,0,0,0.7);
}
.input-field {
    background: none;
    border: none;
    outline: none;
    width: 100%;
    color: #d3d3d3;
}
.input-icon {
    height: 1.2em;
    width: 1.2em;
    fill: white;
}

/* Autofill fix */
input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0 30px #222 inset !important;
    -webkit-text-fill-color: #d3d3d3 !important;
}

/* Rotation animation same as login */
.icon-rotate {
    transform: rotate(180deg);
    transition: transform 0.22s ease-in-out;
    display: inline-block;
}
#toggle-password {
    cursor: pointer;
    background: transparent;
    border: none;
}
#toggle-password:hover i {
    color: #fff;
}
</style>
@endsection
