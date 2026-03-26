<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-100 leading-tight">كوبونات الخصم</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
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

            <!-- Add Promo Code Form -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-lg mb-8">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-bold text-gray-100">إضافة كوبون خصم جديد</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('promo-codes.store') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 items-end">
                        @csrf
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-200 mb-1">رمز الكوبون</label>
                            <input type="text" name="code" required class="block w-full bg-gray-700 border-gray-600 text-gray-100 placeholder-gray-400 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 uppercase font-mono tracking-wider" placeholder="مثال: EID20">
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-200 mb-1">نسبة الخصم (%)</label>
                            <input type="number" name="discount_percentage" step="0.01" min="0.01" max="100" required class="block w-full bg-gray-700 border-gray-600 text-gray-100 placeholder-gray-400 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3" placeholder="20">
                        </div>
                        <div class="sm:col-span-1">
                            <button type="submit" class="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg text-sm transition shadow-lg hover:shadow-indigo-500/20">حفظ الكوبون</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Promo Codes Table -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-lg">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-bold text-gray-100">كوبوناتك الحالية</h3>
                </div>
                <div class="p-6">
                    @if($promoCodes->isEmpty())
                        <p class="text-gray-400 text-center py-8">لا يوجد كوبونات خصم حالياً.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">الرمز</th>
                                        <th class="px-4 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">نسبة الخصم</th>
                                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">الحالة</th>
                                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    @foreach($promoCodes as $code)
                                         <tr class="hover:bg-gray-700/50 transition-colors border-b border-gray-700 last:border-0">
                                            <td class="px-4 py-5 font-mono font-bold text-[#d4af37] text-lg">{{ $code->code }}</td>
                                            <td class="px-4 py-5 font-bold text-white text-lg">{{ number_format($code->discount_percentage, 0) }}%</td>
                                            <td class="px-4 py-5 text-center">
                                                <form action="{{ route('promo-codes.toggle', $code) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($code->is_active)
                                                        <button type="submit" class="inline-flex items-center px-4 py-1.5 rounded-full bg-green-900/30 text-green-400 border border-green-700 hover:bg-green-900/50 transition duration-200">
                                                            <span class="w-2 h-2 rounded-full bg-green-500 me-2 animate-pulse"></span>
                                                            فعال
                                                        </button>
                                                    @else
                                                        <button type="submit" class="inline-flex items-center px-4 py-1.5 rounded-full bg-red-900/30 text-red-400 border border-red-700 hover:bg-red-900/50 transition duration-200">
                                                            <span class="w-2 h-2 rounded-full bg-red-500 me-2"></span>
                                                            غير فعال
                                                        </button>
                                                    @endif
                                                </form>
                                            </td>
                                            <td class="px-4 py-5 text-center flex items-center justify-center gap-4">
                                                <form action="{{ route('promo-codes.destroy', $code) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-gray-500 hover:text-red-400 transition" title="حذف">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
