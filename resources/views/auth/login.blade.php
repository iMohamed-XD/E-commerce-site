<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-[#d4af37]">محلي</h1>
        <p class="text-gray-400 text-sm mt-1">تسجيل الدخول إلى متجرك</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('كلمة المرور')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-600 bg-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-300">{{ __('تذكرني') }}</span>
            </label>
        </div>

        <div class="mt-6 space-y-3">
            <x-primary-button class="w-full justify-center">
                {{ __('دخول') }}
            </x-primary-button>

            @if (Route::has('password.request'))
                <div class="text-center">
                    <a class="text-sm text-gray-400 hover:text-gray-200" href="{{ route('password.request') }}">
                        {{ __('نسيت كلمة المرور؟') }}
                    </a>
                </div>
            @endif

            <div class="text-center border-t border-gray-700 pt-3">
                <a class="text-sm text-[#d4af37] hover:text-yellow-400" href="{{ route('register') }}">
                    {{ __('بائع جديد؟ أنشئ متجرك الآن') }}
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
