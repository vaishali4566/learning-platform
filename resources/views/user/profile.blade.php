@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6 mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">My Profile</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-gray-700">Bio</label>
            <textarea name="bio" class="w-full border rounded px-3 py-2">{{ old('bio', $user->bio) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700">City</label>
                <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-gray-700">Country</label>
                <input type="text" name="country" value="{{ old('country', $user->country) }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block text-gray-700">Profile Image</label>
            <input type="file" name="profile_image" class="w-full border rounded px-3 py-2">
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="mt-3 w-24 h-24 rounded-full object-cover">
            @endif
        </div>

        <div>
            <label class="block text-gray-700">New Password (optional)</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2">
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2 mt-2" placeholder="Confirm password">
        </div>

        <div class="flex justify-between items-center">
            <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">Update Profile</button>

            <form action="{{ route('user.delete') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account?');">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete Account</button>
            </form>
        </div>
    </form>
</div>
@endsection
