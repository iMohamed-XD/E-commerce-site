<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-[#0d1b4b] leading-tight">إدارة التصنيفات</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl overflow-hidden shadow-xl shadow-[#0d1b4b]/6 mb-8">
                <div class="px-6 py-4 border-b border-[#0d1b4b]/10">
                    <h3 class="text-lg font-black text-[#0d1b4b]">إضافة تصنيف جديد</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('categories.store') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                        @csrf
                        <div class="flex-grow">
                            <label class="block text-sm font-bold text-[#0d1b4b]/70 mb-1">اسم التصنيف</label>
                            <input type="text" name="name" required class="block w-full bg-white border border-[#0d1b4b]/15 text-[#0d1b4b] placeholder-[#0d1b4b]/30 rounded-xl shadow-sm focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20 text-sm p-3" placeholder="مثال: ملابس رجالية، إلكترونيات...">
                        </div>
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-[#0d1b4b] hover:bg-[#1a2d6b] text-white font-black rounded-xl text-sm transition shadow-lg shadow-[#0d1b4b]/20">حفظ التصنيف</button>
                    </form>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl overflow-hidden shadow-xl shadow-[#0d1b4b]/6">
                <div class="px-6 py-4 border-b border-[#0d1b4b]/10">
                    <h3 class="text-lg font-black text-[#0d1b4b]">تصنيفات متجرك</h3>
                </div>
                <div class="p-6">
                    @if($categories->isEmpty())
                        <p class="text-[#0d1b4b]/45 text-center py-8">لا يوجد تصنيفات حالياً. ابدأ بإضافة تصنيف جديد أعلاه.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($categories as $category)
                                <div class="flex items-center justify-between p-4 bg-white border border-[#0d1b4b]/10 rounded-2xl hover:border-[#d4af37]/40 transition-all group">
                                    <div>
                                        <div class="text-lg font-black text-[#0d1b4b] group-hover:text-[#a07c1e] transition-colors">{{ $category->name }}</div>
                                        <div class="text-xs text-[#0d1b4b]/45 mt-1">{{ $category->products_count }} منتج</div>
                                    </div>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا التصنيف؟ سيتم فك ارتباطه بجميع المنتجات.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-[#0d1b4b]/35 hover:text-red-600 transition" title="حذف">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
