<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-[#d4af37]">محلي</h1>
        <p class="text-gray-400 text-sm mt-1">أنشئ متجرك الآن وابدأ البيع</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('اسمك الكامل')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('كلمة المرور')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Hidden role - seller only -->
        <input type="hidden" name="role" value="seller">

        <!-- 🔐 Policy Agreement -->
        <div class="mt-6 flex items-start gap-3 text-right">
            <input
                id="terms"
                type="checkbox"
                name="terms"
                required
                class="mt-1 rounded border-gray-600 bg-gray-900 text-[#d4af37] focus:ring-[#d4af37]"
            >

            <label for="terms" class="text-sm text-gray-400 leading-relaxed">
                أوافق على
                <a href="/terms" target="_blank" class="text-[#d4af37] hover:underline">شروط الاستخدام</a>
                و
                <a href="/privacy" target="_blank" class="text-[#d4af37] hover:underline">سياسة الخصوصية</a>
            </label>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('إنشاء حساب البائع') }}
            </x-primary-button>
        </div>

        <div class="mt-4 text-center">
            <a class="text-sm text-gray-400 hover:text-gray-200" href="{{ route('login') }}">
                {{ __('لديك حساب بالفعل؟ تسجيل الدخول') }}
            </a>
        </div>
    </form>
</x-guest-layout>
