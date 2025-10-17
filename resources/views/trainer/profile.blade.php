@extends('layouts.app')

@section('content')
<div class="relative min-h-screen flex items-center justify-center p-4" 
     style="background-image: url('{{ asset('images/image.png') }}'); background-size: cover; background-position: center;">

    <!-- Black overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>

    <!-- Content box -->
    <div class="relative w-full max-w-3xl bg-white bg-opacity-95 rounded-xl shadow-lg p-6 z-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Trainer Profile</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('trainer.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name', $trainer->name) }}" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $trainer->email) }}" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-gray-700">Bio</label>
                <textarea name="bio" class="w-full border rounded px-3 py-2">{{ old('bio', $trainer->bio) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700">City</label>
                    <input type="text" name="city" value="{{ old('city', $trainer->city) }}" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-gray-700">Country</label>
                    <input type="text" name="country" value="{{ old('country', $trainer->country) }}" class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <div>
                <label class="block text-gray-700">Profile Image</label>
                <input type="file" name="profile_image" class="w-full border rounded px-3 py-2">
                @if($trainer->profile_image)
                    <img src="{{ asset('storage/' . $trainer->profile_image) }}" alt="Profile" class="mt-3 w-24 h-24 rounded-full object-cover">
                @endif
            </div>

            <div>
                <label class="block text-gray-700">New Password (optional)</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2">
                <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2 mt-2" placeholder="Confirm password">
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">Update Profile</button>

                <form action="{{ route('trainer.delete') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account?');">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete Account</button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection
