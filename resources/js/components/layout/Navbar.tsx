import React, { useMemo, useState } from 'react';
import { usePage } from '@inertiajs/react';

type SellerLink = {
  href: string;
  label: string;
  startsWith?: boolean;
};

const sellerLinks: SellerLink[] = [
  { href: '/dashboard', label: 'لوحة التحكم' },
  { href: '/categories', label: 'التصنيفات', startsWith: true },
  { href: '/products', label: 'المنتجات', startsWith: true },
  { href: '/orders', label: 'الطلبات', startsWith: true },
  { href: '/promo-codes', label: 'أكواد الخصم', startsWith: true },
  { href: '/dashboard/feedback', label: 'تقييم المنصة', startsWith: true },
];

export default function Navbar() {
  const page = usePage() as any;
  const user = page.props?.auth?.user;
  const currentUrl = (page.url || '').split('?')[0];
  const [openMobile, setOpenMobile] = useState(false);
  const [openUserMenu, setOpenUserMenu] = useState(false);

  const csrf = useMemo(() => {
    if (typeof document === 'undefined') return '';
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  }, []);

  const isActive = (link: SellerLink) => {
    if (link.startsWith) {
      return currentUrl === link.href || currentUrl.startsWith(`${link.href}/`);
    }
    return currentUrl === link.href;
  };

  const navLinkClass = (active: boolean) =>
    active
      ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#d4af37] text-sm font-medium leading-5 text-[#0d1b4b] transition-colors focus:outline-none focus:ring-2 focus:ring-[#d4af37]/40 rounded-md'
      : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[#0d1b4b]/60 hover:text-[#0d1b4b] hover:border-[#d4af37]/50 transition-colors focus:outline-none focus:ring-2 focus:ring-[#d4af37]/40 rounded-md';

  const responsiveNavClass = (active: boolean) =>
    active
      ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#d4af37] text-start text-base font-bold text-[#0d1b4b] bg-[#d4af37]/10 transition duration-150 ease-in-out'
      : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[#0d1b4b]/60 hover:text-[#0d1b4b] hover:bg-[#0d1b4b]/5 hover:border-[#0d1b4b]/20 transition duration-150 ease-in-out';

  return (
    <nav className="sticky top-0 z-50 border-b border-[#0d1b4b]/8 bg-white/80 shadow-sm backdrop-blur-md">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div className="flex h-20 justify-between">
          <div className="flex items-center">
            <div className="shrink-0 flex items-center">
              <a href="/dashboard" className="group flex items-center">
                <div className="relative overflow-hidden rounded-xl border border-[#0d1b4b]/10 bg-white/80 p-2.5 shadow-md transition-all duration-500 group-hover:border-[#d4af37]/40 group-hover:shadow-[0_4px_20px_rgba(212,175,55,0.15)]">
                  <div className="absolute inset-0 translate-x-[-100%] bg-gradient-to-r from-transparent via-[#d4af37]/10 to-transparent transition-transform duration-1000 group-hover:translate-x-[100%]" />
                  <img src="/logo.png" alt="محلي" className="block h-9 w-auto transition-all duration-300 hover:brightness-110" />
                </div>
              </a>
            </div>

            {user?.role === 'seller' ? (
              <div className="hidden space-x-8 space-x-reverse sm:-my-px sm:ms-10 sm:flex">
                {sellerLinks.map((link) => (
                  <a key={link.href} href={link.href} className={navLinkClass(isActive(link))}>
                    {link.label}
                  </a>
                ))}
                <a href="/support" className="mr-4 inline-flex items-center gap-2 self-center rounded-xl bg-pink-500 px-4 py-2 text-xs font-black text-white shadow-lg shadow-pink-500/20 transition-all hover:bg-pink-600">
                  <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
                  </svg>
                  <span>ادعمنا</span>
                </a>
              </div>
            ) : null}
          </div>

          <div className="hidden sm:flex sm:items-center sm:ms-6">
            <div className="relative ms-3">
              <button
                type="button"
                onClick={() => setOpenUserMenu((v) => !v)}
                className="inline-flex items-center rounded-xl border border-[#0d1b4b]/15 bg-white px-4 py-2 text-sm font-medium leading-4 text-[#0d1b4b]/70 shadow-sm transition hover:bg-[#fdfbf4] hover:text-[#0d1b4b]"
              >
                <div className="flex items-center gap-2">
                  <div className="h-6 w-6 rounded-full bg-[#0d1b4b] flex items-center justify-center text-[10px] font-bold text-white">
                    {user?.name?.slice(0, 1) || 'U'}
                  </div>
                  {user?.name || 'مستخدم'}
                </div>
                <div className="ms-2">
                  <svg className="h-4 w-4 fill-current" viewBox="0 0 20 20">
                    <path fillRule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clipRule="evenodd" />
                  </svg>
                </div>
              </button>

              {openUserMenu ? (
                <div className="absolute start-0 z-50 mt-2 w-48 rounded-md shadow-lg">
                  <div className="rounded-md border border-[#0d1b4b]/10 bg-white py-1 shadow-xl">
                    <div className="border-b border-[#0d1b4b]/10 px-4 py-2">
                      <div className="text-xs text-[#0d1b4b]/45">تم تسجيل الدخول كـ</div>
                      <div className="truncate text-sm font-medium text-[#0d1b4b]">{user?.email || ''}</div>
                    </div>
                    <a href="/profile" className="block w-full px-4 py-2 text-start text-sm leading-5 text-[#0d1b4b]/70 transition hover:bg-[#0d1b4b]/6 hover:text-[#0d1b4b]">
                      الملف الشخصي
                    </a>
                    <div className="border-t border-[#0d1b4b]/10" />
                    <form method="POST" action="/logout">
                      <input type="hidden" name="_token" value={csrf} />
                      <button type="submit" className="block w-full px-4 py-2 text-start text-sm leading-5 text-[#0d1b4b]/70 transition hover:bg-red-50 hover:text-red-600">
                        تسجيل الخروج
                      </button>
                    </form>
                  </div>
                </div>
              ) : null}
            </div>
          </div>

          <div className="-me-2 flex items-center sm:hidden">
            <button
              type="button"
              onClick={() => setOpenMobile((v) => !v)}
              className="inline-flex items-center justify-center rounded-xl p-2 text-[#0d1b4b]/45 transition hover:bg-[#0d1b4b]/8 hover:text-[#0d1b4b]"
            >
              {openMobile ? (
                <svg className="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              ) : (
                <svg className="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              )}
            </button>
          </div>
        </div>
      </div>

      {openMobile ? (
        <div className="border-t border-[#0d1b4b]/10 bg-white/95 backdrop-blur-md sm:hidden">
          {user?.role === 'seller' ? (
            <div className="space-y-1 pt-2 pb-3">
              {sellerLinks.map((link) => (
                <a key={link.href} href={link.href} className={responsiveNavClass(isActive(link))}>
                  {link.label}
                </a>
              ))}
              <div className="px-4 py-2">
                <a href="/support" className="flex w-full items-center justify-center gap-2 rounded-xl bg-pink-500 py-3 font-black text-white shadow-lg shadow-pink-500/20">
                  <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
                  </svg>
                  <span>ادعم المنصة</span>
                </a>
              </div>
            </div>
          ) : null}

          <div className="border-t border-[#0d1b4b]/10 pt-4 pb-1">
            <div className="flex items-center justify-between px-4">
              <div>
                <div className="text-base font-bold text-[#0d1b4b]">{user?.name || 'مستخدم'}</div>
                <div className="text-sm font-medium text-[#0d1b4b]/50">{user?.email || ''}</div>
              </div>
              <div className="h-10 w-10 rounded-full bg-[#0d1b4b] flex items-center justify-center font-bold text-white">
                {user?.name?.slice(0, 1) || 'U'}
              </div>
            </div>

            <div className="mt-3 space-y-1">
              <a href="/profile" className="block w-full ps-3 pe-4 py-2 text-start text-base font-medium text-[#0d1b4b]/60 hover:text-[#0d1b4b] hover:bg-[#0d1b4b]/5 transition duration-150 ease-in-out">
                الملف الشخصي
              </a>
              <form method="POST" action="/logout">
                <input type="hidden" name="_token" value={csrf} />
                <button type="submit" className="block w-full ps-3 pe-4 py-2 text-start text-base font-medium text-red-600 hover:bg-red-50 transition duration-150 ease-in-out">
                  تسجيل الخروج
                </button>
              </form>
            </div>
          </div>
        </div>
      ) : null}
    </nav>
  );
}
