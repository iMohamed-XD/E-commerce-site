<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.payment-methods.index') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-[#0d1b4b] leading-tight">تعديل طريقة دفع: {{ $pm->name }}</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 shadow-xl shadow-[#0d1b4b]/5 sm:rounded-3xl p-8">
                
                <form method="POST" action="{{ route('admin.payment-methods.update', $pm) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="name" :value="__('اسم الطريقة (مثال: سيريتل كاش، بيمو)')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $pm->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="account_id" :value="__('رقم الحساب / المستفيد')" />
                        <x-text-input id="account_id" class="block mt-1 w-full text-left" dir="ltr" type="text" name="account_id" :value="old('account_id', $pm->account_id)" required />
                        <x-input-error :messages="$errors->get('account_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="details" :value="__('تفاصيل اختيارية (أوقات التحويل، ملاحظات)')" />
                        <textarea id="details" name="details" rows="3" class="block mt-1 w-full bg-white border border-[#0d1b4b]/15 rounded-xl shadow-sm focus:border-[#d4af37] focus:ring focus:ring-[#d4af37]/20">{{ old('details', $pm->details) }}</textarea>
                        <x-input-error :messages="$errors->get('details')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="logo" :value="__('شعار طريقة الدفع (اختياري)')" />
                            @if($pm->logo_path)
                                <div class="mt-2 mb-2">
                                    <img src="{{ Storage::url($pm->logo_path) }}" class="h-12 w-12 object-contain bg-white rounded border border-black/5 p-1">
                                </div>
                            @endif
                            <input id="logo" class="block mt-1 w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border file:border-[#0d1b4b]/15 file:text-sm file:font-bold file:bg-white file:text-[#0d1b4b] hover:file:bg-[#fdfbf4]" type="file" name="logo" accept="image/*" />
                            <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="qr" :value="__('رمز QR (اختياري)')" />
                            @if($pm->qr_path)
                                <div class="mt-2 mb-2">
                                    <img src="{{ Storage::url($pm->qr_path) }}" class="h-12 w-12 object-contain bg-white rounded border border-black/5 p-1">
                                </div>
                            @endif
                            <input id="qr" class="block mt-1 w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border file:border-[#0d1b4b]/15 file:text-sm file:font-bold file:bg-white file:text-[#0d1b4b] hover:file:bg-[#fdfbf4]" type="file" name="qr" accept="image/*" />
                            <x-input-error :messages="$errors->get('qr')" class="mt-2" />
                        </div>
                    </div>

                    <div class="block mt-4">
                        <label for="is_active" class="inline-flex items-center">
                            <input id="is_active" type="checkbox" class="rounded border-[#0d1b4b]/20 text-[#d4af37] focus:ring-[#d4af37]/30" name="is_active" {{ $pm->is_active ? 'checked' : '' }}>
                            <span class="ms-2 text-sm text-[#0d1b4b]/60">{{ __('نشط') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center gap-4 mt-8">
                        <x-primary-button>حفظ التغييرات</x-primary-button>
                        <a href="{{ route('admin.payment-methods.index') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b]">إلغاء</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
