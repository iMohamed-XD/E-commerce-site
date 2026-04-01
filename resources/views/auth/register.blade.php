<x-guest-layout>

    {{-- Card --}}
    <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl shadow-xl shadow-[#0d1b4b]/6 px-8 py-10">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#d4af37]/10 border border-[#d4af37]/25 text-[#a07c1e] text-[11px] font-bold tracking-widest uppercase mb-5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#d4af37]"></span>
                انضم إلى محلي
            </div>
            <h1 class="text-2xl font-black text-[#0d1b4b] leading-tight">
                أنشئ حسابك
                <span class="text-transparent bg-clip-text bg-gradient-to-l from-[#d4af37] to-[#b8922a]">مجاناً</span>
            </h1>
            <p class="text-[#0d1b4b]/45 text-sm mt-2">ابدأ متجرك الإلكتروني في دقائق</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            {{-- google --}}
            <a href="{{ route('auth.google') }}"
                class="flex items-center justify-center gap-3 w-full px-5 py-3 bg-white border border-[#0d1b4b]/12 rounded-lg text-sm font-medium text-[#0d1b4b] hover:border-[#d4af37]/45 hover:bg-[#fdfbf4] transition-colors duration-200">

                    <svg viewBox="0 0 18 18" width="18" height="18" class="shrink-0">
                        <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 01-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.615z"/>
                        <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 009 18z"/>
                        <path fill="#FBBC05" d="M3.964 10.707A5.41 5.41 0 013.682 9c0-.593.102-1.17.282-1.707V4.961H.957A8.996 8.996 0 000 9c0 1.452.348 2.827.957 4.039l3.007-2.332z"/>
                        <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 00.957 4.961L3.964 7.293C4.672 5.163 6.656 3.58 9 3.58z"/>
                    </svg>

                    Continue with Google
            </a>
            {{-- Name --}}
            <div>
                <x-input-label for="name" :value="__('الاسم الكامل')" />
                <x-text-input
                    id="name"
                    class="block mt-1.5 w-full"
                    type="text"
                    name="name"
                    :value="old('name')"
                    placeholder="مثال: أحمد الحسن"
                    required autofocus autocomplete="name"
                />
                <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
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
                    required autocomplete="username"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            {{-- Password --}}
            <div>
                <x-input-label for="password" :value="__('كلمة المرور')" />
                <x-text-input
                    id="password"
                    class="block mt-1.5 w-full"
                    type="password"
                    name="password"
                    placeholder="8 أحرف على الأقل"
                    required autocomplete="new-password"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

            {{-- Confirm Password --}}
            <div>
                <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />
                <x-text-input
                    id="password_confirmation"
                    class="block mt-1.5 w-full"
                    type="password"
                    name="password_confirmation"
                    placeholder="أعد كتابة كلمة المرور"
                    required autocomplete="new-password"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
            </div>

            {{-- Hidden role --}}
            <input type="hidden" name="role" value="seller">

            {{-- Policy Agreement --}}
            <div class="flex items-start gap-3 pt-1">
                <input
                    id="terms"
                    type="checkbox"
                    name="terms"
                    required
                    class="mt-0.5 w-4 h-4 rounded border-[#0d1b4b]/20 bg-white text-[#d4af37] focus:ring-[#d4af37]/30 focus:ring-offset-0 cursor-pointer"
                >
                <label for="terms" class="text-sm text-[#0d1b4b]/55 leading-relaxed cursor-pointer select-none">
                    أوافق على
                    <a href="/terms" target="_blank" class="text-[#d4af37] hover:text-[#b8922a] font-semibold transition-colors">شروط الاستخدام</a>
                    و
                    <a href="/privacy" target="_blank" class="text-[#d4af37] hover:text-[#b8922a] font-semibold transition-colors">سياسة الخصوصية</a>
                </label>
            </div>

            {{-- Submit --}}
            <div class="pt-2">
                <button
                    type="submit"
                    class="group relative w-full py-3.5 px-6 bg-[#d4af37] text-[#0d1b4b] text-base font-black rounded-2xl
                           hover:bg-[#c5a02e] active:scale-[0.98] transition-all duration-200
                           shadow-lg shadow-[#d4af37]/25 overflow-hidden"
                >
                    <span class="relative z-10">إنشاء حساب البائع</span>
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 rounded-2xl"></div>
                </button>
            </div>

            {{-- Divider --}}
            <div class="relative flex items-center gap-3 py-1">
                <div class="flex-1 h-px bg-[#0d1b4b]/8"></div>
                <span class="text-[#0d1b4b]/30 text-xs">أو</span>
                <div class="flex-1 h-px bg-[#0d1b4b]/8"></div>
            </div>

            {{-- Login link --}}

                <a href="{{ route('login') }}"
                class="block w-full text-center py-3.5 rounded-2xl border border-[#0d1b4b]/12
                       text-[#0d1b4b]/60 text-sm font-bold
                       hover:border-[#0d1b4b]/25 hover:text-[#0d1b4b] hover:bg-[#0d1b4b]/3
                       transition-all duration-200"
            >
                لديك حساب بالفعل؟ تسجيل الدخول
            </a>

        </form>
    </div>

    {{-- Footer note --}}
    <p class="text-center text-[11px] text-[#0d1b4b]/30 mt-6 tracking-wide">
        محلي — منصة التجارة المحلية الفاخرة
    </p>

</x-guest-layout>
