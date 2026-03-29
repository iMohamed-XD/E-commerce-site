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

        /* Smooth scroll */
        html { scroll-behavior: smooth; }

        /* Step connector vertical line */
        .steps-timeline::before {
            content: '';
            position: absolute;
            right: 50%;
            top: 3rem;
            bottom: 3rem;
            width: 1px;
            transform: translateX(50%);
            background: linear-gradient(to bottom, transparent, #d4af3740, #d4af3770, #d4af3740, transparent);
        }

        /* Screenshot frame shimmer on hover */
        .screenshot-frame {
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                        box-shadow 0.4s ease;
        }
        .screenshot-frame:hover {
            transform: translateY(-6px);
            box-shadow: 0 40px 80px rgba(0,0,0,0.6);
        }

        /* Step number ring pulse */
        @keyframes ring-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(212, 175, 55, 0); }
        }
        .step-dot { animation: ring-pulse 3s ease-in-out infinite; }

        /* Fade-in on scroll (CSS-only via animation-timeline if supported, fallback graceful) */
        @supports (animation-timeline: scroll()) {
            .step-row {
                animation: fade-up linear both;
                animation-timeline: view();
                animation-range: entry 0% entry 30%;
            }
            @keyframes fade-up {
                from { opacity: 0; transform: translateY(40px); }
                to   { opacity: 1; transform: translateY(0); }
            }
        }
    </style>
</head>
<body class="antialiased text-gray-100">
    <div class="relative min-h-screen flex flex-col selection:bg-[#d4af37] selection:text-black">

        <!-- ─── Fixed Background Hero ───────────────────────────────── -->
        <div class="fixed inset-0 z-0">
            <img src="{{ asset('images/mahly_hero.png') }}" alt="Hero Background" class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-b from-[#0f1115] via-transparent to-[#0f1115]"></div>
            <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <!-- ─── Navbar ───────────────────────────────────────────────── -->
        {{--
            In dir="rtl" flexbox, the FIRST child sits on the RIGHT edge,
            and the SECOND child sits on the LEFT edge.
            So logo (first) = top-right corner ✓  |  auth buttons (second) = top-left ✓
        --}}
        <nav class="relative w-full p-6 lg:px-12 flex justify-between items-center z-50 backdrop-blur-md bg-black/10 border-b border-gray-800/50">

            {{-- LOGO — right side in RTL --}}
            <div class="flex items-center gap-3">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        <div class="relative bg-gray-800/40 backdrop-blur-xl p-2.5 rounded-xl border border-white/5 shadow-2xl group-hover:border-blue-500/20 transition-all duration-500 overflow-hidden">
                            <!-- Subtle Gleam on Hover -->
                            <div class="absolute inset-0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>

                            <x-application-logo class="block h-9 w-auto hover:brightness-110 transition-all duration-300" />
                        </div>
                    </a>
                </div>
                <span class="hidden sm:inline-block text-[10px] text-gray-500 uppercase tracking-[0.2em] border-r border-gray-700 pr-3 mr-1 mt-0.5">
                    THE LUXURY OF LOCAL
                </span>
            </div>

            {{-- AUTH BUTTONS — left side in RTL --}}
            <div class="flex items-center gap-3 sm:gap-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-500 transition shadow-xl shadow-indigo-600/20 active:scale-95">
                            لوحة التحكم
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm font-bold text-gray-400 hover:text-white transition px-4 py-2">
                            دخول
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-6 py-2.5 rounded-xl bg-[#d4af37] text-black font-black hover:bg-[#c5a02e] transition shadow-xl shadow-[#d4af37]/20 active:scale-95">
                                ابدأ مجاناً
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <!-- ─── Hero Section ─────────────────────────────────────────── -->
        <main class="relative z-10 flex-grow pt-24 pb-12 px-6">
            <div class="max-w-5xl mx-auto text-center">

                {{-- Logo --}}
                <div class="flex justify-center mb-10">
                    <a href="{{ route('dashboard') }}" class="group">
                        <x-application-logo class="h-24 md:h-32 w-auto mx-auto hover:scale-105 hover:brightness-110 transition-all duration-500" />
                    </a>
                </div>

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-900/40 border border-indigo-500/30 text-indigo-300 text-xs font-bold mb-8 animate-pulse">
                    <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                    اكتشف الجيل القادم من المتاجر الإلكترونية
                </div>

                <h1 class="text-5xl md:text-8xl font-black text-white mb-8 leading-[1.1] tracking-tight">
                    من الصفر إلى
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#d4af37] via-[#f7e6a7] to-[#d4af37]">الاحترافية</span>
                </h1>

                <p class="text-xl md:text-2xl text-gray-400 mb-12 max-w-3xl mx-auto leading-relaxed font-light">
                    صممنا <span class="text-white font-bold">محلي</span> لنمنح مشروعك الهوية التي يستحقها.
                    ابدأ الآن بإنشاء متجرك الخاص واستقبل طلباتك بواجهات فاخرة وأدوات ذكية.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <a href="{{ route('register') }}"
                       class="group relative w-full sm:w-auto px-10 py-5 bg-[#d4af37] text-black text-xl font-black rounded-2xl hover:bg-[#c5a02e] transition-all duration-300 shadow-2xl shadow-[#d4af37]/30 overflow-hidden">
                        <span class="relative z-10">افتح متجرك الآن</span>
                        <div class="absolute inset-0 bg-white/20 translate-y-12 group-hover:translate-y-0 transition-transform duration-300"></div>
                    </a>

                    {{-- Button now scrolls to the new #how-it-works section --}}
                    <a href="#how-it-works"
                       class="w-full sm:w-auto px-10 py-5 bg-white/5 backdrop-blur-xl border border-white/10 text-white text-xl font-bold rounded-2xl hover:bg-white/10 transition-all">
                        شاهد كيف يعمل
                    </a>
                </div>

                <!-- Floating Badges -->
                <div class="hidden lg:block">
                    <div class="absolute top-[20%] left-[-10%] p-4 bg-gray-900/60 border border-gray-700 backdrop-blur-xl rounded-2xl rotate-[-12deg] shadow-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
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
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                                </svg>
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

        <!-- ═══════════════════════════════════════════════════════════ -->
        <!--  HOW IT WORKS SECTION                                      -->
        <!-- ═══════════════════════════════════════════════════════════ -->
        <section id="how-it-works" class="relative z-10 py-32 px-6 border-t border-gray-800/50 overflow-hidden">

            <!-- Subtle gold glow in background -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#d4af37]/3 rounded-full blur-[120px]"></div>
            </div>

            <div class="max-w-7xl mx-auto">

                <!-- Section Header -->
                <div class="text-center mb-28">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-[#d4af37]/10 border border-[#d4af37]/25 text-[#d4af37] text-xs font-bold tracking-widest uppercase mb-6">
                        دليل البدء
                    </span>
                    <h2 class="text-4xl md:text-6xl font-black text-white mb-5 leading-tight">
                        ثلاث خطوات إلى
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#d4af37] via-[#f7e6a7] to-[#d4af37]">متجرك</span>
                    </h2>
                    <p class="text-gray-400 text-lg max-w-xl mx-auto leading-relaxed">
                        من التسجيل إلى أول عملية بيع — كل شيء مصمم ليكون بسيطاً، سريعاً، وأنيقاً.
                    </p>
                </div>

                <!-- Steps Wrapper with vertical timeline line on desktop -->
                <div class="relative steps-timeline">

                    {{--
                        RTL FLEX LAYOUT LOGIC
                        ─────────────────────
                        In dir="rtl" with flex-row:
                          • 1st DOM child → appears on the RIGHT
                          • 2nd DOM child → appears on the LEFT

                        Step 1: [text][image]     → text=RIGHT  image=LEFT  ✓
                        Step 2: [image][text]*    → image=RIGHT text=LEFT   ✓  (* order classes fix mobile)
                        Step 3: [text][image]     → text=RIGHT  image=LEFT  ✓
                    --}}

                    <!-- ── STEP 1 ── Register (text right · image left) ──────── -->
                    <div class="step-row relative flex flex-col lg:flex-row items-center gap-10 lg:gap-0 pb-28 lg:pb-36">

                        <!-- Step number dot on the center line (desktop) -->
                        <div class="step-dot hidden lg:flex absolute right-1/2 top-1/2 -translate-y-1/2 translate-x-1/2
                                    w-12 h-12 rounded-full bg-[#0f1115] border-2 border-[#d4af37]
                                    items-center justify-center z-20">
                            <span class="text-[#d4af37] font-black text-base">١</span>
                        </div>

                        {{-- TEXT — right side --}}
                        <div class="w-full lg:w-1/2 lg:pe-16 text-right">

                            <!-- Mobile step badge -->
                            <div class="inline-flex items-center gap-3 mb-6 lg:hidden">
                                <span class="w-10 h-10 rounded-full bg-indigo-900/50 border border-indigo-500/40
                                             flex items-center justify-center text-indigo-400 font-black text-base">
                                    ١
                                </span>
                                <span class="text-indigo-400 text-sm font-bold tracking-wider">الخطوة الأولى</span>
                            </div>

                            <div class="hidden lg:inline-block px-3 py-1 rounded-lg bg-indigo-900/30 border border-indigo-500/20
                                        text-indigo-400 text-xs font-bold tracking-widest uppercase mb-5">
                                الخطوة الأولى
                            </div>

                            <h3 class="text-3xl md:text-4xl font-black text-white mb-5 leading-snug">
                                أنشئ حسابك<br>
                                <span class="text-indigo-400">في ثوانٍ معدودة</span>
                            </h3>

                            <p class="text-gray-400 text-lg leading-relaxed mb-8 max-w-md me-0 ms-auto lg:ms-0">
                                سجّل كبائع على منصة محلي ببياناتك الأساسية فقط. العملية مجانية تماماً وتستغرق أقل من دقيقة — ستحصل فوراً على وصول كامل إلى لوحة التحكم الخاصة بك.
                            </p>

                            <ul class="space-y-3 inline-flex flex-col items-end w-full">
                                @foreach(['تسجيل مجاني بالكامل — لا بطاقة ائتمانية', 'بيانات مشفرة ومحمية بالكامل', 'وصول فوري للوحة التحكم'] as $item)
                                <li class="flex items-center gap-3 text-gray-400">
                                    <span class="text-sm">{{ $item }}</span>
                                    <span class="w-5 h-5 rounded-full bg-green-500/15 border border-green-500/30
                                                 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- SCREENSHOT — left side --}}
                        <div class="w-full lg:w-1/2 lg:ps-16">
                            <div class="relative group">
                                <!-- Glow -->
                                <div class="absolute -inset-4 bg-indigo-500/8 rounded-3xl blur-2xl
                                            group-hover:bg-indigo-500/15 transition-all duration-700"></div>
                                <!-- Browser chrome frame -->
                                <div class="screenshot-frame relative rounded-2xl overflow-hidden
                                            border border-white/8 shadow-2xl shadow-black/60">
                                    <!-- Top chrome bar -->
                                    <div class="flex items-center gap-2 px-4 py-2.5 bg-gray-950 border-b border-gray-800/80">
                                        <div class="flex gap-1.5">
                                            <div class="w-3 h-3 rounded-full bg-red-500/40"></div>
                                            <div class="w-3 h-3 rounded-full bg-yellow-500/40"></div>
                                            <div class="w-3 h-3 rounded-full bg-green-500/40"></div>
                                        </div>
                                        <div class="flex-1 mx-3 px-3 py-1 rounded-md bg-gray-800/70 flex items-center">
                                            <span class="text-gray-500 text-xs font-mono">mahly.com/register</span>
                                        </div>
                                    </div>
                                    <!-- Screenshot -->
                                    <img src="{{ asset('images/register_form.png') }}"
                                         alt="نموذج التسجيل في محلي"
                                         class="w-full object-cover object-top">
                                    <!-- Subtle bottom fade -->
                                    <div class="absolute bottom-0 inset-x-0 h-16 bg-gradient-to-t from-[#0f1115]/60 to-transparent pointer-events-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── STEP 2 ── Create Shop (image right · text left) ─────
                         DOM order: [image][text]
                         In RTL flex-row: image=right, text=left ✓
                         On mobile (flex-col): use order utilities so text appears on top
                    ─────────────────────────────────────────────────────────────-->
                    <div class="step-row relative flex flex-col lg:flex-row items-center gap-10 lg:gap-0 pb-28 lg:pb-36">

                        <div class="step-dot hidden lg:flex absolute right-1/2 top-1/2 -translate-y-1/2 translate-x-1/2
                                    w-12 h-12 rounded-full bg-[#0f1115] border-2 border-[#d4af37]
                                    items-center justify-center z-20">
                            <span class="text-[#d4af37] font-black text-base">٢</span>
                        </div>

                        {{-- SCREENSHOT — right side (first DOM child in RTL)
                             order-2 on mobile so it goes below the text,
                             lg:order-1 restores DOM order (right side) on desktop --}}
                        <div class="w-full lg:w-1/2 lg:pe-16 order-2 lg:order-1">
                            <div class="relative group">
                                <div class="absolute -inset-4 bg-[#d4af37]/5 rounded-3xl blur-2xl
                                            group-hover:bg-[#d4af37]/10 transition-all duration-700"></div>
                                <div class="screenshot-frame relative rounded-2xl overflow-hidden
                                            border border-white/8 shadow-2xl shadow-black/60">
                                    <div class="flex items-center gap-2 px-4 py-2.5 bg-gray-950 border-b border-gray-800/80">
                                        <div class="flex gap-1.5">
                                            <div class="w-3 h-3 rounded-full bg-red-500/40"></div>
                                            <div class="w-3 h-3 rounded-full bg-yellow-500/40"></div>
                                            <div class="w-3 h-3 rounded-full bg-green-500/40"></div>
                                        </div>
                                        <div class="flex-1 mx-3 px-3 py-1 rounded-md bg-gray-800/70 flex items-center">
                                            <span class="text-gray-500 text-xs font-mono">mahly.com/shop/create</span>
                                        </div>
                                    </div>
                                    <img src="{{ asset('images/shop_creation_form.png') }}"
                                         alt="نموذج إنشاء المتجر"
                                         class="w-full object-cover object-top">
                                    <div class="absolute bottom-0 inset-x-0 h-16 bg-gradient-to-t from-[#0f1115]/60 to-transparent pointer-events-none"></div>
                                </div>
                            </div>
                        </div>

                        {{-- TEXT — left side (second DOM child in RTL)
                             order-1 on mobile so it goes above the screenshot --}}
                        <div class="w-full lg:w-1/2 lg:ps-16 text-right order-1 lg:order-2">

                            <div class="inline-flex items-center gap-3 mb-6 lg:hidden">
                                <span class="w-10 h-10 rounded-full bg-[#d4af37]/10 border border-[#d4af37]/30
                                             flex items-center justify-center text-[#d4af37] font-black text-base">
                                    ٢
                                </span>
                                <span class="text-[#d4af37] text-sm font-bold tracking-wider">الخطوة الثانية</span>
                            </div>

                            <div class="hidden lg:inline-block px-3 py-1 rounded-lg bg-[#d4af37]/10 border border-[#d4af37]/20
                                        text-[#d4af37] text-xs font-bold tracking-widest uppercase mb-5">
                                الخطوة الثانية
                            </div>

                            <h3 class="text-3xl md:text-4xl font-black text-white mb-5 leading-snug">
                                صمّم هوية<br>
                                <span class="text-[#d4af37]">متجرك الخاص</span>
                            </h3>

                            <p class="text-gray-400 text-lg leading-relaxed mb-8 max-w-md me-0 ms-auto lg:ms-0">
                                اختر اسم متجرك ورابطه المخصص، أضف صورة غلاف تعكس طابع علامتك، وارفع شعارك الاحترافي. كل تفصيل يُشكّل الانطباع الأول لعملائك.
                            </p>

                            <ul class="space-y-3 inline-flex flex-col items-end w-full">
                                @foreach(['رابط متجر مخصص وفريد من نوعه', 'صورة غلاف وشعار بجودة عالية', 'معاينة فورية قبل الحفظ'] as $item)
                                <li class="flex items-center gap-3 text-gray-400">
                                    <span class="text-sm">{{ $item }}</span>
                                    <span class="w-5 h-5 rounded-full bg-green-500/15 border border-green-500/30
                                                 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- ── STEP 3 ── Add Products (text right · image left) ───── -->
                    <div class="step-row relative flex flex-col lg:flex-row items-center gap-10 lg:gap-0">

                        <div class="step-dot hidden lg:flex absolute right-1/2 top-1/2 -translate-y-1/2 translate-x-1/2
                                    w-12 h-12 rounded-full bg-[#0f1115] border-2 border-[#d4af37]
                                    items-center justify-center z-20">
                            <span class="text-[#d4af37] font-black text-base">٣</span>
                        </div>

                        {{-- TEXT — right side --}}
                        <div class="w-full lg:w-1/2 lg:pe-16 text-right">

                            <div class="inline-flex items-center gap-3 mb-6 lg:hidden">
                                <span class="w-10 h-10 rounded-full bg-purple-900/50 border border-purple-500/40
                                             flex items-center justify-center text-purple-400 font-black text-base">
                                    ٣
                                </span>
                                <span class="text-purple-400 text-sm font-bold tracking-wider">الخطوة الثالثة</span>
                            </div>

                            <div class="hidden lg:inline-block px-3 py-1 rounded-lg bg-purple-900/30 border border-purple-500/20
                                        text-purple-400 text-xs font-bold tracking-widest uppercase mb-5">
                                الخطوة الثالثة
                            </div>

                            <h3 class="text-3xl md:text-4xl font-black text-white mb-5 leading-snug">
                                أضف منتجاتك<br>
                                <span class="text-purple-400">وابدأ البيع</span>
                            </h3>

                            <p class="text-gray-400 text-lg leading-relaxed mb-8 max-w-md me-0 ms-auto lg:ms-0">
                                أدخل اسم المنتج، تصنيفه، سعره الأصلي، صورته، ووصفه التسويقي. فعّل خيار الخصم إن أردت وشاهد منتجك يظهر فوراً في واجهة متجرك.
                            </p>

                            <ul class="space-y-3 inline-flex flex-col items-end w-full">
                                @foreach(['تصنيفات مرنة وقابلة للإضافة', 'نظام خصومات مدمج داخل كل منتج', 'رفع صور المنتجات بسهولة تامة'] as $item)
                                <li class="flex items-center gap-3 text-gray-400">
                                    <span class="text-sm">{{ $item }}</span>
                                    <span class="w-5 h-5 rounded-full bg-green-500/15 border border-green-500/30
                                                 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- SCREENSHOT — left side --}}
                        <div class="w-full lg:w-1/2 lg:ps-16">
                            <div class="relative group">
                                <div class="absolute -inset-4 bg-purple-500/8 rounded-3xl blur-2xl
                                            group-hover:bg-purple-500/15 transition-all duration-700"></div>
                                <div class="screenshot-frame relative rounded-2xl overflow-hidden
                                            border border-white/8 shadow-2xl shadow-black/60">
                                    <div class="flex items-center gap-2 px-4 py-2.5 bg-gray-950 border-b border-gray-800/80">
                                        <div class="flex gap-1.5">
                                            <div class="w-3 h-3 rounded-full bg-red-500/40"></div>
                                            <div class="w-3 h-3 rounded-full bg-yellow-500/40"></div>
                                            <div class="w-3 h-3 rounded-full bg-green-500/40"></div>
                                        </div>
                                        <div class="flex-1 mx-3 px-3 py-1 rounded-md bg-gray-800/70 flex items-center">
                                            <span class="text-gray-500 text-xs font-mono">mahly.com/products/create</span>
                                        </div>
                                    </div>
                                    <img src="{{ asset('images/product_adding_form.png') }}"
                                         alt="نموذج إضافة منتج"
                                         class="w-full object-cover object-top">
                                    <div class="absolute bottom-0 inset-x-0 h-16 bg-gradient-to-t from-[#0f1115]/60 to-transparent pointer-events-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- /steps-timeline -->

                <!-- CTA after steps -->
                <div class="text-center mt-24 pt-12 border-t border-gray-800/50">
                    <p class="text-gray-500 mb-6 text-sm tracking-wider uppercase">جاهز للانطلاق؟</p>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-4 px-12 py-5 bg-gradient-to-l from-[#d4af37] to-[#e8c84a]
                              text-black text-xl font-black rounded-2xl
                              hover:shadow-2xl hover:shadow-[#d4af37]/25 hover:-translate-y-1
                              transition-all duration-300 active:scale-95">
                        <span>ابدأ الآن مجاناً</span>
                        {{-- Arrow pointing left for RTL "forward" direction --}}
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- ─── Features Section ──────────────────────────────────────── -->
        <section id="features" class="relative z-10 py-32 px-6 border-t border-gray-800/50 bg-black/20 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-20">
                    <h2 class="text-4xl md:text-5xl font-black text-white mb-4">
                        لماذا يختار المحترفون <span class="text-[#d4af37]">محلي؟</span>
                    </h2>
                    <p class="text-gray-400 text-lg">كل ما تحتاجه للنجاح في سوق الأونلاين المزدحم.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-8 rounded-3xl bg-gray-900/50 border border-gray-800 hover:border-indigo-500/50 transition-all duration-500 group">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-600/10 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">هوية بصرية فاخرة</h3>
                        <p class="text-gray-500 leading-relaxed">متجر إلكتروني يعكس قيمة علامتك التجارية بتصميم داكن وعصري يجذب العملاء من النظرة الأولى.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-8 rounded-3xl bg-gray-900/50 border border-gray-800 hover:border-[#d4af37]/50 transition-all duration-500 group">
                        <div class="w-14 h-14 rounded-2xl bg-[#d4af37]/10 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">أدوات تسويق ذكية</h3>
                        <p class="text-gray-500 leading-relaxed">نظام كوبونات خصم متطور، إدارة عروض ترويجية، وتحليلات مبيعات دقيقة تساعدك على اتخاذ القرارات الصحيحة.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-8 rounded-3xl bg-gray-900/50 border border-gray-800 hover:border-red-500/50 transition-all duration-500 group">
                        <div class="w-14 h-14 rounded-2xl bg-red-600/10 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">حماية لا تهاون فيها</h3>
                        <p class="text-gray-500 leading-relaxed">نظام صلاحيات متكامل Middleware يضمن أن كل بائع يدير متجره الخاص بأمان تام وخصوصية مطلقة.</p>
                    </div>
                </div>
            </div>
        </section>


    </section>

    <!-- PRICING -->
    <section id="pricing" class="relative z-10 py-32 px-6 border-t border-gray-800/50 bg-black/20 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-20">
                <span class="inline-block px-4 py-1.5 rounded-full border border-[#d4af37]/30 text-[#d4af37] text-xs font-bold tracking-widest uppercase mb-6">خطط الاشتراك</span>
                <h2 class="text-4xl md:text-5xl font-black text-white mb-4">سعر يناسب كل مشروع</h2>
                <p class="text-gray-500 text-lg max-w-xl mx-auto">ابدأ مجاناً واترقَّ مع نمو عملك. لا رسوم خفية، لا التزامات.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">

                <!-- FREE -->
                <div class="flex flex-col rounded-3xl bg-gray-900/50 border border-gray-800 p-8 hover:border-gray-700 transition-all duration-500">
                    <div class="mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-gray-800 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-2">مجاناً</h3>
                        <p class="text-gray-500 text-sm">للبدء وتجربة المنصة</p>
                    </div>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-5xl font-black text-white">$0</span>
                        <span class="text-gray-500 text-sm">/ شهر</span>
                    </div>
                    <ul class="space-y-4 mb-10 flex-1">
                        <li class="flex items-center gap-3 text-gray-400 text-sm"><svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>متجر واحد</li>
                        <li class="flex items-center gap-3 text-gray-400 text-sm"><svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>حتى 10 منتجات</li>
                        <li class="flex items-center gap-3 text-gray-400 text-sm"><svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>تقارير أساسية</li>
                        <li class="flex items-center gap-3 text-gray-600 text-sm"><svg class="w-4 h-4 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>كوبونات الخصم</li>
                        <li class="flex items-center gap-3 text-gray-600 text-sm"><svg class="w-4 h-4 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>دعم أولوية</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center py-3.5 rounded-2xl border border-gray-700 text-gray-300 font-bold hover:border-gray-600 hover:text-white transition">ابدأ مجاناً</a>
                </div>

                <!-- PRO — featured -->
                <div class="relative flex flex-col rounded-3xl bg-gray-900/70 border-2 border-[#d4af37]/60 p-8 plan-popular-glow transition-all duration-500 scale-[1.02]">
                    <div class="absolute -top-4 right-1/2 translate-x-1/2">
                        <span class="inline-block px-5 py-1.5 rounded-full bg-[#d4af37] text-black text-xs font-black tracking-wide">الأكثر شعبية</span>
                    </div>
                    <div class="mb-8 mt-2">
                        <div class="w-12 h-12 rounded-2xl bg-[#d4af37]/10 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-2">احترافي</h3>
                        <p class="text-gray-400 text-sm">للمتاجر المتوسطة النامية</p>
                    </div>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-5xl font-black text-[#d4af37]">$10</span>
                        <span class="text-gray-500 text-sm">/ شهر</span>
                    </div>
                    <ul class="space-y-4 mb-10 flex-1">
                        <li class="flex items-center gap-3 text-gray-300 text-sm"><svg class="w-4 h-4 text-[#d4af37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>حتى 3 متاجر</li>
                        <li class="flex items-center gap-3 text-gray-300 text-sm"><svg class="w-4 h-4 text-[#d4af37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>منتجات غير محدودة</li>
                        <li class="flex items-center gap-3 text-gray-300 text-sm"><svg class="w-4 h-4 text-[#d4af37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>تقارير تفصيلية وتحليلات</li>
                        <li class="flex items-center gap-3 text-gray-300 text-sm"><svg class="w-4 h-4 text-[#d4af37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>كوبونات خصم وعروض</li>
                        <li class="flex items-center gap-3 text-gray-300 text-sm"><svg class="w-4 h-4 text-[#d4af37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>دعم فني مميز</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center py-3.5 rounded-2xl bg-[#d4af37] text-black font-black hover:bg-[#c5a02e] transition">اشترك الآن</a>
                </div>

                <!-- ENTERPRISE -->
                <div class="flex flex-col rounded-3xl bg-gray-900/50 border border-gray-800 p-8 hover:border-indigo-500/40 transition-all duration-500">
                    <div class="mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-2">مؤسسي</h3>
                        <p class="text-gray-500 text-sm">للمشاريع الكبيرة والمتنامية</p>
                    </div>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-5xl font-black text-white">$20</span>
                        <span class="text-gray-500 text-sm">/ شهر</span>
                    </div>
                    <ul class="space-y-4 mb-10 flex-1">
                        <li class="flex items-center gap-3 text-gray-300 text-sm"><svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>متاجر غير محدودة</li>
                        <li class="flex items-center gap-3 text-gray-300 text-sm"><svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>منتجات ومبيعات غير محدودة</li>
                        <li class="flex items-center gap-3 text-gray-300 text-sm"><svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>لوحة تحليلات متقدمة</li>
                        <li class="flex items-center gap-3 text-gray-300 text-sm"><svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>API للتكامل مع أنظمتك</li>
                        <li class="flex items-center gap-3 text-gray-300 text-sm"><svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>مدير حساب مخصص</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center py-3.5 rounded-2xl border border-indigo-500/40 text-indigo-300 font-bold hover:bg-indigo-600/10 hover:border-indigo-400 transition">تواصل معنا</a>
                </div>
            </div>
        </div>
    </section>

        <!-- TESTIMONIALS -->
    <section id="testimonials" class="relative z-10 py-32 px-6 border-t border-gray-800/50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <span class="inline-block px-4 py-1.5 rounded-full border border-[#d4af37]/30 text-[#d4af37] text-xs font-bold tracking-widest uppercase mb-6">آراء البائعين</span>
                <h2 class="text-4xl md:text-5xl font-black text-white mb-4">يثقون بنا ويبيعون معنا</h2>
                <p class="text-gray-500 text-lg max-w-xl mx-auto">قصص حقيقية من بائعين بنوا مشاريعهم على محلي.</p>
            </div>

            <!-- Stars SVG reusable via repeat pattern -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Card 1 -->
                <div class="relative p-8 rounded-3xl bg-gray-900/60 border border-gray-800 hover:border-[#d4af37]/30 transition-all duration-500 flex flex-col">
                    <div class="flex gap-1 mb-6">
                        @for ($i = 0; $i < 5; $i++)<svg class="w-4 h-4 text-[#d4af37]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                    </div>
                    <blockquote class="text-gray-300 leading-relaxed mb-8 flex-1 text-[15px]">"فتحت متجري على محلي في أقل من ساعة. التصميم أبهر عملائي من أول زيارة والطلبات بدأت تنهال من اليوم الأول."</blockquote>
                    <div class="flex items-center gap-4 border-t border-gray-800 pt-6">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-rose-500 to-pink-700 flex items-center justify-center text-white font-black text-sm flex-shrink-0">سم</div>
                        <div><p class="text-white font-bold text-sm">سارة المحمد</p><p class="text-gray-600 text-xs">متجر أناقتي – دمشق</p></div>
                    </div>
                </div>

                <!-- Card 2 — featured -->
                <div class="relative p-8 rounded-3xl bg-[#d4af37]/5 border border-[#d4af37]/20 hover:border-[#d4af37]/40 transition-all duration-500 flex flex-col md:col-span-2 lg:col-span-1">
                    <div class="absolute top-8 left-8 text-[#d4af37]/10 text-9xl font-black leading-none select-none">"</div>
                    <div class="flex gap-1 mb-6 relative z-10">
                        @for ($i = 0; $i < 5; $i++)<svg class="w-4 h-4 text-[#d4af37]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                    </div>
                    <blockquote class="text-white leading-relaxed mb-8 flex-1 text-[16px] relative z-10">"كنت أدير متجري على انستغرام وأرهق من التواصل اليدوي. مع محلي صار عندي نظام حقيقي، الطلبات منظّمة، العملاء سعداء، وأنا أنام مرتاحاً."</blockquote>
                    <div class="flex items-center gap-4 border-t border-[#d4af37]/20 pt-6 relative z-10">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-amber-500 to-yellow-700 flex items-center justify-center text-black font-black text-sm flex-shrink-0">خع</div>
                        <div><p class="text-white font-bold text-sm">خالد العمر</p><p class="text-[#d4af37]/70 text-xs">متجر العطور الأصيلة – حلب</p></div>
                        <div class="mr-auto px-3 py-1 rounded-full bg-[#d4af37]/10 border border-[#d4af37]/20"><span class="text-[#d4af37] text-xs font-bold">خطة احترافية</span></div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="relative p-8 rounded-3xl bg-gray-900/60 border border-gray-800 hover:border-indigo-500/30 transition-all duration-500 flex flex-col">
                    <div class="flex gap-1 mb-6">
                        @for ($i = 0; $i < 5; $i++)<svg class="w-4 h-4 text-[#d4af37]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                    </div>
                    <blockquote class="text-gray-300 leading-relaxed mb-8 flex-1 text-[15px]">"الخطة المجانية كانت كافية للبداية ثم انتقلت للخطة الاحترافية. الفرق كبير — التقارير والكوبونات ساعدتني أضاعف مبيعاتي."</blockquote>
                    <div class="flex items-center gap-4 border-t border-gray-800 pt-6">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-indigo-500 to-violet-700 flex items-center justify-center text-white font-black text-sm flex-shrink-0">رف</div>
                        <div><p class="text-white font-bold text-sm">رنا فارس</p><p class="text-gray-600 text-xs">متجر زهرة – اللاذقية</p></div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="relative p-8 rounded-3xl bg-gray-900/60 border border-gray-800 hover:border-[#d4af37]/30 transition-all duration-500 flex flex-col">
                    <div class="flex gap-1 mb-6">
                        @for ($i = 0; $i < 5; $i++)<svg class="w-4 h-4 text-[#d4af37]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                    </div>
                    <blockquote class="text-gray-300 leading-relaxed mb-8 flex-1 text-[15px]">"اعتمدنا محلي لثلاثة فروع وإدارة كل شيء من مكان واحد وفّرت علينا وقتاً وجهداً هائلاً. الخطة المؤسسية تستحق كل قرش."</blockquote>
                    <div class="flex items-center gap-4 border-t border-gray-800 pt-6">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white font-black text-sm flex-shrink-0">مح</div>
                        <div><p class="text-white font-bold text-sm">محمود الحسن</p><p class="text-gray-600 text-xs">سلسلة متاجر الياسمين – حمص</p></div>
                        <div class="mr-auto px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20"><span class="text-indigo-400 text-xs font-bold">خطة مؤسسية</span></div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="relative p-8 rounded-3xl bg-gray-900/60 border border-gray-800 hover:border-[#d4af37]/30 transition-all duration-500 flex flex-col">
                    <div class="flex gap-1 mb-6">
                        @for ($i = 0; $i < 5; $i++)<svg class="w-4 h-4 text-[#d4af37]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                    </div>
                    <blockquote class="text-gray-300 leading-relaxed mb-8 flex-1 text-[15px]">"كبائعة مستقلة لم أكن أتوقع هذا المستوى من الاحترافية بسعر منخفض. محلي غيّر نظرتي لكيفية بيع منتجاتي اليدوية."</blockquote>
                    <div class="flex items-center gap-4 border-t border-gray-800 pt-6">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-fuchsia-500 to-purple-700 flex items-center justify-center text-white font-black text-sm flex-shrink-0">دم</div>
                        <div><p class="text-white font-bold text-sm">دانا المصري</p><p class="text-gray-600 text-xs">متجر إبداع يدوي – بيروت</p></div>
                    </div>
                </div>

            </div>

            <!-- Trust bar -->
            <div class="mt-20 pt-10 border-t border-gray-800/50 flex flex-col sm:flex-row items-center justify-center gap-10 text-center">
                <div><p class="text-4xl font-black text-white mb-1">+1,200</p><p class="text-gray-500 text-sm">بائع نشط</p></div>
                <div class="hidden sm:block w-px h-12 bg-gray-800"></div>
                <div><p class="text-4xl font-black text-white mb-1">+45,000</p><p class="text-gray-500 text-sm">طلب مكتمل</p></div>
                <div class="hidden sm:block w-px h-12 bg-gray-800"></div>
                <div><p class="text-4xl font-black text-[#d4af37] mb-1">4.9 / 5</p><p class="text-gray-500 text-sm">متوسط التقييم</p></div>
            </div>
        </div>
    </section>

        <!-- ─── Footer ────────────────────────────────────────────────── -->
        <footer class="relative z-10 bg-[#0f1115] border-t border-gray-800 py-20 px-6">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start gap-12">
                <div class="max-w-sm">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('dashboard') }}" class="flex items-center group">
                                    <div class="relative bg-gray-800/40 backdrop-blur-xl p-2.5 rounded-xl border border-white/5 shadow-2xl group-hover:border-blue-500/20 transition-all duration-500 overflow-hidden">
                                        <!-- Subtle Gleam on Hover -->
                                        <div class="absolute inset-0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>
                                        <x-application-logo class="block h-12 w-auto hover:brightness-110 transition-all duration-300" />
                                    </div>
                                </a>
                            </div>
                            <span class="hidden sm:inline-block text-[10px] text-gray-500 uppercase tracking-[0.2em] border-r border-gray-700 pr-3 mr-1 mt-0.5">
                    THE LUXURY OF LOCAL
                </span>
            </div>                    </div>
                    <p class="text-gray-500 leading-relaxed">المنصة الشريكة لكل بائع وبائعة يبحثون عن التميز والفرادة في عالم التجارة الرقمية.</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-12 sm:gap-24">
                    <div>
                        <h4 class="text-white font-bold mb-6">الروابط</h4>
                        <ul class="space-y-4 text-gray-500 text-sm">
                            <li><a href="{{ route('login') }}" class="hover:text-[#d4af37] transition">الدخول</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-[#d4af37] transition">التسجيل</a></li>
                            <li><a href="#pricing" class="hover:text-[#d4af37] transition">الأسعار</a></li>
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
