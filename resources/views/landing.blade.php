<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>محلي | منصتك للتجارة الإلكترونية الفاخرة</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Tajawal', sans-serif !important;
            background-color: #0f1115;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-gray-100">
    <div class="relative min-h-screen flex flex-col selection:bg-[#d4af37] selection:text-black">
        <!-- Background Hero -->
        <div class="fixed inset-0 z-0">
            <img src="{{ asset('images/mahly_hero.png') }}" alt="Hero Background" class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-b from-[#0f1115] via-transparent to-[#0f1115]"></div>
            <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <!-- Navbar -->
        <nav class="relative w-full p-6 lg:px-12 flex justify-between items-center z-50 backdrop-blur-md bg-black/10 border-b border-gray-800/50">
            <div class="flex items-center gap-2">
                <span class="text-3xl font-black text-[#d4af37] tracking-tighter">محلي</span>
                <span class="hidden sm:inline-block text-[10px] text-gray-500 uppercase tracking-[0.2em] border-r border-gray-800 pr-3 mr-3 mt-1">THE LUXURY OF LOCAL</span>
            </div>
            <div class="flex items-center gap-3 sm:gap-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-500 transition shadow-xl shadow-indigo-600/20 active:scale-95">لوحة التحكم</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-400 hover:text-white transition px-4 py-2">دخول</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-xl bg-[#d4af37] text-black font-black hover:bg-[#c5a02e] transition shadow-xl shadow-[#d4af37]/20 active:scale-95">ابدأ مجاناً</a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <!-- Main Content -->
        <main class="relative z-10 flex-grow pt-24 pb-12 px-6">
            <div class="max-w-5xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-900/40 border border-indigo-500/30 text-indigo-300 text-xs font-bold mb-8 animate-pulse">
                    <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                    اكتشف الجيل القادم من المتاجر الإلكترونية
                </div>

                <h1 class="text-5xl md:text-8xl font-black text-white mb-8 leading-[1.1] tracking-tight">
                    من الصفر إلى <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#d4af37] via-[#f7e6a7] to-[#d4af37]">الاحترافية</span>
                </h1>

                <p class="text-xl md:text-2xl text-gray-400 mb-12 max-w-3xl mx-auto leading-relaxed font-light">
                    صممنا <span class="text-white font-bold">محلي</span> لنمنح مشروعك الهوية التي يستحقها. ابدأ الآن بإنشاء متجرك الخاص واستقبل طلباتك بواجهات فاخرة وأدوات ذكية.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <a href="{{ route('register') }}" class="group relative w-full sm:w-auto px-10 py-5 bg-[#d4af37] text-black text-xl font-black rounded-2xl hover:bg-[#c5a02e] transition-all duration-300 shadow-2xl shadow-[#d4af37]/30 overflow-hidden">
                        <span class="relative z-10">افتح متجرك الآن</span>
                        <div class="absolute inset-0 bg-white/20 translate-y-12 group-hover:translate-y-0 transition-transform duration-300"></div>
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-10 py-5 bg-white/5 backdrop-blur-xl border border-white/10 text-white text-xl font-bold rounded-2xl hover:bg-white/10 transition-all">
                        شاهد كيف يعمل
                    </a>
                </div>

                <!-- Floating Badges -->
                <div class="hidden lg:block">
                    <div class="absolute top-[20%] left-[-10%] p-4 bg-gray-900/60 border border-gray-700 backdrop-blur-xl rounded-2xl rotate-[-12deg] shadow-2xl">
                         <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-gray-500">تم استلام طلب جديد</div>
                                <div class="text-sm font-bold text-white">450,000 ل.س</div>
                            </div>
                         </div>
                    </div>
                    <div class="absolute bottom-[20%] right-[-10%] p-4 bg-gray-900/60 border border-gray-700 backdrop-blur-xl rounded-2xl rotate-[8deg] shadow-2xl">
                         <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-gray-500">مبيعات اليوم</div>
                                <div class="text-sm font-bold text-white">+85% نمو</div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Features Section -->
        <section id="features" class="relative z-10 py-32 px-6 border-t border-gray-800/50 bg-black/20 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-20">
                    <h2 class="text-4xl md:text-5xl font-black text-white mb-4">لماذا يختار المحترفون <span class="text-[#d4af37]">محلي؟</span></h2>
                    <p class="text-gray-400 text-lg">كل ما تحتاجه للنجاح في سوق الأونلاين المزدحم.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-8 rounded-3xl bg-gray-900/50 border border-gray-800 hover:border-indigo-500/50 transition-all duration-500 group">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-600/10 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">هوية بصرية فاخرة</h3>
                        <p class="text-gray-500 leading-relaxed">متجر إلكتروني يعكس قيمة علامتك التجارية بتصميم داكن وعصري يجذب العملاء من النظرة الأولى.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-8 rounded-3xl bg-gray-900/50 border border-gray-800 hover:border-[#d4af37]/50 transition-all duration-500 group">
                        <div class="w-14 h-14 rounded-2xl bg-[#d4af37]/10 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">أدوات تسويق ذكية</h3>
                        <p class="text-gray-500 leading-relaxed">نظام كوبونات خصم متطور، إدارة عروض ترويجية، وتحليلات مبيعات دقيقة تساعدك على اتخاذ القرارات الصحيحة.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-8 rounded-3xl bg-gray-900/50 border border-gray-800 hover:border-red-500/50 transition-all duration-500 group">
                        <div class="w-14 h-14 rounded-2xl bg-red-600/10 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">حماية لا تهاون فيها</h3>
                        <p class="text-gray-500 leading-relaxed">نظام صلاحيات متكامل Middleware يضمن أن كل بائع يدير متجره الخاص بأمان تام وخصوصية مطلقة.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative z-10 bg-[#0f1115] border-t border-gray-800 py-20 px-6">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start gap-12">
                <div class="max-w-sm">
                    <div class="flex items-center gap-2 mb-6">
                        <span class="text-4xl font-black text-[#d4af37] tracking-tighter">محلي</span>
                    </div>
                    <p class="text-gray-500 leading-relaxed">المنصة الشريكة لكل بائع وبائعة يبحثون عن التميز والفرادة في عالم التجارة الرقمية.</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-12 sm:gap-24">
                    <div>
                        <h4 class="text-white font-bold mb-6">الروابط</h4>
                        <ul class="space-y-4 text-gray-500 text-sm">
                            <li><a href="{{ route('login') }}" class="hover:text-[#d4af37] transition">الدخول</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-[#d4af37] transition">التسجيل</a></li>
                            <li><a href="#" class="hover:text-[#d4af37] transition">الأسعار</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-bold mb-6">الدعم</h4>
                        <ul class="space-y-4 text-gray-500 text-sm">
                            <li><a href="#" class="hover:text-[#d4af37] transition">الأسئلة الشائعة</a></li>
                            <li><a href="#" class="hover:text-[#d4af37] transition">الدعم الفني</a></li>
                            <li><a href="#" class="hover:text-[#d4af37] transition">سياسة الاستخدام</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto mt-20 pt-8 border-t border-gray-800/50 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-gray-600">
                <p>© {{ date('Y') }} محلي. جميع الحقوق محفوظة.</p>
                <p>تم التصميم بكل شغف لدعم المشاريع المحلية.</p>
            </div>
        </footer>
    </div>
</body>
</html>
