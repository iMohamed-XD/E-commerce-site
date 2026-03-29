<section id="faq" class="relative z-10 py-32 px-6 border-t border-gray-800/50 bg-black/20 backdrop-blur-sm">
    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-20">
            <span class="inline-block px-4 py-1.5 rounded-full border border-[#d4af37]/30 text-[#d4af37] text-xs font-bold tracking-widest uppercase mb-6">
                الأسئلة الشائعة
            </span>
            <h2 class="text-4xl md:text-5xl font-black text-white mb-4">
                كل ما تحتاج معرفته عن <span class="text-[#d4af37]">محلي</span>
            </h2>
            <p class="text-gray-500 text-lg max-w-xl mx-auto">
                أجوبة سريعة وواضحة على أكثر الأسئلة شيوعاً.
            </p>
        </div>

        <!-- FAQ -->
        <div x-data="{ active: 0 }" class="space-y-4">

            @foreach ($items as $index => $item)
                @php
                    $colors = [
                        'gold' => 'hover:border-[#d4af37]/40 text-[#d4af37]',
                        'indigo' => 'hover:border-indigo-500/40 text-indigo-400',
                        'purple' => 'hover:border-purple-500/40 text-purple-400',
                        'red' => 'hover:border-red-500/40 text-red-400',
                    ];

                    $color = $colors[$item['color'] ?? 'gold'];
                @endphp

                <div class="group rounded-2xl bg-gray-900/60 border border-gray-800 {{ explode(' ', $color)[0] }} transition-all duration-300 overflow-hidden">

                    <!-- Question -->
                    <button
                        @click="active === {{ $index }} ? active = null : active = {{ $index }}"
                        class="w-full flex items-center justify-between px-6 py-5 text-right">

                        <span class="text-white font-bold text-lg">
                            {{ $item['question'] }}
                        </span>

                        <svg
                            :class="active === {{ $index }} ? 'rotate-180 {{ explode(' ', $color)[1] }}' : 'text-gray-400'"
                            class="w-5 h-5 transition-all duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Answer -->
                    <div
                        x-show="active === {{ $index }}"
                        x-transition
                        class="px-6 pb-6 text-gray-400 leading-relaxed">

                        {{ $item['answer'] }}
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
