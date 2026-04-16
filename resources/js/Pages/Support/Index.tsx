import React from 'react';
import { Head } from '@inertiajs/react';
import SupportMethodCard from '../../components/support/SupportMethodCard';

type SupportMethod = {
  id: number;
  name: string;
  account_id: string;
  details?: string | null;
  link?: string | null;
  logo_url?: string | null;
  qr_url?: string | null;
};

interface SupportIndexProps {
  paymentMethods: SupportMethod[];
  backUrl: string;
}

export default function SupportIndex({ paymentMethods, backUrl }: SupportIndexProps) {
  return (
    <>
      <Head title="ادعم محلي | طرق التبرع المتاحة" />

      <div className="bg-gray-50 text-[#0d1b4b] antialiased">
        <div className="relative overflow-hidden border-b border-[#0d1b4b]/10 bg-white shadow-sm">
          <div className="pointer-events-none absolute inset-0 bg-[#0d1b4b]/5"></div>
          <div className="relative z-10 mx-auto max-w-7xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <div className="mb-6 flex justify-start">
              <a
                href={backUrl}
                className="inline-flex items-center gap-2 rounded-xl border border-[#0d1b4b]/15 bg-white px-4 py-2 font-bold text-[#0d1b4b] shadow-sm transition-all hover:border-[#0d1b4b]/30 hover:shadow"
              >
                <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>رجوع</span>
              </a>
            </div>

            <a href="/" className="mb-6 inline-block">
              <img
                src="/logo.png"
                alt="محلي"
                className="h-16 w-auto drop-shadow-[0_0_10px_rgba(255,255,255,0.4)] transition-all duration-300"
              />
            </a>

            <h1 className="mb-4 text-4xl font-black text-[#0d1b4b] md:text-5xl">ادعم منصة محلي</h1>
            <p className="mx-auto max-w-2xl text-lg text-[#0d1b4b]/60">
              منصة محلي هي منصة مجانية 100% تهدف لدعم المشاريع الصغيرة. يمكنك مساعدتنا في استمرار وتطوير المنصة من خلال
              التبرع عبر طرق الدفع المتاحة أدناه.
            </p>
          </div>
        </div>

        <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
          {paymentMethods.length === 0 ? (
            <div className="rounded-[2rem] border border-[#0d1b4b]/10 bg-white py-20 text-center shadow-sm">
              <p className="text-xl font-bold text-[#0d1b4b]/50">لا توجد طرق دفع متاحة حالياً.</p>
            </div>
          ) : (
            <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
              {paymentMethods.map((method) => (
                <SupportMethodCard key={method.id} method={method} />
              ))}
            </div>
          )}
        </div>

        <footer className="border-t border-[#0d1b4b]/10 bg-white py-8 text-center text-sm text-[#0d1b4b]/60">
          <p>نشكركم على دعمكم المستمر لمنصة محلي.</p>
        </footer>
      </div>
    </>
  );
}
