<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="dark bg-gray-900 text-gray-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Tajawal', sans-serif !important; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-100 antialiased bg-gray-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-900 my-12">
            <div class="relative group">
                <!-- Premium Glow Effect -->
                <div class="absolute -inset-4 bg-gradient-to-r from-blue-600/20 via-yellow-500/10 to-blue-600/20 rounded-full blur-2xl group-hover:blur-3xl transition-all duration-500 opacity-50"></div>

                <a href="/" class="relative block">
                    <div class="bg-gray-800/40 backdrop-blur-xl border border-white/5 p-8 rounded-[2.5rem] shadow-2xl transition-transform duration-500 hover:scale-105">
                        <div class="absolute inset-0 rounded-[2.5rem] border border-yellow-500/10 pointer-events-none"></div>
                        <x-application-logo class="h-32 w-auto drop-shadow-[0_0_25px_rgba(255,255,255,0.2)] hover:scale-110 transition-transform duration-700" />
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-gray-800 border border-gray-700 shadow-xl overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
