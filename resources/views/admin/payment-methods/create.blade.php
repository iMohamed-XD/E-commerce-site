<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.payment-methods.index') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-[#0d1b4b] leading-tight">إضافة طريقة دفع</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 shadow-xl shadow-[#0d1b4b]/5 sm:rounded-3xl p-8">
                
                <form method="POST" action="{{ route('admin.payment-methods.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('اسم الطريقة (مثال: سيريتل كاش، بيمو)')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="account_id" :value="__('رقم الحساب / المستفيد')" />
                        <x-text-input id="account_id" class="block mt-1 w-full text-left" dir="ltr" type="text" name="account_id" :value="old('account_id')" required />
                        <x-input-error :messages="$errors->get('account_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="details" :value="__('تفاصيل اختيارية (أوقات التحويل، ملاحظات)')" />
                        <textarea id="details" name="details" rows="3" class="block mt-1 w-full bg-white border border-[#0d1b4b]/15 rounded-xl shadow-sm focus:border-[#d4af37] focus:ring focus:ring-[#d4af37]/20">{{ old('details') }}</textarea>
                        <x-input-error :messages="$errors->get('details')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="logo_image" :value="__('شعار طريقة الدفع (اختياري)')" />
                            <input id="logo_image" class="block mt-1 w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border file:border-[#0d1b4b]/15 file:text-sm file:font-bold file:bg-white file:text-[#0d1b4b] hover:file:bg-[#fdfbf4]" type="file" name="logo_image" accept="image/*" />
                            <x-input-error :messages="$errors->get('logo_image')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="qr_image" :value="__('رمز QR (اختياري)')" />
                            <input id="qr_image" class="block mt-1 w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border file:border-[#0d1b4b]/15 file:text-sm file:font-bold file:bg-white file:text-[#0d1b4b] hover:file:bg-[#fdfbf4]" type="file" name="qr_image" accept="image/*" />
                            <x-input-error :messages="$errors->get('qr_image')" class="mt-2" />
                        </div>
                    </div>

                    <div class="block mt-4">
                        <label for="is_active" class="inline-flex items-center">
                            <input id="is_active" type="checkbox" class="rounded border-[#0d1b4b]/20 text-[#d4af37] focus:ring-[#d4af37]/30" name="is_active" checked>
                            <span class="ms-2 text-sm text-[#0d1b4b]/60">{{ __('نشط') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center gap-4 mt-8">
                        <x-primary-button>حفظ طريقة الدفع</x-primary-button>
                        <a href="{{ route('admin.payment-methods.index') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b]">إلغاء</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
