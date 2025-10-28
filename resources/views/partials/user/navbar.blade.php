<nav class="bg-gradient-to-r from-[#0E1625] via-[#1C2541] to-[#0E1625] text-[#E6EDF7] px-6 py-3 flex justify-between items-center shadow-lg fixed top-0 left-0 right-0 z-50 border-b border-[#26304D] backdrop-blur-md bg-opacity-90">
    <!-- Left: Brand + Sidebar Toggle -->
    <div class="flex items-center gap-3">
        <button id="sidebarToggle"
            class="text-[#E6EDF7] hover:text-[#00C2FF] focus:outline-none transition-transform duration-200 hover:scale-110">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>

        <div class="flex items-center gap-2">
            <div class="p-2 rounded-lg bg-[#00C2FF]/10 border border-[#00C2FF]/30 backdrop-blur-sm">
                <i data-lucide="graduation-cap" class="w-5 h-5 text-[#00C2FF]"></i>
            </div>
            <span class="font-semibold text-lg tracking-wide text-[#E6EDF7]">E-Learning</span>
        </div>
    </div>

    <!-- Middle: Quick Links -->
    <div class="hidden md:flex items-center gap-6">
        <!-- Dashboard -->
        <a href="{{ route('user.dashboard') }}"
            class="relative group flex items-center gap-1 transition">
            <i data-lucide="home" class="w-5 h-5 text-[#8A93A8] group-hover:text-[#00C2FF]"></i>
            <span class="group-hover:text-[#00C2FF]">Dashboard</span>
            <span
                class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
        </a>

        <!-- My Courses -->
        <a href="{{ route('courses.mycourses') }}"
            class="relative group flex items-center gap-1 transition">
            <i data-lucide="book-open" class="w-5 h-5 text-[#8A93A8] group-hover:text-[#00C2FF]"></i>
            <span class="group-hover:text-[#00C2FF]">My Courses</span>
            <span
                class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
        </a>

        <!-- All Courses -->
        <a href="{{ route('courses.index') }}"
            class="relative group flex items-center gap-1 transition">
            <i data-lucide="library" class="w-5 h-5 text-[#8A93A8] group-hover:text-[#00C2FF]"></i>
            <span class="group-hover:text-[#00C2FF]">All Courses</span>
            <span
                class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
        </a>

        
    </div>

    <!-- Right: Notifications + Profile -->
    <div class="flex items-center gap-5">
        <!-- Notification -->
        <button class="relative group hover:text-[#00C2FF] transition">
            <i data-lucide="bell" class="w-5 h-5"></i>
            <span
                class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border border-[#0E1625] shadow-md"></span>
        </button>

        <!-- Profile Dropdown -->
        <div class="relative group">
            <button class="flex items-center gap-2 hover:text-[#00C2FF] transition">
                <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                    class="w-9 h-9 rounded-full border-2 border-transparent object-cover transition duration-300 group-hover:border-[#00C2FF] group-hover:shadow-[0_0_10px_#00C2FF50]" />
                <span class="hidden md:inline text-[#E6EDF7]">{{ Auth::user()->name }}</span>
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </button>

            <div
                class="absolute right-0 mt-2 w-44 bg-[#1C2541]/95 border border-[#26304D] text-[#E6EDF7] rounded-md shadow-lg opacity-0 group-hover:opacity-100 group-hover:translate-y-1 transform transition-all duration-200 backdrop-blur-md">
                <a href="{{ route('user.profile') }}"
                    class="block px-4 py-2 text-sm hover:bg-[#00C2FF]/10 hover:text-[#00C2FF] transition">Profile</a>

                <a href="#"
                    class="block px-4 py-2 text-sm hover:bg-[#00C2FF]/10 hover:text-[#00C2FF] transition">Settings</a>

                <form action="{{ route('user.logout') }}" method="POST" class="border-t border-[#26304D]">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-sm hover:bg-[#00C2FF]/10 hover:text-[#00C2FF] transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
