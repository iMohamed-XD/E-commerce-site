@php
    $seedOptions = collect(old('options', [['label' => '', 'quantity' => 0]]))
        ->map(fn ($option) => [
            'label' => trim((string) ($option['label'] ?? '')),
            'quantity' => (int) ($option['quantity'] ?? 0),
        ])
        ->values()
        ->all();

    if (empty($seedOptions)) {
        $seedOptions = [['label' => '', 'quantity' => 0]];
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('products.index') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-[#0d1b4b] leading-tight">إضافة منتج جديد</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 overflow-hidden shadow-xl shadow-[#0d1b4b]/6 sm:rounded-3xl">
                <div class="p-8 text-[#0d1b4b]">
                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-6"
                        x-data="productOptionsForm({
                            hasOptions: {{ old('has_options') ? 'true' : 'false' }},
                            options: {{ Js::from($seedOptions) }}
                        })"
                        x-on:submit="isSubmitting = true">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('اسم المنتج')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="category_id" :value="__('تصنيف المنتج')" />
                                <select id="category_id" name="category_id" class="block mt-1 w-full bg-white border border-[#0d1b4b]/15 text-[#0d1b4b] focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 rounded-xl shadow-sm">
                                    <option value="">-- اختر تصنيفاً --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                                <p class="mt-1 text-xs text-[#0d1b4b]/45">
                                    يمكنك إضافة تصنيفات جديدة من صفحة <a href="{{ route('categories.index') }}" class="text-[#d4af37] hover:text-[#b8922a] hover:underline">إدارة التصنيفات</a>.
                                </p>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('السعر الأساسي (USD)')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" min="0" name="price" :value="old('price')" required />
                            <p class="mt-1 text-xs text-[#0d1b4b]/45">سيدخل البائع السعر بالدولار الأمريكي، وسيظهر للمشتري بالدولار والليرة السورية.</p>
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div class="border border-[#0d1b4b]/10 rounded-2xl p-5 bg-white/80 space-y-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <h4 class="text-base font-bold text-[#0d1b4b]">طريقة إدارة المخزون</h4>
                                    <p class="text-xs text-[#0d1b4b]/45 mt-1">اختر بين كمية بسيطة للمنتج كله أو خيارات متعددة مثل المقاس والوزن والعبوة.</p>
                                </div>
                                <input type="hidden" name="has_options" :value="hasOptions ? 1 : 0">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <button type="button" @click="hasOptions = false"
                                    :class="!hasOptions ? 'bg-[#0d1b4b] text-white border-[#0d1b4b]' : 'bg-white text-[#0d1b4b]/70 border-[#0d1b4b]/15'"
                                    class="rounded-2xl border px-4 py-4 text-right transition">
                                    <span class="block text-sm font-black">مخزون بسيط</span>
                                    <span class="block text-xs mt-1 opacity-80">استخدم كمية واحدة للمنتج بالكامل.</span>
                                </button>
                                <button type="button" @click="enableOptions()"
                                    :class="hasOptions ? 'bg-[#0d1b4b] text-white border-[#0d1b4b]' : 'bg-white text-[#0d1b4b]/70 border-[#0d1b4b]/15'"
                                    class="rounded-2xl border px-4 py-4 text-right transition">
                                    <span class="block text-sm font-black">مخزون حسب الخيارات</span>
                                    <span class="block text-xs mt-1 opacity-80">مثال: صغير، متوسط، كبير أو عبوة 1kg و 5kg.</span>
                                </button>
                            </div>

                            <div x-show="!hasOptions" x-cloak>
                                <x-input-label for="quantity_available" :value="__('الكمية المتاحة')" />
                                <x-text-input id="quantity_available" class="block mt-1 w-full" type="number" step="1" min="0" name="quantity_available" :value="old('quantity_available', 0)" x-bind:disabled="hasOptions" />
                                <x-input-error :messages="$errors->get('quantity_available')" class="mt-2" />
                            </div>

                            <div x-show="hasOptions" x-cloak class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <x-input-label for="options-0-label" :value="__('الخيارات (مثال: مقاس، وزن، عبوة)')" />
                                    <button type="button" @click="addOption()" class="px-3 py-2 rounded-xl bg-[#d4af37]/10 text-[#a07c1e] text-xs font-black hover:bg-[#d4af37]/20 transition">
                                        + إضافة خيار
                                    </button>
                                </div>

                                <template x-for="(option, index) in options" :key="index">
                                    <div class="grid grid-cols-1 sm:grid-cols-[minmax(0,1.7fr)_minmax(0,1fr)_auto] gap-3 items-end">
                                        <div>
                                            <label class="block text-xs font-bold text-[#0d1b4b]/55 mb-1">اسم الخيار</label>
                                            <input :id="`options-${index}-label`" type="text" :name="`options[${index}][label]`" x-model="option.label" x-bind:disabled="!hasOptions"
                                                class="w-full bg-white border border-[#0d1b4b]/15 text-[#0d1b4b] rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4af37]/20 focus:border-[#d4af37]"
                                                placeholder="مثال: صغير">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-[#0d1b4b]/55 mb-1">الكمية</label>
                                            <input type="number" min="0" step="1" :name="`options[${index}][quantity]`" x-model="option.quantity" x-bind:disabled="!hasOptions"
                                                class="w-full bg-white border border-[#0d1b4b]/15 text-[#0d1b4b] rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4af37]/20 focus:border-[#d4af37]">
                                        </div>
                                        <button type="button" @click="removeOption(index)" class="h-[46px] px-4 rounded-xl border border-red-200 text-red-600 text-xs font-black hover:bg-red-50 transition">
                                            حذف
                                        </button>
                                    </div>
                                </template>

                                <x-input-error :messages="$errors->get('options')" class="mt-2" />
                                @foreach ($errors->get('options.*.label') as $messages)
                                    @foreach ($messages as $message)
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @endforeach
                                @endforeach
                                @foreach ($errors->get('options.*.quantity') as $messages)
                                    @foreach ($messages as $message)
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('وصف المنتج')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full bg-white border border-[#0d1b4b]/15 text-[#0d1b4b] focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 rounded-xl shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="image" :value="__('الصورة الأساسية للمنتج')" />
                            <input id="image" class="block mt-1 w-full text-sm text-[#0d1b4b]/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border file:border-[#0d1b4b]/15 file:text-sm file:font-bold file:bg-white file:text-[#0d1b4b] hover:file:bg-[#fdfbf4]" type="file" name="image" accept="image/*" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="secondary_images" :value="__('صور إضافية للمنتج (حد أقصى 3 صور، حجم الصورة 2MB)')" />
                            <input id="secondary_images" multiple class="block mt-1 w-full text-sm text-[#0d1b4b]/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border file:border-[#0d1b4b]/15 file:text-sm file:font-bold file:bg-white file:text-[#0d1b4b] hover:file:bg-[#fdfbf4]" type="file" name="secondary_images[]" accept="image/*" />
                            <x-input-error :messages="$errors->get('secondary_images')" class="mt-2" />
                            @if($errors->has('secondary_images.*'))
                                @foreach($errors->get('secondary_images.*') as $errorMessages)
                                    @foreach((array) $errorMessages as $errorMessage)
                                        <p class="text-sm text-red-600 mt-2">{{ $errorMessage }}</p>
                                    @endforeach
                                @endforeach
                            @endif
                        </div>

                        <div class="border border-[#0d1b4b]/10 rounded-2xl p-5 bg-white/80 space-y-4">
                            <h4 class="text-base font-bold text-[#d4af37]">إعدادات الخصم (اختياري)</h4>
                            <p class="text-xs text-[#0d1b4b]/45">أدخل نسبة الخصم المئوية إن أردت إظهار السعر قبل وبعد الخصم.</p>
                            <div>
                                <label class="block text-sm font-bold text-[#0d1b4b]/70 mb-1">نسبة الخصم (%)</label>
                                <input type="number" name="discount_percent" step="0.01" min="0" max="100"
                                    value="{{ old('discount_percent') }}"
                                    class="w-full bg-white border border-[#0d1b4b]/15 text-[#0d1b4b] rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4af37]/20 focus:border-[#d4af37] placeholder-[#0d1b4b]/30"
                                    placeholder="مثلاً: 20">
                                <x-input-error :messages="$errors->get('discount_percent')" class="mt-2" />
                            </div>
                        </div>

                        <div class="block mt-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-[#0d1b4b]/20 bg-white text-[#d4af37] shadow-sm focus:ring-[#d4af37]/30" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-[#0d1b4b]/60">{{ __('نشط (مرئي للمشترين)') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button x-bind:disabled="isSubmitting">
                                <span x-show="!isSubmitting">{{ __('حفظ المنتج') }}</span>
                                <span x-show="isSubmitting">جارٍ الحفظ...</span>
                            </x-primary-button>
                            <a href="{{ route('products.index') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] ml-4">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('productOptionsForm', (config) => ({
                hasOptions: !!config.hasOptions,
                options: Array.isArray(config.options) && config.options.length ? config.options : [{ label: '', quantity: 0 }],
                isSubmitting: false,

                enableOptions() {
                    this.hasOptions = true;
                    if (!this.options.length) {
                        this.options = [{ label: '', quantity: 0 }];
                    }
                },

                addOption() {
                    this.options.push({ label: '', quantity: 0 });
                },

                removeOption(index) {
                    if (this.options.length === 1) {
                        this.options[0] = { label: '', quantity: 0 };
                        return;
                    }

                    this.options.splice(index, 1);
                },
            }));
        });
    </script>
</x-app-layout>
