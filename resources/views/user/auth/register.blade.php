@extends('layouts.guest')

@section('title', 'User Register')

@section('content')
<div class="relative min-h-screen flex items-center justify-center p-4 bg-cover bg-center" 
     style="background-image: url('{{ asset('images/image.png') }}');">

    <!-- Black overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>

    <!-- Registration Card -->
    <div class="relative w-full max-w-md bg-white rounded-xl shadow-lg p-8 z-10">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">User Registration</h2>

        <form id="register-form" action="{{ route('user.register.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Full Name -->
            <div>
                <label for="name" class="block text-gray-700 font-semibold">Full Name</label>
                <input type="text" name="name" id="name" 
                    class="w-full mt-1 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                    placeholder="Enter your name" value="{{ old('name') }}">
                <p class="text-red-600 text-sm mt-1 hidden" id="name-error"></p>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 font-semibold">Email</label>
                <input type="email" name="email" id="email" 
                    class="w-full mt-1 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                    placeholder="Enter your email" value="{{ old('email') }}">
                <p class="text-red-600 text-sm mt-1 hidden" id="email-error"></p>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-gray-700 font-semibold">Password</label>
                <input type="password" name="password" id="password" 
                    class="w-full mt-1 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                    placeholder="Enter password">
                <p class="text-red-600 text-sm mt-1 hidden" id="password-error"></p>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-gray-700 font-semibold">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                    class="w-full mt-1 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                    placeholder="Confirm password">
                <p class="text-red-600 text-sm mt-1 hidden" id="password_confirmation-error"></p>
            </div>

            <!-- Profile Image -->
            <div>
                <label for="profile_image" class="block text-gray-700 font-semibold">Profile Image</label>
                <input type="file" name="profile_image" id="profile_image"
                    class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600">
                <p class="text-red-600 text-sm mt-1 hidden" id="profile_image-error"></p>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-800 to-green-700 text-white font-semibold py-3 rounded-lg mt-2 hover:opacity-90 transition">
                Register
            </button>

            <!-- Login Link -->
            <p class="text-center text-gray-600 text-sm mt-3">
                Already have an account?
                <a href="{{ route('user.login') }}" class="text-blue-700 font-semibold hover:underline">Login</a>
            </p>
        </form>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('#register-form').on('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        $('#name-error, #email-error, #password-error, #password_confirmation-error, #profile_image-error').text('').hide();

        let formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect;
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
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
@endsection
