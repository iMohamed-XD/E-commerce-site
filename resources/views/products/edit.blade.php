{{-- products/edit --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('products.index') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-[#0d1b4b] leading-tight">تعديل المنتج</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 overflow-hidden shadow-xl shadow-[#0d1b4b]/6 sm:rounded-3xl">
                <div class="p-8 text-[#0d1b4b]">

                    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('اسم المنتج')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="category_id" :value="__('تصنيف المنتج')" />
                                <select id="category_id" name="category_id" class="block mt-1 w-full bg-white border border-[#0d1b4b]/15 text-[#0d1b4b] focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 rounded-xl shadow-sm">
                                    <option value="">-- اختر تصنيفاً --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
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
                            <x-input-label for="price" :value="__('السعر الأصلي (ل.س)')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" min="0" name="price" :value="old('price', $product->price)" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('وصف المنتج')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full bg-white border border-[#0d1b4b]/15 text-[#0d1b4b] focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 rounded-xl shadow-sm">{{ old('description', $product->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="image" :value="__('تحديث صورة المنتج (اختياري)')" />
                            @if($product->image_path)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="" class="h-20 w-20 object-cover rounded-xl shadow border border-[#0d1b4b]/15">
                                </div>
                            @endif
                            <input id="image" class="block mt-1 w-full text-sm text-[#0d1b4b]/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border file:border-[#0d1b4b]/15 file:text-sm file:font-bold file:bg-white file:text-[#0d1b4b] hover:file:bg-[#fdfbf4]" type="file" name="image" accept="image/*" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Discount Section -->
                        <div class="border border-[#0d1b4b]/10 rounded-2xl p-5 bg-white/80 space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-base font-bold text-[#d4af37]">⚡ إعدادات الخصم (اختياري)</h4>
                                @if($product->discount_percent)
                                    <span class="text-xs text-green-400 font-semibold">خصم مفعّل: {{ $product->discount_percent }}%</span>
                                @endif
                            </div>
                            <p class="text-xs text-[#0d1b4b]/45">أدخل نسبة الخصم (مثال: 20 يعني 20% خصم). اتركه فارغاً لعدم تطبيق أي خصم.</p>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-[#0d1b4b]/70 mb-1">نسبة الخصم (%)</label>
                                    <input type="number" name="discount_percent" step="0.01" min="0" max="100"
                                        value="{{ old('discount_percent', $product->discount_percent) }}"
                                        class="w-full bg-white border border-[#0d1b4b]/15 text-[#0d1b4b] rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4af37]/20 focus:border-[#d4af37] placeholder-[#0d1b4b]/30"
                                        placeholder="مثلاً: 20">
                                    @if($product->price && old('discount_percent', $product->discount_percent))
                                        <p class="text-xs text-green-400 mt-2 font-semibold">
                                            السعر بعد الخصم: {{ number_format($product->price * (1 - old('discount_percent', $product->discount_percent) / 100), 2) }} ل.س
                                        </p>
                                    @endif
                                    <x-input-error :messages="$errors->get('discount_percent')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="block mt-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-[#0d1b4b]/20 bg-white text-[#d4af37] shadow-sm focus:ring-[#d4af37]/30" name="is_active" {{ $product->is_active ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-[#0d1b4b]/60">{{ __('نشط (مرئي للمشترين)') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('تحديث المنتج') }}</x-primary-button>
                            <a href="{{ route('products.index') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] ml-4">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
