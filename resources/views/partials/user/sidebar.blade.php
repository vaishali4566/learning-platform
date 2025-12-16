<aside id="sidebar"
    class="bg-white dark:bg-gradient-to-b dark:from-[#0E1625] dark:via-[#1C2541] dark:to-[#0E1625] shadow-xl sidebar-expanded lg:sidebar-expanded fixed lg:static top-0 left-0 bottom-0 flex flex-col z-50 border-r border-gray-200 dark:border-[#26304D] backdrop-blur-md transition-transform duration-300 ease-in-out">

    <!-- Header -->
    {{-- <div
        class="flex items-center p-5 px-7 border-b border-gray-200 dark:border-[#26304D] dark:bg-[#0E1625]/80 backdrop-blur-sm shadow-inner">
        <div class="flex items-center gap-2 animate-fade-in">
            <i data-lucide="book-open" class="mt-0.5 w-6 h-6 text-[#00C2FF]"></i>
            <span id="sidebar-title" class="text-lg font-semibold text-gray-700 dark:text-[#E6EDF7] whitespace-nowrap tracking-wide">
                Learner's Panel
            </span>
        </div>
    </div> --}}

    <!-- Brand + Sidebar Toggle -->
    <div class="flex items-center justify-between [.sidebar-collapsed_&]:justify-center px-4 h-[4.25rem] gap-3 border border-b border-gray-200 dark:border-[#26304D] dark:bg-[#0E1625]/80 backdrop-blur-sm shadow-inner">
        <div class="[.sidebar-collapsed_&]:hidden flex items-center gap-2">
            <div class="p-2 rounded-lg bg-[#0077B6]/10 border-[#0077B6]/30 dark:bg-[#00C2FF]/10 border dark:border-[#00C2FF]/30 backdrop-blur-sm sidebar-text text-sm 2xl:text-base">
                <i data-lucide="graduation-cap" class="w-5 h-5 text-[#00C2FF]"></i>
            </div>
            <span id="sidebar-title" class="font-semibold text-lg tracking-wide text-[#00c2ff] dark:text-[#E6EDF7]">E-Learning</span>
        </div>

        <button id="sidebarToggle"
        class="text-gray-500 dark:text-[#E6EDF7] hover:text-[#00C2FF] focus:outline-none transition-transform duration-300 hover:scale-110">
            <i data-lucide="chevron-left" class="w-6 h-6" id="chevronIcon"></i>
            <i data-lucide="menu" class="hidden w-6 h-6" id="menuIcon"></i>
        </button> 
    </div>

    <!-- Menu -->
    <nav
        class="flex-1 mt-6 relative text-gray-500 dark:text-gray-400 overflow-y-auto px-2 scrollbar-thin scrollbar-thumb-[#101727]/50 scrollbar-track-transparent transition-transform duration-300 ease-in-out">

        <!-- Dashboard -->
        <a href="{{ route('user.dashboard') }}"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 font-medium rounded-lg mb-1 transition-all duration-300 ease-in-out hover:translate-x-1
            {{ request()->routeIs('user.dashboard') ? 'bg-[#00c2ff] text-white dark:bg-[#0f6289] dark:text-white' : 'hover:bg-gray-50 hover:text-gray-800 dark:hover:bg-[#101727]/70 dark:hover:text-[#E6EDF7]' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300"></div>
            <i data-lucide="layout-dashboard"
                class="w-5 h-5 group-hover:dark:text-[#e6edf7] transition-all duration-300 group-hover:scale-110"></i>
            <span class="sidebar-text text-sm 2xl:text-base">Dashboard</span>
        </a>

        <!-- All Courses -->
        <a href="{{ route('user.courses.index') }}"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg font-medium transition-all duration-300 ease-in-out hover:translate-x-1
            {{ request()->routeIs('user.courses.index') ? 'bg-[#00c2ff] text-white dark:bg-[#0f6289] dark:text-white' : 'hover:bg-gray-50 hover:text-gray-800 dark:hover:bg-[#101727]/70 dark:hover:text-[#E6EDF7]' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300"></div>
            <i data-lucide="library"
                class="w-5 h-5 group-hover:dark:text-[#e6edf7] transition-all duration-300"></i>
            <span class="sidebar-text text-sm 2xl:text-base">All Courses</span>
        </a>

        <!-- My Courses -->
        <a href="{{ route('user.courses.my') }}"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg font-medium transition-all duration-300 ease-in-out hover:translate-x-1 
            {{ request()->routeIs('user.courses.my') ? 'bg-[#00c2ff] text-white dark:bg-[#0f6289] dark:text-white' : 'hover:bg-gray-50 hover:text-gray-800 dark:hover:bg-[#101727]/70 dark:hover:text-[#E6EDF7]' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300"></div>
            <i data-lucide="book-marked"
                class="w-5 h-5 group-hover:dark:text-[#e6edf7] transition-all duration-300"></i>
            <span class="sidebar-text text-sm 2xl:text-base">My Courses</span>
        </a>

        <!-- Quizzes -->
        <a href="{{ route('user.quizzes.index') }}"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg font-medium transition-all duration-300 ease-in-out hover:translate-x-1 
            {{ request()->routeIs('user.quizzes.index') ? 'bg-[#00c2ff] text-white dark:bg-[#0f6289] dark:text-white' : 'hover:bg-gray-50 hover:text-gray-800 dark:hover:bg-[#101727]/70 dark:hover:text-[#E6EDF7]' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300"></div>
            <i data-lucide="brain"
                class="w-5 h-5  group-hover:scale-110 transition-all duration-300"></i>
            <span class="sidebar-text text-sm 2xl:text-base">Quizzes</span>
        </a>   
        
         <!-- Practice Test -->
        <a href="{{ route('user.practice.history') }}"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg font-medium transition-all duration-300 ease-in-out hover:translate-x-1 
            {{ request()->routeIs('user.practice.history') ? 'bg-[#00c2ff] text-white dark:bg-[#0f6289] dark:text-white' : 'hover:bg-gray-50 hover:text-gray-800 dark:hover:bg-[#101727]/70 dark:hover:text-[#E6EDF7]' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300"></div>
            <i data-lucide="brain"
                class="w-5 h-5  group-hover:scale-110 transition-all duration-300"></i>
            <span class="sidebar-text text-sm 2xl:text-base">Test History</span>
        </a>   
    </nav>
</aside>