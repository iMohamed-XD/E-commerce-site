<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-bold text-xl text-[#0d1b4b] leading-tight">إدارة المنتجات</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl font-bold">{{ session('success') }}</div>
            @endif

            <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl shadow-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-[#0d1b4b]/5 border-b border-[#0d1b4b]/10">
                        <tr>
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">المنتج</th>
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">المتجر</th>
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">السعر</th>
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">تاريخ الإضافة</th>
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">إجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#0d1b4b]/5">
                        @forelse($products as $product)
                            <tr class="hover:bg-[#0d1b4b]/2 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($product->image_path)
                                            <img src="{{ Storage::url($product->image_path) }}" class="w-10 h-10 rounded-xl object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded-xl bg-[#0d1b4b]/8 flex items-center justify-center text-[#0d1b4b]/30">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                        <span class="font-bold text-[#0d1b4b]">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[#0d1b4b]/60">{{ $product->shop->name ?? '-' }}</td>
                                <td class="px-6 py-4 font-bold text-[#0d1b4b]">{{ number_format($product->price, 2) }} ل.س</td>
                                <td class="px-6 py-4 text-[#0d1b4b]/50 text-xs">{{ $product->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('حذف هذا المنتج نهائياً؟');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs transition">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center text-[#0d1b4b]/40 font-bold">لا توجد منتجات</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-[#0d1b4b]/5">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
