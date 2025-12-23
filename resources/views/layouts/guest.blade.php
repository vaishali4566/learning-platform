<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('Globbusoft_learning channel logo.jpg') }}">
    <title>@yield('title', 'Learning Platform')</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <script>
        (function () {
            const storedTheme = localStorage.getItem('theme');

            if (storedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (storedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            } else {
                // First visit → follow system preference
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        /* footer {
            background: #171717;
            color: #d3d3d3;
            padding: 12px 0;
            font-size: 0.9rem;
        } */

        /* Animations */
        @keyframes fadeSlideUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .fade-slide-up { animation: fadeSlideUp 0.8s ease-out forwards; }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes slideFadeInLeft {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        .left-panel-animate {
            animation: slideFadeInLeft 0.9s ease-out forwards;
        }


         /* ROLE TABS */
          .role-tabs {
            background: #0000000a;
            padding: 7px;
            border-radius: 8px;
            display: flex;
            max-width: fit-content;
            margin: 0 auto;
            /* gap: 6px; */
        }
        .role-tab {
            padding: 4px 25px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #5f5f5f;
            transition: all 0.2s ease;
            background: transparent;
        }

        .role-tab:hover {
            /* background: #e5e7eb; */
        }

        .role-tab.active {
           background: #ffffff;
            color: #0f172a;
            font-weight: 500;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        }

        .dark .role-tabs {
            background: #161c33;
        }

        .dark .role-tab {
            color: #9ca3af;
        }

        .dark .role-tab.active {
            background: #273043;
            color: #fff;
            box-shadow: 0 6px 18px rgba(0,0,0,0.6);
        }


        /* FOOTER */
        footer {
            font-size: 0.85rem;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen  bg-gray-50 dark:bg-[#0B1120]">
    {{-- @include('components.loader') --}}
    <main class="flex flex-1">
        <!-- Left Branding -->
         <div class="hidden md:flex md:w-1/2 items-center justify-center bg-[#288be0] left-panel-animate">
            <img 
                src="{{ asset('images/login.png') }}" 
                alt="Login Illustration"
                class="w-full h-full object-cover"
            />
        </div>

        <!-- Right Login Section -->
        <div class="flex flex-1 items-center justify-center px-6 py-10">
            <div class="w-full max-w-md 2xl:max-w-lg fade-slide-up text-center">

                <!-- Toggle -->
                <div class="flex justify-center mb-6 role-tabs">
                    <button id="userBtn" class="role-tab">User</button>
                    <button id="trainerBtn" class="role-tab">Trainer</button>
                </div>

                <!-- Dynamic content -->
                <div id="authContainer">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>

    {{-- <footer class="text-center mt-auto p-5 text-gray-900 dark:text-[#E6EDF7]">
        &copy; {{ date('Y') }} Learning Platform. All rights reserved.
    </footer> --}}

    <script>
        const userBtn = document.getElementById('userBtn');
        const trainerBtn = document.getElementById('trainerBtn');

        // Detect active route
        const currentUrl = window.location.href;

        // Highlight the correct button based on URL
        if (currentUrl.includes('/user/')) {
            userBtn.classList.add('active');
        } else if (currentUrl.includes('/trainer/')) {
            trainerBtn.classList.add('active');
        }

        // Handle clicks
        userBtn.addEventListener('click', () => {
            window.location.href = "{{ route('user.login') }}";
        });

        trainerBtn.addEventListener('click', () => {
            window.location.href = "{{ route('trainer.login') }}";
        });


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
