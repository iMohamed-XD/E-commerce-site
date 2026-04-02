<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>محلي | منصتك للتجارة الإلكترونية الفاخرة</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Tajawal', sans-serif !important;
            background-color: #ffffff;
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
            background: linear-gradient(to bottom, transparent, #d4af3760, #d4af37, #d4af3760, transparent);
        }

        /* Screenshot frame shimmer on hover */
        .screenshot-frame {
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                        box-shadow 0.4s ease;
        }
        .screenshot-frame:hover {
            transform: translateY(-6px);
            box-shadow: 0 40px 80px rgba(13, 27, 75, 0.15);
        }

        /* Step number ring pulse */
        @keyframes ring-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(212, 175, 55, 0); }
        }
        .step-dot { animation: ring-pulse 3s ease-in-out infinite; }

        /* Fade-in on scroll */
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

        /* Noise texture overlay for depth */
        .noise-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.025'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        /* Gold shimmer on featured card */
        .plan-popular-glow {
            box-shadow: 0 0 40px rgba(212, 175, 55, 0.12), 0 20px 60px rgba(13, 27, 75, 0.08);
        }

        /* Subtle dot-grid pattern for hero */
        .hero-dotgrid {
            background-image: radial-gradient(circle, #0d1b4b12 1px, transparent 1px);
            background-size: 28px 28px;
        }
    </style>
</head>
<body class="antialiased text-[#0d1b4b]">
    <div class="relative min-h-screen flex flex-col selection:bg-[#d4af37] selection:text-black">

        <!-- ─── Fixed Background ──────────────────────────────────────── -->
        <div class="fixed inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-b from-[#eef2ff] via-white to-[#fdfbf4]"></div>
            <div class="absolute inset-0 hero-dotgrid opacity-60"></div>
            <!-- Gold ambient glow top-right -->
            <div class="absolute top-0 right-0 w-[600px] h-[400px] bg-[#d4af37]/8 rounded-full blur-[120px]"></div>
            <!-- Navy ambient glow bottom-left -->
            <div class="absolute bottom-0 left-0 w-[500px] h-[400px] bg-[#0d1b4b]/5 rounded-full blur-[100px]"></div>
        </div>

        <!-- ─── Navbar ───────────────────────────────────────────────── -->
        <nav class="relative w-full p-6 lg:px-12 flex justify-between items-center z-50 backdrop-blur-md bg-white/80 border-b border-[#0d1b4b]/8 shadow-sm">

            {{-- LOGO — right side in RTL --}}
            <div class="flex items-center gap-3">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        <div class="relative bg-white/80 backdrop-blur-xl p-2.5 rounded-xl border border-[#0d1b4b]/10 shadow-md group-hover:border-[#d4af37]/40 group-hover:shadow-[0_4px_20px_rgba(212,175,55,0.15)] transition-all duration-500 overflow-hidden">
                            <div class="absolute inset-0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-[#d4af37]/10 to-transparent"></div>
                            <x-application-logo class="block h-9 w-auto hover:brightness-110 transition-all duration-300" />
                        </div>
                    </a>
                </div>
                <span class="hidden sm:inline-block text-[10px] text-[#0d1b4b]/40 uppercase tracking-[0.2em] border-r border-[#0d1b4b]/15 pr-3 mr-1 mt-0.5">
                    THE LUXURY OF LOCAL
                </span>
            </div>

            {{-- AUTH BUTTONS — left side in RTL --}}
            <div class="flex items-center gap-3 sm:gap-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="px-6 py-2.5 rounded-xl bg-[#0d1b4b] text-white font-bold hover:bg-[#1a2d6b] transition shadow-lg shadow-[#0d1b4b]/20 active:scale-95">
                            لوحة التحكم
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm font-bold text-[#0d1b4b]/60 hover:text-[#0d1b4b] transition px-4 py-2">
                            دخول
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-6 py-2.5 rounded-xl bg-[#d4af37] text-[#0d1b4b] font-black hover:bg-[#c5a02e] transition shadow-xl shadow-[#d4af37]/25 active:scale-95">
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
                        <x-application-logo class="h-40 md:h-48 w-auto mx-auto hover:scale-105 hover:brightness-110 transition-all duration-500" />
                    </a>
                </div>

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#0d1b4b]/8 border border-[#0d1b4b]/15 text-[#0d1b4b]/70 text-xs font-bold mb-8 animate-pulse">
                    <span class="w-2 h-2 rounded-full bg-[#0d1b4b]/60"></span>
                    اكتشف الجيل القادم من المتاجر الإلكترونية
                </div>

                <h1 class="text-5xl md:text-8xl font-black text-[#0d1b4b] mb-8 leading-[1.1] tracking-tight">
                    أنشئ متجرك الإلكتروني
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#d4af37] via-[#e8c84a] to-[#b8922a]">وابدأ البيع خلال دقائق</span>
                </h1>

                <p class="text-xl md:text-2xl text-[#0d1b4b]/60 mb-12 max-w-3xl mx-auto leading-relaxed font-light">
                    لا تحتاج خبرة تقنية، ولا فريق إدارة، مع <span class="text-[#0d1b4b] font-bold">محلي</span> كلشيء جاهز لك لتبدأ وتربح من اليوم الأول
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <a href="{{ route('register') }}"
                       class="group relative w-full sm:w-auto px-10 py-5 bg-[#d4af37] text-[#0d1b4b] text-xl font-black rounded-2xl hover:bg-[#c5a02e] transition-all duration-300 shadow-2xl shadow-[#d4af37]/30 overflow-hidden">
                        <span class="relative z-10">أنشئ متجرك الآن</span>
                        <div class="absolute inset-0 bg-white/20 translate-y-12 group-hover:translate-y-0 transition-transform duration-300"></div>
                    </a>

                    <a href="#how-it-works"
                       class="w-full sm:w-auto px-10 py-5 bg-[#0d1b4b]/5 backdrop-blur-xl border border-[#0d1b4b]/15 text-[#0d1b4b] text-xl font-bold rounded-2xl hover:bg-[#0d1b4b]/10 hover:border-[#0d1b4b]/25 transition-all">
                        شاهد كيف يعمل
                    </a>
                </div>

                <!-- Floating Badges -->
                <div class="hidden lg:block">
                    <div class="absolute top-[20%] left-[-10%] p-4 bg-white border border-[#0d1b4b]/10 backdrop-blur-xl rounded-2xl rotate-[-12deg] shadow-xl shadow-[#0d1b4b]/8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-500/15 flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-[#0d1b4b]/40">تم استلام طلب جديد</div>
                                <div class="text-sm font-bold text-[#0d1b4b]">450,000 ل.س</div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute bottom-[20%] right-[-10%] p-4 bg-white border border-[#0d1b4b]/10 backdrop-blur-xl rounded-2xl rotate-[8deg] shadow-xl shadow-[#0d1b4b]/8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#0d1b4b]/10 flex items-center justify-center">
                                <svg class="w-6 h-6 text-[#0d1b4b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-[#0d1b4b]/40">مبيعات اليوم</div>
                                <div class="text-sm font-bold text-[#0d1b4b]">+85% نمو</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- ═══════════════════════════════════════════════════════════ -->
        <!--  HOW IT WORKS SECTION                                      -->
        <!-- ═══════════════════════════════════════════════════════════ -->
        <section id="how-it-works" class="relative z-10 py-32 px-6 border-t border-[#0d1b4b]/8 overflow-hidden bg-white/40">

            <!-- Subtle gold glow -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#d4af37]/6 rounded-full blur-[120px]"></div>
            </div>

            <div class="max-w-7xl mx-auto">

                <!-- Section Header -->
                <div class="text-center mb-28">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-[#d4af37]/12 border border-[#d4af37]/30 text-[#a07c1e] text-xs font-bold tracking-widest uppercase mb-6">
                        دليل البدء
                    </span>
                    <h2 class="text-4xl md:text-6xl font-black text-[#0d1b4b] mb-5 leading-tight">
                        ثلاث خطوات إلى
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#d4af37] via-[#e8c84a] to-[#b8922a]">متجرك</span>
                    </h2>
                    <p class="text-[#0d1b4b]/55 text-lg max-w-xl mx-auto leading-relaxed">
                        من التسجيل إلى أول عملية بيع — كل شيء مصمم ليكون بسيطاً، سريعاً، وأنيقاً.
                    </p>
                </div>

                <!-- Steps with vertical timeline -->
                <div class="relative steps-timeline">

                    <!-- ── STEP 1 ── Register (text right · image left) ──────── -->
                    <div class="step-row relative flex flex-col lg:flex-row items-center gap-10 lg:gap-0 pb-28 lg:pb-36">

                        <div class="step-dot hidden lg:flex absolute right-1/2 top-1/2 -translate-y-1/2 translate-x-1/2
                                    w-12 h-12 rounded-full bg-white border-2 border-[#d4af37]
                                    items-center justify-center z-20 shadow-md">
                            <span class="text-[#d4af37] font-black text-base">١</span>
                        </div>

                        {{-- TEXT — right side --}}
                        <div class="w-full lg:w-1/2 lg:pe-16 text-right">

                            <div class="inline-flex items-center gap-3 mb-6 lg:hidden">
                                <span class="w-10 h-10 rounded-full bg-[#0d1b4b]/8 border border-[#0d1b4b]/20
                                             flex items-center justify-center text-[#0d1b4b] font-black text-base">
                                    ١
                                </span>
                                <span class="text-[#0d1b4b] text-sm font-bold tracking-wider">الخطوة الأولى</span>
                            </div>

                            <div class="hidden lg:inline-block px-3 py-1 rounded-lg bg-[#0d1b4b]/8 border border-[#0d1b4b]/15
                                        text-[#0d1b4b]/70 text-xs font-bold tracking-widest uppercase mb-5">
                                الخطوة الأولى
                            </div>

                            <h3 class="text-3xl md:text-4xl font-black text-[#0d1b4b] mb-5 leading-snug">
                                أنشئ حسابك<br>
                                <span class="text-[#d4af37]">في ثوانٍ معدودة</span>
                            </h3>

                            <p class="text-[#0d1b4b]/55 text-lg leading-relaxed mb-8 max-w-md me-0 ms-auto lg:ms-0">
                                سجّل كبائع على منصة محلي ببياناتك الأساسية فقط. العملية مجانية تماماً وتستغرق أقل من دقيقة — ستحصل فوراً على وصول كامل إلى لوحة التحكم الخاصة بك.
                            </p>

                            <ul class="space-y-3 inline-flex flex-col items-end w-full">
                                @foreach(['تسجيل مجاني بالكامل — لا بطاقة ائتمانية', 'بيانات مشفرة ومحمية بالكامل', 'وصول فوري للوحة التحكم'] as $item)
                                <li class="flex items-center gap-3 text-[#0d1b4b]/60">
                                    <span class="text-sm">{{ $item }}</span>
                                    <span class="w-5 h-5 rounded-full bg-green-500/15 border border-green-500/30
                                                 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <div class="absolute -inset-4 bg-[#0d1b4b]/4 rounded-3xl blur-2xl
                                            group-hover:bg-[#0d1b4b]/8 transition-all duration-700"></div>
                                <div class="screenshot-frame relative rounded-2xl overflow-hidden
                                            border border-[#0d1b4b]/10 shadow-xl shadow-[#0d1b4b]/8">
                                    <div class="flex items-center gap-2 px-4 py-2.5 bg-[#f0f4ff] border-b border-[#0d1b4b]/10">
                                        <div class="flex gap-1.5">
                                            <div class="w-3 h-3 rounded-full bg-red-400/60"></div>
                                            <div class="w-3 h-3 rounded-full bg-yellow-400/60"></div>
                                            <div class="w-3 h-3 rounded-full bg-green-400/60"></div>
                                        </div>
                                        <div class="flex-1 mx-3 px-3 py-1 rounded-md bg-white border border-[#0d1b4b]/8 flex items-center">
                                            <span class="text-[#0d1b4b]/40 text-xs font-mono">mahly.com/register</span>
                                        </div>
                                    </div>
                                    <img src="{{ asset('images/register_form.png') }}"
                                         alt="نموذج التسجيل في محلي"
                                         class="w-full object-cover object-top">
                                    <div class="absolute bottom-0 inset-x-0 h-16 bg-gradient-to-t from-white/60 to-transparent pointer-events-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── STEP 2 ── Create Shop (image right · text left) ─────-->
                    <div class="step-row relative flex flex-col lg:flex-row items-center gap-10 lg:gap-0 pb-28 lg:pb-36">

                        <div class="step-dot hidden lg:flex absolute right-1/2 top-1/2 -translate-y-1/2 translate-x-1/2
                                    w-12 h-12 rounded-full bg-white border-2 border-[#d4af37]
                                    items-center justify-center z-20 shadow-md">
                            <span class="text-[#d4af37] font-black text-base">٢</span>
                        </div>

                        {{-- SCREENSHOT — right side --}}
                        <div class="w-full lg:w-1/2 lg:pe-16 order-2 lg:order-1">
                            <div class="relative group">
                                <div class="absolute -inset-4 bg-[#d4af37]/6 rounded-3xl blur-2xl
                                            group-hover:bg-[#d4af37]/12 transition-all duration-700"></div>
                                <div class="screenshot-frame relative rounded-2xl overflow-hidden
                                            border border-[#0d1b4b]/10 shadow-xl shadow-[#0d1b4b]/8">
                                    <div class="flex items-center gap-2 px-4 py-2.5 bg-[#f0f4ff] border-b border-[#0d1b4b]/10">
                                        <div class="flex gap-1.5">
                                            <div class="w-3 h-3 rounded-full bg-red-400/60"></div>
                                            <div class="w-3 h-3 rounded-full bg-yellow-400/60"></div>
                                            <div class="w-3 h-3 rounded-full bg-green-400/60"></div>
                                        </div>
                                        <div class="flex-1 mx-3 px-3 py-1 rounded-md bg-white border border-[#0d1b4b]/8 flex items-center">
                                            <span class="text-[#0d1b4b]/40 text-xs font-mono">mahly.com/shop/create</span>
                                        </div>
                                    </div>
                                    <img src="{{ asset('images/shop_creation_form.png') }}"
                                         alt="نموذج إنشاء المتجر"
                                         class="w-full object-cover object-top">
                                    <div class="absolute bottom-0 inset-x-0 h-16 bg-gradient-to-t from-white/60 to-transparent pointer-events-none"></div>
                                </div>
                            </div>
                        </div>

                        {{-- TEXT — left side --}}
                        <div class="w-full lg:w-1/2 lg:ps-16 text-right order-1 lg:order-2">

                            <div class="inline-flex items-center gap-3 mb-6 lg:hidden">
                                <span class="w-10 h-10 rounded-full bg-[#d4af37]/12 border border-[#d4af37]/30
                                             flex items-center justify-center text-[#a07c1e] font-black text-base">
                                    ٢
                                </span>
                                <span class="text-[#a07c1e] text-sm font-bold tracking-wider">الخطوة الثانية</span>
                            </div>

                            <div class="hidden lg:inline-block px-3 py-1 rounded-lg bg-[#d4af37]/10 border border-[#d4af37]/25
                                        text-[#a07c1e] text-xs font-bold tracking-widest uppercase mb-5">
                                الخطوة الثانية
                            </div>

                            <h3 class="text-3xl md:text-4xl font-black text-[#0d1b4b] mb-5 leading-snug">
                                صمّم هوية<br>
                                <span class="text-[#d4af37]">متجرك الخاص</span>
                            </h3>

                            <p class="text-[#0d1b4b]/55 text-lg leading-relaxed mb-8 max-w-md me-0 ms-auto lg:ms-0">
                                اختر اسم متجرك ورابطه المخصص، أضف صورة غلاف تعكس طابع علامتك، وارفع شعارك الاحترافي. كل تفصيل يُشكّل الانطباع الأول لعملائك.
                            </p>

                            <ul class="space-y-3 inline-flex flex-col items-end w-full">
                                @foreach(['رابط متجر مخصص وفريد من نوعه', 'صورة غلاف وشعار بجودة عالية', 'معاينة فورية قبل الحفظ'] as $item)
                                <li class="flex items-center gap-3 text-[#0d1b4b]/60">
                                    <span class="text-sm">{{ $item }}</span>
                                    <span class="w-5 h-5 rounded-full bg-green-500/15 border border-green-500/30
                                                 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    w-12 h-12 rounded-full bg-white border-2 border-[#d4af37]
                                    items-center justify-center z-20 shadow-md">
                            <span class="text-[#d4af37] font-black text-base">٣</span>
                        </div>

                        {{-- TEXT — right side --}}
                        <div class="w-full lg:w-1/2 lg:pe-16 text-right">

                            <div class="inline-flex items-center gap-3 mb-6 lg:hidden">
                                <span class="w-10 h-10 rounded-full bg-[#0d1b4b]/8 border border-[#0d1b4b]/20
                                             flex items-center justify-center text-[#0d1b4b] font-black text-base">
                                    ٣
                                </span>
                                <span class="text-[#0d1b4b] text-sm font-bold tracking-wider">الخطوة الثالثة</span>
                            </div>

                            <div class="hidden lg:inline-block px-3 py-1 rounded-lg bg-[#0d1b4b]/8 border border-[#0d1b4b]/15
                                        text-[#0d1b4b]/70 text-xs font-bold tracking-widest uppercase mb-5">
                                الخطوة الثالثة
                            </div>

                            <h3 class="text-3xl md:text-4xl font-black text-[#0d1b4b] mb-5 leading-snug">
                                أضف منتجاتك<br>
                                <span class="text-[#d4af37]">وابدأ البيع</span>
                            </h3>

                            <p class="text-[#0d1b4b]/55 text-lg leading-relaxed mb-8 max-w-md me-0 ms-auto lg:ms-0">
                                أدخل اسم المنتج، تصنيفه، سعره الأصلي، صورته، ووصفه التسويقي. فعّل خيار الخصم إن أردت وشاهد منتجك يظهر فوراً في واجهة متجرك.
                            </p>

                            <ul class="space-y-3 inline-flex flex-col items-end w-full">
                                @foreach(['تصنيفات مرنة وقابلة للإضافة', 'نظام خصومات مدمج داخل كل منتج', 'رفع صور المنتجات بسهولة تامة'] as $item)
                                <li class="flex items-center gap-3 text-[#0d1b4b]/60">
                                    <span class="text-sm">{{ $item }}</span>
                                    <span class="w-5 h-5 rounded-full bg-green-500/15 border border-green-500/30
                                                 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <div class="absolute -inset-4 bg-[#d4af37]/5 rounded-3xl blur-2xl
                                            group-hover:bg-[#d4af37]/10 transition-all duration-700"></div>
                                <div class="screenshot-frame relative rounded-2xl overflow-hidden
                                            border border-[#0d1b4b]/10 shadow-xl shadow-[#0d1b4b]/8">
                                    <div class="flex items-center gap-2 px-4 py-2.5 bg-[#f0f4ff] border-b border-[#0d1b4b]/10">
                                        <div class="flex gap-1.5">
                                            <div class="w-3 h-3 rounded-full bg-red-400/60"></div>
                                            <div class="w-3 h-3 rounded-full bg-yellow-400/60"></div>
                                            <div class="w-3 h-3 rounded-full bg-green-400/60"></div>
                                        </div>
                                        <div class="flex-1 mx-3 px-3 py-1 rounded-md bg-white border border-[#0d1b4b]/8 flex items-center">
                                            <span class="text-[#0d1b4b]/40 text-xs font-mono">mahly.com/products/create</span>
                                        </div>
                                    </div>
                                    <img src="{{ asset('images/product_adding_form.png') }}"
                                         alt="نموذج إضافة منتج"
                                         class="w-full object-cover object-top">
                                    <div class="absolute bottom-0 inset-x-0 h-16 bg-gradient-to-t from-white/60 to-transparent pointer-events-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- /steps-timeline -->

                <!-- CTA after steps -->
                <div class="text-center mt-24 pt-12 border-t border-[#0d1b4b]/8">
                    <p class="text-[#0d1b4b]/40 mb-6 text-sm tracking-wider uppercase">جاهز للانطلاق؟</p>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-4 px-12 py-5 bg-gradient-to-l from-[#d4af37] to-[#e8c84a]
                              text-[#0d1b4b] text-xl font-black rounded-2xl
                              hover:shadow-2xl hover:shadow-[#d4af37]/30 hover:-translate-y-1
                              transition-all duration-300 active:scale-95">
                        <span>ابدأ الآن مجاناً</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- ─── Features Section ──────────────────────────────────────── -->
        <section id="features" class="relative z-10 py-32 px-6 border-t border-[#0d1b4b]/8 bg-[#f0f4ff]/40 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-20">
                    <h2 class="text-4xl md:text-5xl font-black text-[#0d1b4b] mb-4">
                        لماذا يختار المحترفون <span class="text-[#d4af37]">محلي؟</span>
                    </h2>
                    <p class="text-[#0d1b4b]/55 text-lg">كل ما تحتاجه للنجاح في سوق الأونلاين المزدحم.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-8 rounded-3xl bg-white border border-[#0d1b4b]/8 hover:border-[#0d1b4b]/20 hover:shadow-lg hover:shadow-[#0d1b4b]/5 transition-all duration-500 group">
                        <div class="w-14 h-14 rounded-2xl bg-[#0d1b4b]/8 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-[#0d1b4b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-[#0d1b4b] mb-4">هوية بصرية فاخرة</h3>
                        <p class="text-[#0d1b4b]/55 leading-relaxed">متجر إلكتروني يعكس قيمة علامتك التجارية بتصميم داكن وعصري يجذب العملاء من النظرة الأولى.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-8 rounded-3xl bg-white border border-[#0d1b4b]/8 hover:border-[#d4af37]/40 hover:shadow-lg hover:shadow-[#d4af37]/8 transition-all duration-500 group">
                        <div class="w-14 h-14 rounded-2xl bg-[#d4af37]/10 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-[#a07c1e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-[#0d1b4b] mb-4">أدوات تسويق ذكية</h3>
                        <p class="text-[#0d1b4b]/55 leading-relaxed">نظام كوبونات خصم متطور، إدارة عروض ترويجية، وتحليلات مبيعات دقيقة تساعدك على اتخاذ القرارات الصحيحة.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-8 rounded-3xl bg-white border border-[#0d1b4b]/8 hover:border-red-400/30 hover:shadow-lg hover:shadow-red-500/5 transition-all duration-500 group">
                        <div class="w-14 h-14 rounded-2xl bg-red-500/8 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-[#0d1b4b] mb-4">حماية لا تهاون فيها</h3>
                        <p class="text-[#0d1b4b]/55 leading-relaxed">نظام صلاحيات متكامل Middleware يضمن أن كل بائع يدير متجره الخاص بأمان تام وخصوصية مطلقة.</p>
                    </div>
                </div>
            </div>
        </section>

    </section>

    {{-- <!-- PRICING -->
    <section id="pricing" class="relative z-10 py-32 px-6 border-t border-[#0d1b4b]/8 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-20">
                <span class="inline-block px-4 py-1.5 rounded-full border border-[#d4af37]/30 bg-[#d4af37]/8 text-[#a07c1e] text-xs font-bold tracking-widest uppercase mb-6">خطط الاشتراك</span>
                <h2 class="text-4xl md:text-5xl font-black text-[#0d1b4b] mb-4">سعر يناسب كل مشروع</h2>
                <p class="text-[#0d1b4b]/50 text-lg max-w-xl mx-auto">ابدأ مجاناً واترقَّ مع نمو عملك. لا رسوم خفية، لا التزامات.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">

                <!-- FREE -->
                <div class="flex flex-col rounded-3xl bg-[#f8faff] border border-[#0d1b4b]/8 p-8 hover:border-[#0d1b4b]/15 hover:shadow-md transition-all duration-500">
                    <div class="mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-[#0d1b4b]/8 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-[#0d1b4b]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-[#0d1b4b] mb-2">مجاناً</h3>
                        <p class="text-[#0d1b4b]/45 text-sm">للبدء وتجربة المنصة</p>
                    </div>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-5xl font-black text-[#0d1b4b]">$0</span>
                        <span class="text-[#0d1b4b]/40 text-sm">/ شهر</span>
                    </div>
                    <ul class="space-y-4 mb-10 flex-1">
                        <li class="flex items-center gap-3 text-[#0d1b4b]/60 text-sm"><svg class="w-4 h-4 text-[#0d1b4b]/30 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>متجر واحد</li>
                        <li class="flex items-center gap-3 text-[#0d1b4b]/60 text-sm"><svg class="w-4 h-4 text-[#0d1b4b]/30 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>حتى 10 منتجات</li>
                        <li class="flex items-center gap-3 text-[#0d1b4b]/60 text-sm"><svg class="w-4 h-4 text-[#0d1b4b]/30 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>تقارير أساسية</li>
                        <li class="flex items-center gap-3 text-[#0d1b4b]/25 text-sm"><svg class="w-4 h-4 text-[#0d1b4b]/15 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>كوبونات الخصم</li>
                        <li class="flex items-center gap-3 text-[#0d1b4b]/25 text-sm"><svg class="w-4 h-4 text-[#0d1b4b]/15 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>دعم أولوية</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center py-3.5 rounded-2xl border border-[#0d1b4b]/15 text-[#0d1b4b]/70 font-bold hover:border-[#0d1b4b]/30 hover:text-[#0d1b4b] transition">ابدأ مجاناً</a>
                </div>

                <!-- PRO — featured -->
                <div class="relative flex flex-col rounded-3xl bg-[#fffdf5] border-2 border-[#d4af37]/50 p-8 plan-popular-glow transition-all duration-500 scale-[1.02]">
                    <div class="absolute -top-4 right-1/2 translate-x-1/2">
                        <span class="inline-block px-5 py-1.5 rounded-full bg-[#d4af37] text-[#0d1b4b] text-xs font-black tracking-wide shadow-lg shadow-[#d4af37]/30">الأكثر شعبية</span>
                    </div>
                    <div class="mb-8 mt-2">
                        <div class="w-12 h-12 rounded-2xl bg-[#d4af37]/12 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-[#a07c1e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-[#0d1b4b] mb-2">احترافي</h3>
                        <p class="text-[#0d1b4b]/50 text-sm">للمتاجر المتوسطة النامية</p>
                    </div>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-5xl font-black text-[#d4af37]">$10</span>
                        <span class="text-[#0d1b4b]/40 text-sm">/ شهر</span>
                    </div>
                    <ul class="space-y-4 mb-10 flex-1">
                        <li class="flex items-center gap-3 text-[#0d1b4b]/75 text-sm"><svg class="w-4 h-4 text-[#d4af37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>حتى 3 متاجر</li>
                        <li class="flex items-center gap-3 text-[#0d1b4b]/75 text-sm"><svg class="w-4 h-4 text-[#d4af37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>منتجات غير محدودة</li>
                        <li class="flex items-center gap-3 text-[#0d1b4b]/75 text-sm"><svg class="w-4 h-4 text-[#d4af37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>تقارير تفصيلية وتحليلات</li>
                        <li class="flex items-center gap-3 text-[#0d1b4b]/75 text-sm"><svg class="w-4 h-4 text-[#d4af37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>كوبونات خصم وعروض</li>
                        <li class="flex items-center gap-3 text-[#0d1b4b]/75 text-sm"><svg class="w-4 h-4 text-[#d4af37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>دعم فني مميز</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center py-3.5 rounded-2xl bg-[#d4af37] text-[#0d1b4b] font-black hover:bg-[#c5a02e] transition shadow-lg shadow-[#d4af37]/25">اشترك الآن</a>
                </div>

                <!-- ENTERPRISE -->
                <div class="flex flex-col rounded-3xl bg-[#f8faff] border border-[#0d1b4b]/8 p-8 hover:border-[#0d1b4b]/20 hover:shadow-md transition-all duration-500">
                    <div class="mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-[#0d1b4b]/8 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-[#0d1b4b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-[#0d1b4b] mb-2">مؤسسي</h3>
                        <p class="text-[#0d1b4b]/45 text-sm">للمشاريع الكبيرة والمتنامية</p>
                    </div>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-5xl font-black text-[#0d1b4b]">$20</span>
                        <span class="text-[#0d1b4b]/40 text-sm">/ شهر</span>
                    </div>
                    <ul class="space-y-4 mb-10 flex-1">
                        <li class="flex items-center gap-3 text-[#0d1b4b]/70 text-sm"><svg class="w-4 h-4 text-[#0d1b4b] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>متاجر غير محدودة</li>
                        <li class="flex items-center gap-3 text-[#0d1b4b]/70 text-sm"><svg class="w-4 h-4 text-[#0d1b4b] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>منتجات ومبيعات غير محدودة</li>
                        <li class="flex items-center gap-3 text-[#0d1b4b]/70 text-sm"><svg class="w-4 h-4 text-[#0d1b4b] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>لوحة تحليلات متقدمة</li>
                        <li class="flex items-center gap-3 text-[#0d1b4b]/70 text-sm"><svg class="w-4 h-4 text-[#0d1b4b] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>API للتكامل مع أنظمتك</li>
                        <li class="flex items-center gap-3 text-[#0d1b4b]/70 text-sm"><svg class="w-4 h-4 text-[#0d1b4b] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>مدير حساب مخصص</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center py-3.5 rounded-2xl border border-[#0d1b4b]/20 text-[#0d1b4b]/70 font-bold hover:bg-[#0d1b4b]/5 hover:border-[#0d1b4b]/35 hover:text-[#0d1b4b] transition">تواصل معنا</a>
                </div>
            </div>
            <!-- Policy Agreement Line -->
            <div class="text-center mt-10">
                <p class="text-s text-[#0d1b4b]/35 opacity-70 hover:opacity-100 transition">
                    باستخدامك منصة محلي، فإنك توافق على
                    <a href="/terms" target="_blank" class="text-[#d4af37] hover:underline">شروط الاستخدام</a>
                    و
                    <a href="/privacy" target="_blank" class="text-[#d4af37] hover:underline">سياسة الخصوصية</a>.
                </p>
            </div>
        </div>
    </section> --}}


    <x-faq-section id="faq" :items="[
    [
        'question' => 'هل يمكنني استخدام محلي مجاناً؟',
        'answer' => 'نعم، يمكنك البدء بالخطة المجانية بالكامل بدون أي بطاقة ائتمانية.',
        'color' => 'gold'
    ],
    [
        'question' => 'هل أحتاج خبرة تقنية؟',
        'answer' => 'لا، يمكنك إنشاء متجرك خلال دقائق بدون أي خبرة.',
        'color' => 'indigo'
    ],
    [
        'question' => 'هل يمكنني التحكم بالكوبونات؟',
        'answer' => 'نعم، يمكنك إدارة الخصومات والكوبونات بسهولة من لوحة التحكم.',
        'color' => 'purple'
    ],
    [
        'question' => 'هل البيانات آمنة؟',
        'answer' => 'نستخدم أنظمة حماية متقدمة لضمان أمان بياناتك بالكامل.',
        'color' => 'red'
    ],
    [
        'question' => 'هل يمكنني ترقية خطتي؟',
        'answer' => 'نعم، يمكنك الترقية في أي وقت بسهولة.',
        'color' => 'gold'
    ]
]" />
    <x-support-section id="support" />

    <!-- TESTIMONIALS -->
    <section id="testimonials" class="relative z-10 py-32 px-6 border-t border-[#0d1b4b]/8 bg-[#f0f4ff]/30">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <span class="inline-block px-4 py-1.5 rounded-full border border-[#d4af37]/30 bg-[#d4af37]/8 text-[#a07c1e] text-xs font-bold tracking-widest uppercase mb-6">آراء البائعين</span>
                <h2 class="text-4xl md:text-5xl font-black text-[#0d1b4b] mb-4">يثقون بنا ويبيعون معنا</h2>
                <p class="text-[#0d1b4b]/50 text-lg max-w-xl mx-auto">قصص حقيقية من بائعين بنوا مشاريعهم على محلي.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Card 1 -->
                <div class="relative p-8 rounded-3xl bg-white border border-[#0d1b4b]/8 hover:border-[#d4af37]/30 hover:shadow-lg hover:shadow-[#0d1b4b]/5 transition-all duration-500 flex flex-col">
                    <div class="flex gap-1 mb-6">
                        @for ($i = 0; $i < 5; $i++)<svg class="w-4 h-4 text-[#d4af37]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                    </div>
                    <blockquote class="text-[#0d1b4b]/65 leading-relaxed mb-8 flex-1 text-[15px]">"فتحت متجري على محلي في أقل من ساعة. التصميم أبهر عملائي من أول زيارة والطلبات بدأت تنهال من اليوم الأول."</blockquote>
                    <div class="flex items-center gap-4 border-t border-[#0d1b4b]/8 pt-6">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-rose-500 to-pink-700 flex items-center justify-center text-white font-black text-sm flex-shrink-0">سم</div>
                        <div><p class="text-[#0d1b4b] font-bold text-sm">سارة المحمد</p><p class="text-[#0d1b4b]/40 text-xs">متجر أناقتي – دمشق</p></div>
                    </div>
                </div>

                <!-- Card 2 — featured -->
                <div class="relative p-8 rounded-3xl bg-[#fffdf0] border border-[#d4af37]/25 hover:border-[#d4af37]/45 hover:shadow-lg hover:shadow-[#d4af37]/10 transition-all duration-500 flex flex-col md:col-span-2 lg:col-span-1">
                    <div class="absolute top-8 left-8 text-[#d4af37]/12 text-9xl font-black leading-none select-none">"</div>
                    <div class="flex gap-1 mb-6 relative z-10">
                        @for ($i = 0; $i < 5; $i++)<svg class="w-4 h-4 text-[#d4af37]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                    </div>
                    <blockquote class="text-[#0d1b4b]/80 leading-relaxed mb-8 flex-1 text-[16px] relative z-10">"كنت أدير متجري على انستغرام وأرهق من التواصل اليدوي. مع محلي صار عندي نظام حقيقي، الطلبات منظّمة، العملاء سعداء، وأنا أنام مرتاحاً."</blockquote>
                    <div class="flex items-center gap-4 border-t border-[#d4af37]/20 pt-6 relative z-10">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-amber-500 to-yellow-700 flex items-center justify-center text-white font-black text-sm flex-shrink-0">خع</div>
                        <div><p class="text-[#0d1b4b] font-bold text-sm">خالد العمر</p><p class="text-[#a07c1e] text-xs">متجر العطور الأصيلة – حلب</p></div>
                        <div class="mr-auto px-3 py-1 rounded-full bg-[#d4af37]/10 border border-[#d4af37]/25"><span class="text-[#a07c1e] text-xs font-bold">خطة احترافية</span></div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="relative p-8 rounded-3xl bg-white border border-[#0d1b4b]/8 hover:border-[#0d1b4b]/20 hover:shadow-lg hover:shadow-[#0d1b4b]/5 transition-all duration-500 flex flex-col">
                    <div class="flex gap-1 mb-6">
                        @for ($i = 0; $i < 5; $i++)<svg class="w-4 h-4 text-[#d4af37]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                    </div>
                    <blockquote class="text-[#0d1b4b]/65 leading-relaxed mb-8 flex-1 text-[15px]">"الخطة المجانية كانت كافية للبداية ثم انتقلت للخطة الاحترافية. الفرق كبير — التقارير والكوبونات ساعدتني أضاعف مبيعاتي."</blockquote>
                    <div class="flex items-center gap-4 border-t border-[#0d1b4b]/8 pt-6">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-indigo-500 to-violet-700 flex items-center justify-center text-white font-black text-sm flex-shrink-0">رف</div>
                        <div><p class="text-[#0d1b4b] font-bold text-sm">رنا فارس</p><p class="text-[#0d1b4b]/40 text-xs">متجر زهرة – اللاذقية</p></div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="relative p-8 rounded-3xl bg-white border border-[#0d1b4b]/8 hover:border-[#d4af37]/25 hover:shadow-lg transition-all duration-500 flex flex-col">
                    <div class="flex gap-1 mb-6">
                        @for ($i = 0; $i < 5; $i++)<svg class="w-4 h-4 text-[#d4af37]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                    </div>
                    <blockquote class="text-[#0d1b4b]/65 leading-relaxed mb-8 flex-1 text-[15px]">"اعتمدنا محلي لثلاثة فروع وإدارة كل شيء من مكان واحد وفّرت علينا وقتاً وجهداً هائلاً. الخطة المؤسسية تستحق كل قرش."</blockquote>
                    <div class="flex items-center gap-4 border-t border-[#0d1b4b]/8 pt-6">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white font-black text-sm flex-shrink-0">مح</div>
                        <div><p class="text-[#0d1b4b] font-bold text-sm">محمود الحسن</p><p class="text-[#0d1b4b]/40 text-xs">سلسلة متاجر الياسمين – حمص</p></div>
                        <div class="mr-auto px-3 py-1 rounded-full bg-[#0d1b4b]/8 border border-[#0d1b4b]/15"><span class="text-[#0d1b4b]/60 text-xs font-bold">خطة مؤسسية</span></div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="relative p-8 rounded-3xl bg-white border border-[#0d1b4b]/8 hover:border-[#d4af37]/25 hover:shadow-lg transition-all duration-500 flex flex-col">
                    <div class="flex gap-1 mb-6">
                        @for ($i = 0; $i < 5; $i++)<svg class="w-4 h-4 text-[#d4af37]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                    </div>
                    <blockquote class="text-[#0d1b4b]/65 leading-relaxed mb-8 flex-1 text-[15px]">"كبائعة مستقلة لم أكن أتوقع هذا المستوى من الاحترافية بسعر منخفض. محلي غيّر نظرتي لكيفية بيع منتجاتي اليدوية."</blockquote>
                    <div class="flex items-center gap-4 border-t border-[#0d1b4b]/8 pt-6">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-fuchsia-500 to-purple-700 flex items-center justify-center text-white font-black text-sm flex-shrink-0">دم</div>
                        <div><p class="text-[#0d1b4b] font-bold text-sm">دانا المصري</p><p class="text-[#0d1b4b]/40 text-xs">متجر إبداع يدوي – بيروت</p></div>
                    </div>
                </div>

            </div>

            <!-- Trust bar -->
            <div class="mt-20 pt-10 border-t border-[#0d1b4b]/8 flex flex-col sm:flex-row items-center justify-center gap-10 text-center">
                <div><p class="text-4xl font-black text-[#0d1b4b] mb-1">+1,200</p><p class="text-[#0d1b4b]/45 text-sm">بائع نشط</p></div>
                <div class="hidden sm:block w-px h-12 bg-[#0d1b4b]/10"></div>
                <div><p class="text-4xl font-black text-[#0d1b4b] mb-1">+45,000</p><p class="text-[#0d1b4b]/45 text-sm">طلب مكتمل</p></div>
                <div class="hidden sm:block w-px h-12 bg-[#0d1b4b]/10"></div>
                <div><p class="text-4xl font-black text-[#d4af37] mb-1">4.9 / 5</p><p class="text-[#0d1b4b]/45 text-sm">متوسط التقييم</p></div>
            </div>
        </div>
    </section>

        <!-- ─── Footer — dark navy for premium closure ───────────────── -->
        <footer class="relative z-10 bg-[#0d1b4b] border-t border-[#d4af37]/15 py-20 px-6">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start gap-12">
                <div class="max-w-sm">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('dashboard') }}" class="flex items-center group">
                                    <div class="relative bg-white/10 backdrop-blur-xl p-2.5 rounded-xl border border-white/10 shadow-2xl group-hover:border-[#d4af37]/30 transition-all duration-500 overflow-hidden">
                                        <div class="absolute inset-0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-[#d4af37]/10 to-transparent"></div>
                                        <x-application-logo class="block h-12 w-auto hover:brightness-110 transition-all duration-300" />
                                    </div>
                                </a>
                            </div>
                            <span class="hidden sm:inline-block text-[10px] text-white/30 uppercase tracking-[0.2em] border-r border-white/15 pr-3 mr-1 mt-0.5">
                                THE LUXURY OF LOCAL
                            </span>
                        </div>
                    </div>
                    <p class="text-white/40 leading-relaxed">المنصة الشريكة لكل بائع وبائعة يبحثون عن التميز والفرادة في عالم التجارة الرقمية.</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-12 sm:gap-24">
                    <div>
                        <h4 class="text-white font-bold mb-6">الروابط</h4>
                        <ul class="space-y-4 text-white/40 text-sm">
                            <li><a href="{{ route('login') }}" class="hover:text-[#d4af37] transition">الدخول</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-[#d4af37] transition">التسجيل</a></li>
                            <li><a href="#pricing" class="hover:text-[#d4af37] transition">الأسعار</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-bold mb-6">الدعم</h4>
                        <ul class="space-y-4 text-white/40 text-sm">
                            <li><a href="#faq" class="hover:text-[#d4af37] transition">الأسئلة الشائعة</a></li>
                            <li><a href="#support" class="hover:text-[#d4af37] transition">الدعم الفني</a></li>
                            <li>سياسة <a href="{{ route('terms') }}"><span class="text-[#d4af37]">الاستخدام</span></a> و <a href="{{ route('privacy') }}"><span class="text-[#d4af37]">الخصوصية</span></a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-bold mb-6">المصادر</h4>
                        <ul class="space-y-4 text-white/40 text-sm">
                            <li><a href="{{ route('attributions') }}" class="hover:text-[#d4af37] transition">حقوق النشر</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto mt-20 pt-8 border-t border-white/8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-white/25">
                <p>© {{ date('Y') }} محلي. جميع الحقوق محفوظة.</p>
                <p>تم التصميم بكل شغف لدعم المشاريع المحلية.</p>
            </div>
        </footer>

    </div>
</body>
</html>
