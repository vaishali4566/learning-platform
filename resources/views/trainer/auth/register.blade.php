@extends('layouts.guest')

@section('title', 'Trainer Register')

@section('content')
<!-- Load Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="flex flex-col items-center justify-center p-4">
    <!-- Registration Form -->
    <form id="trainer-register-form" action="{{ route('trainer.register.submit') }}" method="POST" enctype="multipart/form-data" class="form w-full max-w-md">
        @csrf
        <p id="heading">Trainer Registration</p>

        <!-- Full Name -->
        <div class="field">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                fill="currentColor" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3z"/>
                <path fill-rule="evenodd" d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
            </svg>
            <input placeholder="Full Name" class="input-field" type="text" name="name" value="{{ old('name') }}">
        </div>
        <p class="text-red-500 text-sm mt-1 hidden" id="name-error"></p>

        <!-- Email -->
        <div class="field">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                fill="currentColor" viewBox="0 0 16 16">
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v0.217l-8 4.8-8-4.8V4zm0 1.383V12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5.383l-7.555 4.533a1 1 0 0 1-1.11 0L0 5.383z" />
            </svg>
            <input placeholder="Email" class="input-field" type="email" name="email" value="{{ old('email') }}">
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

            <!-- Eye toggle -->
            <button type="button" id="toggle-password"
                class="absolute right-3 top-2.5 text-gray-400 hover:text-white bg-transparent border-none p-1">
                <i id="eye-icon" class="bi bi-eye" style="font-size: 18px;"></i>
            </button>
        </div>
        <p class="text-red-500 text-sm mt-1 hidden" id="password-error"></p>

        <!-- Confirm Password -->
        <div class="field">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
            </svg>
            <input placeholder="Confirm Password" class="input-field" type="password" name="password_confirmation">
        </div>
        <p class="text-red-500 text-sm mt-1 hidden" id="password_confirmation-error"></p>

        <!-- Submit -->
        <div class="btn">
            <button type="submit" class="button1 w-full">Register</button>
        </div>

        <!-- Login Link -->
        <p class="text-center text-gray-400 text-sm mt-4">
            Already have an account?
            <a href="{{ route('trainer.login') }}" class="text-blue-400 hover:underline">Login</a>
        </p>
    </form>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    // Password toggle
    $('#toggle-password').on('click', function () {
        const passwordField = $('#password');
        const icon = $('#eye-icon');
        const isPassword = passwordField.attr('type') === 'password';

        passwordField.attr('type', isPassword ? 'text' : 'password');
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

    // AJAX form submit
    $('#trainer-register-form').on('submit', function (e) {
        e.preventDefault();
        $('#name-error, #email-error, #password-error, #password_confirmation-error').hide();

        let formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) window.location.href = response.redirect;
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.name) $('#name-error').text(errors.name[0]).show();
                    if (errors.email) $('#email-error').text(errors.email[0]).show();
                    if (errors.password) $('#password-error').text(errors.password[0]).show();
                    if (errors.password_confirmation) $('#password_confirmation-error').text(errors.password_confirmation[0]).show();
                } else {
                    alert('Something went wrong. Please try again.');
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
    transform: scale(1.02);
}

#heading {
    text-align: center;
    color: white;
    font-size: 1.2em;
    font-weight: 600;
    margin-bottom: 0.5em;
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
    font-size: 0.9em;
}

.btn {
    display: flex;
    justify-content: center;
    margin-top: 1.2em;
}

.button1 {
    padding: 0.6em 1.8em;
    border-radius: 6px;
    border: none;
    outline: none;
    transition: .3s ease-in-out;
    background-color: #252525;
    color: white;
    font-weight: 600;
}

.button1:hover {
    background-color: black;
}

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
