<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">إدارة المنتجات</h2>
        </div>
    </x-slot>

    <div class="py-12" x-data="productManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-900 border border-green-600 text-green-200 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-900 border border-red-600 text-red-200 px-4 py-3 rounded-lg mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-2xl font-bold text-gray-100">منتجات متجر: <span class="text-[#d4af37]">{{ $shop->name }}</span></h3>
                <a href="{{ route('products.create') }}" class="px-4 py-2 bg-indigo-600 border border-indigo-500 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 shadow-md transition">
                    + إضافة منتج جديد
                </a>
            </div>

            <!-- Bulk Actions Bar -->
            <div x-show="selectedProducts.length > 0" x-transition x-cloak class="bg-gray-800 border border-indigo-700 shadow-lg rounded-lg p-4 mb-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                <span class="font-bold text-indigo-300">تم تحديد <span x-text="selectedProducts.length"></span> منتجات</span>
                <div class="flex flex-wrap gap-2">
                    <button @click="showDiscountModal = true" class="px-4 py-2 bg-green-600 text-white rounded text-sm font-semibold hover:bg-green-500 shadow-sm transition">تفعيل الخصم للمحدد</button>
                    <button @click="submitBulkAction('remove_discount')" class="px-4 py-2 bg-gray-600 text-white rounded text-sm font-semibold hover:bg-gray-500 shadow-sm transition">إيقاف الخصومات للمحدد</button>
                    <button @click="if(confirm('هل أنت متأكد من حذف المنتجات المحددة؟')) submitBulkAction('delete')" class="px-4 py-2 bg-red-700 text-white rounded text-sm font-semibold hover:bg-red-600 shadow-sm transition">حذف المحدد</button>
                    <button @click="selectedProducts = []" class="px-4 py-2 bg-gray-700 border border-gray-600 text-gray-300 rounded text-sm font-semibold hover:bg-gray-600 transition">× إلغاء التحديد</button>
                </div>
            </div>

            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100 overflow-x-auto">
                    @if($products->isEmpty())
                        <div class="text-center py-16">
                            <p class="text-gray-400 text-lg">لم تقم بإضافة أي منتجات حتى الآن.</p>
                            <a href="{{ route('products.create') }}" class="mt-4 inline-block px-5 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-500 transition">+ أضف أول منتج</a>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-900">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right">
                                        <input type="checkbox" @change="toggleAll($event)" class="rounded border-gray-600 bg-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">المنتج</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">السعر</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">الحالة</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                @foreach($products as $product)
                                    <tr :class="selectedProducts.includes({{ $product->id }}) ? 'bg-indigo-950 ring-1 ring-inset ring-indigo-700' : 'hover:bg-gray-700'" class="transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" value="{{ $product->id }}" x-model="selectedProducts" class="rounded border-gray-600 bg-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 product-checkbox">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($product->image_path)
                                                        <img class="h-10 w-10 rounded-full object-cover shadow border border-gray-700" src="{{ asset('storage/' . $product->image_path) }}" alt="">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 border border-gray-600">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ms-4">
                                                    <div class="text-sm font-bold text-gray-100">{{ $product->name }}</div>
                                                    @if($product->discount_percent && $product->discount_active)
                                                        <div class="text-xs text-green-400 font-bold mt-1 inline-flex items-center gap-1">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                                            🏷 خصم {{ $product->discount_percent }}% مفعّل
                                                        </div>
                                                    @elseif($product->discount_percent)
                                                        <div class="text-xs text-gray-500 font-semibold mt-1">🏷 خصم {{ $product->discount_percent }}% (غير مفعل)</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                            @php $effPrice = $product->effectivePrice(); @endphp
                                            @if($product->hasActiveDiscount())
                                                <span class="line-through text-gray-500 block text-xs">{{ number_format($product->price, 2) }} ل.س</span>
                                                <span class="text-green-400 font-bold">{{ number_format($effPrice, 2) }} ل.س</span>
                                                <span class="text-green-600 text-xs block">(-{{ $product->discount_percent }}%)</span>
                                            @else
                                                <span class="font-bold text-gray-100">{{ number_format($product->price, 2) }} ل.س</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                             @if($product->discount_percent)
                                                <form action="{{ route('products.toggle_discount', $product) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($product->discount_active)
                                                        <button type="submit" class="px-3 py-1 text-[10px] font-bold rounded-full bg-green-900/30 text-green-400 border border-green-700 hover:bg-green-900/50 transition">إيقاف الخصم</button>
                                                    @else
                                                        <button type="submit" class="px-3 py-1 text-[10px] font-bold rounded-full bg-gray-700 text-gray-300 border border-gray-600 hover:bg-gray-600 transition">تفعيل الخصم</button>
                                                    @endif
                                                </form>
                                            @else
                                                <span class="text-[10px] text-gray-500">لا يوجد خصم</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            @can('manage', $product)
                                                <a href="{{ route('products.edit', $product) }}" class="text-indigo-400 hover:text-indigo-200 inline-block ms-3 font-semibold transition">تعديل</a>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-300 border-0 bg-transparent cursor-pointer font-semibold ms-2 transition">حذف</button>
                                                </form>
                                            @else
                                                <span class="text-gray-500 italic">غير مصرح</span>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Bulk Discount Modal -->
            <div x-show="showDiscountModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div x-show="showDiscountModal" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-75" @click="showDiscountModal = false"></div>
                    
                    <div x-show="showDiscountModal" x-transition class="relative bg-gray-900 border border-gray-700 rounded-xl shadow-2xl w-full max-w-md z-10">
                        <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-100">تطبيق خصم جماعي</h3>
                            <button @click="showDiscountModal = false" class="text-gray-400 hover:text-gray-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="px-6 py-5 space-y-4">
                            <p class="text-xs text-gray-400">سيتم تطبيق هذا الخصم على <span class="text-indigo-300 font-bold" x-text="selectedProducts.length"></span> منتجات محددة.</p>
                             <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">نسبة الخصم (%) *</label>
                                <input type="number" step="0.01" min="0" max="100" x-model="discountPercent"
                                    class="w-full bg-gray-700 border border-gray-600 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-gray-500"
                                    placeholder="مثال: 20">
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-700 flex gap-3">
                            <button type="button" @click="submitBulkAction('discount')" class="flex-1 bg-green-700 hover:bg-green-600 text-white font-semibold py-2.5 rounded-lg text-sm transition">تطبيق الخصم</button>
                            <button type="button" @click="showDiscountModal = false" class="flex-1 bg-gray-700 hover:bg-gray-600 text-gray-300 font-semibold py-2.5 rounded-lg text-sm transition">إلغاء</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Form for submission -->
            <form id="bulkForm" method="POST" action="{{ route('products.bulk_action') }}" class="hidden">
                @csrf
                <input type="hidden" name="action" x-model="bulkAction">
                <input type="hidden" name="product_ids" x-model="JSON.stringify(selectedProducts)">
                <input type="hidden" name="discount_percent" x-model="discountPercent">
            </form>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('productManager', () => ({
                selectedProducts: [],
                showDiscountModal: false,
                bulkAction: '',
                discountPercent: '',
                discountStarts: '',
                discountEnds: '',

                toggleAll(e) {
                    if (e.target.checked) {
                        this.selectedProducts = Array.from(document.querySelectorAll('.product-checkbox')).map(cb => parseInt(cb.value));
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
