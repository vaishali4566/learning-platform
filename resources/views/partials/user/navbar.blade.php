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

    <!-- Right: Notifications + Profile -->
    <div class="flex items-center gap-5">
        <!-- Notification Dropdown -->
        <div class="relative group">
            <button class="relative hover:text-[#00C2FF] transition">
                <i data-lucide="bell" class="w-5 h-5"></i>
                <span
                    class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border border-[#0E1625] shadow-md"></span>
            </button>

            <!-- Notification Panel -->
            <div class="absolute right-0 mt-3 w-72 bg-[#1C2541]/95 border border-[#26304D] rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 backdrop-blur-md z-50">
                <div class="px-4 py-3 border-b border-[#26304D] font-semibold text-sm text-[#E6EDF7]">
                    Notifications
                </div>

                <ul class="max-h-60 overflow-y-auto text-sm">
                    <!-- Example Notification -->
                    <li class="px-4 py-2 hover:bg-[#00C2FF]/10 cursor-pointer transition">
                        🎓 New course “React Mastery” available!
                    </li>
                    <li class="px-4 py-2 hover:bg-[#00C2FF]/10 cursor-pointer transition">
                        🧠 Quiz results for “Node.js Basics” uploaded.
                    </li>
                    <li class="px-4 py-2 hover:bg-[#00C2FF]/10 cursor-pointer transition">
                        🏆 You’ve earned a certificate in “MongoDB Advanced”.
                    </li>
                </ul>

                <div class="border-t border-[#26304D]">
                    <a href="#"
                        class="block px-4 py-2 text-center text-sm hover:bg-[#00C2FF]/10 hover:text-[#00C2FF] transition">
                        View all
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="relative group">
            @if(Auth::check())
                <button class="flex items-center gap-2 hover:text-[#00C2FF] transition">
                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                        class="w-9 h-9 rounded-full border-2 border-transparent object-cover transition duration-300 group-hover:border-[#00C2FF] group-hover:shadow-[0_0_10px_#00C2FF50]" />
                    <span class="hidden md:inline text-[#E6EDF7]">{{ Auth::user()->name }}</span>
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </button>

                <div class="absolute right-0 mt-2 w-44 bg-[#1C2541]/95 border border-[#26304D] text-[#E6EDF7] rounded-md shadow-lg opacity-0 group-hover:opacity-100 group-hover:translate-y-1 transform transition-all duration-200 backdrop-blur-md z-50">
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
            @else
                <a href="{{ route('user.login') }}"
                    class="px-4 py-2 bg-gradient-to-r from-[#00C2FF] to-[#2F82DB] text-white text-sm font-medium rounded-full hover:shadow-[0_0_10px_#00C2FF70] transition">
                    Login
                </a>
            @endif
        </div>
    </div>
</nav>
