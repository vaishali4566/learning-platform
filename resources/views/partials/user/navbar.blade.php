<nav class="dark:bg-gradient-to-r dark:from-[#0E1625] dark:via-[#1C2541] dark:to-[#0E1625] bg-white text-gray-900 border-b border-gray-200 dark:text-[#E6EDF7] px-3 lg:px-4 2xl:px-8 py-3.5 flex justify-between gap-6 items-center shadow-md dark:border-[#26304D] backdrop-blur-md">

    <!-- Left Section -->
    <div class="flex items-center bg-white dark:bg-[#0E1625]/70 border border-gray-300 dark:border-gray-700
        rounded-lg px-3 py-2 w-64 shadow-sm backdrop-blur-md transition-transform duration-200 focus-within:ring-1 ring-[#00C2FF] dark:ring-[#00C2FF]/60">

        <!-- Search Icon -->
        <i data-lucide="search" class="w-4 h-4 text-gray-500 dark:text-gray-400"></i>

        <!-- Input -->
        <input
            type="text"
            placeholder="Search"
            class="ml-2 w-full bg-transparent focus:outline-none text-xs md:text-sm text-gray-700 dark:text-[#E6EDF7] placeholder-gray-500 dark:placeholder-gray-400"
        />
    </div>

    <!-- Right: Chat + Notifications + Profile -->
    <div class="flex items-center gap-2 md:gap-3">
       <button id="themeToggle" class="w-8 h-8 md:w-9 md:h-9 p-2 flex items-center justify-center rounded-full hover:bg-[#00C2FF]/10 border border-gray-400 dark:border-[#00C2FF]/30 transition-all duration-300 group">
         <!-- Sun Icon (Light Mode) --> 
         <i data-lucide="sun" class="w-4 h-4 md:w-5 md:h-5 text-white hidden" id="sunIcon"></i> 
         <!-- Moon Icon (Dark Mode) --> 
         <i data-lucide="moon" class="w-4 h-4 md:w-5 md:h-5 text-gray-600 dark:text-[#E6EDF7]" id="moonIcon"></i> 
       </button>

        <!-- 💬 Chat Icon -->
        <a href="{{ route('chat.index') }}"
            class="relative text-gray-600 sm:mr-1 dark:text-[#E6EDF7] hover:bg-[#00C2FF]/10 rounded-full transition flex items-center justify-center">
            <div class="w-8 h-8 md:w-9 md:h-9 flex items-center justify-center border border-gray-400 dark:border-[#00C2FF]/30 rounded-full p-2">
               <div class="relative">
                    <i data-lucide="message-circle" class="w-3 h-3 md:w-4 md:h-4"></i>
                    <span
                        class="absolute top-0.5 right-[2px] translate-x-1 -translate-y-1 w-1.5 h-1.5 md:w-2 md:h-2 bg-[#00C2FF] rounded-full shadow-md">
                    </span>
               </div>
            </div>
        </a>
        
        <!-- 👤 Profile Dropdown -->
        <div class="relative group">
            @if(Auth::check())
                <button class="flex items-center gap-2 group-hover:text-[#00C2FF] pl-0 md:pl-3 sm:border-l-2 border-l-gray-200 dark:border-l-gray-700 transition peer">
                    <div class="hidden sm:flex flex-col leading-tight text-right">
                        <span class="hidden md:inline font-semibold capitalize text-gray-900 dark:text-[#E6EDF7]">{{ Auth::user()->name }}</span>
                        <span class="hidden md:flex items-center gap-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            Student
                        </span>
                    </div>
                   <div class="relative">
                     <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                        class="min-w-8 min-h-8 w-8 h-8 md:w-10 md:h-10 rounded-full object-cover transition duration-300" />

                        <span class="absolute bottom-1 right-0.5 w-2.5 h-2.5 bg-green-500 rounded-full"></span>
                   </div>
                </button>

                 <div
                class="absolute -right-2 mt-3 w-64 bg-white dark:bg-[#1C2541]/95 
                        border border-gray-200 dark:border-[#26304D]
                        rounded-xl shadow-[0_4px_25px_rgba(0,0,0,0.08)]
                        opacity-0 scale-95 invisible
                        peer-hover:opacity-100 peer-hover:scale-100 peer-hover:visible
                        hover:opacity-100 hover:scale-100 hover:visible
                        transform transition-all duration-300 z-50 p-4">

                    <!-- Arrow -->
                    {{-- <div class="absolute -top-2 right-6 w-0 h-0 
                                border-l-[8px] border-l-transparent
                                border-r-[8px] border-r-transparent
                                border-b-[8px] border-b-white
                                dark:border-b-[#1C2541]">
                    </div> --}}

                    <!-- User Info -->
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-[#26304D]">
                        <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                            class="w-10 h-10 rounded-full object-cover" />

                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white leading-tight">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                    </div>

                    <!-- Menu -->
                    <div class="mt-3 space-y-1 text-sm">

                        <a href="{{ route('user.profile') }}"
                        class="flex items-center gap-3 px-2 py-2 rounded-lg 
                                hover:bg-gray-100 dark:hover:bg-white/10 
                                transition">
                            <i class="fa-regular fa-user text-gray-500 dark:text-[#E6EDF7]"></i>
                            View Profile
                        </a>

                        <a href="#"
                        class="flex items-center gap-3 px-2 py-2 rounded-lg 
                                hover:bg-gray-100 dark:hover:bg-white/10 
                                transition">
                            <i class="fa-regular fa-square-check text-gray-500 dark:text-[#E6EDF7]"></i>
                            My Task
                        </a>

                        <a href="#"
                        class="flex items-center gap-3 px-2 py-2 rounded-lg 
                                hover:bg-gray-100 dark:hover:bg-white/10 
                                transition">
                            <i class="fa-regular fa-circle-question text-gray-500 dark:text-[#E6EDF7]"></i>
                            Help Center
                        </a>

                        <a href="#"
                        class="flex items-center gap-3 px-2 py-2 rounded-lg 
                                hover:bg-gray-100 dark:hover:bg-white/10 
                                transition">
                            <i class="fa-solid fa-gear text-gray-500 dark:text-[#E6EDF7]"></i>
                            Account Settings
                        </a>

                        <a href="#"
                        class="flex items-center gap-3 px-2 py-2 rounded-lg 
                                hover:bg-gray-100 dark:hover:bg-white/10 
                                transition">
                            <i class="fa-solid fa-layer-group text-gray-500 dark:text-[#E6EDF7]"></i>
                            Upgrade Plan
                        </a>

                        <form action="{{ route('user.logout') }}" method="POST" class="pt-2 border-t border-gray-200 dark:border-[#26304D]">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-3 px-2 py-2 text-red-500 rounded-lg 
                                    hover:bg-red-50 dark:hover:bg-red-500/10
                                    transition w-full text-left">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                Log Out
                            </button>
                        </form>
                    </div>
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
