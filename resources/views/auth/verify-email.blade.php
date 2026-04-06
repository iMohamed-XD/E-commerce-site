<x-guest-layout>
    <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl shadow-xl shadow-[#0d1b4b]/6 px-8 py-10">
        <div class="mb-5 text-sm text-[#0d1b4b]/60 leading-relaxed font-medium">
            {{ __('شكراً لانضمامك إلينا! قبل البدء، هل يمكنك تفعيل حسابك بالضغط على الرابط الذي أرسلناه لتوّنا إلى بريدك الإلكتروني؟ إذا لم يصلك البريد، يسعدنا إرسال رابط آخر.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-5 font-semibold text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl px-4 py-2">
                {{ __('تم إرسال رابط تفعيل جديد إلى البريد الإلكتروني الذي قدمته أثناء التسجيل.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <x-primary-button>
                    {{ __('إعادة إرسال بريد التفعيل') }}
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="underline text-sm text-[#0d1b4b]/60 hover:text-[#0d1b4b] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#d4af37]/40">
                    {{ __('تسجيل الخروج') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
