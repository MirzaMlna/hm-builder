<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans antialiased">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md text-center">
        <div class="flex justify-center mb-6">
            <x-application-logo class="w-20 h-20 fill-current text-gray-700" />
        </div>
        <h1 class="text-xl font-semibold mb-4 text-gray-800">Selamat Datang di {{ config('app.name') }}</h1>
        <p class="text-sm text-gray-600 mb-6">Silakan login atau daftar untuk melanjutkan.</p>

        <div class="flex justify-center gap-4">
            <a href="{{ route('login') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Login
            </a>
            <a href="{{ route('register') }}"
                class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition">
                Register
            </a>
        </div>
    </div>
</body>

</html>
