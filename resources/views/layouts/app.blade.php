<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('Globbusoft_learning channel logo.jpg') }}">

    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-[#0B1220] text-[#E6EDF7]">

    {{-- Loader --}}
    @include('components.loader')

    <!-- Modern Navbar -->
    <nav class="bg-gradient-to-r from-[#0E1625] via-[#1C2541] to-[#0E1625] text-[#E6EDF7] px-6 py-3 flex justify-between items-center shadow-lg fixed top-0 left-0 right-0 z-50 border-b border-[#26304D] backdrop-blur-md bg-opacity-90">
        <!-- Left Section -->
        <div class="flex items-center gap-3">
            <button id="sidebarToggle"
                class="text-[#E6EDF7] hover:text-[#00C2FF] focus:outline-none transition-transform duration-200 hover:scale-110">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <div class="flex items-center gap-2">
                <div class="p-2 rounded-lg bg-[#00C2FF]/10 border border-[#00C2FF]/30 backdrop-blur-sm">
                    <i data-lucide="graduation-cap" class="w-5 h-5 text-[#00C2FF]"></i>
                </div>
                <span class="font-semibold text-lg text-[#E6EDF7] tracking-wide">E-Learning</span>
            </div>
        </div>

        <!-- Right Section -->
        <div class="flex items-center gap-6">
            @if(Auth::guard('web')->check())
                <a href="{{ route('user.dashboard') }}" class="relative group transition">
                    <span class="group-hover:text-[#00C2FF]">Dashboard</span>
                    <span class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
                </a>
                <a href="{{ route('user.profile') }}" class="relative group transition">
                    <span class="group-hover:text-[#00C2FF]">Profile</span>
                    <span class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
                </a>
                <form method="POST" action="{{ route('user.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="relative group transition">
                        <span class="group-hover:text-[#00C2FF]">Logout</span>
                        <span class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
                    </button>
                </form>

            @elseif(Auth::guard('trainer')->check())
                <a href="{{ route('trainer.dashboard') }}" class="relative group transition">
                    <span class="group-hover:text-[#00C2FF]">Dashboard</span>
                    <span class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
                </a>
                <a href="{{ route('trainer.profile') }}" class="relative group transition">
                    <span class="group-hover:text-[#00C2FF]">Profile</span>
                    <span class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
                </a>
                <form method="POST" action="{{ route('trainer.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="relative group transition">
                        <span class="group-hover:text-[#00C2FF]">Logout</span>
                        <span class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
                    </button>
                </form>
            @else
                <a href="{{ route('user.login') }}" class="relative group transition">
                    <span class="group-hover:text-[#00C2FF]">User Login</span>
                    <span class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
                </a>
                <a href="{{ route('trainer.login') }}" class="relative group transition">
                    <span class="group-hover:text-[#00C2FF]">Trainer Login</span>
                    <span class="absolute -bottom-1 left-0 w-0 group-hover:w-full h-[2px] bg-[#00C2FF] rounded transition-all duration-300"></span>
                </a>
            @endif
        </div>
    </nav>

    <main class="pt-16">
        @yield('content')
    </main>

    @include('partials.chatbot')

    <!-- <script>
        lucide.createIcons();

        window.addEventListener('load', () => {
            const loader = document.getElementById('page-loader');
            loader.classList.add('opacity-0');
            loader.style.transition = 'opacity 0.8s ease';
            setTimeout(() => loader.remove(), 800);
        });
    </script> -->
<script>
  // Keep loader for 3 seconds then hide it
  window.addEventListener('load', () => {
      const loader = document.getElementById('page-loader');
      setTimeout(() => {
          loader.classList.add('opacity-0');
          loader.style.transition = 'opacity 1s ease';
          setTimeout(() => loader.remove(), 1000);
      }, 3000); // 3000ms = 3 seconds
  });
</script>


</body>
</html>
