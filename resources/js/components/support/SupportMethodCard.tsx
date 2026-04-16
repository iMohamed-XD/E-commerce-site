import React from 'react';

type SupportMethod = {
  id: number;
  name: string;
  account_id: string;
  details?: string | null;
  link?: string | null;
  logo_url?: string | null;
  qr_url?: string | null;
};

interface SupportMethodCardProps {
  method: SupportMethod;
}

export default function SupportMethodCard({ method }: SupportMethodCardProps) {
  const isWhatsAppLink =
    typeof method.link === 'string' &&
    (method.link.toLowerCase().includes('wa.me') || method.link.toLowerCase().includes('whatsapp'));

  return (
    <div className="flex flex-col items-center rounded-[2rem] border border-[#0d1b4b]/10 bg-white p-8 text-center shadow-sm transition-shadow hover:shadow-xl">
      {method.logo_url ? (
        <img src={method.logo_url} className="mb-6 h-16 object-contain" alt={method.name} />
      ) : (
        <div className="mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-[#0d1b4b]/5">
          <svg className="h-8 w-8 text-[#0d1b4b]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth="2"
              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
        </div>
      )}

      <h3 className="mb-2 text-2xl font-black">{method.name}</h3>
      <div className="mb-6 inline-block rounded-xl border border-[#d4af37]/30 bg-[#fdfbf4] px-6 py-3">
        <p className="mb-1 text-sm font-medium text-[#0d1b4b]/60">رقم الحساب / المستفيد</p>
        <p className="text-lg font-bold" dir="ltr">
          {method.account_id}
        </p>
      </div>

      {method.details ? <p className="mb-6 text-sm text-[#0d1b4b]/60">{method.details}</p> : null}

      {method.link ? (
        <a
          href={method.link}
          target="_blank"
          rel="noopener noreferrer"
          className="mb-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-green-500 px-6 py-3 font-bold text-white shadow-lg shadow-green-500/20 transition-all hover:bg-green-600 active:scale-95"
        >
          <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
          </svg>
          <span>{isWhatsAppLink ? 'فتح في واتساب' : 'افتح الرابط المباشر'}</span>
        </a>
      ) : null}

      {method.qr_url ? (
        <div className="mt-auto">
          <p className="mb-3 text-xs font-bold uppercase tracking-widest text-[#0d1b4b]/40">امسح الكود للدفع</p>
          <img src={method.qr_url} className="mx-auto h-32 w-32 rounded-xl border p-1 shadow-inner object-contain" alt="QR Code" />
        </div>
      ) : null}
    </div>
  );
}
