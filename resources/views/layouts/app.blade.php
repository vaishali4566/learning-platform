<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-900 to-green-700 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">

            <!-- Logo -->
            <a href="{{ url('/') }}" class="text-xl font-bold">Learning Platform</a>

            <!-- Navigation -->
            <nav class="space-x-4">
                @if(Auth::guard('web')->check())
                    <!-- User Links -->
                    <a href="{{ route('user.dashboard') }}" class="hover:underline">Dashboard</a>
                    <a href="{{ route('user.profile') }}" class="hover:underline">Profile</a>
                    <form method="POST" action="{{ route('user.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:underline bg-transparent">Logout</button>
                    </form>

                @elseif(Auth::guard('trainer')->check())
                    <!-- Trainer Links -->
                    <a href="{{ route('trainer.dashboard') }}" class="hover:underline">Dashboard</a>
                    <a href="{{ route('trainer.profile') }}" class="hover:underline">Profile</a>
                    <form method="POST" action="{{ route('trainer.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:underline bg-transparent">Logout</button>
                    </form>

                @else
                    <!-- Guest Links -->
                    <a href="{{ route('user.login') }}" class="hover:underline">User Login</a>
                    <a href="{{ route('trainer.login') }}" class="hover:underline">Trainer Login</a>
                @endif
            </nav>

        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

</body>
</html>
