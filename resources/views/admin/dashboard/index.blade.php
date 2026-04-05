<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-[#0d1b4b] leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            لوحة تحكم الإدارة
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Total Shops -->
                <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl p-6 shadow-xl shadow-[#0d1b4b]/5 flex items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl bg-[#0d1b4b]/5 flex items-center justify-center">
                        <svg class="w-8 h-8 text-[#0d1b4b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#0d1b4b]/50">إجمالي المتاجر</p>
                        <h3 class="text-3xl font-black text-[#0d1b4b] mt-1">{{ number_format($stats['total_shops'] ?? 0) }}</h3>
                    </div>
                </div>

                <!-- Total Sellers -->
                <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl p-6 shadow-xl shadow-[#0d1b4b]/5 flex items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl bg-[#d4af37]/10 flex items-center justify-center">
                        <svg class="w-8 h-8 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#0d1b4b]/50">إجمالي البائعين</p>
                        <h3 class="text-3xl font-black text-[#0d1b4b] mt-1">{{ number_format($stats['total_users'] ?? 0) }}</h3>
                    </div>
                </div>

                <!-- Average Feedback -->
                <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl p-6 shadow-xl shadow-[#0d1b4b]/5 flex items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#0d1b4b]/50">متوسط التقييم</p>
                        <h3 class="text-3xl font-black text-[#0d1b4b] mt-1">{{ number_format($stats['avg_feedback'] ?? 0, 1) }} <span class="text-sm font-medium text-[#0d1b4b]/40">/ 5.0</span></h3>
                    </div>
                </div>
            </div>

            <!-- Main Panels -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Management Links -->
                <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl p-8 shadow-xl shadow-[#0d1b4b]/5">
                    <h3 class="text-xl font-bold text-[#0d1b4b] mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        إدارة المنصة
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('admin.shops.index') }}" class="group block p-4 rounded-2xl bg-[#0d1b4b]/5 border border-[#0d1b4b]/10 hover:border-[#d4af37]/40 hover:shadow-lg transition">
                            <span class="block text-[#0d1b4b] font-bold group-hover:text-[#d4af37] transition">إدارة المتاجر</span>
                        </a>
                        <a href="{{ route('admin.sellers.index') }}" class="group block p-4 rounded-2xl bg-[#0d1b4b]/5 border border-[#0d1b4b]/10 hover:border-[#d4af37]/40 hover:shadow-lg transition">
                            <span class="block text-[#0d1b4b] font-bold group-hover:text-[#d4af37] transition">إدارة البائعين</span>
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="group block p-4 rounded-2xl bg-[#0d1b4b]/5 border border-[#0d1b4b]/10 hover:border-[#d4af37]/40 hover:shadow-lg transition">
                            <span class="block text-[#0d1b4b] font-bold group-hover:text-[#d4af37] transition">إدارة المنتجات</span>
                        </a>
                        <a href="{{ route('admin.promo-codes.index') }}" class="group block p-4 rounded-2xl bg-[#0d1b4b]/5 border border-[#0d1b4b]/10 hover:border-[#d4af37]/40 hover:shadow-lg transition">
                            <span class="block text-[#0d1b4b] font-bold group-hover:text-[#d4af37] transition">إدارة الأكواد</span>
                        </a>
                        <a href="{{ route('admin.payment-methods.index') }}" class="group block p-4 rounded-2xl bg-[#0d1b4b]/5 border border-[#0d1b4b]/10 hover:border-[#d4af37]/40 hover:shadow-lg transition">
                            <span class="block text-[#0d1b4b] font-bold group-hover:text-[#d4af37] transition">طرق الدفع للدعم</span>
                        </a>
                        <a href="{{ route('admin.feedback.index') }}" class="group block p-4 rounded-2xl bg-[#0d1b4b]/5 border border-[#0d1b4b]/10 hover:border-[#d4af37]/40 hover:shadow-lg transition">
                            <span class="block text-[#0d1b4b] font-bold group-hover:text-[#d4af37] transition">التقييمات</span>
                        </a>
                    </div>
                </div>

                <!-- Recent Feedback -->
                <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl p-8 shadow-xl shadow-[#0d1b4b]/5 overflow-hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-[#0d1b4b] flex items-center gap-2">
                            <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                            أحدث التقييمات
                        </h3>
                        <a href="{{ route('admin.feedback.index') }}" class="text-sm font-bold text-[#d4af37] hover:text-[#b8922a]">عرض الكل</a>
                    </div>
                    
                    @if(isset($recentFeedbacks) && $recentFeedbacks->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentFeedbacks as $feedback)
                                <div class="p-4 rounded-xl border border-[#0d1b4b]/10 bg-white">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center gap-2">
                                            @for($i=0; $i<$feedback->rating; $i++)
                                                <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                            @endfor
                                        </div>
                                        <span class="text-xs text-[#0d1b4b]/40">{{ $feedback->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm font-bold text-[#0d1b4b]">{{ $feedback->user->name }} - {{ $feedback->shop->name ?? 'البائع' }}</p>
                                    @if($feedback->comments)
                                        <p class="text-sm text-[#0d1b4b]/60 mt-1 line-clamp-2">{{ $feedback->comments }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-[#0d1b4b]/40 py-8">لا توجد تقييمات حتى الآن</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
