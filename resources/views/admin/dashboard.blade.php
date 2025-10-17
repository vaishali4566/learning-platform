@extends('layouts.app')

@section('content')
<div class="relative min-h-screen flex items-center justify-center p-4 bg-cover bg-center" 
     style="background-image: url('{{ asset('images/image.png') }}');">

    <!-- Black overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>

    <!-- Content box -->
    <div class="relative w-full max-w-4xl bg-white rounded-xl shadow-lg p-10 z-10">
        <h1 class="text-3xl font-semibold text-gray-800 text-center mb-4">
            Welcome, {{ Auth::user()->name }}
        </h1>

        <p class="text-center text-gray-600 text-lg mb-8">
            This is your admin dashboard. You can manage users, trainers, and monitor admin actions here.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('admin.users') }}" class="flex items-center justify-center px-5 py-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition">
                <span class="text-gray-700 font-medium">Manage Users</span>
            </a>
            <a href="{{ route('admin.trainers') }}" class="flex items-center justify-center px-5 py-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition">
                <span class="text-gray-700 font-medium">Manage Trainers</span>
            </a>
            <a href="#" class="flex items-center justify-center px-5 py-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition">
                <span class="text-gray-700 font-medium">Admin Logs</span>
            </a>
        </div>

        <form action="{{ route('user.logout') }}" method="POST" class="text-center">
            @csrf
            <button type="submit" class="px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection
