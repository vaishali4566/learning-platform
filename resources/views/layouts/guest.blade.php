<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Learning Platform')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #003FA6; border-radius: 3px; }
        .login-button {
            background-color: #1a1a1a;
            color: #fff;
            padding: 8px 16px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 14px;
            text-decoration: none;
        }
        .login-button:hover {
            background-color: #333;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-800 to-green-700 text-white p-2 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-xl font-bold">Learning Platform</a>
            <nav class="space-x-4">
                @if(Request::is('user/login') || Request::is('user/register'))
                    <!-- Only show Trainer Login -->
                    <a href="{{ route('trainer.login') }}" class="login-button">
                         Trainer Login
                    </a>
                @elseif(Request::is('trainer/login') || Request::is('trainer/register'))
                    <!-- Only show User Login -->
                    <a href="{{ route('user.login') }}" class="login-button">
                         User Login
                    </a>
                @else
                    <!-- Normal navbar for other pages -->
                    <a href="{{ route('user.login') }}" class="login-button">
                         User Login
                    </a>
                    <a href="{{ route('user.register') }}" class="hover:underline">User Register</a>
                    <a href="{{ route('trainer.login') }}" class="login-button">
                         Trainer Login
                    </a>
                    <a href="{{ route('trainer.register') }}" class="hover:underline">Trainer Register</a>
                @endif
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 text-gray-700 p-4 text-center border-t">
        &copy; {{ date('Y') }} Learning Platform. All rights reserved.
    </footer>

</body>
</html>