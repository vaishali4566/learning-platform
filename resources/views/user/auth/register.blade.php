@extends('layouts.guest')

@section('title', 'Learning Project')

@section('content')
<div class="relative  flex items-center justify-center p-4 bg-cover bg-center"
    

    <!-- Registration Card -->
    <div class="relative w-full max-w-md bg-[#171717] rounded-xl shadow-lg p-8 z-10">

        <h2 id="heading" class="text-2xl font-bold text-center text-white mb-6">Swagatham 👋 User</h2>

        <!-- Role Toggle -->
        <div class="flex justify-center gap-4 mb-6">
            <button type="button" class="role-toggle px-4 py-2 rounded-md bg-gray-900 text-white font-semibold" data-role="user">User</button>
            <button type="button" class="role-toggle px-4 py-2 rounded-md bg-gray-700 text-white font-semibold" data-role="trainer">Trainer</button>
        </div>

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
                <input placeholder="Password" class="input-field pr-8" type="password" id="password" name="password">
                <button type="button" id="toggle-password" class="absolute right-4 top-3 text-gray-400 hover:text-gray-200">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z" />
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z" />
                    </svg>
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

            <!-- Profile Image -->
            <!-- <div class="field">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" viewBox="0 0 16 16">
                    <path d="M10.5 5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                    <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z"/>
                </svg>
                <input type="file" class="input-field" name="profile_image">
            </div>
            <p class="text-red-600 text-sm mt-1 hidden" id="profile_image-error"></p> -->

            <!-- Buttons -->
            <div class="flex justify-center gap-4 mt-4">
                <button type="submit" class="px-6 py-2 bg-blue-700 text-white font-semibold rounded hover:bg-blue-800 transition">Register</button>
                <a href="{{ route('user.login') }}" class="px-6 py-2 bg-gray-700 text-white font-semibold rounded hover:bg-gray-800 transition">Login</a>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Password toggle
    $('#toggle-password').on('click', function() {
        const passwordField = $('#password');
        const isPassword = passwordField.attr('type') === 'password';
        passwordField.attr('type', isPassword ? 'text' : 'password');
    });

    // Role toggle
    $('.role-toggle').on('click', function() {
        const role = $(this).data('role');
        const form = $('#register-form');

        if(role === 'user') {
            form.attr('action', "{{ route('user.register.submit') }}");
        } else {
            form.attr('action', "{{ route('trainer.register') }}");
        }

        $('.role-toggle').each(function() {
            if ($(this).data('role') === role) {
                $(this).removeClass('bg-gray-900').addClass('bg-gray-700');
            } else {
                $(this).removeClass('bg-gray-700').addClass('bg-gray-900');
            }
        });

        $('#heading').text('Swagatham ' + (role === 'user' ? 'User' : 'Trainer') + ' 👋');
    });

    // AJAX form submit
    $('#register-form').on('submit', function(e) {
        e.preventDefault();
        $('#name-error, #email-error, #password-error, #password_confirmation-error, #profile_image-error').text('').hide();

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
                    if(errors.profile_image) $('#profile_image-error').text(errors.profile_image[0]).show();
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
input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 30px #222 inset !important; /* same as input bg */
        -webkit-text-fill-color: #d3d3d3 !important; /* match your text color */
        transition: background-color 5000s ease-in-out 0s;
    }

    input:-webkit-autofill:focus,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px #222 inset !important;
        -webkit-text-fill-color: #d3d3d3 !important;
    }
</style>
@endsection
