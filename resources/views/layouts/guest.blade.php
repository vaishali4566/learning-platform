<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Learning Platform')</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        footer {
            background: #171717;
            color: #d3d3d3;
            padding: 12px 0;
            font-size: 0.9rem;
        }

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
        .bg-animated {
            background: linear-gradient(135deg, #101727, #1e3a8a, #10b981, #3b82f6);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
        }

        /* Role toggle buttons */
        .role-toggle {
            background: #1f2937;
            color: #cbd5e1;
            padding: 6px 16px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .role-toggle.active {
            background: #374151;
            color: #fff;
        }
        .role-toggle:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body class="flex flex-col min-h-screen bg-gray-900">

    <main class="flex flex-1">
        <!-- Left Branding -->
        <div class="hidden md:flex md:w-1/2 bg-animated items-center justify-center p-6">
            <div class="text-center px-8 text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Learning Platform</h1>
                <p class="text-white/80 text-lg md:text-xl">Learn anytime, anywhere with our platform.</p>
            </div>
        </div>

        <!-- Right Login Section -->
        <div class="flex flex-1 items-center justify-center p-6 bg-gray-900">
            <div class="w-full max-w-md fade-slide-up text-center">

                <!-- Toggle -->
                <div class="flex justify-center gap-3 mb-6">
                    <button id="userBtn" class="role-toggle">User</button>
                    <button id="trainerBtn" class="role-toggle">Trainer</button>
                </div>

                <!-- Dynamic content -->
                <div id="authContainer">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center mt-auto p-5">
        &copy; {{ date('Y') }} Learning Platform. All rights reserved.
    </footer>

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
    </script>

</body>
</html>
