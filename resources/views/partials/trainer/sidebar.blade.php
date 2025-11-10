<aside id="sidebar"
    class="bg-gradient-to-b from-[#0E1625] via-[#1C2541] to-[#0E1625] shadow-2xl sidebar-expanded fixed top-16 left-0 bottom-0 flex flex-col z-30 border-r border-[#26304D] backdrop-blur-md bg-opacity-95 transition-all duration-500 ease-in-out">

    <!-- Header -->
    <div
        class="flex items-center justify-center py-5 border-b border-[#26304D] bg-[#0E1625]/80 backdrop-blur-sm shadow-inner">
        <div class="flex items-center gap-2 animate-fade-in">
            <i data-lucide="book-open" class="w-6 h-6 text-[#00C2FF] animate-pulse-slow"></i>
            <span id="sidebar-title" class="text-lg font-semibold text-[#E6EDF7] whitespace-nowrap tracking-wide">
                Trainer Panel
            </span>
        </div>
    </div>

    <!-- Menu -->
    <nav class="flex-1 mt-4 relative text-[#8A93A8] overflow-y-auto px-2 scrollbar-thin scrollbar-thumb-[#101727]/50 scrollbar-track-transparent">

        <!-- Dashboard -->
        <a href="{{ route('trainer.dashboard') }}"
            class="group relative flex items-center gap-3 px-5 py-3 font-medium rounded-lg mb-1 transition-all duration-300 ease-in-out hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('trainer.dashboard') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300 ease-in-out"></div>
            <i data-lucide="layout-dashboard" class="w-5 h-5 text-[#00C2FF] transition-all duration-300 group-hover:scale-110"></i>
            <span class="sidebar-text transition-all duration-300">Dashboard</span>
        </a>

        <!-- All Courses -->
        <a href="{{ route('trainer.courses.index') }}"
            class="group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg cursor-pointer transition-all duration-300 ease-in-out hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('trainer.courses.index') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300 ease-in-out"></div>
            <i data-lucide="library" class="w-5 h-5 text-[#3A6EA5] group-hover:text-[#00C2FF] transition-all duration-300"></i>
            <span class="sidebar-text">All Courses</span>
        </a>

        <!-- My Courses -->
        <a href="{{ route('trainer.courses.my') }}"
            class="group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg cursor-pointer transition-all duration-300 ease-in-out hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('trainer.courses.my') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300 ease-in-out"></div>
            <i data-lucide="book" class="w-5 h-5 text-[#00C2FF] group-hover:scale-110 transition-all duration-300"></i>
            <span class="sidebar-text">My Courses</span>
        </a>

        <!-- Purchased Courses -->
        <a href="{{ route('trainer.courses.my.purchases') }}"
            class="group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg cursor-pointer transition-all duration-300 ease-in-out hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('trainer.courses.my.purchases') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300 ease-in-out"></div>
            <i data-lucide="shopping-cart" class="w-5 h-5 text-[#00C2FF] group-hover:scale-110 transition-all duration-300"></i>
            <span class="sidebar-text">Purchased Courses</span>
        </a>

        <!-- Create Course -->
        <a href="{{ route('trainer.courses.create') }}"
            class="group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg cursor-pointer transition-all duration-300 ease-in-out hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('trainer.courses.create') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300 ease-in-out"></div>
            <i data-lucide="plus-circle" class="w-5 h-5 text-[#00C2FF] group-hover:scale-110 transition-all duration-300"></i>
            <span class="sidebar-text">Create Course</span>
        </a>

        <!-- Students -->
        <a href="{{ route('trainer.students.index') }}"
            class="group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg cursor-pointer transition-all duration-300 ease-in-out hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('trainer.students.index') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300 ease-in-out"></div>
            <i data-lucide="users" class="w-5 h-5 text-[#3A6EA5] group-hover:text-[#00C2FF] transition-all duration-300"></i>
            <span class="sidebar-text">Students</span>
        </a>

        <!-- Reports -->
        <a href="{{ route('trainer.report') }}"
            class="group relative flex items-center gap-3 px-5 py-3 mb-1 rounded-lg cursor-pointer transition-all duration-300 ease-in-out hover:translate-x-1 hover:bg-[#101727]/70 hover:text-[#E6EDF7]
            {{ request()->routeIs('trainer.report') ? 'bg-[#101727] text-[#00C2FF] border-r-4 border-[#00C2FF]' : '' }}">
            <div class="absolute left-0 h-0 group-hover:h-full w-[3px] bg-[#00C2FF] rounded transition-all duration-300 ease-in-out"></div>
            <i data-lucide="bar-chart-3" class="w-5 h-5 text-[#3A6EA5] group-hover:text-[#00C2FF] transition-all duration-300"></i>
            <span class="sidebar-text">Reports</span>
        </a>

    </nav>
</aside>
