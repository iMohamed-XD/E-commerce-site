<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-bold text-xl text-[#0d1b4b] leading-tight">إدارة البائعين</h2>
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
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">البائع</th>
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">البريد</th>
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">المتجر</th>
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">تاريخ التسجيل</th>
                            <th class="px-6 py-4 text-right font-black text-[#0d1b4b]/60 text-xs uppercase tracking-widest">إجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#0d1b4b]/5">
                        @forelse($sellers as $seller)
                            <tr class="hover:bg-[#0d1b4b]/2 transition">
                                <td class="px-6 py-4 font-bold text-[#0d1b4b]">{{ $seller->name }}</td>
                                <td class="px-6 py-4 text-[#0d1b4b]/60 font-mono text-xs">{{ $seller->email }}</td>
                                <td class="px-6 py-4">
                                    @if($seller->shop)
                                        <a href="{{ route('shop.show', $seller->shop->slug) }}" target="_blank" class="text-[#d4af37] font-bold hover:underline">{{ $seller->shop->name }}</a>
                                    @else
                                        <span class="text-[#0d1b4b]/35 text-xs">لا يوجد متجر</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-[#0d1b4b]/50 text-xs">{{ $seller->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('admin.sellers.destroy', $seller) }}" onsubmit="return confirm('حذف هذا البائع وجميع بياناته نهائياً؟');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs transition">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center text-[#0d1b4b]/40 font-bold">لا يوجد بائعون مسجلون</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-[#0d1b4b]/5">
                    {{ $sellers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
