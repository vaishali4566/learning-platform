@extends('layouts.app')

@section('title', 'User Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 to-green-700 p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">User Login</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Use direct URL for action since route is not named -->
        <form action="/login" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-gray-700 font-semibold">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full mt-1 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                    placeholder="Enter your email"
                >
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-semibold">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                    class="w-full mt-1 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                    placeholder="Enter your password"
                >
            </div>

            <div class="flex justify-between items-center">
                <div>
                    <input type="checkbox" name="remember" id="remember" class="mr-1">
                    <label for="remember" class="text-gray-600 text-sm">Remember me</label>
                </div>
                <a href="#" class="text-blue-700 hover:underline text-sm">Forgot password?</a>
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
@endsection
