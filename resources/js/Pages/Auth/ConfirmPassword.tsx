import React from 'react';
import { useForm } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

export default function ConfirmPassword() {
  const { data, setData, post, processing, errors, reset } = useForm({
    password: '',
  });

  const submit: React.FormEventHandler<HTMLFormElement> = (e) => {
    e.preventDefault();
    post('/confirm-password', {
      onFinish: () => reset('password'),
    });
  };

  return (
    <GuestLayout title="تأكيد كلمة المرور" subtitle="للمتابعة، يرجى إدخال كلمة مرور الحساب الحالي">
      <form onSubmit={submit} className="space-y-5">
        <div>
          <label className="text-sm font-bold">كلمة المرور</label>
          <input
            type="password"
            value={data.password}
            onChange={(e) => setData('password', e.target.value)}
            className="mt-1.5 w-full rounded-xl border border-[#0d1b4b]/20 bg-white px-4 py-2.5 outline-none transition focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20"
            required
          />
          {errors.password ? <p className="mt-1 text-xs font-semibold text-red-600">{errors.password}</p> : null}
        </div>

        <button
          type="submit"
          disabled={processing}
          className="w-full rounded-2xl bg-[#0d1b4b] px-6 py-3.5 text-base font-black text-white transition hover:bg-[#1a2d6b] disabled:cursor-not-allowed disabled:opacity-50"
        >
          {processing ? 'جارٍ التأكيد...' : 'تأكيد'}
        </button>
      </form>
    </GuestLayout>
  );
}

