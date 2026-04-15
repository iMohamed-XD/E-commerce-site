import React, { useMemo, useState } from 'react';
import { Link } from '@inertiajs/react';
import MarketingLayout from '../Layouts/MarketingLayout';

interface LandingProps {
  sellersCount: number;
  ordersCount: number;
  avgRating: number;
}

type FaqItem = {
  question: string;
  answer: string;
  color: 'gold' | 'indigo' | 'purple' | 'red';
};

const HOW_STEPS = [
  {
    number: '١',
    label: 'الخطوة الأولى',
    title: 'أنشئ حسابك',
    accent: 'في ثوانٍ معدودة',
    body:
      'سجّل كبائع على منصة محلي ببياناتك الأساسية فقط. العملية مجانية تماماً وتستغرق أقل من دقيقة — ستحصل فوراً على وصول كامل إلى لوحة التحكم الخاصة بك.',
    bullets: ['تسجيل مجاني بالكامل — لا بطاقة ائتمانية', 'بيانات مشفرة ومحمية بالكامل', 'وصول فوري للوحة التحكم'],
    image: '/images/register_form.png',
  },
  {
    number: '٢',
    label: 'الخطوة الثانية',
    title: 'صمّم متجرك',
    accent: 'بهوية احترافية',
    body:
      'اختر اسم متجرك، ارفع شعارك وصورة الغلاف، واضبط هويتك البصرية بسهولة. خلال دقائق يصبح لديك متجر جاهز للعرض والبيع.',
    bullets: ['رابط متجر مخصص وفريد من نوعه', 'صورة غلاف وشعار بجودة عالية', 'معاينة فورية قبل الحفظ'],
    image: '/images/shop_creation_form.png',
  },
  {
    number: '٣',
    label: 'الخطوة الثالثة',
    title: 'أضف منتجاتك',
    accent: 'وابدأ البيع فوراً',
    body:
      'أضف منتجاتك مع الصور والأسعار والخصومات والتصنيفات. النظام مصمم ليجعل إدارة المنتجات سهلة وسريعة حتى بدون خبرة تقنية.',
    bullets: ['تصنيفات مرنة وقابلة للإضافة', 'نظام خصومات مدمج داخل كل منتج', 'رفع صور المنتجات بسهولة تامة'],
    image: '/images/product_adding_form.png',
  },
] as const;

const FAQ_ITEMS: FaqItem[] = [
  { question: 'هل يمكنني البدء مجاناً؟', answer: 'نعم، يمكنك البدء بالخطة المجانية مباشرة وبدون أي بطاقة ائتمانية.', color: 'gold' },
  { question: 'هل أحتاج خبرة تقنية لإدارة المتجر؟', answer: 'لا، المنصة مصممة لتكون بسيطة جداً ويمكن لأي شخص استخدامها بسهولة.', color: 'indigo' },
  { question: 'هل يوجد دعم فني عند الحاجة؟', answer: 'نعم، فريق الدعم متاح لمساعدتك عبر البريد والدردشة المباشرة.', color: 'purple' },
  { question: 'هل بياناتي وبيانات العملاء آمنة؟', answer: 'بالتأكيد، نستخدم معايير حماية وتشفير عالية للحفاظ على بياناتك بالكامل.', color: 'red' },
  { question: 'هل يمكنني ترقية خطتي؟', answer: 'نعم، يمكنك الترقية في أي وقت بسهولة.', color: 'gold' },
];

const PRICING = [
  {
    name: 'خطة البداية',
    price: 'مجانية',
    note: 'للبدء واختبار المنصة',
    cta: 'ابدأ مجاناً',
    variant: 'default',
    ctaClass:
      'block text-center py-3.5 rounded-2xl border border-[#0d1b4b]/20 bg-white/80 text-[#0d1b4b] font-black hover:border-[#0d1b4b]/35 hover:bg-white transition',
    features: ['متجر واحد', 'إدارة المنتجات والطلبات', 'دعم فني أساسي'],
    featured: false,
  },
  {
    name: 'الخطة الاحترافية',
    price: 'قريباً',
    note: 'أدوات متقدمة للنمو',
    cta: 'اشترك الآن',
    variant: 'gold',
    ctaClass:
      'block text-center py-3.5 rounded-2xl bg-[#d4af37] text-[#0d1b4b] font-black hover:bg-[#c5a02e] transition shadow-lg shadow-[#d4af37]/25',
    features: ['إحصاءات وتقارير موسعة', 'كوبونات وعروض ترويجية', 'أولوية في الدعم الفني'],
    featured: true,
  },
  {
    name: 'الخطة المؤسسية',
    price: 'مخصصة',
    note: 'للشركات والمتاجر الكبيرة',
    cta: 'تواصل معنا',
    variant: 'navy',
    ctaClass:
      'block text-center py-3.5 rounded-2xl bg-[#0d1b4b] text-white font-black hover:bg-[#1a2d6b] transition shadow-lg shadow-[#0d1b4b]/25',
    features: ['مزايا مخصصة', 'دعم خاص مخصص', 'حلول مرنة حسب النشاط'],
    featured: false,
  },
] as const;

const FAQ_COLORS: Record<FaqItem['color'], string> = {
  gold: 'hover:border-[#d4af37]/40',
  indigo: 'hover:border-[#0d1b4b]/30',
  purple: 'hover:border-[#a07c1e]/35',
  red: 'hover:border-red-500/40',
};

function formatNumber(value: number) {
  return new Intl.NumberFormat('en-US').format(value);
}

export default function Landing({ sellersCount, ordersCount, avgRating }: LandingProps) {
  const [activeFaq, setActiveFaq] = useState(0);
  const year = useMemo(() => new Date().getFullYear(), []);

  return (
    <MarketingLayout title="محلي | منصتك للتجارة الإلكترونية الفاخرة">
      <main className="relative z-10 flex-grow px-6 pb-12 pt-24">
        <div className="mx-auto max-w-5xl text-center">
          <div className="mb-10 flex justify-center">
            <Link href="/dashboard" className="group">
              <img src="/logo.png" alt="محلي" className="mx-auto h-40 w-auto transition-all duration-500 hover:scale-105 hover:brightness-110 md:h-48" />
            </Link>
          </div>

          <div className="mb-8 inline-flex animate-pulse items-center gap-2 rounded-full border border-[#0d1b4b]/15 bg-[#0d1b4b]/8 px-4 py-2 text-xs font-bold text-[#0d1b4b]/70">
            <span className="h-2 w-2 rounded-full bg-[#0d1b4b]/60" />
            اكتشف الجيل القادم من المتاجر الإلكترونية
          </div>

          <h1 className="mb-8 text-5xl font-black leading-[1.1] tracking-tight text-[#0d1b4b] md:text-8xl">
            أنشئ متجرك الإلكتروني
            <span className="bg-gradient-to-r from-[#d4af37] via-[#e8c84a] to-[#b8922a] bg-clip-text text-transparent"> وابدأ البيع خلال دقائق</span>
          </h1>

          <p className="mx-auto mb-12 max-w-3xl text-xl font-light leading-relaxed text-[#0d1b4b]/60 md:text-2xl">
            لا تحتاج خبرة تقنية، ولا فريق إدارة، مع <span className="font-bold text-[#0d1b4b]">محلي</span> كلشيء جاهز لك لتبدأ وتربح من اليوم الأول
          </p>

          <div className="flex flex-col items-center justify-center gap-6 sm:flex-row">
            <Link
              href="/register"
              className="group relative w-full overflow-hidden rounded-2xl bg-[#d4af37] px-10 py-5 text-xl font-black text-[#0d1b4b] shadow-2xl shadow-[#d4af37]/30 transition-all duration-300 hover:bg-[#c5a02e] sm:w-auto"
            >
              <span className="relative z-10">أنشئ متجرك الآن</span>
              <div className="absolute inset-0 translate-y-12 bg-white/20 transition-transform duration-300 group-hover:translate-y-0" />
            </Link>

            <a href="#how-it-works" className="w-full rounded-2xl border border-[#0d1b4b]/15 bg-[#0d1b4b]/5 px-10 py-5 text-xl font-bold text-[#0d1b4b] transition-all hover:border-[#0d1b4b]/25 hover:bg-[#0d1b4b]/10 sm:w-auto">
              شاهد كيف يعمل
            </a>
          </div>
        </div>
      </main>

      <section id="how-it-works" className="relative z-10 overflow-hidden border-t border-[#0d1b4b]/8 bg-white/40 px-6 py-32">
        <div className="pointer-events-none absolute inset-0">
          <div className="absolute left-1/2 top-1/2 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#d4af37]/6 blur-[120px]" />
        </div>

        <div className="relative mx-auto max-w-7xl">
          <div className="mb-28 text-center">
            <span className="mb-6 inline-block rounded-full border border-[#d4af37]/30 bg-[#d4af37]/12 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-[#a07c1e]">دليل البدء</span>
            <h2 className="mb-5 text-4xl font-black leading-tight text-[#0d1b4b] md:text-6xl">
              ثلاث خطوات إلى <span className="bg-gradient-to-r from-[#d4af37] via-[#e8c84a] to-[#b8922a] bg-clip-text text-transparent">متجرك</span>
            </h2>
            <p className="mx-auto max-w-xl text-lg leading-relaxed text-[#0d1b4b]/55">من التسجيل إلى أول عملية بيع — كل شيء مصمم ليكون بسيطاً، سريعاً، وأنيقاً.</p>
          </div>

          <div className="steps-timeline relative">
            {HOW_STEPS.map((step, idx) => {
              const reverse = idx === 1;
              return (
                <div key={step.number} className="step-row relative flex flex-col items-center gap-10 pb-28 lg:flex-row lg:gap-0 lg:pb-36">
                  <div className="step-dot absolute right-1/2 top-1/2 z-20 hidden h-12 w-12 translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full border-2 border-[#d4af37] bg-white shadow-md lg:flex">
                    <span className="text-base font-black text-[#d4af37]">{step.number}</span>
                  </div>

                  <div className={`w-full lg:w-1/2 ${reverse ? 'lg:order-2 lg:ps-16 text-left lg:text-left' : 'lg:pe-16 text-right'}`}>
                    <div className={`mb-6 inline-flex items-center gap-3 lg:hidden ${reverse ? 'justify-start' : ''}`}>
                      <span className="flex h-10 w-10 items-center justify-center rounded-full border border-[#0d1b4b]/20 bg-[#0d1b4b]/8 text-base font-black text-[#0d1b4b]">{step.number}</span>
                      <span className="text-sm font-bold tracking-wider text-[#0d1b4b]">{step.label}</span>
                    </div>

                    <div className="mb-5 hidden w-fit rounded-lg border border-[#0d1b4b]/15 bg-[#0d1b4b]/8 px-3 py-1 text-xs font-bold uppercase tracking-widest text-[#0d1b4b]/70 lg:inline-block">{step.label}</div>

                    <h3 className="mb-5 text-3xl font-black leading-snug text-[#0d1b4b] md:text-4xl">
                      {step.title}
                      <br />
                      <span className="text-[#d4af37]">{step.accent}</span>
                    </h3>

                    <p className={`mb-8 max-w-md text-lg leading-relaxed text-[#0d1b4b]/55 ${reverse ? 'ms-0 me-auto' : 'ms-auto me-0 lg:ms-0'}`}>{step.body}</p>

                    <ul className={`inline-flex w-full flex-col space-y-3 ${reverse ? 'items-start' : 'items-end'}`}>
                      {step.bullets.map((item) => (
                        <li key={item} className="flex items-center gap-3 text-[#0d1b4b]/60">
                          <span className="text-sm">{item}</span>
                          <span className="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full border border-green-500/30 bg-green-500/15">
                            <svg className="h-3 w-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                            </svg>
                          </span>
                        </li>
                      ))}
                    </ul>
                  </div>

                  <div className={`w-full lg:w-1/2 ${reverse ? 'lg:order-1 lg:pe-16' : 'lg:ps-16'}`}>
                    <div className="screenshot-frame overflow-hidden rounded-3xl border border-[#0d1b4b]/10 bg-white/75 p-3 shadow-2xl shadow-[#0d1b4b]/8 backdrop-blur-xl">
                      <img src={step.image} alt={step.title} className="h-auto w-full rounded-2xl object-cover" />
                    </div>
                  </div>
                </div>
              );
            })}
          </div>

          <div className="mt-4 text-center">
            <Link
              href="/register"
              className="inline-flex items-center gap-4 rounded-2xl bg-gradient-to-l from-[#d4af37] to-[#e8c84a] px-10 py-4 font-black text-[#0d1b4b] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[#d4af37]/25 active:scale-95"
            >
              <span>ابدأ الآن مجاناً خلال دقيقة واحدة</span>
              <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
              </svg>
            </Link>
          </div>
        </div>
      </section>

      <section id="pricing" className="relative z-10 border-t border-[#0d1b4b]/8 bg-[#f8fbff]/50 px-6 py-32">
        <div className="mx-auto max-w-7xl">
          <div className="mb-16 text-center">
            <span className="mb-6 inline-block rounded-full border border-[#0d1b4b]/15 bg-[#0d1b4b]/5 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-[#0d1b4b]/60">الأسعار</span>
            <h2 className="mb-4 text-4xl font-black text-[#0d1b4b] md:text-6xl">
              خطط تناسب <span className="text-[#d4af37]">جميع المتاجر</span>
            </h2>
            <p className="mx-auto max-w-xl text-lg text-[#0d1b4b]/55">ابدأ مجاناً الآن، ثم اختر الخطة الأنسب لمرحلة نمو متجرك.</p>
          </div>

          <div className="mt-10 grid grid-cols-1 gap-6 lg:grid-cols-3">
            {PRICING.map((plan) => (
              <div
                key={plan.name}
                className={`rounded-3xl border p-8 backdrop-blur-xl transition-all duration-300 ${
                  plan.variant === 'gold'
                    ? 'plan-popular-glow border-[#d4af37]/35 bg-[#fffdf2]'
                    : plan.variant === 'navy'
                      ? 'border-[#0d1b4b]/20 bg-[#eef2ff] shadow-lg shadow-[#0d1b4b]/10'
                      : 'border-[#0d1b4b]/10 bg-white/75'
                }`}
              >
                <h3 className="mb-2 text-2xl font-black text-[#0d1b4b]">{plan.name}</h3>
                <p className="mb-4 text-3xl font-black text-[#0d1b4b]">{plan.price}</p>
                <p className="mb-8 text-sm text-[#0d1b4b]/55">{plan.note}</p>
                <ul className="mb-8 space-y-3 text-sm text-[#0d1b4b]/70">
                  {plan.features.map((feature) => (
                    <li key={feature}>• {feature}</li>
                  ))}
                </ul>
                <Link href={plan.name === 'الخطة المؤسسية' ? '/support' : '/register'} className={plan.ctaClass}>
                  {plan.cta}
                </Link>
              </div>
            ))}
          </div>
        </div>
      </section>

      <section id="faq" className="relative z-10 border-t border-[#0d1b4b]/8 bg-white/30 px-6 py-32 backdrop-blur-sm">
        <div className="mx-auto max-w-5xl">
          <div className="mb-20 text-center">
            <span className="mb-6 inline-block rounded-full border border-[#d4af37]/30 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-[#d4af37]">الأسئلة الشائعة</span>
            <h2 className="mb-4 text-4xl font-black text-[#0d1b4b] md:text-5xl">
              كل ما تحتاج معرفته عن <span className="text-[#d4af37]">محلي</span>
            </h2>
            <p className="mx-auto max-w-xl text-lg text-[#0d1b4b]/50">أجوبة سريعة وواضحة على أكثر الأسئلة شيوعاً.</p>
          </div>

          <div className="space-y-4">
            {FAQ_ITEMS.map((item, index) => {
              const open = activeFaq === index;
              return (
                <div key={item.question} className={`group overflow-hidden rounded-2xl border border-[#0d1b4b]/10 bg-white/70 backdrop-blur-xl transition-all duration-300 ${FAQ_COLORS[item.color]}`}>
                  <button onClick={() => setActiveFaq(open ? -1 : index)} className="flex w-full items-center justify-between px-6 py-5 text-right">
                    <span className="text-lg font-bold text-[#0d1b4b]">{item.question}</span>
                    <svg className={`h-5 w-5 transition-all duration-300 ${open ? 'rotate-180 text-[#d4af37]' : 'text-[#0d1b4b]/35'}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                  {open && <div className="px-6 pb-6 leading-relaxed text-[#0d1b4b]/65">{item.answer}</div>}
                </div>
              );
            })}
          </div>
        </div>
      </section>

      <section id="support" className="relative z-10 border-t border-[#0d1b4b]/8 bg-white px-6 py-32">
        <div className="pointer-events-none absolute inset-0">
          <div className="absolute left-1/2 top-1/2 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#d4af37]/5 blur-[120px]" />
        </div>

        <div className="relative mx-auto max-w-6xl">
          <div className="mb-20 text-center">
            <span className="mb-6 inline-block rounded-full border border-[#0d1b4b]/15 bg-[#0d1b4b]/5 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-[#0d1b4b]/60">الدعم الفني</span>
            <h2 className="mb-4 text-4xl font-black text-[#0d1b4b] md:text-5xl">
              نحن هنا <span className="text-[#d4af37]">لمساعدتك</span>
            </h2>
            <p className="mx-auto max-w-xl text-lg text-[#0d1b4b]/50">فريق الدعم جاهز للإجابة على استفساراتك ومساعدتك في أي وقت تحتاجه.</p>
          </div>

          <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
            <SupportCard
              title="البريد الإلكتروني"
              body="تواصل معنا وسنرد عليك خلال 24 ساعة."
              cta="support@mahly.org"
              href="mailto:support@mahly.org"
              variant="default"
              ctaVariant="blue"
              iconPath="M3 8l7.89 4.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2z"
            />
            <SupportCard
              title="الدردشة المباشرة"
              body="احصل على مساعدة فورية من فريقنا."
              cta="ابدأ المحادثة"
              href="https://wa.me/message/JDYLWDNU6PXQA1"
              variant="gold"
              ctaVariant="gold"
              iconPath="M8 10h.01M12 10h.01M16 10h.01M9 16H5l-2 2V5a2 2 0 012-2h14a2 2 0 012 2v11a2 2 0 01-2 2h-5l-4 4v-4z"
            />
            <SupportCard
              title="مركز المساعدة"
              body="دروس ومقالات تساعدك على النجاح."
              cta="تصفح الأسئلة"
              href="#faq"
              variant="default"
              ctaVariant="outline"
              iconPath="M12 18h.01M12 14a4 4 0 10-4-4"
            />
          </div>

          <div className="mt-20 border-t border-[#0d1b4b]/8 pt-10 text-center">
            <p className="mb-6 text-[#0d1b4b]/45">ما زلت بحاجة إلى مساعدة؟</p>
            <Link
              href="/register"
              className="inline-flex items-center gap-4 rounded-2xl bg-gradient-to-l from-[#d4af37] to-[#e8c84a] px-10 py-4 font-black text-[#0d1b4b] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[#d4af37]/25 active:scale-95"
            >
              <span>ابدأ الآن وسنرشدك خطوة بخطوة</span>
              <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
              </svg>
            </Link>
          </div>
        </div>
      </section>

      <footer className="relative z-10 border-t border-[#d4af37]/15 bg-[#0d1b4b] px-6 py-20">
        <div className="mx-auto flex max-w-7xl flex-col items-start justify-between gap-12 md:flex-row">
          <div className="max-w-sm">
            <div className="mb-6 flex items-center gap-2">
              <Link href="/dashboard" className="group flex items-center">
                <div className="relative overflow-hidden rounded-xl border border-white/10 bg-white/10 p-2.5 shadow-2xl transition-all duration-500 group-hover:border-[#d4af37]/30">
                  <div className="absolute inset-0 translate-x-[-100%] bg-gradient-to-r from-transparent via-[#d4af37]/10 to-transparent transition-transform duration-1000 group-hover:translate-x-[100%]" />
                  <img src="/logo.png" alt="محلي" className="block h-12 w-auto transition-all duration-300 hover:brightness-110" />
                </div>
              </Link>
              <span className="mt-0.5 mr-1 hidden border-r border-white/15 pr-3 text-[10px] uppercase tracking-[0.2em] text-white/30 sm:inline-block">THE LUXURY OF LOCAL</span>
            </div>
            <p className="mb-8 leading-relaxed text-white/40">المنصة الشريكة لكل بائع وبائعة يبحثون عن التميز والفرادة في عالم التجارة الرقمية.</p>
            <Link href="/support" className="inline-flex items-center gap-2 rounded-2xl bg-pink-500 px-8 py-4 text-lg font-black text-white shadow-2xl shadow-pink-500/40 transition-all hover:bg-pink-600 active:scale-95">
              <svg className="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
              </svg>
              <span>ادعم استمرار مشروعنا</span>
            </Link>
          </div>

          <div className="grid grid-cols-2 gap-12 sm:gap-16 xl:grid-cols-4">
            <FooterCol
              title="الروابط"
              links={[
                { href: '/login', label: 'الدخول' },
                { href: '/register', label: 'التسجيل' },
                { href: '#pricing', label: 'الأسعار' },
              ]}
            />
            <div>
              <h4 className="mb-6 font-bold text-white">الدعم</h4>
              <ul className="space-y-4 text-sm text-white/40">
                <li><a href="#faq" className="transition hover:text-[#d4af37]">الأسئلة الشائعة</a></li>
                <li><a href="#support" className="transition hover:text-[#d4af37]">الدعم الفني</a></li>
                <li>
                  سياسة <a href="/terms" className="text-[#d4af37]">الاستخدام</a> و <a href="/privacy" className="text-[#d4af37]">الخصوصية</a>
                </li>
              </ul>
            </div>
            <div>
              <h4 className="mb-6 font-bold text-white">السوشال ميديا</h4>
              <ul className="space-y-4 text-sm text-white/40">
                <li><a href="https://www.facebook.com/share/1J5urLe3tg/?mibextid=wwXIfr" target="_blank" rel="noreferrer" className="transition hover:text-[#d4af37]">Facebook</a></li>
                <li><a href="https://www.instagram.com/mahly.1?igsh=ODQ0NHA5aWVsYmhs&utm_source=qr" target="_blank" rel="noreferrer" className="transition hover:text-[#d4af37]">Instagram</a></li>
              </ul>
            </div>
            <FooterCol title="المصادر" links={[{ href: '/attributions', label: 'حقوق النشر' }]} />
          </div>
        </div>

        <div className="mx-auto mt-20 flex max-w-7xl flex-col items-center justify-between gap-4 border-t border-white/8 pt-8 text-xs text-white/25 sm:flex-row">
          <p>© {year} محلي. جميع الحقوق محفوظة.</p>
          <p>تم التصميم بكل شغف لدعم المشاريع المحلية.</p>
        </div>
      </footer>
    </MarketingLayout>
  );
}

function SupportCard({
  title,
  body,
  cta,
  href,
  variant,
  ctaVariant,
  iconPath,
}: {
  title: string;
  body: string;
  cta: string;
  href: string;
  variant: 'default' | 'gold' | 'navy';
  ctaVariant?: 'blue' | 'gold' | 'outline';
  iconPath: string;
}) {
  const isGold = variant === 'gold';
  const isNavy = variant === 'navy';
  const card = isGold
    ? 'bg-[#fffdf5] border-[#d4af37]/25 hover:border-[#d4af37]/50 hover:shadow-[#d4af37]/8'
    : isNavy
      ? 'bg-[#0d1b4b] border-[#0d1b4b] hover:border-[#1a2d6b] hover:shadow-[#0d1b4b]/25'
      : 'bg-[#f8faff] border-[#0d1b4b]/8 hover:border-[#0d1b4b]/25 hover:shadow-[#0d1b4b]/5';
  const iconWrap = isGold ? 'bg-[#d4af37]/12' : isNavy ? 'bg-white/10' : 'bg-[#0d1b4b]/8';
  const iconColor = isGold ? 'text-[#a07c1e]' : isNavy ? 'text-white' : 'text-[#0d1b4b]';
  const titleColor = isNavy ? 'text-white' : 'text-[#0d1b4b]';
  const bodyColor = isNavy ? 'text-white/70' : 'text-[#0d1b4b]/50';
  const ctaClass =
    ctaVariant === 'gold'
      ? 'rounded-xl bg-[#d4af37] px-6 py-2.5 font-black text-[#0d1b4b] shadow-md shadow-[#d4af37]/20 transition hover:bg-[#c5a02e]'
      : ctaVariant === 'blue'
        ? 'rounded-xl bg-[#0d1b4b] px-6 py-2.5 font-black text-white shadow-md shadow-[#0d1b4b]/20 transition hover:bg-[#1a2d6b]'
        : isNavy
          ? 'rounded-xl bg-white px-6 py-2.5 font-bold text-[#0d1b4b] transition hover:bg-[#f1f5ff]'
          : 'rounded-xl border border-[#0d1b4b]/20 px-6 py-2.5 text-[#0d1b4b]/65 transition hover:border-[#0d1b4b]/35 hover:bg-[#0d1b4b]/5 hover:text-[#0d1b4b]';

  return (
    <div className={`group rounded-3xl border p-8 text-center transition-all duration-500 hover:shadow-lg ${card}`}>
      <div className={`mx-auto mb-6 flex h-14 w-14 items-center justify-center rounded-2xl transition group-hover:scale-110 ${iconWrap}`}>
        <svg className={`h-7 w-7 ${iconColor}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d={iconPath} />
        </svg>
      </div>
      <h3 className={`mb-3 text-xl font-bold ${titleColor}`}>{title}</h3>
      <p className={`mb-6 text-sm ${bodyColor}`}>{body}</p>
      <a href={href} target={href.startsWith('http') ? '_blank' : undefined} rel={href.startsWith('http') ? 'noreferrer' : undefined} className={`inline-block ${ctaClass}`}>
        {cta}
      </a>
    </div>
  );
}

function FooterCol({ title, links }: { title: string; links: Array<{ href: string; label: string }> }) {
  return (
    <div>
      <h4 className="mb-6 font-bold text-white">{title}</h4>
      <ul className="space-y-4 text-sm text-white/40">
        {links.map((link) => (
          <li key={`${title}-${link.href}`}>
            <a href={link.href} className="transition hover:text-[#d4af37]">
              {link.label}
            </a>
          </li>
        ))}
      </ul>
    </div>
  );
}
