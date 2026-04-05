<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $product->name }} | {{ $shop->name }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
        
        <style>
            body { font-family: 'Tajawal', sans-serif !important; }
            [x-cloak] { display: none !important; }

            :root {
                --shop-primary: {{ $shop->color_hex }};
                --shop-primary-hover: color-mix(in srgb, {{ $shop->color_hex }} 85%, black);
                --shop-accent: #d4af37;
                --shop-accent-soft: #fdfbf4;
            }

            .theme-primary-bg { background-color: var(--shop-primary) !important; }
            .theme-primary-bg-hover:hover { background-color: var(--shop-primary-hover) !important; }
            .theme-primary-text { color: var(--shop-primary) !important; }
            .theme-accent-text { color: var(--shop-accent) !important; }
            .theme-accent-soft-bg { background-color: var(--shop-accent-soft) !important; }
            .theme-accent-border { border-color: color-mix(in srgb, var(--shop-accent) 45%, transparent) !important; }
            
            .hero-glass {
                background: rgba(255, 255, 255, 0.78);
                backdrop-filter: blur(12px);
                border: 1px solid color-mix(in srgb, var(--shop-primary) 14%, transparent);
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="theme-primary-text antialiased bg-[#fdfbf4]">
        
        <!-- Navbar -->
        <nav class="bg-white/85 backdrop-blur-md border-b border-[#0d1b4b]/10 sticky top-0 z-40 transition-all duration-300 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <a href="{{ route('shop.show', $shop->slug) }}" class="flex items-center group">
                            <div class="ml-4 p-2 rounded-xl bg-gray-50 border border-[#0d1b4b]/10 group-hover:border-[#0d1b4b]/20 transition">
                                <svg class="w-5 h-5 text-[#0d1b4b]/60 group-hover:text-[#0d1b4b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                            <div class="relative">
                                @if($shop->logo_path)
                                    <img src="{{ Storage::url($shop->logo_path) }}" alt="{{ $shop->name }}" class="h-12 w-12 rounded-2xl object-cover border-2 theme-accent-border shadow-lg shadow-[#d4af37]/15">
                                @else
                                    <div class="h-12 w-12 rounded-2xl theme-primary-bg flex items-center justify-center text-white font-black text-xl shadow-lg">
                                        {{ mb_substr($shop->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="mx-4">
                                <h1 class="text-xl font-black text-[#0d1b4b] tracking-tight">{{ $shop->name }}</h1>
                                <span class="text-[10px] theme-accent-text font-bold tracking-widest uppercase">محلي ستور</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-[#0d1b4b]/5 p-8 lg:p-12 border border-[#0d1b4b]/5">
                <div class="flex flex-col lg:flex-row gap-12" x-data="{ mainImage: '{{ count($images) > 0 ? $images[0] : '' }}' }">
                    
                    <!-- Left: Images -->
                    <div class="w-full lg:w-1/2 flex flex-col gap-4">
                        @if(count($images) > 0)
                            <div class="w-full h-96 lg:h-[32rem] rounded-3xl overflow-hidden bg-[#0d1b4b]/5 border border-[#0d1b4b]/10 shadow-inner group">
                                <img :src="mainImage" alt="{{ $product->name }}" class="w-full h-full object-contain p-4 transition-transform duration-500 group-hover:scale-105">
                            </div>
                            <!-- Thumbnails Slider -->
                            @if(count($images) > 1)
                                <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                                    @foreach($images as $img)
                                        <button @mouseover="mainImage = '{{ $img }}'" 
                                                @click="mainImage = '{{ $img }}'"
                                                :class="mainImage === '{{ $img }}' ? 'ring-2 ring-[#d4af37] border-transparent' : 'border-[#0d1b4b]/10 hover:border-[#d4af37]/50'"
                                                class="w-20 h-20 rounded-2xl border-2 flex-shrink-0 overflow-hidden transition-all bg-[#0d1b4b]/5">
                                            <img src="{{ $img }}" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="w-full h-96 lg:h-[32rem] rounded-3xl overflow-hidden bg-[#0d1b4b]/5 border border-[#0d1b4b]/10 shadow-inner flex items-center justify-center text-[#0d1b4b]/30">
                                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>

                    <!-- Right: Details -->
                    <div class="w-full lg:w-1/2 flex flex-col justify-center">
                        @if($product->category)
                            <span class="theme-accent-text text-xs font-black uppercase tracking-widest mb-3 block">{{ $product->category->name }}</span>
                        @endif
                        <h1 class="text-3xl lg:text-5xl font-black text-[#0d1b4b] mb-6 leading-tight">{{ $product->name }}</h1>
                        
                        <div class="mb-8">
                            @if($product->hasActiveDiscount())
                                <div class="flex items-center gap-4">
                                    <span class="text-4xl font-black text-[#0d1b4b]">{{ number_format($product->effectivePrice(), 2) }} <span class="text-lg font-medium text-[#0d1b4b]/45">ل.س</span></span>
                                    <div class="flex flex-col">
                                        <span class="line-through text-[#0d1b4b]/35 text-lg">{{ number_format($product->price, 0) }}</span>
                                        <span class="text-red-500 font-bold text-xs uppercase tracking-widest mt-0.5">وفر {{ number_format($product->discount_percent, 0) }}%</span>
                                    </div>
                                </div>
                            @else
                                <span class="text-4xl font-black text-[#0d1b4b]">{{ number_format($product->price, 2) }} <span class="text-lg font-medium text-[#0d1b4b]/45">ل.س</span></span>
                            @endif
                        </div>

                        <div class="prose prose-lg text-[#0d1b4b]/70 font-medium mb-10 leading-relaxed border-t border-b border-[#0d1b4b]/5 py-6">
                            {{ $product->description ?? 'لا يوجد وصف متاح لهذا المنتج حالياً.' }}
                        </div>

                        <a href="{{ route('shop.show', $shop->slug) }}" class="w-full theme-primary-bg theme-primary-bg-hover text-white font-black py-5 px-6 rounded-2xl shadow-2xl shadow-[#0d1b4b]/20 transition-all text-center text-lg flex justify-center items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            الرجوع للمتجر للإضافة للسلة
                        </a>
                        <p class="text-center text-xs text-[#0d1b4b]/45 mt-4">قم بإضافة المنتج للسلة من واجهة المتجر الرئيسية</p>
                    </div>
                    
                </div>
            </div>
        </main>

    </body>
</html>
