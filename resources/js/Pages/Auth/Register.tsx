import React from 'react';
import { Link, useForm } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

export default function Register() {
  const { data, setData, post, processing, errors } = useForm({
    name: '',
    email: '',
    phone_number: '',
    password: '',
    password_confirmation: '',
    role: 'seller',
    terms: false,
  });

  const submit: React.FormEventHandler<HTMLFormElement> = (e) => {
    e.preventDefault();
    post('/register');
  };

  return (
    <GuestLayout title="إنشاء حساب بائع">
      <div className="rounded-3xl border border-[#0d1b4b]/10 bg-white/70 px-8 py-10 shadow-xl shadow-[#0d1b4b]/6 backdrop-blur-xl">
        <div className="mb-8 text-center">
          <div className="mb-5 inline-flex items-center gap-2 rounded-full border border-[#d4af37]/25 bg-[#d4af37]/10 px-3 py-1.5 text-[11px] font-bold uppercase tracking-widest text-[#a07c1e]">
            <span className="h-1.5 w-1.5 rounded-full bg-[#d4af37]" />
            انضم إلى محلي
          </div>
          <h1 className="text-2xl font-black leading-tight text-[#0d1b4b]">
            أنشئ حسابك <span className="bg-gradient-to-l from-[#d4af37] to-[#b8922a] bg-clip-text text-transparent">مجاناً</span>
          </h1>
          <p className="mt-2 text-sm text-[#0d1b4b]/45">ابدأ متجرك الإلكتروني في دقائق</p>
        </div>

        <form onSubmit={submit} className="space-y-5">
          <a
            href="/auth/google"
            className="flex w-full items-center justify-center gap-3 rounded-lg border border-[#0d1b4b]/12 bg-white px-5 py-3 text-sm font-medium text-[#0d1b4b] transition-colors duration-200 hover:border-[#d4af37]/45 hover:bg-[#fdfbf4]"
          >
            <GoogleIcon />
            <span>المتابعة باستخدام Google</span>
          </a>

          <Field label="الاسم الكامل" error={errors.name}>
            <input
              id="name"
              value={data.name}
              onChange={(e) => setData('name', e.target.value)}
              placeholder="مثال: أحمد الحسن"
              autoComplete="name"
              className={inputClass}
              required
              autoFocus
            />
          </Field>

          <Field label="البريد الإلكتروني" error={errors.email}>
            <input
              id="email"
              type="email"
              value={data.email}
              onChange={(e) => setData('email', e.target.value)}
              placeholder="example@email.com"
              autoComplete="username"
              className={inputClass}
              required
            />
          </Field>

          <Field label="رقم الهاتف" error={errors.phone_number}>
            <input
              id="phone_number"
              dir="ltr"
              value={data.phone_number}
              onChange={(e) => setData('phone_number', e.target.value)}
              placeholder="0987654321"
              autoComplete="tel"
              className={inputClass}
              required
            />
          </Field>

          <Field label="كلمة المرور" error={errors.password}>
            <input
              id="password"
              type="password"
              value={data.password}
              onChange={(e) => setData('password', e.target.value)}
              placeholder="8 أحرف على الأقل"
              autoComplete="new-password"
              className={inputClass}
              required
            />
          </Field>

          <Field label="تأكيد كلمة المرور" error={errors.password_confirmation}>
            <input
              id="password_confirmation"
              type="password"
              value={data.password_confirmation}
              onChange={(e) => setData('password_confirmation', e.target.value)}
              placeholder="أعد كتابة كلمة المرور"
              autoComplete="new-password"
              className={inputClass}
              required
            />
          </Field>

          <input type="hidden" name="role" value={data.role} />

          <label htmlFor="terms" className="flex cursor-pointer items-start gap-3 pt-1">
            <input
              id="terms"
              type="checkbox"
              checked={data.terms}
              onChange={(e) => setData('terms', e.target.checked)}
              className="mt-0.5 h-4 w-4 cursor-pointer rounded border-[#0d1b4b]/20 bg-white text-[#d4af37] focus:ring-[#d4af37]/30 focus:ring-offset-0"
              required
            />
            <span className="text-sm leading-relaxed text-[#0d1b4b]/55">
              أوافق على
              <a href="/terms" target="_blank" rel="noreferrer" className="mx-1 font-semibold text-[#d4af37] transition-colors hover:text-[#b8922a]">
                شروط الاستخدام
              </a>
              و
              <a href="/privacy" target="_blank" rel="noreferrer" className="mr-1 font-semibold text-[#d4af37] transition-colors hover:text-[#b8922a]">
                سياسة الخصوصية
              </a>
            </span>
          </label>
          {errors.terms ? <p className="text-xs font-semibold text-red-600">{errors.terms}</p> : null}

          <div className="pt-2">
            <button
              type="submit"
              disabled={processing}
              className="group relative w-full overflow-hidden rounded-2xl bg-[#d4af37] px-6 py-3.5 text-base font-black text-[#0d1b4b] shadow-lg shadow-[#d4af37]/25 transition-all duration-200 hover:bg-[#c5a02e] active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-60"
            >
              <span className="relative z-10">{processing ? 'جاري إنشاء الحساب...' : 'إنشاء حساب البائع'}</span>
              <div className="absolute inset-0 translate-y-full rounded-2xl bg-white/20 transition-transform duration-300 group-hover:translate-y-0" />
            </button>
          </div>

          <div className="relative flex items-center gap-3 py-1">
            <div className="h-px flex-1 bg-[#0d1b4b]/8" />
            <span className="text-xs text-[#0d1b4b]/30">أو</span>
            <div className="h-px flex-1 bg-[#0d1b4b]/8" />
          </div>

          <Link
            href="/login"
            className="block w-full rounded-2xl border border-[#0d1b4b]/12 py-3.5 text-center text-sm font-bold text-[#0d1b4b]/60 transition-all duration-200 hover:border-[#0d1b4b]/25 hover:bg-[#0d1b4b]/3 hover:text-[#0d1b4b]"
          >
            لديك حساب بالفعل؟ تسجيل الدخول
          </Link>
        </form>
      </div>

      <p className="mt-6 text-center text-[11px] tracking-wide text-[#0d1b4b]/30">محلي - منصة التجارة المحلية الفاخرة</p>
    </GuestLayout>
  );
}

const inputClass =
  'mt-1.5 block w-full rounded-xl border border-[#0d1b4b]/20 bg-white px-4 py-2.5 outline-none transition focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20';

function Field({
  label,
  error,
  children,
}: {
  label: string;
  error?: string;
  children: React.ReactNode;
}) {
  return (
    <div>
      <label className="text-sm font-bold text-[#0d1b4b]">{label}</label>
      {children}
      {error ? <p className="mt-1.5 text-xs font-semibold text-red-600">{error}</p> : null}
    </div>
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
