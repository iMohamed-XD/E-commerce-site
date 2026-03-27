<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('لوحة التحكم') }}
        </h2>
    </x-slot>

    <!-- Adding Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(auth()->user()->isSeller())
                @if(!auth()->user()->shop)
                    <!-- Shop Setup Wizard with Cropper -->
                    <div class="bg-gray-800 border-gray-700 overflow-hidden shadow-sm sm:rounded-lg" x-data="logoCropper()">
                        <div class="p-8 text-gray-100">
                            <h3 class="text-2xl font-bold mb-2 text-indigo-700">أهلاً بك في محلي!</h3>
                            <p class="mb-6 text-gray-400">لبدء البيع، يرجى إعداد تفاصيل متجرك الإلكتروني أدناه.</p>
                            
                            <form method="POST" action="{{ route('shop.store') }}" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="name" :value="__('اسم المتجر')" />
                                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus x-model="name" @input="updateSlug" />
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="slug" :value="__('رابط المتجر (اسم بالانكليزية للرابط)')" />
                                        <x-text-input id="slug" class="block mt-1 w-full font-mono text-sm" type="text" name="slug" required x-model="slug" @input="manualSlug = true" />
                                        <p class="mt-2 text-xs text-gray-400">رابط متجرك سيكون: <span class="text-indigo-400 font-bold" dir="ltr">{{ url('/shop') }}/<span x-text="slug"></span></span></p>
                                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <x-input-label for="hero_image" :value="__('صورة الغلاف (Hero Image)')" />
                                        <input id="hero_image" class="block mt-1 w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-900 file:text-indigo-200 hover:file:bg-indigo-800" type="file" name="hero_image" accept="image/*" />
                                        <x-input-error :messages="$errors->get('hero_image')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="description" :value="__('وصف المتجر (اختياري)')" />
                                    <textarea id="description" name="description" rows="3" class="block mt-1 w-full bg-gray-700 border-gray-600 text-gray-100 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="logo_wizard" :value="__('شعار المتجر (اختياري)')" />
                                    <input id="logo_wizard" class="block mt-1 w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-indigo-200 hover:file:bg-indigo-800" type="file" name="logo" accept="image/*" @change="loadFile" />
                                    <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                                    <input type="hidden" name="cropped_logo" :value="croppedData">
                                </div>

                                <!-- Cropped Preview -->
                                <div x-show="croppedData" class="mt-4" x-cloak>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">معاينة الشعار المقتطع</label>
                                    <img :src="croppedData" class="w-32 h-32 rounded-full object-cover border-2 border-[#d4af37] shadow-sm" alt="Preview">
                                    <button type="button" @click="croppedData = ''; document.getElementById('logo_wizard').value = ''" class="mt-2 text-sm text-red-600 hover:text-red-800">إزالة</button>
                                </div>

                                <div class="flex items-center justify-start gap-4 pt-6">
                                    <x-primary-button class="bg-[#d4af37] text-black font-black hover:bg-[#c5a02e]">
                                        {{ __('إنشاء المتجر وحفظ البيانات') }}
                                    </x-primary-button>
                                </div>
                            </form>

                            <!-- Cropper Modal -->
                            <div x-show="showCropper" class="fixed inset-0 z-50 overflow-y-auto" x-cloak aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="showCropper" class="fixed inset-0 bg-gray-9000 bg-opacity-75 transition-opacity" @click="showCropper = false"></div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                    
                                    <div x-show="showCropper" 
                                        class="inline-block align-bottom bg-gray-800 border-gray-700 rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                                        <div class="bg-gray-800 border-gray-700 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-bold text-gray-100 mb-4">اقتطاع وتعديل الشعار</h3>
                                            <p class="text-sm text-gray-400 mb-4">قم بتغيير حجم الشعار وتحريكه ليناسب الدائرة.</p>
                                            <div class="w-full max-h-96 overflow-hidden bg-gray-800 flex justify-center items-center rounded text-center">
                                                <img id="cropperImage" src="" alt="Source Image" class="max-w-full max-h-96">
                                            </div>
                                        </div>
                                        <div class="bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                                            <button type="button" @click="saveCrop" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">تطبيق الشعار</button>
                                            <button type="button" @click="showCropper = false; document.getElementById('logo').value = ''" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-800 border-gray-700 text-base font-medium text-gray-300 hover:bg-gray-900 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">إلغاء</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Seller Dashboard Analytics -->
                    @php
                        $shop = auth()->user()->shop;
                        $totalProducts = $shop->products()->count();
                        $totalOrders = $shop->orders()->count();
                        $totalRevenue = $shop->orders()->where('status', 'completed')->sum('total_amount');
                    @endphp
                    <div class="bg-gray-800 border-gray-700 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-100">
                            <h3 class="text-2xl font-bold mb-4">متجرك: {{ $shop->name }}</h3>
                            <p class="mb-4 text-gray-300">رابط متجرك: <a href="{{ url('/shop/' . $shop->slug) }}" class="text-indigo-600 underline font-semibold" target="_blank" dir="ltr">{{ url('/shop/' . $shop->slug) }}</a></p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                                <div class="bg-indigo-50 rounded-lg p-6 border border-indigo-100 shadow-sm text-center">
                                    <h4 class="text-indigo-800 font-semibold mb-2">إجمالي المنتجات</h4>
                                    <p class="text-3xl font-bold text-indigo-900">{{ $totalProducts }}</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-6 border border-green-100 shadow-sm text-center">
                                    <h4 class="text-green-800 font-semibold mb-2">إجمالي الطلبات</h4>
                                    <p class="text-3xl font-bold text-green-900">{{ $totalOrders }}</p>
                                </div>
                                <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-100 shadow-sm text-center lg:col-span-2">
                                    <h4 class="text-yellow-800 font-semibold mb-2">إجمالي الأرباح (المكتملة)</h4>
                                    <p class="text-3xl font-bold text-yellow-900">{{ number_format($totalRevenue, 2) }} <span class="text-lg">ل.س</span></p>
                                </div>
                            </div>

                            <div class="mt-8 flex flex-wrap gap-4">
                                <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 shadow-md transition">
                                    إدارة المنتجات
                                </a>
                                <a href="{{ route('promo-codes.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-700 border border-gray-600 text-indigo-400 rounded-md font-bold text-sm uppercase tracking-widest hover:bg-gray-600 shadow-md transition">
                                    كوبونات الخصم
                                </a>
                                <a href="{{ route('orders.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-700 border border-transparent rounded-md font-bold text-sm text-white uppercase tracking-widest hover:bg-gray-600 shadow-md transition">
                                    سجل الطلبات
                                </a>
                                <a href="{{ route('categories.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-700 border border-gray-600 text-green-400 rounded-md font-bold text-sm uppercase tracking-widest hover:bg-gray-600 shadow-md transition">
                                    إدارة التصنيفات
                                </a>
                            </div>

                            <!-- Edit Shop Section -->
                            <div class="mt-12 pt-8 border-t border-gray-700">
                                <h4 class="text-xl font-bold mb-6 text-white flex items-center gap-2">
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    إعدادات المتجر
                                </h4>
                                
                                <form method="POST" action="{{ route('shop.update') }}" enctype="multipart/form-data" class="space-y-6 max-w-4xl">
                                    @csrf
                                    @method('PATCH')

                                        <div>
                                            <x-input-label for="edit_name" :value="__('اسم المتجر')" class="text-gray-400" />
                                            <x-text-input id="edit_name" class="block mt-1 w-full bg-gray-900 border-gray-700 text-white focus:ring-indigo-500" type="text" name="name" :value="old('name', $shop->name)" required />
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="edit_slug" :value="__('رابط المتجر (English Slug)')" class="text-gray-400" />
                                            <x-text-input id="edit_slug" class="block mt-1 w-full bg-gray-900 border-gray-700 text-white focus:ring-indigo-500 font-mono text-sm" type="text" name="slug" :value="old('slug', $shop->slug)" required />
                                            <p class="mt-1 text-[10px] text-gray-500">الرابط المباشر: {{ url('/shop/' . $shop->slug) }}</p>
                                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <x-input-label for="edit_logo" :value="__('تحديث الشعار')" class="text-gray-400" />
                                            <input id="edit_logo" type="file" name="logo" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer" accept="image/*" />
                                            <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                                        </div>

                                    <div>
                                        <x-input-label for="edit_hero" :value="__('تحديث صورة الغلاف (Hero Image)')" class="text-gray-400" />
                                        <input id="edit_hero" type="file" name="hero_image" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-700 file:text-white hover:file:bg-gray-600 cursor-pointer" accept="image/*" />
                                        <x-input-error :messages="$errors->get('hero_image')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_description" :value="__('وصف المتجر')" class="text-gray-400" />
                                        <textarea id="edit_description" name="description" rows="3" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 rounded-2xl focus:ring-indigo-500">{{ old('description', $shop->description) }}</textarea>
                                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-500 shadow-lg shadow-indigo-600/20">حفظ التغييرات</x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- Buyer Dashboard -->
                <div class="bg-gray-800 border-gray-700 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-100">
                        <h3 class="text-xl font-bold mb-4">أهلاً بك يا {{ auth()->user()->name }}!</h3>
                        <p>تصفح المتاجر واكتشف منتجات جديدة على منصة محلي.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Adding Cropper.js Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('logoCropper', () => ({
                showCropper: false,
                cropper: null,
                croppedData: '',
                name: '',
                slug: '',
                manualSlug: false,

                updateSlug() {
                    if (!this.manualSlug) {
                        this.slug = this.name
                            .toLowerCase()
                            .replace(/[^\w\s-]/g, '')
                            .replace(/[\s_-]+/g, '-')
                            .replace(/^-+|-+$/g, '');
                    }
                },
                
                loadFile(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            this.showCropper = true;
                            this.$nextTick(() => {
                                if (this.cropper) { this.cropper.destroy(); }
                                const image = document.getElementById('cropperImage');
                                image.src = event.target.result;
                                this.cropper = new Cropper(image, {
                                    aspectRatio: 1,
                                    viewMode: 1,
                                    dragMode: 'move',
                                    autoCropArea: 1,
                                });
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                },
                
                saveCrop() {
                    if (this.cropper) {
                        const canvas = this.cropper.getCroppedCanvas({
                            width: 300,
                            height: 300,
                            imageSmoothingEnabled: true,
                            imageSmoothingQuality: 'high',
                        });
                        this.croppedData = canvas.toDataURL('image/png');
                        this.showCropper = false;
                    }
                }
            }));
        });
    </script>
</x-app-layout>
