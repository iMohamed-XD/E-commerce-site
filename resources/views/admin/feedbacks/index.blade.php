<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-bold text-xl text-[#0d1b4b] leading-tight">التقييمات</h2>
            <span class="text-sm text-[#0d1b4b]/40 font-medium">{{ $totalFeedbacks }} تقييم — متوسط {{ number_format($averageRating, 1) }}/5</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter by rating -->
            <div class="flex gap-2 mb-6">
                <a href="{{ route('admin.feedbacks.index') }}"
                   class="px-4 py-2 rounded-xl text-sm font-bold transition {{ !request('rating') ? 'bg-[#0d1b4b] text-white' : 'bg-white border border-[#0d1b4b]/15 text-[#0d1b4b]/60 hover:bg-[#0d1b4b]/5' }}">الكل</a>
                @for($r = 5; $r >= 1; $r--)
                    <a href="{{ route('admin.feedbacks.index', ['rating' => $r]) }}"
                       class="px-4 py-2 rounded-xl text-sm font-bold transition {{ request('rating') == $r ? 'bg-[#d4af37] text-white' : 'bg-white border border-[#0d1b4b]/15 text-[#0d1b4b]/60 hover:bg-[#0d1b4b]/5' }}">
                        {{ $r }} ★
                    </a>
                @endfor
            </div>

            <div class="space-y-4">
                @forelse($feedbacks as $feedback)
                    <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-2xl p-6 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-black text-[#0d1b4b]">{{ $feedback->user->name ?? 'مجهول' }}</p>
                                <p class="text-xs text-[#0d1b4b]/40 mt-0.5">{{ $feedback->user->shop->name ?? 'بدون متجر' }} · {{ $feedback->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $feedback->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        @if($feedback->comments)
                            <p class="mt-3 text-[#0d1b4b]/70 font-medium leading-relaxed">{{ $feedback->comments }}</p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-20 text-[#0d1b4b]/40 font-bold">لا توجد تقييمات</div>
                @endforelse
            </div>

            <div class="mt-6">{{ $feedbacks->links() }}</div>
        </div>
    </div>
</x-app-layout>
