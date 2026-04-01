<x-guest-layout>
    <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl shadow-xl shadow-[#0d1b4b]/6 px-8 py-10">
        <div class="mb-5 text-sm text-[#0d1b4b]/60">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-5 font-semibold text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl px-4 py-2">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="underline text-sm text-[#0d1b4b]/60 hover:text-[#0d1b4b] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#d4af37]/40">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
