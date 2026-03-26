<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $shop->name }} | محلي</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
        
        <style>
            body { font-family: 'Tajawal', sans-serif !important; }
            [x-cloak] { display: none !important; }
            
            .text-gold { color: #d4af37; }
            .bg-gold { background-color: #d4af37; }
            .border-gold { border-color: #d4af37; }
            .hover-bg-gold:hover { background-color: #c5a017; }

            .hero-glass {
                background: rgba(17, 24, 39, 0.7);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .logo-watermark {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                opacity: 0.05;
                filter: grayscale(100%) brightness(200%);
                pointer-events: none;
                width: 60%;
                max-width: 500px;
                z-index: 0;
            }

            @keyframes cart-bounce {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.2); }
            }
            .animate-cart { animation: cart-bounce 0.5s ease-in-out; }

            @keyframes fade-out-up {
                0% { opacity: 1; transform: translateY(0); }
                100% { opacity: 0; transform: translateY(-20px); }
            }
            .animate-toast { animation: fade-out-up 2s forwards; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#0f1115] text-gray-100 antialiased" x-data="shoppingCart('{{ $shop->slug }}')">
        
        <!-- Toast Notification -->
        <div x-show="showToast" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed bottom-10 left-10 z-[100] bg-indigo-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 font-bold"
             x-cloak>
            <svg class="w-6 h-6 border-2 border-white rounded-full p-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            <span x-text="toastMessage"></span>
        </div>

        <!-- Navbar -->
        <nav class="bg-gray-900/80 backdrop-blur-md border-b border-gray-800 sticky top-0 z-40 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <div class="relative">
                            @if($shop->logo_path)
                                <img src="{{ asset('storage/' . $shop->logo_path) }}" alt="{{ $shop->name }}" class="h-12 w-12 rounded-2xl object-cover border-2 border-indigo-500/30 shadow-lg shadow-indigo-500/10">
                            @else
                                <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center text-white font-black text-xl shadow-lg">
                                    {{ mb_substr($shop->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="mx-4">
                            <h1 class="text-xl font-black text-white tracking-tight">{{ $shop->name }}</h1>
                            <span class="text-[10px] text-indigo-400 font-bold tracking-widest uppercase">محلي ستور</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button @click="isCartOpen = true" 
                                :class="{ 'animate-cart': cartAnimating }"
                                class="relative group p-3 bg-gray-800/50 hover:bg-indigo-600 border border-gray-700 hover:border-indigo-500 rounded-2xl transition-all duration-300 shadow-sm">
                            <svg class="w-6 h-6 text-gray-300 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span x-show="totalItems > 0" x-text="totalItems" x-cloak class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[22px] h-[22px] px-1 text-[10px] font-black leading-none text-white bg-red-600 rounded-full border-2 border-gray-900 shadow-lg transition-transform" :class="cartAnimating ? 'scale-125' : ''"></span>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-gray-900 h-[400px] flex items-center justify-center">
            @if($shop->hero_image_path)
                <img src="{{ asset('storage/' . $shop->hero_image_path) }}" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-b from-gray-900/60 via-gray-900/40 to-[#0f1115]"></div>
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/40 via-gray-900 to-purple-900/40 opacity-50"></div>
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
            @endif

            @if($shop->logo_path)
                <img src="{{ asset('storage/' . $shop->logo_path) }}" class="logo-watermark">
            @endif

            <div class="relative z-10 max-w-4xl mx-auto text-center px-6">
                <div class="mb-6 inline-block">
                    @if($shop->logo_path)
                        <img src="{{ asset('storage/' . $shop->logo_path) }}" class="h-24 w-24 mx-auto rounded-3xl object-cover border-4 border-white/10 shadow-2xl backdrop-blur-sm">
                    @endif
                </div>
                <h2 class="text-4xl md:text-6xl font-black text-white mb-6 drop-shadow-2xl">
                    {{ $shop->name }}
                </h2>
                @if($shop->description)
                    <div class="hero-glass rounded-2xl p-6 max-w-2xl mx-auto">
                        <p class="text-gray-200 text-lg md:text-xl font-medium leading-relaxed drop-shadow-md">
                            {{ $shop->description }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative">
            
            @if(session('success'))
                <div class="mb-8">
                    <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-2xl flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="font-bold">تهانينا! {{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Categories Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 border-b border-gray-800 pb-8">
                <div>
                    <h2 class="text-4xl font-black text-white mb-2">تسوق منتجاتنا</h2>
                    <p class="text-gray-500 font-medium">اختر من تشكيلة واسعة من المنتجات المميزة</p>
                </div>
                
                @if($categories->count() > 0)
                <div class="flex flex-wrap gap-2" x-data="{ activeCategory: 'all' }">
                    <button @click="activeCategory = 'all'; $dispatch('filter-category', 'all')" 
                            :class="activeCategory === 'all' ? 'bg-indigo-600 text-white shadow-indigo-500/20' : 'bg-gray-800 text-gray-400 hover:bg-gray-700'"
                            class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 shadow-sm border border-transparent">
                        الكل
                    </button>
                    @foreach($categories as $cat)
                    <button @click="activeCategory = '{{ $cat }}'; $dispatch('filter-category', '{{ $cat }}')"
                            :class="activeCategory === '{{ $cat }}' ? 'bg-indigo-600 text-white shadow-indigo-500/20' : 'bg-gray-800 text-gray-400 hover:bg-gray-700'"
                            class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 shadow-sm border border-transparent">
                        {{ $cat }}
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            @if($shop->products->isEmpty())
                <div class="text-center py-32 bg-gray-900 border-2 border-dashed border-gray-800 rounded-3xl">
                    <div class="bg-gray-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-600">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <p class="text-gray-400 text-xl font-bold">لا توجد منتجات حالياً.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" x-data="{ currentFilter: 'all' }" @filter-category.window="currentFilter = $event.detail">
                    @foreach($shop->products as $product)
                        @php
                            $isDiscounted = $product->hasActiveDiscount();
                            $currentPrice = $product->effectivePrice();
                        @endphp
                        <div x-show="currentFilter === 'all' || currentFilter === '{{ $product->category }}'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="bg-gray-900 border border-gray-800 rounded-[2.5rem] shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 hover:border-gray-700 transition-all duration-500 overflow-hidden group flex flex-col h-full relative">
                            
                            @if($isDiscounted)
                                <div class="absolute top-6 left-6 z-10 bg-red-600 text-white text-[10px] font-black px-3 py-1.5 rounded-full shadow-xl shadow-red-600/20 uppercase tracking-tighter">
                                    وفر {{ number_format($product->discount_percent, 0) }}%
                                </div>
                            @endif

                            <div class="relative w-full h-72 overflow-hidden bg-gray-800/50">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-[1.5s] ease-out">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-700 bg-gray-800/50">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-0 group-hover:opacity-60 transition-opacity duration-500"></div>
                            </div>

                            <div class="p-8 flex flex-col flex-grow relative">
                                @if($product->category)
                                    <span class="text-indigo-400 text-[10px] font-black uppercase tracking-widest mb-2 block">{{ $product->category }}</span>
                                @endif
                                <h3 class="text-xl font-extrabold text-white mb-3 group-hover:text-indigo-400 transition-colors duration-300 leading-tight">{{ $product->name }}</h3>
                                
                                <div class="mb-6">
                                    @if($isDiscounted)
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl font-black text-white">{{ number_format($currentPrice, 2) }} <span class="text-xs font-medium text-gray-400">ل.س</span></span>
                                            <span class="line-through text-gray-600 text-sm">{{ number_format($product->price, 0) }}</span>
                                        </div>
                                    @else
                                        <span class="text-2xl font-black text-white">{{ number_format($product->price, 2) }} <span class="text-xs font-medium text-gray-400">ل.س</span></span>
                                    @endif
                                </div>

                                <button @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $currentPrice }}, '{{ $product->image_path ? asset('storage/' . $product->image_path) : '' }}')" 
                                        class="mt-auto w-full group/btn relative overflow-hidden bg-white text-black font-black py-4 px-6 rounded-2xl hover:bg-indigo-600 hover:text-white transition-all duration-300 flex items-center justify-center gap-3 shadow-xl">
                                    <svg class="w-5 h-5 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    <span>أضف للسلة</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 border-t border-gray-800 py-12 mt-20">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="bg-gray-800 p-2 rounded-lg text-white">
                        <x-application-logo class="h-6 w-auto fill-current" />
                    </div>
                    <span class="text-lg font-black text-white">محلي</span>
                </div>
                <p class="text-gray-500 text-sm">جميع الحقوق محفوظة &copy; {{ date('Y') }} {{ $shop->name }}</p>
                <p class="text-[10px] text-gray-700 mt-2 uppercase tracking-[0.2em]">بواسطة محلي للتجارة الإلكترونية</p>
            </div>
        </footer>

        <!-- Cart Slide-over -->
        <div x-show="isCartOpen" class="fixed inset-0 z-50 overflow-hidden" x-cloak>
            <div class="absolute inset-0 overflow-hidden">
                <div x-show="isCartOpen" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity shadow-2xl" @click="isCartOpen = false"></div>

                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-0 sm:pl-10">
                    <div x-show="isCartOpen" 
                         x-transition:enter="transform transition ease-out duration-500" 
                         x-transition:enter-start="translate-x-full" 
                         x-transition:enter-end="translate-x-0" 
                         x-transition:leave="transform transition ease-in duration-500" 
                         x-transition:leave-start="translate-x-0" 
                         x-transition:leave-end="translate-x-full" 
                         class="pointer-events-auto w-screen max-w-md">
                        <div class="flex h-full flex-col bg-[#16181d] shadow-2xl border-l border-gray-800">
                            <div class="flex-1 overflow-y-auto px-6 py-8">
                                <div class="flex items-center justify-between mb-10">
                                    <h2 class="text-2xl font-black text-white">حقيبة التسوق</h2>
                                    <button type="button" class="p-2 bg-gray-800 text-gray-400 hover:text-white rounded-xl transition" @click="isCartOpen = false">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                <div class="space-y-6">
                                    <template x-if="cart.length === 0">
                                        <div class="text-center py-20 bg-gray-900/50 rounded-3xl border border-gray-800">
                                            <p class="text-gray-500 font-bold">العربة فارغة حالياً</p>
                                        </div>
                                    </template>
                                    <ul class="space-y-4">
                                        <template x-for="item in cart" :key="item.id">
                                            <li class="flex gap-4 p-4 bg-gray-900/50 rounded-[1.5rem] border border-gray-800/50">
                                                <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl bg-gray-800">
                                                    <img :src="item.image" class="h-full w-full object-cover" x-show="item.image">
                                                    <div class="h-full w-full flex items-center justify-center text-gray-600" x-show="!item.image">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                </div>
                                                <div class="flex flex-1 flex-col justify-between">
                                                    <div class="flex justify-between items-start">
                                                        <h3 class="text-sm font-black text-white" x-text="item.name"></h3>
                                                        <p class="text-sm font-bold text-indigo-400" x-text="number_format(item.price * item.quantity, 2) + ' ل.س'"></p>
                                                    </div>
                                                    <div class="flex items-center justify-between text-xs pt-2">
                                                        <div class="flex items-center bg-gray-800 rounded-lg p-1">
                                                            <button @click="updateQuantity(item.id, item.quantity - 1)" class="w-6 h-6 rounded-md bg-gray-700 text-white font-black">-</button>
                                                            <span class="px-3 font-black text-gray-300" x-text="item.quantity"></span>
                                                            <button @click="updateQuantity(item.id, item.quantity + 1)" class="w-6 h-6 rounded-md bg-gray-700 text-white font-black">+</button>
                                                        </div>
                                                        <button @click="removeFromCart(item.id)" class="text-red-500 font-bold hover:text-red-400 transition">إزالة</button>
                                                    </div>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>

                            <div class="px-6 py-8 bg-[#1a1d24] border-t border-gray-800">
                                <div class="space-y-4 mb-8" x-show="cart.length > 0">
                                    <div x-data="{ openPromo: false }">
                                        <button @click="openPromo = !openPromo" class="flex items-center gap-2 text-indigo-400 text-xs font-black uppercase tracking-widest hover:text-indigo-300 transition mb-3">
                                            <span>أضف كود خصم</span>
                                            <svg class="w-4 h-4 transition-transform" :class="openPromo ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </button>
                                        <div x-show="openPromo" x-transition class="flex gap-2">
                                            <input type="text" x-model="promoInput" x-bind:disabled="promoApplied" class="flex-1 bg-gray-900 border-gray-700 rounded-xl px-4 py-3 text-sm font-bold text-white uppercase placeholder-gray-600 focus:ring-2 focus:ring-indigo-600 transition" placeholder="PROMO20">
                                            <button @click="applyPromo" x-show="!promoApplied" class="bg-indigo-600 px-6 py-3 rounded-xl text-sm font-black text-white hover:bg-indigo-500 transition">تطبيق</button>
                                            <button @click="removePromo" x-show="promoApplied" class="bg-red-600 px-6 py-3 rounded-xl text-sm font-black text-white hover:bg-red-500 transition">إلغاء</button>
                                        </div>
                                        <p x-show="promoMessage" x-text="promoMessage" :class="promoApplied ? 'text-green-400' : 'text-red-400'" class="text-[10px] mt-2 font-black uppercase tracking-widest"></p>
                                    </div>

                                    <div class="flex justify-between items-center text-sm font-bold border-t border-gray-800 pt-6">
                                        <span class="text-gray-500">المجموع الكلي</span>
                                        <div class="text-right">
                                            <p x-show="promoApplied" class="line-through text-gray-600 text-xs mb-1" x-text="number_format(cartTotal, 2)"></p>
                                            <p class="text-2xl font-black text-white tracking-tighter" x-text="number_format(finalTotal, 2) + ' ل.س'"></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <button @click="isCheckoutOpen = true; isCartOpen = false" x-bind:disabled="cart.length === 0" class="w-full bg-indigo-600 disabled:bg-gray-800 text-white font-black py-5 rounded-2xl shadow-xl shadow-indigo-600/20 hover:bg-indigo-500 transition-all duration-300 transform active:scale-[0.98]">
                                    المتابعة لإنهاء الطلب
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Form Modal -->
        <div x-show="isCheckoutOpen" class="fixed inset-0 z-[60] overflow-y-auto px-4 py-8" x-cloak>
            <div class="flex items-center justify-center min-h-screen">
                <div x-show="isCheckoutOpen" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-md" @click="isCheckoutOpen = false"></div>

                <div x-show="isCheckoutOpen" 
                    x-transition:enter="transform transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-12"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="relative bg-[#0f1115] border border-gray-800 p-8 sm:p-12 rounded-[3rem] shadow-2xl max-w-xl w-full">
                    
                    <form action="{{ route('shop.checkout', $shop->slug) }}" method="POST" @submit="submitCheckout">
                        @csrf
                        <input type="hidden" name="cart" x-model="JSON.stringify(cart)">
                        <input type="hidden" name="promo_code" x-model="promoInput" x-bind:disabled="!promoApplied">
                        
                        <div class="mb-10 text-center">
                            <h3 class="text-3xl font-black text-white mb-2">إتمام الطلب</h3>
                            <p class="text-gray-500 font-medium italic">يرجى تعبئة بيانات التوصيل بدقة</p>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] px-1">الاسم الكامل</label>
                                <input type="text" name="buyer_name" required class="w-full bg-gray-900 border-gray-800 rounded-2xl px-5 py-4 text-white font-bold placeholder-gray-700 focus:ring-2 focus:ring-indigo-600 transition" placeholder="محمد السوري">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] px-1">رقم الهاتف</label>
                                    <input type="tel" name="buyer_phone" required dir="ltr" class="w-full bg-gray-900 border-gray-800 rounded-2xl px-5 py-4 text-white font-bold text-left placeholder-gray-700 focus:ring-2 focus:ring-indigo-600 transition" placeholder="+963 --- ---">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] px-1">البريد (اختياري)</label>
                                    <input type="email" name="buyer_email" dir="ltr" class="w-full bg-gray-900 border-gray-800 rounded-2xl px-5 py-4 text-white font-bold text-left placeholder-gray-700 focus:ring-2 focus:ring-indigo-600 transition" placeholder="mail@example.com">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] px-1">عنوان التوصيل</label>
                                <textarea name="buyer_address" required rows="3" class="w-full bg-gray-900 border-gray-800 rounded-2xl px-5 py-4 text-white font-bold placeholder-gray-700 focus:ring-2 focus:ring-indigo-600 transition resize-none" placeholder="المحافظة، المنطقة، أقرب نقطة دالة..."></textarea>
                            </div>
                        </div>

                        <div class="mt-10 p-6 bg-gray-900 rounded-3xl border border-gray-800">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs text-gray-500 font-bold">الدفع</span>
                                <span class="text-xs text-white font-black">💵 عند الاستلام</span>
                            </div>
                            <div class="flex justify-between items-center border-t border-gray-800 pt-4">
                                <span class="text-lg font-black text-white">المجموع</span>
                                <span class="text-3xl font-black text-indigo-500" x-text="number_format(finalTotal, 2) + ' ل.س'"></span>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col gap-3">
                            <button type="submit" class="w-full bg-white text-black font-black py-5 rounded-2xl shadow-2xl hover:bg-gray-200 transition-all transform active:scale-95">
                                ✅ إرسال الطلب الآن
                            </button>
                            <button type="button" @click="isCheckoutOpen = false; isCartOpen = true" class="w-full text-gray-500 font-bold py-3 hover:text-white transition">
                                إلغاء والعودة للسلة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function number_format(number, decimals = 2) {
                return parseFloat(number).toFixed(decimals).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

            document.addEventListener('alpine:init', () => {
                Alpine.data('shoppingCart', (shopSlug) => ({
                    isCartOpen: false,
                    isCheckoutOpen: false,
                    cart: [],
                    promoInput: '',
                    promoApplied: false,
                    promoMessage: '',
                    discountPercentage: 0,
                    
                    // Toast State
                    showToast: false,
                    toastMessage: '',
                    cartAnimating: false,
                    
                    init() {
                        const savedCart = localStorage.getItem('mahly_cart_' + shopSlug);
                        if (savedCart) {
                            try {
                                this.cart = JSON.parse(savedCart);
                            } catch (e) {
                                this.cart = [];
                            }
                        }
                        
                        this.$watch('cart', value => {
                            localStorage.setItem('mahly_cart_' + shopSlug, JSON.stringify(value));
                            if (value.length === 0) {
                                this.removePromo();
                            }
                        });
                    },
                    
                    get totalItems() {
                        return this.cart.reduce((total, item) => total + item.quantity, 0);
                    },
                    
                    get cartTotal() {
                        return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                    },

                    get finalTotal() {
                        if (this.promoApplied && this.discountPercentage > 0) {
                            return this.cartTotal - (this.cartTotal * (this.discountPercentage / 100));
                        }
                        return this.cartTotal;
                    },
                    
                    addToCart(id, name, price, image) {
                        const existingItem = this.cart.find(item => item.id === id);
                        if (existingItem) {
                            existingItem.quantity += 1;
                        } else {
                            this.cart.push({
                                id: id,
                                name: name,
                                price: parseFloat(price),
                                image: image,
                                quantity: 1
                            });
                        }
                        
                        // Interaction: Show Toast and Animate Cart instead of opening it
                        this.toastMessage = `تم إضافة "${name}" إلى السلة`;
                        this.showToast = true;
                        this.cartAnimating = true;
                        
                        setTimeout(() => {
                            this.showToast = false;
                        }, 3000);
                        
                        setTimeout(() => {
                            this.cartAnimating = false;
                        }, 500);
                    },
                    
                    updateQuantity(id, quantity) {
                        if (quantity < 1) {
                            this.removeFromCart(id);
                            return;
                        }
                        const item = this.cart.find(item => item.id === id);
                        if (item) {
                            item.quantity = quantity;
                        }
                    },
                    
                    removeFromCart(id) {
                        this.cart = this.cart.filter(item => item.id !== id);
                        if (this.cart.length === 0) {
                            this.isCheckoutOpen = false;
                        }
                    },

                    async applyPromo() {
                        if (!this.promoInput.trim()) return;
                        
                        this.promoMessage = 'جاري التحقق...';
                        try {
                            const response = await fetch(`/shop/${shopSlug}/apply-promo`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ code: this.promoInput })
                            });
                            
                            const data = await response.json();
                            if (data.valid) {
                                this.promoApplied = true;
                                this.discountPercentage = data.discount_percentage;
                                this.promoMessage = data.message;
                            } else {
                                this.promoApplied = false;
                                this.discountPercentage = 0;
                                this.promoMessage = data.message || 'الكود غير صحيح';
                            }
                        } catch (error) {
                            this.promoMessage = 'حدث خطأ في الاتصال.';
                        }
                    },

                    removePromo() {
                        this.promoApplied = false;
                        this.promoInput = '';
                        this.discountPercentage = 0;
                        this.promoMessage = '';
                    },

                    submitCheckout(e) {
                        if (this.cart.length === 0) {
                            e.preventDefault();
                            alert("عربة التسوق فارغة!");
                            return false;
                        }
                        localStorage.removeItem('mahly_cart_' + shopSlug);
                    }
                }));
            });
        </script>
    </body>
</html>
