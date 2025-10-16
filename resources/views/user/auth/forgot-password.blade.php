<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-900 to-green-700 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Forgot Password</h2>

        {{-- Success Message --}}
        @if(session('status'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center">
                {{ session('status') }}
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Forgot Password Form --}}
        <form method="POST" action="{{ route('user.password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-gray-700 font-semibold mb-1">Email Address</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    required
                    placeholder="Enter your email"
                    class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-gradient-to-r from-blue-800 to-green-700 text-white font-semibold py-3 rounded-lg hover:opacity-90 transition"
            >
                Send Reset Link
            </button>

            <p class="text-center text-gray-600 text-sm mt-3">
                Remembered your password? 
                <a href="{{ route('user.login') }}" class="text-blue-700 font-semibold hover:underline">Login</a>
            </p>
        </form>
    </div>

</body>
</html>
