@extends('layouts.guest')

@section('title', 'Trainer Register')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 to-green-700 p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Trainer Registration</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/trainer/register" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-gray-700 font-semibold">Full Name</label>
                <input type="text" name="name" id="name" required
                    class="w-full mt-1 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                    placeholder="Enter your name" value="{{ old('name') }}">
            </div>

            <div>
                <label for="email" class="block text-gray-700 font-semibold">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full mt-1 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                    placeholder="Enter your email" value="{{ old('email') }}">
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-semibold">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full mt-1 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                    placeholder="Enter password">
            </div>

            <div>
                <label for="password_confirmation" class="block text-gray-700 font-semibold">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full mt-1 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                    placeholder="Confirm password">
            </div>

            <div>
                <label for="profile_image" class="block text-gray-700 font-semibold">Profile Image</label>
                <input type="file" name="profile_image" id="profile_image"
                    class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-800 to-green-700 text-white font-semibold py-3 rounded-lg mt-2">
                Register
            </button>

            <p class="text-center text-gray-600 text-sm mt-3">
                Already have an account? 
                <a href="{{ route('trainer.login') }}" class="text-blue-700 font-semibold hover:underline">Login</a>
            </p>
        </form>
    </div>
</div>
@endsection
