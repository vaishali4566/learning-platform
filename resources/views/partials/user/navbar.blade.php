<nav class="dark:bg-gradient-to-r dark:from-[#0E1625] dark:via-[#1C2541] dark:to-[#0E1625] bg-white text-gray-900 border-b border-gray-200 dark:text-[#E6EDF7] px-3 md:px-6 py-3.5 flex justify-between gap-6 items-center shadow-md dark:border-[#26304D] backdrop-blur-md">

    <!-- Left Section -->
    <div class="flex items-center bg-white dark:bg-[#0E1625]/70 border border-gray-300 dark:border-[#26304D]
        rounded-lg px-3 py-2 w-64 shadow-sm backdrop-blur-md transition-transform duration-200 focus-within:ring-1 ring-[#00C2FF]/50">

        <!-- Search Icon -->
        <i data-lucide="search" class="w-4 h-4 text-gray-500 dark:text-gray-400"></i>

        <!-- Input -->
        <input
            type="text"
            placeholder="Search"
            class="ml-2 w-full bg-transparent focus:outline-none text-sm text-gray-700 dark:text-[#E6EDF7] placeholder-gray-500 dark:placeholder-gray-400"
        />
    </div>

    <!-- Right: Chat + Notifications + Profile -->
    <div class="flex items-center gap-2 md:gap-5">
       <button id="themeToggle" class="w-8 h-8 md:w-10 md:h-10 p-2 flex items-center justify-center rounded-full hover:bg-[#00C2FF]/10  border border-gray-400 dark:border-[#00C2FF]/20 transition-all duration-300 group">
         <!-- Sun Icon (Light Mode) --> 
         <i data-lucide="sun" class="w-4 h-4 md:w-5 md:h-5 text-white hidden" id="sunIcon"></i> 
         <!-- Moon Icon (Dark Mode) --> 
         <i data-lucide="moon" class="w-4 h-4 md:w-5 md:h-5 text-gray-900 dark:text-[#E6EDF7]" id="moonIcon"></i> 
        </button>

        <!-- 💬 Chat Icon -->
        <a href="{{ route('chat.index') }}"
            class="relative text-gray-800 dark:text-[#E6EDF7] hover:text-[#00C2FF] transition flex items-center justify-center">
            <div class="relative flex items-center justify-center">
                <i data-lucide="message-circle" class="w-4 h-4 md:w-5 md:h-5"></i>
                <span
                    class="absolute top-0 right-0 translate-x-1 -translate-y-1 w-2.5 h-2.5 bg-green-500 rounded-full border border-[#0E1625] shadow-md">
                </span>
            </div>
        </a>
        
        <!-- 👤 Profile Dropdown -->
        <div class="relative group">
            @if(Auth::check())
                <button class="flex items-center gap-2 hover:text-[#00C2FF] transition">
                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                        class="w-8 h-8 md:w-9 md:h-9 rounded-full border-2 border-transparent object-cover transition duration-300 group-hover:border-[#00C2FF] group-hover:shadow-[0_0_10px_#00C2FF50]" />
                    <span class="hidden md:inline text-gray-900 dark:text-[#E6EDF7]">{{ Auth::user()->name }}</span>
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </button>

                <div class="absolute right-0 mt-2 w-44 bg-white border-gray-200 dark:bg-[#1C2541]/95 border dark:border-[#26304D] text-gray-900 dark:text-[#E6EDF7] rounded-md shadow-lg opacity-0 group-hover:opacity-100 group-hover:translate-y-1 transform transition-all duration-300 backdrop-blur-md z-50">
                    <a href="{{ route('user.profile') }}"
                        class="block px-4 py-2 text-sm hover:bg-[#0077B6]/10 hover:text-[#0077B6] dark:hover:bg-[#00C2FF]/10 dark:hover:text-[#00C2FF] transition">Profile</a>
                    
                    <form action="{{ route('user.logout') }}" method="POST" class="border-t border-gray-200 dark:border-[#26304D]">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm hover:bg-[#0077B6]/10 hover:text-[#0077B6] dark:hover:bg-[#00C2FF]/10 dark:hover:text-[#00C2FF] transition">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('user.login') }}"
                    class="px-4 py-2 bg-gradient-to-r from-[#0077B6] to-[#00A8E8] hover:shadow-[0_0_10px_#0099E550] dark:bg-gradient-to-r dark:from-[#00C2FF] dark:to-[#2F82DB]
                        dark:hover:shadow-[0_0_10px_#00C2FF70] text-white text-sm font-medium rounded-full transition">
                    Login
                </a>
            @endif
        </div>
    </div>
</nav>
