<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Learning Platform')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Optional: custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #003FA6;
            border-radius: 3px;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-900 to-green-700 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-xl font-bold">Learning Platform</a>
            <nav class="space-x-4">
                <a href="{{ route('user.login') }}" class="hover:underline">User Login</a>
                <a href="{{ route('user.register') }}" class="hover:underline">User Register</a>
                <a href="{{ route('trainer.login') }}" class="hover:underline">Trainer Login</a>
                <a href="{{ route('trainer.register') }}" class="hover:underline">Trainer Register</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 text-gray-700 p-4 text-center border-t mt-8">
        &copy; {{ date('Y') }} Learning Platform. All rights reserved.
    </footer>

</body>
</html>
