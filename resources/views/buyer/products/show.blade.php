<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->name }} | {{ $shop->name }}</title>

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

        .theme-primary-bg { background-color: var(--shop-primary) !important; }
        .theme-accent-text { color: var(--shop-accent) !important; }

        #main-image {
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        .thumb-btn {
            border: 2px solid rgba(13, 27, 75, 0.12);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .thumb-btn:hover {
            border-color: rgba(212, 175, 55, 0.55);
        }

        .thumb-btn.active {
            border-color: transparent;
            box-shadow: 0 0 0 2.5px var(--shop-accent);
        }

        .product-option-card {
            border-color: rgba(13, 27, 75, 0.12);
            background: white;
        }

        .product-option-card.is-selected {
            border-color: var(--shop-accent);
            background: #fff9e8;
            box-shadow: 0 0 0 1px color-mix(in srgb, var(--shop-accent) 25%, transparent);
        }

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
            box-shadow: 0 20px 40px rgba(13, 27, 75, 0.25);
            transform: translateY(120%);
            opacity: 0;
            transition: transform 0.35s cubic-bezier(.22, .68, 0, 1.2), opacity 0.3s ease;
            pointer-events: none;
        }

        #cart-toast.show {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body class="antialiased bg-[#fdfbf4]" style="color: var(--shop-primary);">

    <div id="cart-toast">
        <svg style="width:1.4rem;height:1.4rem;border:2px solid white;border-radius:50%;padding:2px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
        </svg>
        <span id="cart-toast-text">تمت الإضافة! جاري التوجيه للمتجر…</span>
    </div>

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

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-[#0d1b4b]/5 p-8 lg:p-12 border border-[#0d1b4b]/5">
            <div class="flex flex-col lg:flex-row gap-12">
                @php
                    $hasImages = count($images) > 0;
                    $currentPriceUsd = $product->effectivePrice();
                    $currentPriceSyp = $product->effectivePriceInSyp($usdToSypRate);
                    $basePriceSyp = $product->priceInSyp($usdToSypRate);
                    $isOutOfStock = !$product->isInStock();
                    $totalStock = $product->totalStock();
                @endphp

                <div class="w-full lg:w-1/2 flex flex-col gap-4">
                    @if($hasImages)
                        <div class="w-full h-96 lg:h-[32rem] rounded-3xl overflow-hidden bg-[#0d1b4b]/5 border border-[#0d1b4b]/10 shadow-inner flex items-center justify-center p-4">
                            <img id="main-image"
                                 src="{{ $images[0] }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-contain">
                        </div>

                        @if(count($images) > 1)
                            <div class="flex gap-3 overflow-x-auto pb-2" style="scrollbar-width:none;">
                                @foreach($images as $i => $img)
                                    <button type="button"
                                            class="thumb-btn {{ $i === 0 ? 'active' : '' }} w-20 h-20 rounded-2xl flex-shrink-0 overflow-hidden bg-[#0d1b4b]/5"
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

                <div class="w-full lg:w-1/2 flex flex-col justify-between">
                    <div>
                        @if($product->category)
                            <span class="theme-accent-text text-xs font-black uppercase tracking-widest mb-3 block">{{ $product->category->name }}</span>
                        @endif

                        <h1 class="text-3xl lg:text-5xl font-black text-[#0d1b4b] mb-6 leading-tight">{{ $product->name }}</h1>

                        <div class="mb-8 space-y-3">
                            @if($product->hasActiveDiscount())
                                <div class="space-y-2">
                                    <div class="flex flex-wrap items-baseline gap-3">
                                        <span class="text-4xl font-black text-[#0d1b4b]">${{ number_format($currentPriceUsd, 2) }}</span>
                                        <span class="text-lg font-bold text-[#0d1b4b]/55">{{ number_format($currentPriceSyp, 0) }} ل.س</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <span class="line-through text-[#0d1b4b]/35 text-lg">${{ number_format($product->price, 2) }}</span>
                                        <span class="line-through text-[#0d1b4b]/30 text-sm">{{ number_format($basePriceSyp, 0) }} ل.س</span>
                                        <span class="text-red-500 font-bold text-xs uppercase tracking-widest">وفر {{ number_format($product->discount_percent, 0) }}%</span>
                                    </div>
                                </div>
                            @else
                                <div class="space-y-2">
                                    <div class="flex flex-wrap items-baseline gap-3">
                                        <span class="text-4xl font-black text-[#0d1b4b]">${{ number_format($product->price, 2) }}</span>
                                        <span class="text-lg font-bold text-[#0d1b4b]/55">{{ number_format($basePriceSyp, 0) }} ل.س</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="text-[#0d1b4b]/70 font-medium text-lg leading-relaxed border-t border-b border-[#0d1b4b]/8 py-6 mb-8">
                            {{ $product->description ?? 'لا يوجد وصف متاح لهذا المنتج حالياً.' }}
                        </div>

                        @if($product->has_options)
                            <div class="mb-8">
                                <p class="text-xs font-black theme-accent-text uppercase tracking-[0.2em] mb-4">اختر الخيار</p>
                                <div class="space-y-3">
                                    @foreach($product->productOptions as $option)
                                        @php($optionInStock = (int) $option->quantity > 0)
                                        <label class="block cursor-pointer" onclick="selectProductOption({{ $option->id }}); return false;">
                                            <input
                                                id="product-option-{{ $option->id }}"
                                                type="radio"
                                                name="product_option"
                                                value="{{ $option->id }}"
                                                data-option-id="{{ $option->id }}"
                                                data-option-label="{{ $option->label }}"
                                                data-option-stock="{{ (int) $option->quantity }}"
                                                class="peer sr-only"
                                                onchange="syncProductOptionCards(); updateAddToCartState()"
                                                {{ $optionInStock ? '' : 'disabled' }}
                                            >
                                            <div data-option-card class="product-option-card rounded-2xl border px-5 py-4 transition-all {{ $optionInStock ? '' : 'border-red-200 bg-red-50 text-red-600 opacity-70' }}">
                                                <div class="flex items-center justify-between gap-4">
                                                    <div>
                                                        <p class="font-black text-[#0d1b4b]">{{ $option->label }}</p>
                                                        <p class="mt-1 text-xs {{ $optionInStock ? 'text-[#0d1b4b]/45' : 'text-red-500' }}">
                                                            {{ $optionInStock ? 'الكمية المتوفرة: ' . (int) $option->quantity : 'غير متوفر حاليا' }}
                                                        </p>
                                                    </div>
                                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-[11px] font-black {{ $optionInStock ? 'bg-[#0d1b4b]/5 text-[#0d1b4b]/65' : 'bg-red-100 text-red-600' }}">
                                                        {{ $optionInStock ? 'متوفر' : 'نفد' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="mt-3 text-sm text-[#0d1b4b]/50" id="product-option-help">اختر خياراً متوفراً قبل الإضافة إلى السلة.</p>
                            </div>
                        @else
                            <div class="mb-8 rounded-2xl border border-[#0d1b4b]/10 bg-[#f8faff] px-5 py-4">
                                <p class="text-xs font-black theme-accent-text uppercase tracking-[0.2em]">حالة المخزون</p>
                                <p class="mt-2 text-sm font-bold text-[#0d1b4b]">{{ $isOutOfStock ? 'نفدت الكمية حالياً.' : 'المتوفر حالياً: ' . $totalStock }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3">
                        <button id="add-to-cart-btn"
                                type="button"
                                onclick="addProductToCart()"
                                class="w-full text-white font-black py-5 px-6 rounded-2xl shadow-2xl transition-all text-lg flex justify-center items-center gap-3 disabled:opacity-55 disabled:cursor-not-allowed"
                                style="background-color:var(--shop-primary);"
                                {{ $isOutOfStock ? 'disabled' : '' }}>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span id="add-to-cart-label">{{ $isOutOfStock ? 'نفدت الكمية' : ($product->has_options ? 'اختر خياراً متوفراً' : 'أضف للسلة') }}</span>
                        </button>

                        <a href="{{ route('shop.show', $shop->slug) }}"
                           class="w-full bg-white border-2 border-[#0d1b4b]/10 hover:border-[#0d1b4b]/25 text-[#0d1b4b]/60 hover:text-[#0d1b4b] font-bold py-4 px-6 rounded-2xl transition-all text-lg flex justify-center items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            العودة للمتجر
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        var mainImg = document.getElementById('main-image');

        function selectThumb(btn) {
            document.querySelectorAll('.thumb-btn').forEach(function (thumb) {
                thumb.classList.remove('active');
            });
            btn.classList.add('active');

            var newSrc = btn.getAttribute('data-src');
            if (!mainImg || !newSrc) return;

            mainImg.style.opacity = '0';
            mainImg.style.transform = 'scale(0.97)';

            setTimeout(function () {
                mainImg.src = newSrc;
                mainImg.style.transition = 'none';
                mainImg.style.opacity = '0';
                mainImg.style.transform = 'scale(0.97)';
                mainImg.getBoundingClientRect();
                mainImg.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                mainImg.style.opacity = '1';
                mainImg.style.transform = 'scale(1)';
            }, 200);
        }

        var SHOP_SLUG = @json($shop->slug);
        var STORAGE_KEY = 'mahly_cart_' + SHOP_SLUG;
        var SHOP_URL = @json(route('shop.show', $shop->slug));
        var PRODUCT_ID = {{ $product->id }};
        var PRODUCT_NAME = @json($product->name);
        var PRODUCT_PRICE_USD = {{ $currentPriceUsd }};
        var PRODUCT_IMG = @json($hasImages ? $images[0] : '');
        var PRODUCT_HAS_OPTIONS = {{ $product->has_options ? 'true' : 'false' }};
        var PRODUCT_TOTAL_STOCK = {{ $totalStock }};
        var PRODUCT_IN_STOCK = {{ $isOutOfStock ? 'false' : 'true' }};
        var SELECTED_PRODUCT_OPTION_ID = null;

        function showCartToast(message, shouldRedirect) {
            var toast = document.getElementById('cart-toast');
            var toastText = document.getElementById('cart-toast-text');

            if (toastText) {
                toastText.textContent = message;
            }

            toast.classList.add('show');
            setTimeout(function () {
                toast.classList.remove('show');
            }, shouldRedirect ? 1200 : 2200);

            if (shouldRedirect) {
                setTimeout(function () {
                    window.location.href = SHOP_URL;
                }, 1300);
            }
        }

        function makeCartKey(productId, optionId) {
            return productId + ':' + (optionId || 'simple');
        }

        function safeStorageGet(key) {
            try {
                if (window.localStorage) {
                    return window.localStorage.getItem(key);
                }
            } catch (e) {}

            return null;
        }

        function safeStorageSet(key, value) {
            try {
                if (window.localStorage) {
                    window.localStorage.setItem(key, value);
                    return true;
                }
            } catch (e) {}

            return false;
        }

        var FALLBACK_CART = [];

        function normalizeCart(cart) {
            if (!Array.isArray(cart)) return [];

            return cart
                .map(function (item) {
                    var source = item || {};
                    var sourceProductId = source.product_id;
                    if (sourceProductId === undefined || sourceProductId === null || sourceProductId === '') {
                        sourceProductId = source.id;
                    }

                    var productId = parseInt(sourceProductId || 0, 10);
                    if (!productId) return null;

                    var optionId = source.option_id !== undefined && source.option_id !== null && source.option_id !== ''
                        ? parseInt(source.option_id, 10)
                        : null;

                    var sourcePriceUsd = source.unit_price_usd;
                    if (sourcePriceUsd === undefined || sourcePriceUsd === null || sourcePriceUsd === '') {
                        sourcePriceUsd = source.price;
                    }

                    var sourceQuantity = source.quantity;
                    if (sourceQuantity === undefined || sourceQuantity === null || sourceQuantity === '') {
                        sourceQuantity = 1;
                    }

                    var sourceOptionLabel = source.option_label;
                    if (sourceOptionLabel === undefined || sourceOptionLabel === null || sourceOptionLabel === '') {
                        sourceOptionLabel = source.product_option_label;
                    }
                    var sourceMaxQuantity = parseInt(source.maxQuantity, 10);

                    return {
                        id: productId,
                        product_id: productId,
                        name: source.name || PRODUCT_NAME,
                        unit_price_usd: parseFloat(sourcePriceUsd || PRODUCT_PRICE_USD),
                        image: source.image || PRODUCT_IMG,
                        quantity: Math.max(parseInt(sourceQuantity, 10), 1),
                        option_id: optionId,
                        option_label: (sourceOptionLabel || '').toString().trim() || null,
                        cart_key: source.cart_key || makeCartKey(productId, optionId),
                        maxQuantity: Number.isFinite(sourceMaxQuantity) && sourceMaxQuantity > 0 ? sourceMaxQuantity : null,
                    };
                })
                .filter(Boolean);
        }

        function buildSelectedOption(input) {
            if (!input) return null;

            return {
                option_id: parseInt(input.getAttribute('data-option-id'), 10),
                option_label: input.getAttribute('data-option-label'),
                maxQuantity: parseInt(input.getAttribute('data-option-stock'), 10) || 0,
            };
        }

        function getSelectedOption() {
            if (SELECTED_PRODUCT_OPTION_ID !== null) {
                var remembered = document.getElementById('product-option-' + SELECTED_PRODUCT_OPTION_ID);
                if (remembered && !remembered.disabled) {
                    remembered.checked = true;
                    return buildSelectedOption(remembered);
                }
            }

            var checked = document.querySelector('input[name="product_option"]:checked');
            if (!checked) return null;

            SELECTED_PRODUCT_OPTION_ID = parseInt(checked.getAttribute('data-option-id'), 10);

            return buildSelectedOption(checked);
        }

        function syncProductOptionCards() {
            document.querySelectorAll('input[name="product_option"]').forEach(function (input) {
                var label = input.closest('label');
                var card = label ? label.querySelector('[data-option-card]') : null;
                if (!card || input.disabled) return;

                card.classList.toggle('is-selected', input.checked);
            });
        }

        function selectProductOption(optionId) {
            var input = document.getElementById('product-option-' + optionId);
            if (!input || input.disabled) return;

            SELECTED_PRODUCT_OPTION_ID = optionId;

            if (!input.checked) {
                input.checked = true;
                input.dispatchEvent(new Event('change', { bubbles: true }));
                return;
            }

            syncProductOptionCards();
            updateAddToCartState();
        }

        function updateAddToCartState() {
            var button = document.getElementById('add-to-cart-btn');
            var label = document.getElementById('add-to-cart-label');
            var help = document.getElementById('product-option-help');

            if (!button || !label) return;

            if (!PRODUCT_IN_STOCK) {
                button.disabled = true;
                label.textContent = 'نفدت الكمية';
                if (help) help.textContent = 'هذا المنتج غير متوفر حاليا.';
                return;
            }

            if (!PRODUCT_HAS_OPTIONS) {
                button.disabled = false;
                label.textContent = 'أضف للسلة';
                return;
            }

            var selectedOption = getSelectedOption();
            if (!selectedOption) {
                button.disabled = true;
                label.textContent = 'اختر خياراً متوفراً';
                if (help) help.textContent = 'يلزم اختيار خيار متوفر قبل الإضافة إلى السلة.';
                return;
            }

            if (selectedOption.maxQuantity <= 0) {
                button.disabled = true;
                label.textContent = 'الخيار غير متوفر';
                if (help) help.textContent = 'الخيار المحدد غير متوفر حالياً.';
                return;
            }

            button.disabled = false;
            label.textContent = 'أضف الخيار المحدد للسلة';
            if (help) help.textContent = 'الخيار المحدد متوفر ويمكن إضافته الآن.';
        }

        function addProductToCart() {
            if (!PRODUCT_IN_STOCK) {
                showCartToast('هذا المنتج غير متوفر حالياً.', false);
                return;
            }

            var selectedOption = null;
            var available = PRODUCT_TOTAL_STOCK;

            if (PRODUCT_HAS_OPTIONS) {
                selectedOption = getSelectedOption();
                if (!selectedOption) {
                    showCartToast('اختر خياراً متوفراً قبل الإضافة إلى السلة.', false);
                    return;
                }

                if (selectedOption.maxQuantity <= 0) {
                    showCartToast('الخيار المحدد غير متوفر حالياً.', false);
                    return;
                }

                available = selectedOption.maxQuantity;
            }

            var cart = [];
            var savedCart = safeStorageGet(STORAGE_KEY);
            if (savedCart === null) {
                cart = normalizeCart(FALLBACK_CART);
            } else {
                try {
                    cart = normalizeCart(JSON.parse(savedCart) || []);
                } catch (e) {
                    cart = normalizeCart(FALLBACK_CART);
                }
            }

            var cartKey = makeCartKey(PRODUCT_ID, selectedOption ? selectedOption.option_id : null);
            var existing = cart.find(function (item) { return item.cart_key === cartKey; });

            if (existing) {
                if (existing.quantity >= available) {
                    showCartToast('لا يمكن طلب أكثر من الكمية المتاحة لهذا الخيار.', false);
                    return;
                }

                existing.quantity += 1;
                existing.maxQuantity = available;
            } else {
                cart.push({
                    id: PRODUCT_ID,
                    product_id: PRODUCT_ID,
                    name: PRODUCT_NAME,
                    unit_price_usd: PRODUCT_PRICE_USD,
                    image: PRODUCT_IMG,
                    quantity: 1,
                    option_id: selectedOption ? selectedOption.option_id : null,
                    option_label: selectedOption ? selectedOption.option_label : null,
                    cart_key: cartKey,
                    maxQuantity: available,
                });
            }

            FALLBACK_CART = normalizeCart(cart);
            safeStorageSet(STORAGE_KEY, JSON.stringify(FALLBACK_CART));

            var button = document.getElementById('add-to-cart-btn');
            button.disabled = true;
            button.style.opacity = '0.7';

            var toastMessage = selectedOption
                ? 'تمت إضافة "' + PRODUCT_NAME + '" - ' + selectedOption.option_label + ' إلى السلة'
                : 'تمت إضافة "' + PRODUCT_NAME + '" إلى السلة';

            showCartToast(toastMessage, true);
        }

        document.addEventListener('DOMContentLoaded', function () {
            var prechecked = document.querySelector('input[name="product_option"]:checked');
            if (prechecked) {
                SELECTED_PRODUCT_OPTION_ID = parseInt(prechecked.getAttribute('data-option-id'), 10);
            }

            syncProductOptionCards();
            updateAddToCartState();
        });
    </script>

</body>
</html>
