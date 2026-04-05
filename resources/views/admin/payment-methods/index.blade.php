<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard.index') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-[#0d1b4b] leading-tight">إدارة طرق الدفع</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-end">
                <a href="{{ route('admin.payment-methods.create') }}" class="bg-[#d4af37] hover:bg-[#b8922a] text-white font-bold py-2 px-6 rounded-xl transition shadow-lg shadow-[#d4af37]/20">
                    إضافة طريقة دفع
                </a>
            </div>

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
                                <th class="pb-3 px-4 font-bold">الطريقة (الاسم)</th>
                                <th class="pb-3 px-4 font-bold">الحساب</th>
                                <th class="pb-3 px-4 font-bold">الحالة</th>
                                <th class="pb-3 px-4 font-bold text-center">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentMethods as $pm)
                                <tr class="border-b border-[#0d1b4b]/5 hover:bg-[#fdfbf4] transition">
                                    <td class="py-4 px-4 font-bold text-[#0d1b4b] flex items-center gap-3">
                                        @if($pm->logo_path)
                                            <img src="{{ Storage::disk('media')->temporaryUrl($pm->logo_path, now()->addHours(2)) }}" class="h-8 w-8 object-contain bg-white rounded p-1 border border-black/5" alt="">
                                        @endif
                                        {{ $pm->name }}
                                    </td>
                                    <td class="py-4 px-4 text-[#0d1b4b]/80" dir="ltr">{{ $pm->account_id }}</td>
                                    <td class="py-4 px-4">
                                        @if($pm->is_active)
                                            <span class="text-green-600 bg-green-50 px-2 py-1 rounded text-xs font-bold">نشط</span>
                                        @else
                                            <span class="text-red-600 bg-red-50 px-2 py-1 rounded text-xs font-bold">غير نشط</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <form method="POST" action="{{ route('admin.payment-methods.destroy', $pm) }}" onsubmit="return confirm('هل أنت متأكد من حذف طريقة الدفع هذه؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-bold px-3 py-1 bg-red-50 hover:bg-red-100 rounded-lg transition">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-[#0d1b4b]/40">لا توجد طرق دفع حالياً</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
