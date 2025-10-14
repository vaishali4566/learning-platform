<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100">
    <header class="bg-gradient-to-r from-blue-900 to-green-700 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-xl font-bold">Learning Platform</a>
            <nav class="space-x-4">
                <a href="{{ route('user.dashboard') }}" class="hover:underline">Dashboard</a>
                <a href="{{ route('user.profile') }}" class="hover:underline">Profile</a>
                <form method="POST" action="{{ route('user.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:underline bg-transparent">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="">
        @yield('content')
    </main>
</body>
</html>