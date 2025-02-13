<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900 flex items-center justify-center min-h-screen">

@if (Route::has('login'))
    <div class="text-center">
        @auth
            <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white font-semibold text-lg">
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white font-semibold text-lg">
                Login
            </a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-6 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white font-semibold text-lg">
                    Register
                </a>
            @endif
        @endauth
    </div>
@endif

</body>
</html>
