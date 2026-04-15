import React from 'react';
import { useForm } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

interface VerifyEmailProps {
  status?: string | null;
}

export default function VerifyEmail({ status }: VerifyEmailProps) {
  const { post, processing } = useForm({});

  const resend = (e: React.FormEvent) => {
    e.preventDefault();
    post('/email/verification-notification');
  };

  return (
    <GuestLayout title="تأكيد البريد الإلكتروني" subtitle="تحقق من بريدك ثم اضغط زر إعادة الإرسال عند الحاجة">
      <div className="space-y-5">
        <p className="text-sm leading-relaxed text-[#0d1b4b]/65">
          قبل المتابعة، يرجى التأكد من بريدك الإلكتروني عبر الرابط الذي أرسلناه لك.
        </p>

        {status === 'verification-link-sent' ? (
          <div className="rounded-xl border border-green-300 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
            تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.
          </div>
        ) : null}

        <form onSubmit={resend}>
          <button
            type="submit"
            disabled={processing}
            className="w-full rounded-2xl bg-[#d4af37] px-6 py-3.5 text-base font-black text-[#0d1b4b] transition hover:bg-[#c5a02e] disabled:cursor-not-allowed disabled:opacity-50"
          >
            {processing ? 'جارٍ الإرسال...' : 'إعادة إرسال رابط التحقق'}
          </button>
        </form>
      </div>
    </GuestLayout>
  );
}

