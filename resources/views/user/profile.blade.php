@extends('layouts.app')

@section('content')
<div class="relative min-h-screen flex items-center justify-center p-6 bg-cover bg-center"
     style="background-image: url('{{ asset('images/image.png') }}');">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>

    <!-- Modern Profile Card -->
    <div class="relative w-50 max-w-3xl bg-[#2f76a615] backdrop-blur-md rounded-md shadow-2xl z-10 border border-white/20 p-8">

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="relative inline-block">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" 
                         alt="Profile" 
                         class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-lg">
                @else
                    <div class="w-28 h-28 rounded-full bg-gray-200 flex items-center justify-center  text-3xl font-bold border-4 border-white shadow-lg">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                @endif
            </div>
            <h2 class="text-3xl font-bold text-white mt-4">{{ $user->name }}</h2>
            <p class="text-white">{{ $user->email }}</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Name & Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative">
                    <label class="block text-white font-semibold mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full border text-white bg-[#93a8be46] border-gray-800 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm hover:shadow-md transition">
                </div>

                <div class="relative">
                    <label class="block text-white font-semibold mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full border text-white bg-[#93a8be46] border-gray-800 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm hover:shadow-md transition">
                </div>
            </div>

            <!-- Bio -->
            <div>
                <label class="block text-white font-semibold mb-1">Bio</label>
                <textarea name="bio" rows="3"
                          class="w-full border text-white bg-[#93a8be46] border-gray-800 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm hover:shadow-md transition resize-none">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <!-- City / Country -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-white font-semibold mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}"
                           class="w-full border text-white bg-[#93a8be46] border-gray-800 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm hover:shadow-md transition">
                </div>

                <div>
                    <label class="block text-white font-semibold mb-1">Country</label>
                    <input type="text" name="country" value="{{ old('country', $user->country) }}"
                           class="w-full border text-white bg-[#93a8be46] border-gray-800 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm hover:shadow-md transition">
                </div>
            </div>

            <!-- Profile Image -->
            <div>
                <label class="block text-white font-semibold mb-1">Profile Image</label>
                <input type="file" name="profile_image"
                       class="w-full border text-white bg-[#93a8be46] border-gray-800 rounded-md px-4 py-2  focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm hover:shadow-md transition">
            </div>

            <!-- Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-white font-semibold mb-1">New Password</label>
                    <input type="password" name="password" placeholder="Enter new password"
                           class="w-full border text-white bg-[#93a8be46] border-gray-800 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm hover:shadow-md transition">
                </div>

                <div>
                    <label class="block text-white font-semibold mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Confirm new password"
                           class="w-full border text-white bg-[#93a8be46] border-gray-800 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm hover:shadow-md transition">
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-4">
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-md shadow-lg transition">
                    Update Profile
                </button>

                <form action="{{ route('user.delete') }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete your account?');" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-50 bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-md shadow-lg transition">
                        Delete Account
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection
