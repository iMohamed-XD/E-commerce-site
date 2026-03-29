<section id="support" class="relative z-10 py-32 px-6 border-t border-gray-800/50">

    <!-- Background Glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
                    w-[600px] h-[600px] bg-indigo-500/5 rounded-full blur-[120px]"></div>
    </div>

    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-20">
            <span class="inline-block px-4 py-1.5 rounded-full border border-indigo-500/30 text-indigo-400 text-xs font-bold tracking-widest uppercase mb-6">
                الدعم الفني
            </span>

            <h2 class="text-4xl md:text-5xl font-black text-white mb-4">
                نحن هنا <span class="text-indigo-400">لمساعدتك</span>
            </h2>

            <p class="text-gray-500 text-lg max-w-xl mx-auto">
                فريق الدعم جاهز للإجابة على استفساراتك ومساعدتك في أي وقت تحتاجه.
            </p>
        </div>

        <!-- Support Options -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Email -->
            <div class="group p-8 rounded-3xl bg-gray-900/60 border border-gray-800 hover:border-indigo-500/40 transition-all duration-500 text-center">

                <div class="w-14 h-14 mx-auto mb-6 rounded-2xl bg-indigo-500/10 flex items-center justify-center group-hover:scale-110 transition">
                    <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-white mb-3">البريد الإلكتروني</h3>
                <p class="text-gray-500 text-sm mb-6">تواصل معنا وسنرد عليك خلال 24 ساعة.</p>

                <a href="mailto:support@mahly.com"
                   class="inline-block px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-500 transition">
                    support@mahly.com
                </a>
            </div>

            <!-- Live Chat -->
            <div class="group p-8 rounded-3xl bg-gray-900/60 border border-gray-800 hover:border-[#d4af37]/40 transition-all duration-500 text-center">

                <div class="w-14 h-14 mx-auto mb-6 rounded-2xl bg-[#d4af37]/10 flex items-center justify-center group-hover:scale-110 transition">
                    <svg class="w-7 h-7 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5l-2 2V5a2 2 0 012-2h14a2 2 0 012 2v11a2 2 0 01-2 2h-5l-4 4v-4z"/>
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-white mb-3">الدردشة المباشرة</h3>
                <p class="text-gray-500 text-sm mb-6">احصل على مساعدة فورية من فريقنا.</p>

                <button class="px-6 py-2.5 rounded-xl bg-[#d4af37] text-black font-black hover:bg-[#c5a02e] transition">
                    ابدأ المحادثة
                </button>
            </div>

            <!-- Help Center -->
            <div class="group p-8 rounded-3xl bg-gray-900/60 border border-gray-800 hover:border-purple-500/40 transition-all duration-500 text-center">

                <div class="w-14 h-14 mx-auto mb-6 rounded-2xl bg-purple-500/10 flex items-center justify-center group-hover:scale-110 transition">
                    <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 18h.01M12 14a4 4 0 10-4-4"/>
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-white mb-3">مركز المساعدة</h3>
                <p class="text-gray-500 text-sm mb-6">دروس ومقالات تساعدك على النجاح.</p>

                <a href="#faq"
                   class="inline-block px-6 py-2.5 rounded-xl border border-purple-500/40 text-purple-300 hover:bg-purple-500/10 transition">
                    تصفح الأسئلة
                </a>
            </div>

        </div>

        <!-- Bottom CTA -->
        <div class="mt-20 text-center pt-10 border-t border-gray-800/50">
            <p class="text-gray-500 mb-6">ما زلت بحاجة إلى مساعدة؟</p>

            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-4 px-10 py-4 bg-gradient-to-l from-indigo-600 to-indigo-500
                      text-white font-bold rounded-2xl hover:shadow-xl hover:shadow-indigo-500/20 transition-all">

                <span>ابدأ الآن وسنرشدك خطوة بخطوة</span>

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
            </a>
        </div>

    </div>
</section>
