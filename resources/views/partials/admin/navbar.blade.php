<nav
    class="bg-gradient-to-r from-[#0E1625] via-[#1C2541] to-[#0E1625] text-[#E6EDF7] px-6 py-3 flex justify-between items-center shadow-lg fixed top-0 left-0 right-0 z-50 border-b border-[#26304D] backdrop-blur-md bg-opacity-90">

    <!-- Left: Brand + Sidebar Toggle -->
    <div class="flex items-center gap-3">
        <!-- Sidebar Toggle -->
        <button id="sidebarToggle"
            class="text-[#E6EDF7] hover:text-[#00C2FF] focus:outline-none transition-transform duration-200 hover:scale-110">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>

        <!-- Logo / Title -->
        <div class="flex items-center gap-2">
            <div class="p-2 rounded-lg bg-[#00C2FF]/10 border border-[#00C2FF]/30 backdrop-blur-sm">
                <i data-lucide="shield" class="w-5 h-5 text-[#00C2FF]"></i>
            </div>
            <span class="font-semibold text-lg tracking-wide text-[#E6EDF7]">Admin Panel</span>
        </div>
    </div>

    <!-- Middle: Quick Links -->
    <div class="hidden md:flex items-center gap-6">
        <a href="#"
            class="relative group flex items-center gap-1 transition">
            <i data-lucide="layout-dashboard" class="w-5 h-5 text-[#8A93A8] group-hover:text-[#00C2FF]"></i>
            <span class="group-hover:text-[#00C2FF]">Dashboard</span>
            <span
                class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
        </a>

        <a href="#"
            class="relative group flex items-center gap-1 transition">
            <i data-lucide="users" class="w-5 h-5 text-[#8A93A8] group-hover:text-[#00C2FF]"></i>
            <span class="group-hover:text-[#00C2FF]">Users</span>
            <span
                class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
        </a>

        <a href="#"
            class="relative group flex items-center gap-1 transition">
            <i data-lucide="book-open-check" class="w-5 h-5 text-[#8A93A8] group-hover:text-[#00C2FF]"></i>
            <span class="group-hover:text-[#00C2FF]">Courses</span>
            <span
                class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
        </a>

        <a href="#"
            class="relative group flex items-center gap-1 transition">
            <i data-lucide="bar-chart-3" class="w-5 h-5 text-[#8A93A8] group-hover:text-[#00C2FF]"></i>
            <span class="group-hover:text-[#00C2FF]">Reports</span>
            <span
                class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
        </a>
    </div>

    <!-- Right: Profile Dropdown -->
    <div class="flex items-center gap-5">
        <!-- Notification -->
        <button class="relative group hover:text-[#00C2FF] transition">
            <i data-lucide="bell" class="w-5 h-5"></i>
            <span
                class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border border-[#0E1625] shadow-md"></span>
        </button>

        <!-- Profile Dropdown -->
        <div class="relative">
            <!-- Dropdown Trigger -->
            <button id="profileBtn"
                class="flex items-center gap-2 hover:text-[#00C2FF] transition peer">
                <img src="{{ Auth::user()->profile_photo_url ?? asset('images/default-avatar.png') }}"
                    alt="Admin Avatar"
                    class="w-9 h-9 rounded-full border-2 border-transparent object-cover transition duration-300 hover:border-[#00C2FF] hover:shadow-[0_0_10px_#00C2FF50]" />
                <span class="hidden md:inline text-[#E6EDF7]">
                   {{ Auth::user()->name ?? 'Admin' }}
                </span>
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </button>

            <!-- Dropdown Menu -->
            <div
                class="absolute right-0 mt-2 w-44 bg-[#1C2541]/95 border border-[#26304D] text-[#E6EDF7] rounded-md shadow-lg 
                opacity-0 scale-95 invisible peer-hover:opacity-100 hover:opacity-100 peer-hover:scale-100 hover:scale-100 peer-hover:visible hover:visible
                transition-all duration-200 backdrop-blur-md z-50">
                
                <a href="#"
                    class="block px-4 py-2 text-sm hover:bg-[#00C2FF]/10 hover:text-[#00C2FF] transition">Profile</a>
                <a href="#"
                    class="block px-4 py-2 text-sm hover:bg-[#00C2FF]/10 hover:text-[#00C2FF] transition">Settings</a>
                <form action="#" method="POST" class="border-t border-[#26304D]">
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
