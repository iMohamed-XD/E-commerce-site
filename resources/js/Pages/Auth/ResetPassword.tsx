import React from 'react';
import { useForm } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

interface ResetPasswordProps {
  token: string;
  email?: string;
}

export default function ResetPassword({ token, email = '' }: ResetPasswordProps) {
  const { data, setData, post, processing, errors } = useForm({
    token,
    email,
    password: '',
    password_confirmation: '',
  });

  const submit: React.FormEventHandler<HTMLFormElement> = (e) => {
    e.preventDefault();
    post('/reset-password');
  };

  return (
    <GuestLayout title="تعيين كلمة مرور جديدة" subtitle="أدخل كلمة مرور قوية لتأمين حسابك">
      <form onSubmit={submit} className="space-y-5">
        <Field label="البريد الإلكتروني" error={errors.email}>
          <input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} className={inputClass} required />
        </Field>

        <Field label="كلمة المرور الجديدة" error={errors.password}>
          <input type="password" value={data.password} onChange={(e) => setData('password', e.target.value)} className={inputClass} required />
        </Field>

        <Field label="تأكيد كلمة المرور" error={errors.password_confirmation}>
          <input
            type="password"
            value={data.password_confirmation}
            onChange={(e) => setData('password_confirmation', e.target.value)}
            className={inputClass}
            required
          />
        </Field>

        <button
          type="submit"
          disabled={processing}
          className="w-full rounded-2xl bg-[#d4af37] px-6 py-3.5 text-base font-black text-[#0d1b4b] transition hover:bg-[#c5a02e] disabled:cursor-not-allowed disabled:opacity-50"
        >
          {processing ? 'جارٍ الحفظ...' : 'حفظ كلمة المرور'}
        </button>
      </form>
    </GuestLayout>
  );
}

const inputClass =
  'mt-1.5 w-full rounded-xl border border-[#0d1b4b]/20 bg-white px-4 py-2.5 outline-none transition focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20';

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
      <label className="text-sm font-bold">{label}</label>
      {children}
      {error ? <p className="mt-1 text-xs font-semibold text-red-600">{error}</p> : null}
    </div>
  );
}

