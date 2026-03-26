<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">إضافة منتج جديد</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-100">
                    
                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('اسم المنتج')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="category" :value="__('التصنيف (فئة المنتج)')" />
                                <x-text-input id="category" class="block mt-1 w-full" type="text" name="category" :value="old('category')" placeholder="مثلاً: حلويات، مفروشات..." />
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('السعر الأصلي (ل.س)')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" min="0" name="price" :value="old('price')" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('وصف المنتج')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full bg-gray-700 border-gray-600 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="image" :value="__('صورة المنتج')" />
                            <input id="image" class="block mt-1 w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-900 file:text-indigo-200 hover:file:bg-indigo-800" type="file" name="image" accept="image/*" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Discount Section -->
                        <div class="border border-gray-700 rounded-lg p-5 bg-gray-900 space-y-4">
                            <h4 class="text-base font-bold text-[#d4af37]">⚡ إعدادات الخصم (اختياري)</h4>
                            <p class="text-xs text-gray-400">أدخل نسبة الخصم (مثال: 20 يعني 20% خصم). اتركه فارغاً لعدم تطبيق أي خصم.</p>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1">نسبة الخصم (%)</label>
                                    <input type="number" name="discount_percent" step="0.01" min="0" max="100"
                                        value="{{ old('discount_percent') }}"
                                        class="w-full bg-gray-700 border border-gray-600 text-gray-100 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-gray-500"
                                        placeholder="مثلاً: 20">
                                    <x-input-error :messages="$errors->get('discount_percent')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="block mt-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-gray-600 bg-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" checked>
                                <span class="ms-2 text-sm text-gray-300">{{ __('نشط (مرئي للمشترين)') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('حفظ المنتج') }}</x-primary-button>
                            <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-gray-100 ml-4">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
