<aside id="sidebar"
    class="bg-gradient-to-b from-[#0E1625] via-[#1C2541] to-[#0E1625] shadow-2xl sidebar-expanded fixed top-16 left-0 bottom-0 flex flex-col z-30 border-r border-[#26304D] backdrop-blur-md bg-opacity-95 transition-all duration-500 ease-in-out">

    <!-- Header -->
    <div
        class="flex items-center justify-center py-5 border-b border-[#26304D] bg-[#0E1625]/80 backdrop-blur-sm shadow-inner">
        <div class="flex items-center gap-2 animate-fade-in">
            <i data-lucide="shield" class="w-6 h-6 text-[#00C2FF] animate-pulse-slow"></i>
            <span id="sidebar-title" class="text-lg font-semibold text-[#E6EDF7] whitespace-nowrap tracking-wide">
                Admin Panel
            </span>
        </div>
    </div>

    <!-- Menu -->
    <nav
        class="flex-1 mt-4 relative text-[#8A93A8] overflow-y-auto px-2 scrollbar-thin scrollbar-thumb-[#101727]/50 scrollbar-track-transparent transition-all duration-500 ease-in-out">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 font-medium rounded-lg mb-1 transition-all duration-300 ease-in-out 
            hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('admin.dashboard') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300"></div>
            <i data-lucide="layout-dashboard"
                class="w-5 h-5 text-[#00C2FF] transition-all duration-300 group-hover:scale-110"></i>
            <span class="sidebar-text">Dashboard Overview</span>
        </a>

        <!-- User Management -->
        <a href="{{ route('admin.users') }}"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg font-medium transition-all duration-300 ease-in-out 
            hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('admin.users') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#4ADE80] rounded transition-all duration-300"></div>
            <i data-lucide="users"
                class="w-5 h-5 text-[#4ADE80] group-hover:text-[#00C2FF] transition-all duration-300"></i>
            <span class="sidebar-text">User Management</span>
        </a>

        <!-- Course Management -->
        <a href="{{ route('admin.courses') }}"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg font-medium transition-all duration-300 ease-in-out 
            hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('admin.courses') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#38BDF8] rounded transition-all duration-300"></div>
            <i data-lucide="book-open"
                class="w-5 h-5 text-[#38BDF8] group-hover:text-[#00C2FF] transition-all duration-300"></i>
            <span class="sidebar-text">Course Management</span>
        </a>

        <!-- Trainer Management -->
        <a href="{{ route('admin.trainers') }}"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg font-medium transition-all duration-300 ease-in-out 
            hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('admin.trainers') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#FACC15] rounded transition-all duration-300"></div>
            <i data-lucide="user-check"
                class="w-5 h-5 text-[#FACC15] group-hover:text-[#00C2FF] transition-all duration-300"></i>
            <span class="sidebar-text">Trainer Management</span>
        </a>

        <!-- Quiz Management -->
        <a href="#"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg font-medium transition-all duration-300 ease-in-out 
            hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#FB923C] rounded transition-all duration-300"></div>
            <i data-lucide="clipboard-list"
                class="w-5 h-5 text-[#FB923C] group-hover:text-[#00C2FF] transition-all duration-300"></i>
            <span class="sidebar-text">Quiz Management</span>
        </a>

        <!-- Payment Reports -->
        <a href="#"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg font-medium transition-all duration-300 ease-in-out 
            hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#C084FC] rounded transition-all duration-300"></div>
            <i data-lucide="credit-card"
                class="w-5 h-5 text-[#C084FC] group-hover:text-[#00C2FF] transition-all duration-300"></i>
            <span class="sidebar-text">Payment Reports</span>
        </a>

        <!-- Analytics -->
        <a href="#"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg font-medium transition-all duration-300 ease-in-out 
            hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#60A5FA] rounded transition-all duration-300"></div>
            <i data-lucide="bar-chart-3"
                class="w-5 h-5 text-[#60A5FA] group-hover:text-[#00C2FF] transition-all duration-300"></i>
            <span class="sidebar-text">Analytics</span>
        </a>

        <!-- System Settings -->
        <a href="#"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg font-medium transition-all duration-300 ease-in-out 
            hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#94A3B8] rounded transition-all duration-300"></div>
            <i data-lucide="settings"
                class="w-5 h-5 text-[#94A3B8] group-hover:text-[#00C2FF] transition-all duration-300"></i>
            <span class="sidebar-text">System Settings</span>
        </a>

        <!-- Logout -->
        <form action="{{ route('admin.logout') }}" method="POST"
            class="menu-item group relative flex items-center gap-3 px-5 py-3 mb-2 mt-4 rounded-lg font-medium transition-all duration-300 ease-in-out 
            hover:translate-x-1 hover:bg-red-900/40 hover:text-red-400">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full text-left">
                <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-red-500 rounded transition-all duration-300"></div>
                <i data-lucide="log-out"
                    class="w-5 h-5 text-red-500 group-hover:scale-110 transition-all duration-300"></i>
                <span class="sidebar-text">Logout</span>
            </button>
        </form>
    </nav>
</aside>
