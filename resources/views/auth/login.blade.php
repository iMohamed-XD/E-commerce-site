<x-guest-layout>

    {{-- Card --}}
    <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl shadow-xl shadow-[#0d1b4b]/6 px-8 py-10">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#0d1b4b]/6 border border-[#0d1b4b]/12 text-[#0d1b4b]/60 text-[11px] font-bold tracking-widest uppercase mb-5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#0d1b4b]/50"></span>
                مرحباً بعودتك
            </div>
            <h1 class="text-2xl font-black text-[#0d1b4b] leading-tight">
                تسجيل
                <span class="text-transparent bg-clip-text bg-gradient-to-l from-[#d4af37] to-[#b8922a]">الدخول</span>
            </h1>
            <p class="text-[#0d1b4b]/45 text-sm mt-2">ادخل إلى لوحة تحكم متجرك</p>
        </div>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-5" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Google OAuth --}}
            <a href="{{ route('auth.google') }}"
               class="group flex items-center justify-center gap-3 w-full py-3 px-5
                      bg-white border border-[#0d1b4b]/12 rounded-2xl
                      text-sm font-bold text-[#0d1b4b]/70
                      hover:border-[#d4af37]/50 hover:bg-[#fdfbf4] hover:text-[#0d1b4b]
                      transition-all duration-200 shadow-sm">
                <svg viewBox="0 0 18 18" width="18" height="18" class="shrink-0">
                    <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 01-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.615z"/>
                    <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 009 18z"/>
                    <path fill="#FBBC05" d="M3.964 10.707A5.41 5.41 0 013.682 9c0-.593.102-1.17.282-1.707V4.961H.957A8.996 8.996 0 000 9c0 1.452.348 2.827.957 4.039l3.007-2.332z"/>
                    <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 00.957 4.961L3.964 7.293C4.672 5.163 6.656 3.58 9 3.58z"/>
                </svg>
                <span>المتابعة عبر Google</span>
            </a>

            {{-- Divider --}}
            <div class="relative flex items-center gap-3">
                <div class="flex-1 h-px bg-[#0d1b4b]/8"></div>
                <span class="text-[#0d1b4b]/30 text-xs">أو بالبريد الإلكتروني</span>
                <div class="flex-1 h-px bg-[#0d1b4b]/8"></div>
            </div>

            {{-- Email --}}
            <div>
                <x-input-label for="email" :value="__('البريد الإلكتروني')" />
                <x-text-input
                    id="email"
                    class="block mt-1.5 w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    placeholder="example@email.com"
                    required autofocus autocomplete="username"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            {{-- Password --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <x-input-label for="password" :value="__('كلمة المرور')" />
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-xs text-[#d4af37] hover:text-[#b8922a] font-semibold transition-colors">
                            نسيت كلمة المرور؟
                        </a>
                    @endif
                </div>
                <x-text-input
                    id="password"
                    class="block w-full"
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    required autocomplete="current-password"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center gap-2.5">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="w-4 h-4 rounded border-[#0d1b4b]/20 bg-white text-[#d4af37]
                           focus:ring-[#d4af37]/30 focus:ring-offset-0 cursor-pointer"
                >
                <label for="remember_me" class="text-sm text-[#0d1b4b]/55 cursor-pointer select-none">
                    تذكرني
                </label>
            </div>

            {{-- Submit --}}
            <div class="pt-1">
                <button
                    type="submit"
                    class="group relative w-full py-3.5 px-6 bg-[#0d1b4b] text-white text-base font-black rounded-2xl
                           hover:bg-[#1a2d6b] active:scale-[0.98] transition-all duration-200
                           shadow-lg shadow-[#0d1b4b]/20 overflow-hidden"
                >
                    <span class="relative z-10">دخول إلى المتجر</span>
                    <div class="absolute inset-0 bg-white/5 translate-y-full group-hover:translate-y-0 transition-transform duration-300 rounded-2xl"></div>
                </button>
            </div>

            {{-- Register link --}}

                <a href="{{ route('register') }}"
                class="block w-full text-center py-3.5 rounded-2xl border border-[#d4af37]/30
                       text-[#a07c1e] text-sm font-bold bg-[#d4af37]/5
                       hover:border-[#d4af37]/60 hover:bg-[#d4af37]/10 hover:text-[#0d1b4b]
                       transition-all duration-200"
            >
                بائع جديد؟ أنشئ متجرك مجاناً
            </a>

        </form>
    </div>

    {{-- Footer note --}}
    <p class="text-center text-[11px] text-[#0d1b4b]/30 mt-6 tracking-wide">
        محلي — منصة التجارة المحلية الفاخرة
    </p>

</x-guest-layout>
