<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-lg text-[#0d1b4b] tracking-tight">
            {{ __('لوحة التحكم') }}
        </h2>
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </x-slot>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">

    <div class="py-10 px-4 sm:px-6 lg:px-8" x-data="logoCropper('{{ auth()->user()->shop->slug ?? '' }}')">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- ── Success Alert ───────────────────────────────────────── --}}
            @if(session('success'))
                <div class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-green-50 border border-green-200 text-green-800 text-sm font-semibold shadow-sm">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(auth()->user()->isSeller())
                @if(!auth()->user()->shop)

                    {{-- ════════════════════════════════════════════════════ --}}
                    {{--  SHOP SETUP WIZARD                                  --}}
                    {{-- ════════════════════════════════════════════════════ --}}
                    <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl shadow-xl shadow-[#0d1b4b]/6 overflow-hidden">

                        {{-- Card header band --}}
                        <div class="px-8 pt-8 pb-6 border-b border-[#0d1b4b]/8">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#d4af37]/10 border border-[#d4af37]/25 text-[#a07c1e] text-[11px] font-bold tracking-widest uppercase mb-4">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#d4af37]"></span>
                                خطوتك الأولى
                            </div>
                            <h3 class="text-2xl font-black text-[#0d1b4b]">أهلاً بك في <span class="text-transparent bg-clip-text bg-gradient-to-l from-[#d4af37] to-[#b8922a]">محلي!</span></h3>
                            <p class="mt-1.5 text-[#0d1b4b]/50 text-sm">لبدء البيع، يرجى إعداد تفاصيل متجرك الإلكتروني أدناه.</p>
                        </div>

                        <div class="p-8">
                            <form method="POST" action="{{ route('shop.store') }}" enctype="multipart/form-data" class="space-y-6">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="name" :value="__('اسم المتجر')" />
                                        <x-text-input id="name" class="block mt-1.5 w-full" type="text" name="name"
                                            placeholder="مثال: متجر العطور الأصيلة"
                                            required autofocus x-model="name" @input="updateSlug" />
                                        <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                                    </div>
                                    <div>
                                        <x-input-label for="slug" :value="__('رابط المتجر (بالإنجليزية)')" />
                                        <x-text-input id="slug" class="block mt-1.5 w-full font-mono text-sm" type="text" name="slug"
                                            placeholder="my-shop-name"
                                            required x-model="slug" @input="manualSlug = true" />
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            <p class="text-[11px] text-[#0d1b4b]/40" dir="ltr">{{ url('/shop') }}/<span x-text="slug" class="text-[#0d1b4b]/70 font-semibold"></span></p>
                                            <template x-if="isCheckingSlug">
                                                <span class="text-[10px] text-[#0d1b4b]/40 animate-pulse font-medium">جاري التحقق...</span>
                                            </template>
                                            <template x-if="!isCheckingSlug && slug && !slugAvailable">
                                                <span class="inline-flex items-center gap-1 text-[10px] text-red-500 font-bold">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                                    مستخدم بالفعل
                                                </span>
                                            </template>
                                            <template x-if="!isCheckingSlug && slug && slugAvailable">
                                                <span class="inline-flex items-center gap-1 text-[10px] text-green-600 font-bold">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                    متاح
                                                </span>
                                            </template>
                                        </div>
                                        <x-input-error :messages="$errors->get('slug')" class="mt-1.5" />
                                    </div>
                                </div>

                                {{-- Hero Image --}}
                                <div>
                                    <x-input-label for="hero_image" :value="__('صورة الغلاف')" />
                                    <div class="mt-1.5">
                                        <label for="hero_image" class="flex items-center gap-3 px-4 py-3 rounded-xl border border-[#0d1b4b]/15 bg-white cursor-pointer hover:border-[#d4af37]/50 hover:bg-[#fdfbf4] transition-all duration-200">
                                            <div class="w-8 h-8 rounded-lg bg-[#0d1b4b]/6 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-[#0d1b4b]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            <span class="text-sm text-[#0d1b4b]/45">اختر صورة الغلاف...</span>
                                            <input id="hero_image" type="file" name="hero_image" accept="image/*" class="hidden" />
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('hero_image')" class="mt-1.5" />
                                </div>

                                {{-- Description --}}
                                <div>
                                    <x-input-label for="description" :value="__('وصف المتجر (اختياري)')" />
                                    <textarea id="description" name="description" rows="3"
                                        placeholder="اكتب وصفاً موجزاً لمتجرك..."
                                        class="block mt-1.5 w-full bg-white border border-[#0d1b4b]/15 text-[#0d1b4b] placeholder-[#0d1b4b]/30
                                               focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 rounded-xl shadow-sm py-2.5 px-4
                                               outline-none transition-all duration-200 resize-none"></textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-1.5" />
                                </div>

                                <x-shop-theme-picker
                                    name="color"
                                    :selected="old('color', 'navy')"
                                />

                                {{-- Logo with Cropper --}}
                                <div>
                                    <x-input-label for="logo_wizard" :value="__('شعار المتجر (اختياري)')" />
                                    <div class="mt-1.5">
                                        <label for="logo_wizard" class="flex items-center gap-3 px-4 py-3 rounded-xl border border-[#0d1b4b]/15 bg-white cursor-pointer hover:border-[#d4af37]/50 hover:bg-[#fdfbf4] transition-all duration-200">
                                            <div class="w-8 h-8 rounded-lg bg-[#d4af37]/10 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-[#a07c1e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                            </div>
                                            <span class="text-sm text-[#0d1b4b]/45">اختر شعار المتجر...</span>
                                            <input id="logo_wizard" type="file" name="logo" accept="image/*" class="hidden" @change="loadFile" />
                                        </label>
                                    </div>
                                    <input type="hidden" name="cropped_logo" :value="croppedData">
                                    <x-input-error :messages="$errors->get('logo')" class="mt-1.5" />

                                    {{-- Logo preview --}}
                                    <div x-show="croppedData" class="mt-4 flex items-center gap-4" x-cloak>
                                        <img :src="croppedData" class="w-20 h-20 rounded-2xl object-cover border-2 border-[#d4af37]/40 shadow-md" alt="معاينة الشعار">
                                        <div>
                                            <p class="text-sm font-bold text-[#0d1b4b]">معاينة الشعار</p>
                                            <button type="button" @click="croppedData = ''; document.getElementById('logo_wizard').value = ''"
                                                class="mt-1 text-xs text-red-500 hover:text-red-700 font-semibold transition-colors">
                                                إزالة الشعار
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <button type="submit"
                                        x-bind:disabled="!slugAvailable || isCheckingSlug"
                                        class="group relative px-8 py-3.5 bg-[#d4af37] text-[#0d1b4b] font-black rounded-2xl
                                               hover:bg-[#c5a02e] active:scale-[0.98] transition-all duration-200
                                               shadow-lg shadow-[#d4af37]/25 overflow-hidden
                                               disabled:opacity-40 disabled:cursor-not-allowed disabled:active:scale-100">
                                        <span class="relative z-10">إنشاء المتجر وحفظ البيانات</span>
                                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 rounded-2xl"></div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                @else
                    {{-- ════════════════════════════════════════════════════ --}}
                    {{--  SELLER DASHBOARD                                   --}}
                    {{-- ════════════════════════════════════════════════════ --}}
                    @php
                        $shop = auth()->user()->shop;
                        $totalCategories = $shop->categories()->count();
                        $totalProducts = $shop->products()->count();
                        $totalOrders   = $shop->orders()->count();
                        $totalRevenue  = $shop->orders()->where('status', 'completed')->sum('total_amount');
                        $needsCategoryOnboarding = $totalCategories === 0;
                        $needsProductOnboarding = $totalProducts === 0;
                        $showSellerOnboarding = $needsCategoryOnboarding || $needsProductOnboarding;
                    @endphp

                    {{-- Shop header --}}
                    <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl shadow-xl shadow-[#0d1b4b]/6 p-8">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                            <div>
                                <p class="text-[11px] text-[#0d1b4b]/40 uppercase tracking-widest font-bold mb-1">متجرك النشط</p>
                                <h3 class="text-2xl font-black text-[#0d1b4b]">{{ $shop->name }}</h3>
                                <a href="{{ url('/shop/' . $shop->slug) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 mt-1.5 text-sm text-[#d4af37] hover:text-[#b8922a] font-semibold transition-colors" dir="ltr">
                                    {{ url('/shop/' . $shop->slug) }}
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-50 border border-green-200">
                                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                <span class="text-xs text-green-700 font-bold">متجر نشط</span>
                            </div>
                        </div>

                        @if($showSellerOnboarding)
                            <div class="mb-8 rounded-2xl border border-[#d4af37]/35 bg-[#fff9e8] p-5">
                                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-widest text-[#a07c1e]">دليل إعداد المتجر</p>
                                        <h4 class="mt-1 text-lg font-black text-[#0d1b4b]">ابدأ من هنا: التصنيفات ثم المنتجات</h4>
                                        <p class="mt-1 text-sm text-[#0d1b4b]/65">أنشئ التصنيفات أولاً حتى تظهر منتجاتك بشكل منظم وأسهل للتصفح.</p>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#0d1b4b] px-4 py-2 text-sm font-bold text-white hover:bg-[#1a2d6b] transition-all">
                                            {{ $needsCategoryOnboarding ? 'إنشاء التصنيفات' : 'إدارة التصنيفات' }}
                                        </a>
                                        <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-[#0d1b4b]/20 bg-white px-4 py-2 text-sm font-bold text-[#0d1b4b] hover:bg-[#f8faff] transition-all">
                                            {{ $needsProductOnboarding ? 'إضافة أول منتج' : 'إضافة منتج' }}
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2">
                                    <div class="rounded-xl border px-3 py-2 text-sm {{ $needsCategoryOnboarding ? 'border-amber-300 bg-amber-50 text-amber-900' : 'border-green-200 bg-green-50 text-green-800' }}">
                                        {{ $needsCategoryOnboarding ? 'الخطوة 1: أنشئ تصنيفًا واحدًا على الأقل.' : 'الخطوة 1 مكتملة: تم إضافة التصنيفات.' }}
                                    </div>
                                    <div class="rounded-xl border px-3 py-2 text-sm {{ $needsProductOnboarding ? 'border-amber-300 bg-amber-50 text-amber-900' : 'border-green-200 bg-green-50 text-green-800' }}">
                                        {{ $needsProductOnboarding ? 'الخطوة 2: أضف أول منتج في متجرك.' : 'الخطوة 2 مكتملة: تم إضافة المنتجات.' }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Stats --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="p-6 rounded-2xl bg-[#0d1b4b]/4 border border-[#0d1b4b]/8 text-center hover:bg-[#0d1b4b]/6 transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-[#0d1b4b]/8 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-5 h-5 text-[#0d1b4b]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <p class="text-3xl font-black text-[#0d1b4b]">{{ $totalProducts }}</p>
                                <p class="text-xs text-[#0d1b4b]/45 font-medium mt-1">إجمالي المنتجات</p>
                            </div>
                            <div class="p-6 rounded-2xl bg-green-50 border border-green-100 text-center hover:bg-green-100/60 transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <p class="text-3xl font-black text-green-800">{{ $totalOrders }}</p>
                                <p class="text-xs text-green-700/60 font-medium mt-1">إجمالي الطلبات</p>
                            </div>
                            <div class="p-6 rounded-2xl bg-[#d4af37]/8 border border-[#d4af37]/20 text-center hover:bg-[#d4af37]/12 transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-[#d4af37]/15 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-5 h-5 text-[#a07c1e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <p class="text-3xl font-black text-[#a07c1e]">{{ number_format($totalRevenue, 0) }}</p>
                                <p class="text-xs text-[#a07c1e]/60 font-medium mt-1">الأرباح المكتملة (ل.س)</p>
                            </div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="mt-8 pt-6 border-t border-[#0d1b4b]/8 flex flex-wrap gap-3">
                            <a href="{{ route('products.index') }}"
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0d1b4b] text-white text-sm font-bold rounded-xl hover:bg-[#1a2d6b] transition-all shadow-md shadow-[#0d1b4b]/20 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                المنتجات
                            </a>
                            <a href="{{ route('orders.index') }}"
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition-all shadow-md shadow-green-600/20 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                الطلبات
                            </a>
                            <a href="{{ route('promo-codes.index') }}"
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#d4af37]/10 border border-[#d4af37]/30 text-[#a07c1e] text-sm font-bold rounded-xl hover:bg-[#d4af37]/20 hover:border-[#d4af37]/50 transition-all active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                الكوبونات
                            </a>
                            <a href="{{ route('categories.index') }}"
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0d1b4b]/5 border border-[#0d1b4b]/12 text-[#0d1b4b]/70 text-sm font-bold rounded-xl hover:bg-[#0d1b4b]/8 hover:border-[#0d1b4b]/20 transition-all active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                التصنيفات
                            </a>
                        </div>
                    </div>

                    {{-- ── Edit Shop Settings ──────────────────────────────── --}}
                    <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl shadow-xl shadow-[#0d1b4b]/6 overflow-hidden">

                        <div class="px-8 pt-8 pb-6 border-b border-[#0d1b4b]/8 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#0d1b4b]/6 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#0d1b4b]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-[#0d1b4b]">إعدادات المتجر</h4>
                                <p class="text-xs text-[#0d1b4b]/40 mt-0.5">تحديث بيانات متجرك وهويته البصرية</p>
                            </div>
                        </div>

                        <div class="p-8">
                            <form method="POST" action="{{ route('shop.update') }}" enctype="multipart/form-data" class="space-y-6 max-w-4xl">
                                @csrf
                                @method('PATCH')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="edit_name" :value="__('اسم المتجر')" />
                                        <x-text-input id="edit_name" class="block mt-1.5 w-full" type="text" name="name"
                                            :value="old('name', $shop->name)" required x-model="name" />
                                        <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                                    </div>
                                    <div>
                                        <x-input-label for="edit_slug" :value="__('رابط المتجر (بالإنجليزية)')" />
                                        <x-text-input id="edit_slug" class="block mt-1.5 w-full font-mono text-sm" type="text" name="slug"
                                            :value="old('slug', $shop->slug)" required x-model="slug" @input="manualSlug = true" />
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            <p class="text-[11px] text-[#0d1b4b]/40" dir="ltr">{{ url('/shop') }}/<span x-text="slug" class="text-[#0d1b4b]/70 font-semibold"></span></p>
                                            <template x-if="isCheckingSlug">
                                                <span class="text-[10px] text-[#0d1b4b]/40 animate-pulse font-medium">جاري التحقق...</span>
                                            </template>
                                            <template x-if="!isCheckingSlug && slug && !slugAvailable">
                                                <span class="inline-flex items-center gap-1 text-[10px] text-red-500 font-bold">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                                    مستخدم بالفعل
                                                </span>
                                            </template>
                                            <template x-if="!isCheckingSlug && slug && slugAvailable && slug !== '{{ $shop->slug }}'">
                                                <span class="inline-flex items-center gap-1 text-[10px] text-green-600 font-bold">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                    متاح
                                                </span>
                                            </template>
                                        </div>
                                        <x-input-error :messages="$errors->get('slug')" class="mt-1.5" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Logo upload --}}
                                    <div>
                                        <x-input-label for="edit_logo" :value="__('تحديث الشعار')" />
                                        <div class="mt-1.5">
                                            <label for="edit_logo" class="flex items-center gap-3 px-4 py-3 rounded-xl border border-[#0d1b4b]/15 bg-white cursor-pointer hover:border-[#d4af37]/50 hover:bg-[#fdfbf4] transition-all duration-200">
                                                <div class="w-8 h-8 rounded-lg bg-[#d4af37]/10 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-4 h-4 text-[#a07c1e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                                </div>
                                                <span class="text-sm text-[#0d1b4b]/45">اختر شعاراً جديداً...</span>
                                                <input id="edit_logo" type="file" name="logo" accept="image/*" class="hidden" @change="loadFile" />
                                            </label>
                                        </div>
                                        <input type="hidden" name="cropped_logo" :value="croppedData">
                                        <x-input-error :messages="$errors->get('logo')" class="mt-1.5" />

                                        <div x-show="croppedData" class="mt-4 flex items-center gap-4" x-cloak>
                                            <img :src="croppedData" class="w-16 h-16 rounded-xl object-cover border-2 border-[#d4af37]/40 shadow-sm" alt="معاينة">
                                            <button type="button"
                                                @click="croppedData = ''; document.getElementById('edit_logo').value = ''"
                                                class="text-xs text-red-500 hover:text-red-700 font-semibold transition-colors">
                                                إزالة
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Hero image upload --}}
                                    <div>
                                        <x-input-label for="edit_hero" :value="__('تحديث صورة الغلاف')" />
                                        <div class="mt-1.5">
                                            <label for="edit_hero" class="flex items-center gap-3 px-4 py-3 rounded-xl border border-[#0d1b4b]/15 bg-white cursor-pointer hover:border-[#d4af37]/50 hover:bg-[#fdfbf4] transition-all duration-200">
                                                <div class="w-8 h-8 rounded-lg bg-[#0d1b4b]/6 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-4 h-4 text-[#0d1b4b]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                                <span class="text-sm text-[#0d1b4b]/45">اختر صورة الغلاف...</span>
                                                <input id="edit_hero" type="file" name="hero_image" accept="image/*" class="hidden" />
                                            </label>
                                        </div>
                                        <x-input-error :messages="$errors->get('hero_image')" class="mt-1.5" />
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div>
                                    <x-input-label for="edit_description" :value="__('وصف المتجر')" />
                                    <textarea id="edit_description" name="description" rows="3"
                                        class="block mt-1.5 w-full bg-white border border-[#0d1b4b]/15 text-[#0d1b4b] placeholder-[#0d1b4b]/30
                                               focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 rounded-xl shadow-sm py-2.5 px-4
                                               outline-none transition-all duration-200 resize-none">{{ old('description', $shop->description) }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-1.5" />
                                </div>

                                <x-shop-theme-picker
                                    name="color"
                                    :selected="old('color', $shop->color ?? 'navy')"
                                />

                                <div>
                                    <button type="submit"
                                        x-bind:disabled="!slugAvailable || isCheckingSlug"
                                        class="group relative px-8 py-3.5 bg-[#0d1b4b] text-white font-black rounded-2xl
                                               hover:bg-[#1a2d6b] active:scale-[0.98] transition-all duration-200
                                               shadow-lg shadow-[#0d1b4b]/20 overflow-hidden
                                               disabled:opacity-40 disabled:cursor-not-allowed disabled:active:scale-100">
                                        <span class="relative z-10">حفظ التغييرات</span>
                                        <div class="absolute inset-0 bg-white/5 translate-y-full group-hover:translate-y-0 transition-transform duration-300 rounded-2xl"></div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                @endif

            @else
                {{-- ════════════════════════════════════════════════════ --}}
                {{--  BUYER DASHBOARD                                    --}}
                {{-- ════════════════════════════════════════════════════ --}}
                <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl shadow-xl shadow-[#0d1b4b]/6 p-10 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#d4af37]/10 border border-[#d4af37]/20 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-[#a07c1e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <h3 class="text-2xl font-black text-[#0d1b4b] mb-2">أهلاً بك يا {{ auth()->user()->name }}!</h3>
                    <p class="text-[#0d1b4b]/50 max-w-sm mx-auto">تصفح المتاجر واكتشف منتجات جديدة على منصة محلي.</p>
                </div>
            @endif

        </div>
    </div>

    {{-- ── Cropper Modal ─────────────────────────────────────────────── --}}
    <div x-show="showCropper" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        {{-- Backdrop --}}
        <div x-show="showCropper"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-[#0d1b4b]/50 backdrop-blur-sm"
             @click="showCropper = false"></div>

        {{-- Modal panel --}}
        <div x-show="showCropper"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl shadow-[#0d1b4b]/20 border border-[#0d1b4b]/10 overflow-hidden">

            <div class="px-6 py-5 border-b border-[#0d1b4b]/8 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black text-[#0d1b4b]">اقتطاع الشعار</h3>
                    <p class="text-xs text-[#0d1b4b]/40 mt-0.5">حرك وكبّر الشعار ليناسب الإطار الدائري</p>
                </div>
                <button @click="showCropper = false"
                    class="w-8 h-8 rounded-xl bg-[#0d1b4b]/6 flex items-center justify-center hover:bg-[#0d1b4b]/10 transition-colors">
                    <svg class="w-4 h-4 text-[#0d1b4b]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6">
                <div class="w-full max-h-80 overflow-hidden bg-[#0d1b4b]/3 rounded-2xl flex justify-center items-center">
                    <img id="cropperImage" src="" alt="Source Image" class="max-w-full max-h-80">
                </div>
            </div>

            <div class="px-6 pb-6 flex gap-3 justify-end">
                <button type="button"
                    @click="showCropper = false; document.getElementById('logo_wizard') ? document.getElementById('logo_wizard').value = '' : null; document.getElementById('edit_logo') ? document.getElementById('edit_logo').value = '' : null"
                    class="px-5 py-2.5 rounded-xl border border-[#0d1b4b]/15 text-[#0d1b4b]/60 text-sm font-bold hover:border-[#0d1b4b]/25 hover:text-[#0d1b4b] transition-all">
                    إلغاء
                </button>
                <button type="button" @click="saveCrop"
                    class="px-5 py-2.5 rounded-xl bg-[#d4af37] text-[#0d1b4b] text-sm font-black hover:bg-[#c5a02e] transition-all shadow-md shadow-[#d4af37]/25">
                    تطبيق الشعار
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('logoCropper', (initialSlug = '') => ({
                showCropper: false,
                cropper: null,
                croppedData: '',
                name: {!! json_encode(auth()->user()->shop->name ?? '') !!},
                slug: initialSlug,
                manualSlug: false,
                initialSlug: initialSlug,
                slugAvailable: true,
                isCheckingSlug: false,
                slugTimeout: null,

                init() {
                    this.$watch('slug', () => {
                        this.checkSlugAvailability();
                    });
                },

                async checkSlugAvailability() {
                    const currentSlug = this.slug.trim();
                    if (!currentSlug) { this.slugAvailable = true; this.isCheckingSlug = false; return; }
                    if (currentSlug === this.initialSlug) { this.slugAvailable = true; this.isCheckingSlug = false; return; }

                    this.isCheckingSlug = true;
                    if (this.slugTimeout) clearTimeout(this.slugTimeout);

                    this.slugTimeout = setTimeout(async () => {
                        try {
                            const response = await fetch(`{{ route('shop.checkSlug') }}?slug=${encodeURIComponent(currentSlug)}`, {
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            });
                            if (!response.ok) throw new Error('Network error');
                            const data = await response.json();
                            this.slugAvailable = data.available;
                        } catch (e) {
                            this.slugAvailable = true;
                        } finally {
                            this.isCheckingSlug = false;
                        }
                    }, 400);
                },

                updateSlug() {
                    if (!this.manualSlug) {
                        this.slug = this.name
                            .toLowerCase()
                            .replace(/[^\w\s-]/g, '')
                            .replace(/[\s_-]+/g, '-')
                            .replace(/^-+|-+$/g, '');
                    }
                },

                loadFile(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            this.showCropper = true;
                            this.$nextTick(() => {
                                if (this.cropper) this.cropper.destroy();
                                const image = document.getElementById('cropperImage');
                                image.src = event.target.result;
                                this.cropper = new Cropper(image, {
                                    aspectRatio: 1,
                                    viewMode: 1,
                                    dragMode: 'move',
                                    autoCropArea: 1,
                                });
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                },

                saveCrop() {
                    if (this.cropper) {
                        const canvas = this.cropper.getCroppedCanvas({ width: 300, height: 300, imageSmoothingEnabled: true, imageSmoothingQuality: 'high' });
                        this.croppedData = canvas.toDataURL('image/png');
                        this.showCropper = false;
                    }
                }
            }));
        });
    </script>
</x-app-layout>
