import React from 'react';
import { Link, useForm, usePage } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';
import type { SharedPageProps } from '../../types/inertia';

export default function Login() {
  const page = usePage<SharedPageProps>();
  const status = page.props.flash?.status;

  const { data, setData, post, processing, errors } = useForm({
    email: '',
    password: '',
    remember: false,
  });

  const submit: React.FormEventHandler<HTMLFormElement> = (e) => {
    e.preventDefault();
    post('/login');
  };

  return (
    <GuestLayout title="تسجيل الدخول">
      <div className="rounded-3xl border border-[#0d1b4b]/10 bg-white/70 px-8 py-10 shadow-xl shadow-[#0d1b4b]/6 backdrop-blur-xl">
        <div className="mb-8 text-center">
          <div className="mb-5 inline-flex items-center gap-2 rounded-full border border-[#0d1b4b]/12 bg-[#0d1b4b]/6 px-3 py-1.5 text-[11px] font-bold uppercase tracking-widest text-[#0d1b4b]/60">
            <span className="h-1.5 w-1.5 rounded-full bg-[#0d1b4b]/50" />
            مرحباً بعودتك
          </div>
          <h1 className="text-2xl font-black leading-tight text-[#0d1b4b]">
            تسجيل <span className="bg-gradient-to-l from-[#d4af37] to-[#b8922a] bg-clip-text text-transparent">الدخول</span>
          </h1>
          <p className="mt-2 text-sm text-[#0d1b4b]/45">ادخل إلى لوحة تحكم متجرك</p>
        </div>

        {status ? (
          <div className="mb-5 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-2 text-sm font-semibold text-emerald-700">
            {status}
          </div>
        ) : null}

        <form onSubmit={submit} className="space-y-5">
          <a
            href="/auth/google"
            className="group flex w-full items-center justify-center gap-3 rounded-2xl border border-[#0d1b4b]/12 bg-white px-5 py-3 text-sm font-bold text-[#0d1b4b]/70 shadow-sm transition-all duration-200 hover:border-[#d4af37]/50 hover:bg-[#fdfbf4] hover:text-[#0d1b4b]"
          >
            <GoogleIcon />
            <span>المتابعة عبر Google</span>
          </a>

          <div className="relative flex items-center gap-3">
            <div className="h-px flex-1 bg-[#0d1b4b]/8" />
            <span className="text-xs text-[#0d1b4b]/30">أو بالبريد الإلكتروني</span>
            <div className="h-px flex-1 bg-[#0d1b4b]/8" />
          </div>

          <div>
            <label htmlFor="email" className="text-sm font-bold text-[#0d1b4b]">
              البريد الإلكتروني
            </label>
            <input
              id="email"
              type="email"
              name="email"
              value={data.email}
              onChange={(e) => setData('email', e.target.value)}
              placeholder="example@email.com"
              autoComplete="username"
              className="mt-1.5 block w-full rounded-xl border border-[#0d1b4b]/20 bg-white px-4 py-2.5 outline-none transition focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20"
              required
              autoFocus
            />
            {errors.email ? <p className="mt-1.5 text-xs font-semibold text-red-600">{errors.email}</p> : null}
          </div>

          <div>
            <div className="mb-1.5 flex items-center justify-between">
              <label htmlFor="password" className="text-sm font-bold text-[#0d1b4b]">
                كلمة المرور
              </label>
              <Link href="/forgot-password" className="text-xs font-semibold text-[#d4af37] transition-colors hover:text-[#b8922a]">
                نسيت كلمة المرور؟
              </Link>
            </div>
            <input
              id="password"
              type="password"
              name="password"
              value={data.password}
              onChange={(e) => setData('password', e.target.value)}
              placeholder="••••••••"
              autoComplete="current-password"
              className="block w-full rounded-xl border border-[#0d1b4b]/20 bg-white px-4 py-2.5 outline-none transition focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20"
              required
            />
            {errors.password ? <p className="mt-1.5 text-xs font-semibold text-red-600">{errors.password}</p> : null}
          </div>

          <label htmlFor="remember_me" className="flex cursor-pointer items-center gap-2.5 select-none">
            <input
              id="remember_me"
              type="checkbox"
              name="remember"
              checked={data.remember}
              onChange={(e) => setData('remember', e.target.checked)}
              className="h-4 w-4 cursor-pointer rounded border-[#0d1b4b]/20 bg-white text-[#d4af37] focus:ring-[#d4af37]/30 focus:ring-offset-0"
            />
            <span className="text-sm text-[#0d1b4b]/55">تذكرني</span>
          </label>

          <div className="pt-1">
            <button
              type="submit"
              disabled={processing}
              className="group relative w-full overflow-hidden rounded-2xl bg-[#0d1b4b] px-6 py-3.5 text-base font-black text-white shadow-lg shadow-[#0d1b4b]/20 transition-all duration-200 hover:bg-[#1a2d6b] active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-60"
            >
              <span className="relative z-10">{processing ? 'جاري تسجيل الدخول...' : 'دخول إلى المتجر'}</span>
              <div className="absolute inset-0 translate-y-full rounded-2xl bg-white/5 transition-transform duration-300 group-hover:translate-y-0" />
            </button>
          </div>

          <Link
            href="/register"
            className="block w-full rounded-2xl border border-[#d4af37]/30 bg-[#d4af37]/5 py-3.5 text-center text-sm font-bold text-[#a07c1e] transition-all duration-200 hover:border-[#d4af37]/60 hover:bg-[#d4af37]/10 hover:text-[#0d1b4b]"
          >
            بائع جديد؟ أنشئ متجرك مجاناً
          </Link>
        </form>
      </div>

      <p className="mt-6 text-center text-[11px] tracking-wide text-[#0d1b4b]/30">محلي — منصة التجارة المحلية الفاخرة</p>
    </GuestLayout>
  );
}

function GoogleIcon() {
  return (
    <svg viewBox="0 0 18 18" width="18" height="18" className="shrink-0">
      <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 01-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.615z" />
      <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 009 18z" />
      <path fill="#FBBC05" d="M3.964 10.707A5.41 5.41 0 013.682 9c0-.593.102-1.17.282-1.707V4.961H.957A8.996 8.996 0 000 9c0 1.452.348 2.827.957 4.039l3.007-2.332z" />
      <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 00.957 4.961L3.964 7.293C4.672 5.163 6.656 3.58 9 3.58z" />
    </svg>
  );
}
