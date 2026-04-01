<div class="max-w-4xl mx-auto px-4 py-12">

    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black text-[#0d1b4b] mb-4">
            {{ $title }}
        </h1>
        <p class="text-[#0d1b4b]/50 text-sm max-w-xl mx-auto">
            شكراً لاستخدامك منصة محلي. يرجى الاطلاع على مصادر الأيقونات المستخدمة في المنصة:
        </p>
    </div>

    <!-- Sections -->
    <div class="space-y-8 text-right">
        @foreach ($sections as $section)
            <div class="group p-6 rounded-2xl bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 hover:border-[#d4af37]/40 transition-all duration-500">
                <h2 class="text-lg font-bold text-[#d4af37] mb-3">
                    {{ $section['title'] }}
                </h2>
                <a href="{{ $section['link'] }}" target="_blank" class="text-[#0d1b4b]/65 leading-relaxed text-sm hover:text-[#a07c1e] transition-colors">
                    {{ $section['content'] }}
                </a>
            </div>
        @endforeach
    </div>

    <!-- CTA -->
    @if($ctaText && $ctaLink)
        <div class="mt-12 text-center pt-8 border-t border-[#0d1b4b]/10">
            <a href="{{ $ctaLink }}"
               class="inline-block px-8 py-3 bg-[#d4af37] text-[#0d1b4b] font-black rounded-xl hover:bg-[#c5a02e] transition">
                {{ $ctaText }}
            </a>
        </div>
    @endif

</div>
