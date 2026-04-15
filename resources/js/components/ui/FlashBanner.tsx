import React from 'react';

interface FlashBannerProps {
  message?: string | null;
  type?: 'success' | 'error' | 'info';
}

const palette: Record<NonNullable<FlashBannerProps['type']>, string> = {
  success: 'border-green-300 bg-green-50 text-green-800',
  error: 'border-red-300 bg-red-50 text-red-700',
  info: 'border-[#d4af37]/40 bg-[#d4af37]/10 text-[#7f651a]',
};

export function FlashBanner({ message, type = 'info' }: FlashBannerProps) {
  if (!message) return null;

  return (
    <div className={`rounded-2xl border px-4 py-3 text-sm font-semibold shadow-sm ${palette[type]}`}>
      {message}
    </div>
  );
}
