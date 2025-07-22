<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    {{-- Navbar muncul di HP --}}
    @include('layouts.navbar')

    <div class="flex flex-1">
        {{-- Sidebar hanya tampil di desktop --}}
        @include('layouts.sidebar')

        {{-- Konten utama --}}
        <div class="flex-1 md:ml-64 flex flex-col min-h-screen">
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            {{-- Konten halaman --}}
            <main class="flex-1">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            @include('layouts.footer')
        </div>
    </div>
</body>



</html>
