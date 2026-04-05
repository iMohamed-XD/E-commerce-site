<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-bold text-xl text-[#0d1b4b] leading-tight">أكواد الخصم</h2>
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
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">الكود</th>
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">المتجر</th>
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">الخصم</th>
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">الحالة</th>
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">الانتهاء</th>
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">إجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#0d1b4b]/5">
                        @forelse($promoCodes as $code)
                            <tr class="hover:bg-[#0d1b4b]/2 transition">
                                <td class="px-6 py-4 font-mono font-black text-[#0d1b4b] tracking-widest">{{ $code->code }}</td>
                                <td class="px-6 py-4 text-[#0d1b4b]/60">{{ $code->shop->name ?? '-' }}</td>
                                <td class="px-6 py-4 font-bold text-[#d4af37]">{{ $code->discount_percent }}%</td>
                                <td class="px-6 py-4">
                                    @if($code->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">مفعّل</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-lg text-xs font-bold">معطّل</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-[#0d1b4b]/50 text-xs">{{ $code->expires_at ? $code->expires_at->format('Y-m-d') : 'لا يوجد' }}</td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('admin.promo-codes.destroy', $code) }}" onsubmit="return confirm('حذف هذا الكود نهائياً؟');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs transition">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-[#0d1b4b]/40 font-bold">لا توجد أكواد خصم</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-[#0d1b4b]/5">
                    {{ $promoCodes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
