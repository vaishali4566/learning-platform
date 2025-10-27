@extends('layouts.app')

@section('content')
<div class="relative min-h-screen flex flex-col items-center justify-center p-6 bg-cover bg-center"
     style="background-image: url('{{ asset('images/image.png') }}');">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>

    <!-- Dashboard Card -->
    <div class="relative w-full max-w-4xl bg-[#2f76a615] backdrop-blur-md rounded-xl shadow-2xl z-10 border border-white/20 p-10">

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white">Welcome, {{ Auth::guard('trainer')->user()->name }}</h1>
            <p class="text-white/80 mt-2">
                This is your trainer dashboard. Manage your profile, courses, and other trainer settings here.
            </p>
        </div>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('trainer.profile') }}" class="flex flex-col items-center justify-center bg-[#93a8be46] text-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition transform hover:scale-105">
                <span class="text-lg font-semibold">My Profile</span>
            </a>

            <a href="{{ route('courses.mycourses') }}" class="flex flex-col items-center justify-center bg-[#93a8be46] text-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition transform hover:scale-105">
                <span class="text-lg font-semibold">My Courses</span>
            </a>

            <a href="#" class="flex flex-col items-center justify-center bg-[#93a8be46] text-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition transform hover:scale-105">
                <span class="text-lg font-semibold">Settings</span>
            </a>
        </div>

        <!-- Logout Button -->
        <form action="{{ route('trainer.logout') }}" method="POST" class="text-center">
            @csrf
            <button type="submit" 
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-3 rounded-xl shadow-lg transition">
                Logout
            </button>
        </form>

    </div>
</div>
@endsection
