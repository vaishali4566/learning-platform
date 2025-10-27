<nav class="bg-blue-600 text-white px-6 py-3 flex justify-between items-center shadow-md fixed top-0 left-0 right-0 z-50">
    <div class="flex items-center gap-3">
        <button id="sidebarToggle" class="text-white hover:text-gray-200 focus:outline-none">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        <div class="flex items-center gap-2">
            <i data-lucide="graduation-cap" class="w-6 h-6"></i>
            <span class="font-semibold text-lg">E-Learning</span>
        </div>
    </div>

    <div class="flex items-center gap-6">
        <a href="{{ route('trainer.dashboard') }}" class="hover:text-gray-200">Profile</a>
        <a href="#" class="hover:text-gray-200">Courses</a>
        <a href="#" class="hover:text-gray-200">All Courses</a>
        <img src="{{ asset('images/default-avatar.png') }}"
            alt="Profile"
            class="w-10 h-10 rounded-full border-2 border-white cursor-pointer object-cover">
    </div>
</nav>