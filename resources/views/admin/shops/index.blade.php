<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-[#0d1b4b] leading-tight">إدارة المتاجر</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl p-8 shadow-xl shadow-[#0d1b4b]/5 overflow-hidden">
                
                @if(session('success'))
                    <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="border-b-2 border-[#0d1b4b]/10 text-[#0d1b4b]/50 text-sm">
                                <th class="pb-3 px-4 font-bold">اسم المتجر</th>
                                <th class="pb-3 px-4 font-bold">الرابط</th>
                                <th class="pb-3 px-4 font-bold">مالك المتجر</th>
                                <th class="pb-3 px-4 font-bold">اللون</th>
                                <th class="pb-3 px-4 font-bold text-center">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shops as $shop)
                                <tr class="border-b border-[#0d1b4b]/5 hover:bg-[#fdfbf4] transition">
                                    <td class="py-4 px-4 font-bold text-[#0d1b4b]">{{ $shop->name }}</td>
                                    <td class="py-4 px-4 text-[#0d1b4b]/60" dir="ltr">{{ $shop->slug }}</td>
                                    <td class="py-4 px-4 text-[#0d1b4b]/80">{{ $shop->user->name ?? 'غير معروف' }}</td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full border border-black/10" style="background-color: {{ $shop->color_hex }}"></div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-4">
                                        <a href="{{ route('admin.shops.show', $shop) }}" class="text-[#d4af37] font-bold hover:underline">إدارة</a>
                                        <form method="POST" action="{{ route('admin.shops.destroy', $shop) }}" onsubmit="return confirm('هل أنت متأكد من حذف المتجر وجميع منتجاته وصوره نهائياً؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-bold transition">حذف</button>
                                        </form>
                                    </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-[#0d1b4b]/40">لا توجد متاجر حالياً</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $shops->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
