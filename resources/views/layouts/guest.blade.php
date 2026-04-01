<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

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
<body class="antialiased text-[#0d1b4b] min-h-screen">

    {{-- Fixed layered background matching the landing page --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-b from-[#eef2ff] via-white to-[#fdfbf4]"></div>
        <div class="absolute inset-0 hero-dotgrid opacity-60"></div>
        <div class="absolute top-0 right-0 w-[600px] h-[400px] bg-[#d4af37]/8 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[400px] bg-[#0d1b4b]/5 rounded-full blur-[100px]"></div>
    </div>

    {{-- Minimal top bar --}}
    <nav class="relative z-50 w-full px-6 lg:px-12 py-5 flex items-center justify-between backdrop-blur-md bg-white/80 border-b border-[#0d1b4b]/8 shadow-sm">
        <a href="/" class="flex items-center group">
            <div class="relative bg-white/80 backdrop-blur-xl p-2 rounded-xl border border-[#0d1b4b]/10 shadow-md group-hover:border-[#d4af37]/40 group-hover:shadow-[0_4px_20px_rgba(212,175,55,0.15)] transition-all duration-500 overflow-hidden">
                <div class="absolute inset-0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-[#d4af37]/10 to-transparent"></div>
                <x-application-logo class="block h-8 w-auto" />
            </div>
            <span class="hidden sm:inline-block text-[10px] text-[#0d1b4b]/40 uppercase tracking-[0.2em] border-r border-[#0d1b4b]/15 pr-3 mr-3 mt-0.5">
                THE LUXURY OF LOCAL
            </span>
        </a>
        <a href="{{ route('login') }}" class="text-sm font-bold text-[#0d1b4b]/50 hover:text-[#0d1b4b] transition-colors duration-200">
            تسجيل الدخول
        </a>
    </nav>

    {{-- Main content --}}
    <main class="relative z-10 min-h-[calc(100vh-69px)] flex items-center justify-center py-16 px-4">
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </main>

</body>
</html>
