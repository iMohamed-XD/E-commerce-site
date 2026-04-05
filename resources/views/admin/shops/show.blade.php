<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.shops.index') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h2 class="font-bold text-xl text-[#0d1b4b] leading-tight">إدارة متجر: {{ $shop->name }}</h2>
            </div>
            <a href="{{ route('shop.show', $shop->slug) }}" target="_blank" class="bg-[#0d1b4b] text-white px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-[#0d1b4b]/90 transition">
                <span>زيارة المتجر</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl font-bold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- 1. Shop & Seller Info -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Shop Details -->
                <div class="lg:col-span-2 bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-[2rem] p-8 shadow-xl shadow-[#0d1b4b]/5">
                    <h3 class="text-xl font-black text-[#0d1b4b] mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        بيانات المتجر
                    </h3>
                    <form action="{{ route('admin.shops.update', $shop) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-[#0d1b4b]/50 mb-2">اسم المتجر</label>
                                <input type="text" name="name" value="{{ $shop->name }}" class="w-full bg-[#0d1b4b]/5 border-0 rounded-2xl p-4 text-[#0d1b4b] focus:ring-2 focus:ring-[#d4af37]">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#0d1b4b]/50 mb-2">الرابط البديل (Slug)</label>
                                <input type="text" disabled value="{{ $shop->slug }}" class="w-full bg-[#0d1b4b]/2 border-0 rounded-2xl p-4 text-[#0d1b4b]/40 cursor-not-allowed">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#0d1b4b]/50 mb-2">وصف المتجر</label>
                            <textarea name="description" rows="3" class="w-full bg-[#0d1b4b]/5 border-0 rounded-2xl p-4 text-[#0d1b4b] focus:ring-2 focus:ring-[#d4af37]">{{ $shop->description }}</textarea>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="bg-[#d4af37] text-white px-8 py-4 rounded-2xl font-black shadow-lg shadow-[#d4af37]/20 hover:bg-[#b8922a] transition">تحديث البيانات</button>
                        </div>
                    </form>
                </div>

                <!-- Seller Info -->
                <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-[2rem] p-8 shadow-xl shadow-[#0d1b4b]/5">
                    <h3 class="text-xl font-black text-[#0d1b4b] mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        معلومات البائع
                    </h3>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 p-4 bg-[#0d1b4b]/5 rounded-2xl">
                            <div class="w-12 h-12 rounded-xl bg-[#0d1b4b] flex items-center justify-center text-white font-black text-xl">
                                {{ mb_substr($shop->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[#0d1b4b] font-black">{{ $shop->user->name }}</p>
                                <p class="text-[#0d1b4b]/40 text-xs">{{ $shop->user->email }}</p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-[#0d1b4b]/5 space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-[#0d1b4b]/40 font-bold">تاريخ الانضمام</span>
                                <span class="text-[#0d1b4b] font-black">{{ $shop->user->created_at->format('Y-m-d') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-[#0d1b4b]/40 font-bold">عدد المنتجات</span>
                                <span class="text-[#0d1b4b] font-black">{{ $shop->products->count() }}</span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.sellers.destroy', $shop->user) }}" onsubmit="return confirm('حذف هذا البائع وجميع بياناته؟ سيؤدي ذلك لحذف المتجر والمنتجات أيضاً!');" class="pt-6">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full text-red-500 font-bold text-sm bg-red-50 py-3 rounded-xl hover:bg-red-100 transition">حذف حساب البائع</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 2. Products Section -->
            <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-[2rem] shadow-xl shadow-[#0d1b4b]/5 overflow-hidden">
                <div class="p-8 border-b border-[#0d1b4b]/5 flex items-center justify-between bg-white/40">
                    <h3 class="text-xl font-black text-[#0d1b4b] flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10l-8-4m8 4v10M4 7v10l8 4"/></svg>
                        منتجات المتجر ({{ $shop->products->count() }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#0d1b4b]/5">
                            <tr class="text-[#0d1b4b]/60 text-xs font-black uppercase tracking-widest text-right">
                                <th class="px-8 py-4">المنتج</th>
                                <th class="px-8 py-4">السعر</th>
                                <th class="px-8 py-4">التوفير</th>
                                <th class="px-8 py-4">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#0d1b4b]/5">
                            @forelse($shop->products as $product)
                                <tr class="hover:bg-[#0d1b4b]/2 transition">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($product->image_path)
                                                <img src="{{ Storage::url($product->image_path) }}" class="w-12 h-12 rounded-xl object-cover shadow-sm bg-white p-0.5">
                                            @else
                                                <div class="w-12 h-12 rounded-xl bg-[#0d1b4b]/10 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-[#0d1b4b]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            @endif
                                            <span class="font-black text-[#0d1b4b]">{{ $product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 font-black text-[#0d1b4b]">{{ number_format($product->price, 0) }} ل.س</td>
                                    <td class="px-8 py-4">
                                        @if($product->discount_percent > 0)
                                            <span class="bg-red-50 text-red-600 px-3 py-1 rounded-lg text-xs font-black">{{ $product->discount_percent }}%</span>
                                        @else
                                            <span class="text-[#0d1b4b]/30">-</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-4">
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('حذف هذا المنتج؟');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 font-bold hover:underline">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-8 py-12 text-center text-[#0d1b4b]/40 font-bold">لا توجد منتجات مسجلة</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. Promo Codes Section -->
            <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-[2rem] shadow-xl shadow-[#0d1b4b]/5 overflow-hidden">
                <div class="p-8 border-b border-[#0d1b4b]/5 flex items-center justify-between bg-white/40">
                    <h3 class="text-xl font-black text-[#0d1b4b] flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h.01M13 11h.01M13 15h.01M17 7h.01M17 11h.01M17 15h.01"/></svg>
                        أكواد الخصم ({{ $shop->promoCodes->count() }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#0d1b4b]/5">
                            <tr class="text-[#0d1b4b]/60 text-xs font-black uppercase tracking-widest text-right">
                                <th class="px-8 py-4">الكود</th>
                                <th class="px-8 py-4">الخصم</th>
                                <th class="px-8 py-4">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#0d1b4b]/5">
                            @forelse($shop->promoCodes as $code)
                                <tr class="hover:bg-[#0d1b4b]/2 transition">
                                    <td class="px-8 py-4 font-mono font-black text-[#0d1b4b] tracking-widest">{{ $code->code }}</td>
                                    <td class="px-8 py-4 font-black text-[#d4af37]">{{ $code->discount_percent }}%</td>
                                    <td class="px-8 py-4">
                                        <form method="POST" action="{{ route('admin.promo-codes.destroy', $code) }}" onsubmit="return confirm('حذف هذا الكود؟');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 font-bold hover:underline">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-8 py-12 text-center text-[#0d1b4b]/40 font-bold">لا توجد أكواد خصم</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
