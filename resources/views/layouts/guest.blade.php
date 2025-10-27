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
            background: #f0f2f5;
        }

        footer {
            background: #171717;
            color: #d3d3d3;
            padding: 12px 0;
            font-size: 0.9rem;
        }

        a {
            color: #3b82f6;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Fade + slide up */
        @keyframes fadeSlideUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-slide-up {
            animation: fadeSlideUp 0.8s ease-out forwards;
        }

        /* Gradient animation for left panel */
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

        /* Buttons hover scale */
        .role-toggle, .button1, .button2, .button3 {
            transition: all 0.3s ease;
        }

        .role-toggle:hover, .button1:hover, .button2:hover, .button3:hover {
            transform: scale(1.05);
        }

        /* Floating + glowing effect for whole heading */
        @keyframes floatGlow {
            0%, 100% { transform: translateY(0); text-shadow: 0 0 10px #ffffff33; }
            50% { transform: translateY(-6px); text-shadow: 0 0 25px #ffffff77; }
        }

        /* Per-letter waving effect */
        @keyframes wave {
            0%, 100% { transform: translateY(0); }
            25% { transform: translateY(-6px); }
            50% { transform: translateY(3px); }
            75% { transform: translateY(-3px); }
        }

        /* Heading container for fade-in + floatGlow */
        .heading-animate {
            display: inline-block;
            animation: fadeSlideUp 0.8s ease-out forwards 0.2s,
                       floatGlow 4s ease-in-out infinite 0.5s;
            opacity: 0;
        }

        /* Each letter gets waving animation */
        .heading-animate span {
            display: inline-block;
            animation: wave 1.5s ease-in-out infinite;
        }

        /* Input animation */
        .input-animate {
            animation: fadeSlideUp 0.8s ease-out forwards;
            animation-delay: 0.4s;
            opacity: 0;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">

    <!-- Main Content -->
    <main class="flex flex-1">
        <!-- Left side: image / branding -->
        <div class="hidden md:flex md:w-1/2 bg-animated items-center justify-center p-6">
            <div class="text-center px-8">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 heading-animate">
                    Learning Platform
                </h1>
                <p class="text-white/80 text-lg md:text-xl " style="animation-delay:0.3s;">
                    Learn anytime, anywhere with our platform.
                </p>
            </div>
        </div>

        <!-- Right side: login form -->
        <div class="flex flex-1 items-center justify-center p-6 bg-gray-900">
            <div class="w-full max-w-md md:max-w-lg fade-slide-up">
                @yield('content')
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center mt-auto p-5">
        &copy; {{ date('Y') }} Learning Platform. All rights reserved.
    </footer>

    <!-- JS: Auto-wrap heading letters for waving effect -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const heading = document.querySelector('.heading-animate');
            if (heading) {
                const text = heading.textContent.trim();
                heading.textContent = '';
                text.split('').forEach((char, index) => {
                    const span = document.createElement('span');
                    span.textContent = char;
                    span.style.animationDelay = `${index * 0.05}s`;
                    heading.appendChild(span);
                });
            }
        });
    </script>

</body>

</html>
