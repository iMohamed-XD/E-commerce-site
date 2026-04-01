<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Tajawal', sans-serif !important; }

        .hero-dotgrid {
            background-image: radial-gradient(circle, #0d1b4b12 1px, transparent 1px);
            background-size: 28px 28px;
        }
    </style>
</head>
<body class="font-sans antialiased text-[#0d1b4b]">

    {{-- Fixed layered background --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-b from-[#eef2ff] via-white to-[#fdfbf4]"></div>
        <div class="absolute inset-0 hero-dotgrid opacity-60"></div>
        <div class="absolute top-0 right-0 w-[600px] h-[400px] bg-[#d4af37]/8 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[400px] bg-[#0d1b4b]/5 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col">

        @include('layouts.navigation')

        {{-- Page Heading --}}
        @isset($header)
            <header class="backdrop-blur-md bg-white/80 border-b border-[#0d1b4b]/8 shadow-sm">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Page Content --}}
        <main class="flex-grow">
            {{ $slot }}
        </main>

    </div>

</body>
</html>
