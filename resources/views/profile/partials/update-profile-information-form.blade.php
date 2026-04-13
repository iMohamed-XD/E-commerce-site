<section>
    <header>
        <h2 class="text-xl font-black text-[#0d1b4b] uppercase tracking-tight">
            {{ __('معلومات الحساب') }}
        </h2>

        <p class="mt-2 text-sm text-[#0d1b4b]/50">
            {{ __('قم بتحديث معلومات ملفك الشخصي وبريدك الإلكتروني ورقم الهاتف.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('الاسم')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="phone_number" :value="__('رقم الهاتف')" />
            <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" :value="old('phone_number', $user->phone_number)" required autocomplete="tel" dir="ltr" />
            <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            @if ($user->google_id)
                <x-text-input
                    id="email"
                    type="email"
                    class="mt-1 block w-full opacity-80 cursor-not-allowed"
                    :value="$user->email"
                    readonly
                    disabled
                />
                <p class="mt-2 text-xs text-[#0d1b4b]/50">
                    {{ __('الحساب المرتبط بـ Google لا يمكنه تغيير البريد الإلكتروني.') }}
                </p>
            @else
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            @endif

            @if (!$user->google_id && $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-[#0d1b4b]/70">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-[#d4af37] hover:text-[#b8922a] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#d4af37]/40">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-700">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('حفظ') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-[#0d1b4b]/50"
                >{{ __('تم الحفظ.') }}</p>
            @endif
        </div>
    </form>
</section>
