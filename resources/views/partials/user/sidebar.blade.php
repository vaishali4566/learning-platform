<aside id="sidebar"
    class="bg-gradient-to-b from-[#0E1625] via-[#1C2541] to-[#0E1625] shadow-2xl sidebar-expanded fixed top-16 left-0 bottom-0 flex flex-col z-30 border-r border-[#26304D] backdrop-blur-md bg-opacity-95 transition-all duration-500 ease-in-out">

    <!-- Header -->
    <div
        class="flex items-center justify-center py-5 border-b border-[#26304D] bg-[#0E1625]/80 backdrop-blur-sm shadow-inner">
        <div class="flex items-center gap-2 animate-fade-in">
            <i data-lucide="book-open" class="w-6 h-6 text-[#00C2FF] animate-pulse-slow"></i>
            <span id="sidebar-title" class="text-lg font-semibold text-[#E6EDF7] whitespace-nowrap tracking-wide">
                Learner Panel
            </span>
        </div>
    </div>

    <!-- Menu -->
    <nav
        class="flex-1 mt-4 relative text-[#8A93A8] overflow-y-auto px-2 scrollbar-thin scrollbar-thumb-[#101727]/50 scrollbar-track-transparent transition-all duration-500 ease-in-out">

        <!-- Dashboard -->
        <a href="{{ route('user.dashboard') }}"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 font-medium rounded-lg mb-1 transition-all duration-300 ease-in-out hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('user.dashboard') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300"></div>
            <i data-lucide="layout-dashboard"
                class="w-5 h-5 text-[#00C2FF] transition-all duration-300 group-hover:scale-110"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <!-- All Courses -->
        <a href="{{ route('user.courses.index') }}"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg font-medium transition-all duration-300 ease-in-out hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('user.courses.index') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300"></div>
            <i data-lucide="book-open"
                class="w-5 h-5 text-[#3A6EA5] group-hover:text-[#00C2FF] transition-all duration-300"></i>
            <span class="sidebar-text">All Courses</span>
        </a>

        <!-- My Courses -->
        <a href="{{ route('user.courses.my') }}"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg font-medium transition-all duration-300 ease-in-out hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('user.courses.my') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300"></div>
            <i data-lucide="book-open"
                class="w-5 h-5 text-[#3A6EA5] group-hover:text-[#00C2FF] transition-all duration-300"></i>
            <span class="sidebar-text">My Courses</span>
        </a>

        <!-- Quizzes -->
        <a href="{{ route('user.quizzes.index') }}"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg font-medium transition-all duration-300 ease-in-out hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('user.quizzes.index') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300"></div>
            <i data-lucide="brain"
                class="w-5 h-5 text-[#00C2FF] group-hover:scale-110 transition-all duration-300"></i>
            <span class="sidebar-text">Quizzes</span>
        </a>

    

        


        
    </nav>
</aside>