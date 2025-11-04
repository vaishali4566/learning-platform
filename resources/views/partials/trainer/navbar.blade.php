<nav
    class="bg-gradient-to-r from-[#0E1625] via-[#1C2541] to-[#0E1625] text-[#E6EDF7] px-6 py-3 flex justify-between items-center shadow-lg fixed top-0 left-0 right-0 z-50 border-b border-[#26304D] backdrop-blur-md bg-opacity-90">

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

        <!-- 🔔 Notifications Dropdown -->
        <div class="relative">
            <!-- Trigger -->
            <button id="notifBtn"
                class="relative group hover:text-[#00C2FF] transition peer">
                <i data-lucide="bell" class="w-5 h-5"></i>
                <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border border-[#0E1625] shadow-md"></span>
            </button>

            <!-- Dropdown -->
            <div
                class="absolute right-0 mt-3 w-80 bg-[#1C2541]/95 border border-[#26304D] text-[#E6EDF7] rounded-lg shadow-lg 
                opacity-0 scale-95 invisible peer-hover:opacity-100 hover:opacity-100 peer-hover:scale-100 hover:scale-100 
                peer-hover:visible hover:visible transition-all duration-200 backdrop-blur-md z-50">

                <!-- Header -->
                <div class="flex items-center justify-between px-4 py-2 border-b border-[#26304D]">
                    <span class="font-semibold text-sm text-[#E6EDF7]">Notifications</span>
                    <a href="#" class="text-xs text-[#00C2FF] hover:underline">Mark all read</a>
                </div>

                <!-- Notification List -->
                <div class="max-h-60 overflow-y-auto custom-scrollbar">
                    <!-- Example 1 -->
                    <div class="px-4 py-3 flex items-start gap-3 hover:bg-[#00C2FF]/10 transition cursor-pointer">
                        <div class="p-2 rounded-full bg-[#00C2FF]/10">
                            <i data-lucide="user-plus" class="w-4 h-4 text-[#00C2FF]"></i>
                        </div>
                        <div>
                            <p class="text-sm">New student <span class="text-[#00C2FF] font-medium">Amit</span> joined your course.</p>
                            <p class="text-xs text-gray-400 mt-1">2 mins ago</p>
                        </div>
                    </div>

                    <!-- Example 2 -->
                    <div class="px-4 py-3 flex items-start gap-3 hover:bg-[#00C2FF]/10 transition cursor-pointer">
                        <div class="p-2 rounded-full bg-[#00C2FF]/10">
                            <i data-lucide="book-open" class="w-4 h-4 text-[#00C2FF]"></i>
                        </div>
                        <div>
                            <p class="text-sm">Your course <span class="text-[#00C2FF]">React Basics</span> was purchased.</p>
                            <p class="text-xs text-gray-400 mt-1">15 mins ago</p>
                        </div>
                    </div>

                    <!-- Example 3 -->
                    <div class="px-4 py-3 flex items-start gap-3 hover:bg-[#00C2FF]/10 transition cursor-pointer">
                        <div class="p-2 rounded-full bg-[#00C2FF]/10">
                            <i data-lucide="star" class="w-4 h-4 text-[#00C2FF]"></i>
                        </div>
                        <div>
                            <p class="text-sm">You received a new rating on your course.</p>
                            <p class="text-xs text-gray-400 mt-1">1 hour ago</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center py-2 border-t border-[#26304D]">
                    <a href="#" class="text-xs text-[#00C2FF] hover:underline">View all notifications</a>
                </div>
            </div>
        </div>

        <!-- 👤 Profile Dropdown -->
        <div class="relative">
            <button id="profileBtn"
                class="flex items-center gap-2 hover:text-[#00C2FF] transition peer">
                <img src="{{ Auth::guard('trainer')->user()->profile_photo_url ?? asset('images/default-avatar.png') }}"
                    alt="Trainer Avatar"
                    class="w-9 h-9 rounded-full border-2 border-transparent object-cover transition duration-300 hover:border-[#00C2FF] hover:shadow-[0_0_10px_#00C2FF50]" />
                <span class="hidden md:inline text-[#E6EDF7]">
                    {{ Auth::guard('trainer')->user()->name ?? 'Trainer' }}
                </span>
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </button>

            <div
                class="absolute right-0 mt-2 w-44 bg-[#1C2541]/95 border border-[#26304D] text-[#E6EDF7] rounded-md shadow-lg 
                opacity-0 scale-95 invisible peer-hover:opacity-100 hover:opacity-100 peer-hover:scale-100 hover:scale-100 peer-hover:visible hover:visible
                transition-all duration-200 backdrop-blur-md z-50">
                
                <a href="{{ route('trainer.profile') }}"
                    class="block px-4 py-2 text-sm hover:bg-[#00C2FF]/10 hover:text-[#00C2FF] transition">Profile</a>
                <a href="#"
                    class="block px-4 py-2 text-sm hover:bg-[#00C2FF]/10 hover:text-[#00C2FF] transition">Settings</a>
                <form action="{{ route('trainer.logout') }}" method="POST" class="border-t border-[#26304D]">
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
