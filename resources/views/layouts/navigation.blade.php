{{-- layouts.navigation --}}
<nav x-data="{ open: false }" class="backdrop-blur-md bg-white/80 border-b border-[#0d1b4b]/8 sticky top-0 z-50 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        <div class="relative bg-white/80 backdrop-blur-xl p-2.5 rounded-xl border border-[#0d1b4b]/10 shadow-md group-hover:border-[#d4af37]/40 group-hover:shadow-[0_4px_20px_rgba(212,175,55,0.15)] transition-all duration-500 overflow-hidden">
                            <!-- Subtle Gleam on Hover -->
                            <div class="absolute inset-0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-[#d4af37]/10 to-transparent"></div>

                            <x-application-logo class="block h-9 w-auto hover:brightness-110 transition-all duration-300" />
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 space-x-reverse sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-sm font-medium transition-colors hover:text-[#0d1b4b] focus:text-[#0d1b4b] focus:outline-none focus:ring-2 focus:ring-[#d4af37]/40 rounded-md">
                        {{ __('لوحة التحكم') }}
                    </x-nav-link>
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="text-sm font-medium transition-colors hover:text-[#0d1b4b] focus:text-[#0d1b4b] focus:outline-none focus:ring-2 focus:ring-[#d4af37]/40 rounded-md">
                        {{ __('التصنيفات') }}
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="text-sm font-medium transition-colors hover:text-[#0d1b4b] focus:text-[#0d1b4b] focus:outline-none focus:ring-2 focus:ring-[#d4af37]/40 rounded-md">
                        {{ __('المنتجات') }}
                    </x-nav-link>
                    <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="text-sm font-medium transition-colors hover:text-[#0d1b4b] focus:text-[#0d1b4b] focus:outline-none focus:ring-2 focus:ring-[#d4af37]/40 rounded-md">
                        {{ __('الطلبات') }}
                    </x-nav-link>
                    <x-nav-link :href="route('promo-codes.index')" :active="request()->routeIs('promo-codes.*')" class="text-sm font-medium transition-colors hover:text-[#0d1b4b] focus:text-[#0d1b4b] focus:outline-none focus:ring-2 focus:ring-[#d4af37]/40 rounded-md">
                        {{ __('أكواد الخصم') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="ms-3 relative">
                    <x-dropdown align="left" width="48" contentClasses="py-1 bg-white border border-[#0d1b4b]/10 shadow-xl">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-[#0d1b4b]/15 text-sm leading-4 font-medium rounded-xl text-[#0d1b4b]/70 bg-white hover:bg-[#fdfbf4] hover:text-[#0d1b4b] focus:outline-none focus:ring-2 focus:ring-[#d4af37]/40 transition ease-in-out duration-150 shadow-sm">
                                <div class="flex items-center gap-2">
                                    <div class="h-6 w-6 rounded-full bg-[#0d1b4b] flex items-center justify-center text-[10px] text-white font-bold">
                                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    {{ Auth::user()->name }}
                                </div>

                                <div class="ms-2">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-[#0d1b4b]/10">
                                <div class="text-xs text-[#0d1b4b]/45">تم تسجيل الدخول كـ</div>
                                <div class="text-sm font-medium text-[#0d1b4b] truncate">{{ Auth::user()->email }}</div>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')" class="hover:bg-[#0d1b4b]/6 transition-colors">
                                {{ __('الملف الشخصي') }}
                            </x-dropdown-link>

                            @if(Auth::user()->shop)
                                <x-dropdown-link :href="route('shop.show', Auth::user()->shop->slug)" target="_blank" class="hover:bg-[#d4af37]/10 transition-colors text-[#a07c1e] font-semibold">
                                    {{ __('عرض متجري') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-[#0d1b4b]/10"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();"
                                        class="hover:bg-red-50 hover:text-red-600 transition-colors">
                                    {{ __('تسجيل الخروج') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-[#0d1b4b]/45 hover:text-[#0d1b4b] hover:bg-[#0d1b4b]/8 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/95 border-t border-[#0d1b4b]/10 backdrop-blur-md">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-right">
                {{ __('لوحة التحكم') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="text-right">
                {{ __('التصنيفات') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="text-right">
                {{ __('المنتجات') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="text-right">
                {{ __('الطلبات') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('promo-codes.index')" :active="request()->routeIs('promo-codes.*')" class="text-right">
                {{ __('أكواد الخصم') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-[#0d1b4b]/10">
            <div class="px-4 flex items-center justify-between">
                <div>
                    <div class="font-bold text-base text-[#0d1b4b]">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-[#0d1b4b]/50">{{ Auth::user()->email }}</div>
                </div>
                <div class="h-10 w-10 rounded-full bg-[#0d1b4b] flex items-center justify-center text-white font-bold">
                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-right">
                    {{ __('الملف الشخصي') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-right text-red-600">
                        {{ __('تسجيل الخروج') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
