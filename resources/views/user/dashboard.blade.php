@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 to-green-700 p-4">
    <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-4xl font-bold mb-4 text-gray-800 text-center">Welcome, {{ Auth::user()->name }}</h1>

        <p class="text-center text-gray-600 text-lg mb-8">
            This is your user dashboard. You can view your profile, courses, and manage your account here.
        </p>

        <div class="flex justify-center gap-4 flex-wrap">
            <a href="{{ route('user.profile') }}" class="px-6 py-3 bg-gradient-to-r from-blue-800 to-green-700 text-white font-semibold rounded-lg hover:opacity-90">
                My Profile
            </a>
            <a href="#" class="px-6 py-3 bg-gradient-to-r from-blue-800 to-green-700 text-white font-semibold rounded-lg hover:opacity-90">
                My Courses
            </a>
            <a href="#" class="px-6 py-3 bg-gradient-to-r from-blue-800 to-green-700 text-white font-semibold rounded-lg hover:opacity-90">
                Settings
            </a>
        </div>

        <form action="{{ route('user.logout') }}" method="POST" class="mt-8 text-center">
            @csrf
            <button type="submit" class="px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection
