@extends('layouts.guest')

@section('title', 'User Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 to-green-700 p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">User Login</h2>

        <form id="login-form" action="{{ route('user.login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-gray-700 font-semibold">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    class="w-full mt-1 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                    placeholder="Enter your email"
                >
                <!-- Inline error -->
                <p class="text-red-600 text-sm mt-1 hidden" id="email-error"></p>
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-semibold">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="w-full mt-1 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                    placeholder="Enter your password"
                >
                <!-- Inline error -->
                <p class="text-red-600 text-sm mt-1 hidden" id="password-error"></p>
            </div>

            <div class="flex justify-between items-center">
                <div>
                    <input type="checkbox" name="remember" id="remember" class="mr-1">
                    <label for="remember" class="text-gray-600 text-sm">Remember me</label>
                </div>
                <a href="{{route('password.request')}}" class="text-blue-700 hover:underline text-sm">Forgot password?</a>
            </div>

            <button
                type="submit"
                class="w-full bg-gradient-to-r from-blue-800 to-green-700 text-white font-semibold py-3 rounded-lg mt-2"
            >
                Login
            </button>

            <p class="text-center text-gray-600 text-sm mt-3">
                Don’t have an account? 
                <a href="{{ route('user.register') }}" class="text-blue-700 font-semibold hover:underline">Register</a>
            </p>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('#login-form').on('submit', function(e) {
        e.preventDefault();

        // Clear previous inline errors
        $('#email-error').text('').hide();
        $('#password-error').text('').hide();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    window.location.href = response.redirect;
                }
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    // Show email error
                    if(errors.email) {
                        $('#email-error').text(errors.email[0]).show();
                    }

                    // Show password error
                    if(errors.password) {
                        $('#password-error').text(errors.password[0]).show();
                    }
                } else {
                    // Generic error (show under password field)
                    $('#password-error').text('Something went wrong. Please try again.').show();
                }
            }
        });
    });
});
</script>
@endsection
