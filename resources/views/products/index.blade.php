<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition" aria-label="الرجوع إلى لوحة التحكم">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-[#0d1b4b] leading-tight">إدارة المنتجات</h2>
        </div>
    </x-slot>

    <div class="py-12" x-data="productManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl p-6 shadow-xl shadow-[#0d1b4b]/6">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-black text-[#0d1b4b]">منتجات متجر: <span class="text-[#d4af37]">{{ $shop->name }}</span></h3>
                        <p class="text-sm text-[#0d1b4b]/45 mt-1">إجمالي النتائج: {{ $products->total() }}</p>
                    </div>
                    <a href="{{ route('products.create') }}" class="px-5 py-2.5 bg-[#0d1b4b] border border-[#0d1b4b] rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-[#1a2d6b] shadow-md shadow-[#0d1b4b]/20 transition">
                        + إضافة منتج جديد
                    </a>
                </div>

                <form method="GET" action="{{ route('products.index') }}" class="mt-5 grid grid-cols-1 md:grid-cols-5 gap-3">
                    <div>
                        <label for="products-field" class="block text-xs font-bold text-[#0d1b4b]/60 mb-1">الحقل</label>
                        <div class="relative">
                            <select id="products-field" name="field" class="appearance-none w-full rounded-xl border border-[#0d1b4b]/15 bg-white px-4 py-2.5 text-sm font-medium text-[#0d1b4b]/70 shadow-sm transition hover:bg-[#fdfbf4] hover:text-[#0d1b4b] focus:outline-none focus:ring-2 focus:ring-[#d4af37]/40">
                                <option value="">اختر الحقل للتصفية</option>
                                <option value="id" @selected($field === 'id')>رقم المنتج</option>
                                <option value="name" @selected($field === 'name')>الاسم</option>
                                <option value="description" @selected($field === 'description')>الوصف</option>
                                <option value="category_name" @selected($field === 'category_name')>الفئة</option>
                                <option value="price" @selected($field === 'price')>السعر</option>
                                <option value="quantity_available" @selected($field === 'quantity_available')>الكمية المتاحة</option>
                                <option value="is_active" @selected($field === 'is_active')>نشط / غير نشط</option>
                                <option value="discount_percent" @selected($field === 'discount_percent')>نسبة الخصم</option>
                                <option value="discount_active" @selected($field === 'discount_active')>حالة الخصم</option>
                                <option value="created_at" @selected($field === 'created_at')>تاريخ الإنشاء</option>
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 start-3 flex items-center text-[#0d1b4b]/45">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="products-value-text" class="block text-xs font-bold text-[#0d1b4b]/60 mb-1">القيمة</label>
                        <input id="products-value-text" name="value" value="{{ $value }}" type="text" class="w-full bg-white border border-[#0d1b4b]/15 rounded-xl px-3 py-2.5 text-sm text-[#0d1b4b]" placeholder="اكتب قيمة البحث أو التصفية">
                        <div id="products-value-select-wrap" class="relative hidden">
                            <select id="products-value-select" class="appearance-none w-full rounded-xl border border-[#0d1b4b]/15 bg-white px-4 py-2.5 text-sm font-medium text-[#0d1b4b]/70 shadow-sm transition hover:bg-[#fdfbf4] hover:text-[#0d1b4b] focus:outline-none focus:ring-2 focus:ring-[#d4af37]/40"></select>
                            <span class="pointer-events-none absolute inset-y-0 start-3 flex items-center text-[#0d1b4b]/45">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </div>
                    </div>

                    <div>
                        <label for="products-per-page" class="block text-xs font-bold text-[#0d1b4b]/60 mb-1">عدد النتائج</label>
                        <div class="relative">
                            <select id="products-per-page" name="per_page" class="appearance-none w-full rounded-xl border border-[#0d1b4b]/15 bg-white px-4 py-2.5 text-sm font-medium text-[#0d1b4b]/70 shadow-sm transition hover:bg-[#fdfbf4] hover:text-[#0d1b4b] focus:outline-none focus:ring-2 focus:ring-[#d4af37]/40">
                                @foreach([10,15,20,25,30] as $size)
                                    <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }} لكل صفحة</option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 start-3 flex items-center text-[#0d1b4b]/45">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-[#0d1b4b] text-white font-black rounded-xl py-2.5 text-sm hover:bg-[#1a2d6b] transition">تصفية</button>
                        <a href="{{ route('products.index', ['per_page' => $perPage]) }}" class="px-4 py-2.5 border border-[#0d1b4b]/15 rounded-xl text-sm font-bold text-[#0d1b4b]/70 bg-white hover:bg-[#fdfbf4] transition">إعادة ضبط</a>
                    </div>
                </form>
            </div>

            <div x-show="selectedProducts.length > 0" x-transition x-cloak class="bg-white/80 border border-[#d4af37]/30 shadow-lg rounded-2xl p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                <span class="font-black text-[#a07c1e]">تم تحديد <span x-text="selectedProducts.length"></span> منتجات</span>
                <div class="flex flex-wrap gap-2">
                    <button @click="showDiscountModal = true" class="px-4 py-2 bg-[#d4af37] text-[#0d1b4b] rounded-xl text-sm font-black hover:bg-[#c5a02e] shadow-sm transition">تفعيل الخصم للمحدد</button>
                    <button @click="submitBulkAction('remove_discount')" class="px-4 py-2 bg-[#0d1b4b]/8 text-[#0d1b4b] rounded-xl text-sm font-bold hover:bg-[#0d1b4b]/12 shadow-sm transition">إيقاف الخصومات للمحدد</button>
                    <button @click="if(confirm('هل أنت متأكد من حذف المنتجات المحددة؟')) submitBulkAction('delete')" class="px-4 py-2 bg-red-500 text-white rounded-xl text-sm font-bold hover:bg-red-600 shadow-sm transition">حذف المحدد</button>
                    <button @click="selectedProducts = []" class="px-4 py-2 bg-white border border-[#0d1b4b]/15 text-[#0d1b4b]/60 rounded-xl text-sm font-bold hover:bg-[#fdfbf4] transition">إلغاء التحديد</button>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 overflow-hidden shadow-xl shadow-[#0d1b4b]/6 sm:rounded-3xl">
                <div class="p-6 text-[#0d1b4b] overflow-x-auto">
                    @if($products->isEmpty())
                        <div class="text-center py-16">
                            <p class="text-[#0d1b4b]/45 text-lg">لا توجد منتجات مطابقة للمرشحات الحالية.</p>
                            <a href="{{ route('products.create') }}" class="mt-4 inline-block px-5 py-2.5 bg-[#0d1b4b] text-white rounded-xl text-sm font-black hover:bg-[#1a2d6b] transition">+ أضف منتجاً جديداً</a>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-[#0d1b4b]/10">
                            <thead class="bg-[#f4f7ff]">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right">
                                        <input type="checkbox" @change="toggleAll($event)" class="rounded border-[#0d1b4b]/20 bg-white text-[#d4af37] shadow-sm focus:ring-[#d4af37]/30">
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-[#0d1b4b]/45 uppercase tracking-wider">المنتج</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-[#0d1b4b]/45 uppercase tracking-wider">السعر</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-[#0d1b4b]/45 uppercase tracking-wider">حالة الخصم</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-[#0d1b4b]/45 uppercase tracking-wider">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white/50 divide-y divide-[#0d1b4b]/8">
                                @foreach($products as $product)
                                    <tr :class="selectedProducts.includes({{ $product->id }}) ? 'bg-[#d4af37]/10 ring-1 ring-inset ring-[#d4af37]/35' : 'hover:bg-[#0d1b4b]/4'" class="transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" value="{{ $product->id }}" x-model="selectedProducts" class="rounded border-[#0d1b4b]/20 bg-white text-[#d4af37] shadow-sm focus:ring-[#d4af37]/30 product-checkbox">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($product->image_path)
                                                        <img class="h-10 w-10 rounded-full object-cover shadow border border-[#0d1b4b]/15" src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-[#0d1b4b]/5 flex items-center justify-center text-[#0d1b4b]/35 border border-[#0d1b4b]/12">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ms-4">
                                                    <div class="text-sm font-black text-[#0d1b4b]">{{ $product->name }}</div>
                                                    <div class="text-xs text-[#0d1b4b]/50 font-semibold mt-1">الفئة: {{ $product->category?->name ?? 'غير مصنف' }}</div>
                                                    <div class="text-xs text-[#0d1b4b]/50 font-semibold mt-1">المتاح: {{ (int) $product->quantity_available }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#0d1b4b]/50">
                                            @php $effPrice = $product->effectivePrice(); @endphp
                                            @if($product->hasActiveDiscount())
                                                <span class="line-through text-[#0d1b4b]/35 block text-xs">{{ number_format($product->price, 2) }} ل.س</span>
                                                <span class="text-[#a07c1e] font-bold">{{ number_format($effPrice, 2) }} ل.س</span>
                                                <span class="text-[#a07c1e] text-xs block">(-{{ $product->discount_percent }}%)</span>
                                            @else
                                                <span class="font-black text-[#0d1b4b]">{{ number_format($product->price, 2) }} ل.س</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                             @if($product->discount_percent)
                                                <form action="{{ route('products.toggle_discount', $product) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($product->discount_active)
                                                        <button type="submit" class="px-3 py-1 text-[10px] font-bold rounded-full bg-[#d4af37]/10 text-[#a07c1e] border border-[#d4af37]/30 hover:bg-[#d4af37]/20 transition">إيقاف الخصم</button>
                                                    @else
                                                        <button type="submit" class="px-3 py-1 text-[10px] font-bold rounded-full bg-white text-[#0d1b4b]/70 border border-[#0d1b4b]/15 hover:bg-[#fdfbf4] transition">تفعيل الخصم</button>
                                                    @endif
                                                </form>
                                            @else
                                                <span class="text-[10px] text-[#0d1b4b]/40">لا يوجد خصم</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            @can('manage', $product)
                                                <a href="{{ route('products.edit', $product) }}" class="text-[#d4af37] hover:text-[#b8922a] inline-block ms-3 font-bold transition">تعديل</a>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-600 border-0 bg-transparent cursor-pointer font-bold ms-2 transition">حذف</button>
                                                </form>
                                            @else
                                                <span class="text-[#0d1b4b]/40 italic">غير مصرح</span>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            @if($products->lastPage() > 1)
                <div class="bg-white/70 border border-[#0d1b4b]/10 rounded-2xl px-4 py-3 flex flex-wrap items-center justify-between gap-3">
                    <div class="text-sm text-[#0d1b4b]/60">الصفحة {{ $products->currentPage() }} من {{ $products->lastPage() }}</div>
                    <div class="flex items-center gap-1">
                        @if($products->onFirstPage())
                            <span class="px-3 py-1.5 rounded-lg bg-[#0d1b4b]/5 text-[#0d1b4b]/30 text-sm font-bold">السابق</span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-[#0d1b4b]/15 bg-white text-[#0d1b4b] text-sm font-bold hover:bg-[#fdfbf4]">السابق</a>
                        @endif

                        @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                            <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg text-sm font-bold {{ $page === $products->currentPage() ? 'bg-[#0d1b4b] text-white' : 'border border-[#0d1b4b]/15 bg-white text-[#0d1b4b] hover:bg-[#fdfbf4]' }}">{{ $page }}</a>
                        @endforeach

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-[#0d1b4b]/15 bg-white text-[#0d1b4b] text-sm font-bold hover:bg-[#fdfbf4]">التالي</a>
                        @else
                            <span class="px-3 py-1.5 rounded-lg bg-[#0d1b4b]/5 text-[#0d1b4b]/30 text-sm font-bold">التالي</span>
                        @endif
                    </div>
                </div>
            @endif

            <div x-show="showDiscountModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div x-show="showDiscountModal" x-transition.opacity class="fixed inset-0 bg-[#0d1b4b]/45 backdrop-blur-sm" @click="showDiscountModal = false"></div>

                    <div x-show="showDiscountModal" x-transition class="relative bg-white border border-[#0d1b4b]/10 rounded-2xl shadow-2xl shadow-[#0d1b4b]/15 w-full max-w-md z-10">
                        <div class="px-6 py-4 border-b border-[#0d1b4b]/10 flex items-center justify-between">
                            <h3 class="text-lg font-black text-[#0d1b4b]">تطبيق خصم جماعي</h3>
                            <button @click="showDiscountModal = false" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="px-6 py-5 space-y-4">
                            <p class="text-xs text-[#0d1b4b]/45">سيتم تطبيق هذا الخصم على <span class="text-[#a07c1e] font-bold" x-text="selectedProducts.length"></span> منتجات محددة.</p>
                             <div>
                                <label class="block text-sm font-bold text-[#0d1b4b]/70 mb-1">نسبة الخصم (%)</label>
                                <input type="number" step="0.01" min="0" max="100" x-model="discountPercent"
                                    class="w-full bg-white border border-[#0d1b4b]/15 text-[#0d1b4b] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4af37]/20 focus:border-[#d4af37] placeholder-[#0d1b4b]/30"
                                    placeholder="مثال: 20">
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-[#0d1b4b]/10 flex gap-3">
                            <button type="button" @click="submitBulkAction('discount')" class="flex-1 bg-[#d4af37] hover:bg-[#c5a02e] text-[#0d1b4b] font-black py-2.5 rounded-xl text-sm transition">تطبيق الخصم</button>
                            <button type="button" @click="showDiscountModal = false" class="flex-1 bg-white hover:bg-[#fdfbf4] border border-[#0d1b4b]/15 text-[#0d1b4b]/60 font-bold py-2.5 rounded-xl text-sm transition">إلغاء</button>
                        </div>
                    </div>
                </div>
            </div>

            <form id="bulkForm" method="POST" action="{{ route('products.bulk_action') }}" class="hidden">
                @csrf
                <input type="hidden" name="action" x-model="bulkAction">
                <input type="hidden" name="product_ids" x-model="JSON.stringify(selectedProducts)">
                <input type="hidden" name="discount_percent" x-model="discountPercent">
            </form>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fieldInput = document.getElementById('products-field');
            const textInput = document.getElementById('products-value-text');
            const selectWrap = document.getElementById('products-value-select-wrap');
            const selectInput = document.getElementById('products-value-select');

            if (!fieldInput || !textInput || !selectWrap || !selectInput) {
                return;
            }

            const initialValue = @json((string) $value);
            const optionsByField = {
                is_active: [
                    { value: '1', label: 'نشط' },
                    { value: '0', label: 'غير نشط' },
                ],
                discount_active: [
                    { value: '1', label: 'مفعّل' },
                    { value: '0', label: 'غير مفعّل' },
                ],
            };

            const syncValueInput = () => {
                const fieldName = fieldInput.value;
                const options = optionsByField[fieldName] ?? null;

                if (!options) {
                    textInput.classList.remove('hidden');
                    textInput.setAttribute('name', 'value');
                    selectWrap.classList.add('hidden');
                    selectInput.removeAttribute('name');
                    return;
                }

                selectInput.innerHTML = options
                    .map((option) => `<option value="${option.value}">${option.label}</option>`)
                    .join('');

                if (initialValue && options.some((option) => option.value === initialValue)) {
                    selectInput.value = initialValue;
                } else {
                    selectInput.selectedIndex = 0;
                }

                textInput.classList.add('hidden');
                textInput.removeAttribute('name');
                selectWrap.classList.remove('hidden');
                selectInput.setAttribute('name', 'value');
            };

            fieldInput.addEventListener('change', syncValueInput);
            syncValueInput();
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('productManager', () => ({
                selectedProducts: [],
                showDiscountModal: false,
                bulkAction: '',
                discountPercent: '',

                toggleAll(e) {
                    if (e.target.checked) {
                        this.selectedProducts = Array.from(document.querySelectorAll('.product-checkbox')).map(cb => parseInt(cb.value, 10));
                    } else {
                        this.selectedProducts = [];
                    }
                },

                submitBulkAction(action) {
                    this.bulkAction = action;
                    if (action === 'discount' && !this.discountPercent) {
                        alert('يرجى إدخال نسبة الخصم (0-100).');
                        return;
                    }
                    this.showDiscountModal = false;
                    setTimeout(() => {
                        document.getElementById('bulkForm').submit();
                    }, 50);
                }
            }));
        });
    </script>
</x-app-layout>
