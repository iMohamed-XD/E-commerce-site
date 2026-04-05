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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Tajawal', sans-serif !important; }

        :root {
            --shop-primary: {{ $shop->color_hex }};
            --shop-primary-hover: color-mix(in srgb, {{ $shop->color_hex }} 85%, black);
            --shop-accent: #d4af37;
        }

        .theme-primary-bg  { background-color: var(--shop-primary) !important; }
        .theme-accent-text { color: var(--shop-accent) !important; }
        .theme-accent-border { border-color: color-mix(in srgb, var(--shop-accent) 45%, transparent) !important; }

        /* ---- Slider ---- */
        #main-image {
            transition: opacity 0.25s ease, transform 0.25s ease;
        }
        #main-image.is-switching {
            opacity: 0;
            transform: scale(0.97);
        }

        .thumb-btn {
            border: 2px solid rgba(13,27,75,0.12);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .thumb-btn:hover {
            border-color: rgba(212,175,55,0.55);
        }
        .thumb-btn.active {
            border-color: transparent;
            box-shadow: 0 0 0 2.5px var(--shop-accent);
        }

        /* ---- Toast ---- */
        #cart-toast {
            position: fixed;
            bottom: 2rem;
            left: 2rem;
            z-index: 999;
            background: var(--shop-primary);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 1rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 20px 40px rgba(13,27,75,0.25);
            transform: translateY(120%);
            opacity: 0;
            transition: transform 0.35s cubic-bezier(.22,.68,0,1.2), opacity 0.3s ease;
            pointer-events: none;
        }
        #cart-toast.show {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body class="antialiased bg-[#fdfbf4]" style="color: var(--shop-primary);">

    <!-- Toast -->
    <div id="cart-toast">
        <svg style="width:1.4rem;height:1.4rem;border:2px solid white;border-radius:50%;padding:2px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
        </svg>
        <span>تمت الإضافة! جاري التوجيه للمتجر…</span>
    </div>

    <!-- Navbar -->
    <nav style="background:rgba(255,255,255,0.88);backdrop-filter:blur(12px);" class="border-b border-[#0d1b4b]/10 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center h-20">
                <a href="{{ route('shop.show', $shop->slug) }}" class="flex items-center gap-3 group">
                    <div class="p-2 rounded-xl bg-gray-50 border border-[#0d1b4b]/10 group-hover:border-[#0d1b4b]/25 transition">
                        <svg class="w-5 h-5 text-[#0d1b4b]/55 group-hover:text-[#0d1b4b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    @if($shop->logo_path)
                        <img src="{{ Storage::url($shop->logo_path) }}" alt="{{ $shop->name }}"
                             class="h-11 w-11 rounded-2xl object-cover border-2 shadow-md"
                             style="border-color: color-mix(in srgb, var(--shop-accent) 45%, transparent);">
                    @else
                        <div class="h-11 w-11 rounded-2xl theme-primary-bg flex items-center justify-center text-white font-black text-xl shadow-md">
                            {{ mb_substr($shop->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-lg font-black text-[#0d1b4b]">{{ $shop->name }}</p>
                        <span class="text-[10px] font-bold tracking-widest uppercase theme-accent-text">محلي ستور</span>
                    </div>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-[#0d1b4b]/5 p-8 lg:p-12 border border-[#0d1b4b]/5">
            <div class="flex flex-col lg:flex-row gap-12">

                <!-- ===== IMAGE PANEL ===== -->
                @php
                    $hasImages = count($images) > 0;
                    $currentPrice = $product->hasActiveDiscount() ? $product->effectivePrice() : $product->price;
                @endphp

                <div class="w-full lg:w-1/2 flex flex-col gap-4">
                    @if($hasImages)
                        <!-- Main viewer -->
                        <div class="w-full h-96 lg:h-[32rem] rounded-3xl overflow-hidden bg-[#0d1b4b]/5 border border-[#0d1b4b]/10 shadow-inner flex items-center justify-center p-4">
                            <img id="main-image"
                                 src="{{ $images[0] }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-contain">
                        </div>

                        <!-- Thumbnails -->
                        @if(count($images) > 1)
                            <div class="flex gap-3 overflow-x-auto pb-2" style="scrollbar-width:none;">
                                @foreach($images as $i => $img)
                                    <button type="button"
                                            class="thumb-btn {{ $i === 0 ? 'active' : '' }} w-20 h-20 rounded-2xl flex-shrink-0 overflow-hidden bg-[#0d1b4b]/5"
                                            data-index="{{ $i }}"
                                            data-src="{{ $img }}"
                                            onclick="selectThumb(this)">
                                        <img src="{{ $img }}" alt="صورة {{ $i + 1 }}" class="w-full h-full object-cover pointer-events-none">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="w-full h-96 lg:h-[32rem] rounded-3xl overflow-hidden bg-[#0d1b4b]/5 border border-[#0d1b4b]/10 shadow-inner flex items-center justify-center text-[#0d1b4b]/30">
                            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- ===== DETAIL PANEL ===== -->
                <div class="w-full lg:w-1/2 flex flex-col justify-between">
                    <div>
                        @if($product->category)
                            <span class="theme-accent-text text-xs font-black uppercase tracking-widest mb-3 block">{{ $product->category->name }}</span>
                        @endif

                        <h1 class="text-3xl lg:text-5xl font-black text-[#0d1b4b] mb-6 leading-tight">{{ $product->name }}</h1>

                        <!-- Price -->
                        <div class="mb-8">
                            @if($product->hasActiveDiscount())
                                <div class="flex items-center gap-4">
                                    <span class="text-4xl font-black text-[#0d1b4b]">
                                        {{ number_format($product->effectivePrice(), 2) }}
                                        <span class="text-base font-medium text-[#0d1b4b]/45">ل.س</span>
                                    </span>
                                    <div class="flex flex-col">
                                        <span class="line-through text-[#0d1b4b]/35 text-lg">{{ number_format($product->price, 0) }}</span>
                                        <span class="text-red-500 font-bold text-xs uppercase tracking-widest">وفر {{ number_format($product->discount_percent, 0) }}%</span>
                                    </div>
                                </div>
                            @else
                                <span class="text-4xl font-black text-[#0d1b4b]">
                                    {{ number_format($product->price, 2) }}
                                    <span class="text-base font-medium text-[#0d1b4b]/45">ل.س</span>
                                </span>
                            @endif
                        </div>

                        <!-- Description -->
                        <div class="text-[#0d1b4b]/70 font-medium text-lg leading-relaxed border-t border-b border-[#0d1b4b]/8 py-6 mb-8">
                            {{ $product->description ?? 'لا يوجد وصف متاح لهذا المنتج حالياً.' }}
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col gap-3">
                        <!-- Add to Cart -->
                        <button id="add-to-cart-btn"
                                type="button"
                                onclick="addProductToCart()"
                                class="w-full text-white font-black py-5 px-6 rounded-2xl shadow-2xl transition-all text-lg flex justify-center items-center gap-3"
                                style="background-color:var(--shop-primary);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            أضف للسلة
                        </button>

                        <!-- Go Back -->
                        <a href="{{ route('shop.show', $shop->slug) }}"
                           class="w-full bg-white border-2 border-[#0d1b4b]/10 hover:border-[#0d1b4b]/25 text-[#0d1b4b]/60 hover:text-[#0d1b4b] font-bold py-4 px-6 rounded-2xl transition-all text-lg flex justify-center items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            العودة للمتجر
                        </a>
                    </div>
                </div>

            </div><!-- /flex row -->
        </div>
    </main>

    <!-- ===== PURE VANILLA JS ===== -->
    <script>
        /* ---------- Slider ---------- */
        var mainImg = document.getElementById('main-image');

        function selectThumb(btn) {
            // Deactivate all thumbs
            document.querySelectorAll('.thumb-btn').forEach(function(b) {
                b.classList.remove('active');
            });
            btn.classList.add('active');

            var newSrc = btn.getAttribute('data-src');
            if (!mainImg || !newSrc) return;

            // Fade out → swap src → fade in
            mainImg.style.opacity  = '0';
            mainImg.style.transform = 'scale(0.97)';

            setTimeout(function() {
                mainImg.src = newSrc;
                mainImg.style.transition = 'none';   // instant set
                mainImg.style.opacity   = '0';
                mainImg.style.transform = 'scale(0.97)';

                // Force reflow then animate in
                mainImg.getBoundingClientRect();
                mainImg.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                mainImg.style.opacity   = '1';
                mainImg.style.transform = 'scale(1)';
            }, 200);
        }

        /* ---------- Cart ---------- */
        var SHOP_SLUG     = @json($shop->slug);
        var STORAGE_KEY   = 'mahly_cart_' + SHOP_SLUG;
        var SHOP_URL      = @json(route('shop.show', $shop->slug));
        var PRODUCT_ID    = {{ $product->id }};
        var PRODUCT_NAME  = @json($product->name);
        var PRODUCT_PRICE = {{ $currentPrice }};
        var PRODUCT_IMG   = @json($hasImages ? $images[0] : '');

        function addProductToCart() {
            var btn = document.getElementById('add-to-cart-btn');
            btn.disabled = true;
            btn.style.opacity = '0.7';

            // Read cart
            var cart = [];
            try { cart = JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; } catch(e) {}

            // Add / increment
            var existing = cart.find(function(i) { return i.id === PRODUCT_ID; });
            if (existing) {
                existing.quantity += 1;
            } else {
                cart.push({
                    id:       PRODUCT_ID,
                    name:     PRODUCT_NAME,
                    price:    PRODUCT_PRICE,
                    image:    PRODUCT_IMG,
                    quantity: 1
                });
            }
            localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));

            // Show toast
            var toast = document.getElementById('cart-toast');
            toast.classList.add('show');

            // Redirect after brief pause
            setTimeout(function() {
                window.location.href = SHOP_URL;
            }, 1300);
        }
    </script>

</body>
</html>
