<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-[#0d1b4b] leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            تقييم المنصة
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-[#0d1b4b]/10">
                <div class="p-8 sm:p-12">
                    
                    @if(session('success'))
                        <div class="mb-8 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="text-center mb-10">
                        <h3 class="text-2xl font-black text-[#0d1b4b] mb-3">رأيك يهمنا</h3>
                        <p class="text-[#0d1b4b]/60">نسعى دائماً لتطوير منصة محلي. يرجى تزويدنا بتقييمك لتجربة استخدام المنصة.</p>
                    </div>

                    <form method="POST" action="{{ route('feedback.store') }}" class="space-y-8" x-data="{ rating: {{ $feedback ? $feedback->rating : 5 }} }">
                        @csrf
                        
                        <div class="text-center">
                            <label class="block text-sm font-bold text-[#0d1b4b] mb-4">تقييمك للمنصة (من 1 إلى 5)</label>
                            <div class="flex justify-center gap-2 flex-row-reverse">
                                @for($i=5; $i>=1; $i--)
                                    <button type="button" @click="rating = {{ $i }}" 
                                            class="p-2 transition-transform hover:scale-110"
                                            :class="rating >= {{ $i }} ? 'text-[#d4af37]' : 'text-gray-300'">
                                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" x-model="rating">
                            <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="comments" value="ملاحظاتك ومقترحاتك (اختياري)" />
                            <textarea id="comments" name="comments" rows="5" class="mt-2 w-full border-[#0d1b4b]/20 focus:border-[#d4af37] focus:ring-[#d4af37] rounded-xl shadow-sm text-sm p-4">{{ old('comments', $feedback->comments ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('comments')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-[#0d1b4b] hover:bg-[#0d1b4b]/90 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg shadow-[#0d1b4b]/20">
                                حفظ التقييم
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
