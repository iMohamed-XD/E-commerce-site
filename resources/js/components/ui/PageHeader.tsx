import React from 'react';

interface PageHeaderProps {
  title: string;
  backHref?: string;
  backLabel?: string;
}

export function PageHeader({ title, backHref = '/dashboard', backLabel = 'الرجوع إلى لوحة التحكم' }: PageHeaderProps) {
  return (
    <div className="flex items-center gap-4">
      <a href={backHref} className="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition" aria-label={backLabel}>
        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" />
        </svg>
      </a>
      <h2 className="font-semibold text-xl text-[#0d1b4b] leading-tight">{title}</h2>
    </div>
  );
}
