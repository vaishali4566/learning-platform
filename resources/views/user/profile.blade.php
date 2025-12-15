@extends('layouts.user.index')

@section('content')
<div class="relative min-h-screen flex items-center justify-center px-6 py-10 overflow-hidden 
            bg-gradient-to-br from-[#0A0E19] via-[#0E1426] to-[#141C33]">

    <!-- Animated Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-[#00C2FF]/10 via-transparent to-[#2F82DB]/5 
                animate-gradient-slow blur-3xl opacity-40"></div>

    <!-- Glassmorphic Profile Container -->
    <div class="relative z-10 w-full max-w-5xl bg-white/10 backdrop-blur-2xl rounded-md shadow-[0_0_40px_rgba(0,194,255,0.1)] border border-white/10 p-8 md:p-10 flex flex-col md:flex-row gap-10
                transition-all duration-700 ease-in-out transform hover:scale-[1.01] hover:shadow-[0_0_50px_rgba(0,194,255,0.2)]">

        <!-- Left Section: Profile Summary -->
        <div class="md:w-1/3 flex flex-col items-center text-center space-y-4 border-b md:border-b-0 md:border-r border-white/10 pb-6 md:pb-0 md:pr-6">
            @if($user->profile_image)
            <div class="relative group">
                <img src="{{ asset('storage/' . $user->profile_image) }}"
                    alt="Profile"
                    class="w-32 h-32 rounded-full object-cover border-4 border-[#00C2FF]/60 shadow-lg transition-transform duration-500 group-hover:scale-110 group-hover:rotate-1">
                <div class="absolute inset-0 rounded-full bg-gradient-to-t from-[#00C2FF]/20 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
            </div>
            @else
            <div class="relative w-32 h-32 rounded-full bg-[#1C2541] flex items-center justify-center text-3xl font-bold border-4 border-[#00C2FF]/60 shadow-lg text-[#E6EDF7] transition-transform duration-500 hover:scale-110 hover:rotate-1">
                {{ strtoupper(substr($user->name,0,1)) }}
            </div>
            @endif

            <div>
                <h2 class="text-xl font-semibold text-[#E6EDF7]">{{ $user->name }}</h2>
                <p class="text-[#9CA3B0] text-sm">{{ $user->email }}</p>
                <p class="text-[#9CA3B0] text-xs mt-1 italic tracking-wide">{{ $user->country ?? '—' }}</p>
            </div>
        </div>

        <!-- Right Section: Edit Form -->
        <div class="md:w-2/3">
            <h3 class="text-lg md:text-xl font-semibold text-[#E6EDF7] mb-5 flex items-center gap-2 border-b border-white/10 pb-2">
                <span class="text-[#00C2FF]">⚙️</span> Edit Your Profile
            </h3>

            @if(session('success'))
            <div class="bg-[#00C2FF]/10 text-[#00C2FF] p-3 rounded-md mb-4 text-center text-sm border border-[#00C2FF]/40 font-medium shadow-sm">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Name & Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[#A1A9C4] text-sm mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full bg-[#1C2541]/70 text-[#E6EDF7] border border-[#00C2FF]/20 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00C2FF]/70 text-sm transition-all duration-300 placeholder-gray-400">
                    </div>

                    <div>
                        <label class="block text-[#A1A9C4] text-sm mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full bg-[#1C2541]/70 text-[#E6EDF7] border border-[#00C2FF]/20 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00C2FF]/70 text-sm transition-all duration-300">
                    </div>
                </div>

                <!-- Bio -->
                <div>
                    <label class="block text-[#A1A9C4] text-sm mb-1">Bio</label>
                    <textarea name="bio" rows="3"
                        class="w-full bg-[#1C2541]/70 text-[#E6EDF7] border border-[#00C2FF]/20 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00C2FF]/70 resize-none text-sm transition-all duration-300 placeholder-gray-400">{{ old('bio', $user->bio) }}</textarea>
                </div>

                <!-- City & Country -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[#A1A9C4] text-sm mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}"
                            class="w-full bg-[#1C2541]/70 text-[#E6EDF7] border border-[#00C2FF]/20 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00C2FF]/70 text-sm transition-all duration-300">
                    </div>

                    <div>
                        <label class="block text-[#A1A9C4] text-sm mb-1">Country</label>
                        <input type="text" name="country" value="{{ old('country', $user->country) }}"
                            class="w-full bg-[#1C2541]/70 text-[#E6EDF7] border border-[#00C2FF]/20 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00C2FF]/70 text-sm transition-all duration-300">
                    </div>
                </div>

                <!-- Profile Image -->
                <div>
                    <label class="block text-[#00C2FF] text-sm font-semibold mb-1">Profile Image</label>
                    <input type="file" name="profile_image"
                        class="w-full bg-[#1C2541]/80 text-[#E6EDF7] border border-[#00C2FF]/20 rounded-lg px-3 py-2 
                                  focus:outline-none focus:ring-2 focus:ring-[#00C2FF]/70 text-sm transition-all duration-300 
                                  file:mr-3 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium 
                                  file:bg-gradient-to-r file:from-[#2f82db] file:to-[#00C2FF] file:text-white hover:file:opacity-90 cursor-pointer">
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[#A1A9C4] text-sm mb-1">Old Password</label>
                        <input type="password" name="old_password" placeholder="Enter old password"
                            class="w-full bg-[#1C2541]/70 text-[#E6EDF7] border border-[#00C2FF]/20 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00C2FF]/70 text-sm transition-all duration-300 placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-[#A1A9C4] text-sm mb-1">New Password</label>
                        <input type="password" name="password" placeholder="Enter new password"
                            class="w-full bg-[#1C2541]/70 text-[#E6EDF7] border border-[#00C2FF]/20 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00C2FF]/70 text-sm transition-all duration-300 placeholder-gray-400">
                    </div>

                    <div>
                        <label class="block text-[#A1A9C4] text-sm mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm new password"
                            class="w-full bg-[#1C2541]/70 text-[#E6EDF7] border border-[#00C2FF]/20 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00C2FF]/70 text-sm transition-all duration-300 placeholder-gray-400">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col md:flex-row justify-between items-center gap-3 pt-3">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-[#2f82db] to-[#00C2FF] hover:from-[#00C2FF] hover:to-[#2f82db] text-white font-semibold text-sm px-6 py-2.5 rounded-md shadow-lg transition-all duration-300 transform hover:scale-[1.03] hover:shadow-[0_0_20px_rgba(0,194,255,0.4)]">
                        Save Profile
                    </button>

                    <form action="{{ route('user.delete') }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete your account?');" class="flex-1">
                        @csrf
                        <button type="submit"
                            class=" bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-600 text-white font-semibold text-sm px-6 py-2.5 rounded-md shadow-lg transition-all duration-300 transform hover:scale-[1.03] hover:shadow-[0_0_20px_rgba(255,0,0,0.4)]">
                            Delete Account
                        </button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* 🔹 Animated background gradient */
    @keyframes gradient-slow {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .animate-gradient-slow {
        background-size: 200% 200%;
        animation: gradient-slow 12s ease infinite;
    }

    /* 🔹 Autofill fixes */
    input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px rgba(30, 64, 175, 0.1) inset !important;
        -webkit-text-fill-color: #e5e7eb !important;
    }

    input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0px 1000px rgba(59, 130, 246, 0.15) inset !important;
        -webkit-text-fill-color: #fff !important;
    }

    input:autofill {
        background: rgba(30, 64, 175, 0.1) !important;
        color: #e5e7eb !important;
    }
</style>
@endsection