<aside id="sidebar" class="bg-white shadow-md sidebar-expanded transition-all fixed top-16 left-0 bottom-0 flex flex-col z-30">
    <div class="flex items-center justify-center py-5 border-b">
        <div class="flex items-center gap-2">
            <i data-lucide="book-open" class="w-6 h-6 text-blue-600"></i>
            <span id="sidebar-title" class="text-lg font-semibold text-gray-700 whitespace-nowrap">
                E-Learning Admin
            </span>
        </div>
    </div>

    <nav class="flex-1 mt-4 relative">
        <a href="{{ route('trainer.dashboard') }}"
            class="menu-item flex items-center gap-3 px-6 py-2 font-medium cursor-pointer 
                  {{ request()->routeIs('trainer.dashboard1') ? 'bg-blue-100 text-blue-700 border-r-4 border-blue-500' : 'hover:bg-blue-50 text-gray-700' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5 text-blue-600"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>
        <a href="{{ route('trainer.courses.index') }}" class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
            <i data-lucide="library" class="w-5 h-5 text-green-600"></i>
            <span class="sidebar-text">All Courses</span>
            <span class="tooltip">All Courses</span>
        </a>
        <a href="{{ route('trainer.courses.my') }}" class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
            <i data-lucide="library" class="w-5 h-5 text-green-600"></i>
            <span class="sidebar-text">My Courses</span>
            <span class="tooltip">My Courses</span>
        </a>
        <div class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
            <i data-lucide="users" class="w-5 h-5 text-yellow-600"></i>
            <span class="sidebar-text">Students</span>
            <span class="tooltip">Students</span>
        </div>
        <a href="{{ route('trainer.courses.create') }}" class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
            <i data-lucide="user-check" class="w-5 h-5 text-red-600"></i>
            <span class="sidebar-text">Create Course</span>
            <span class="tooltip">Create Course</span>
        </a>
        <div class="menu-item relative flex items-center gap-3 px-6 py-2 hover:bg-blue-50 text-gray-700 font-medium cursor-pointer">
            <i data-lucide="bar-chart-3" class="w-5 h-5 text-purple-600"></i>
            <span class="sidebar-text">Reports</span>
            <span class="tooltip">Reports</span>
        </div>
    </nav>
</aside>