import React, { PropsWithChildren } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import { FlashBanner } from '../Components/ui/FlashBanner';
import type { SharedPageProps } from '../types/inertia';

interface GuestLayoutProps extends PropsWithChildren {
  title: string;
}

export default function GuestLayout({ title, children }: GuestLayoutProps) {
  const page = usePage<SharedPageProps>();
  const flash = page.props.flash ?? {};

  return (
    <>
      <Head title={title} />

      <div className="min-h-screen text-[#0d1b4b] antialiased">
        <div className="fixed inset-0 z-0 pointer-events-none">
          <div className="absolute inset-0 bg-gradient-to-b from-[#eef2ff] via-white to-[#fdfbf4]" />
          <div className="absolute inset-0 hero-dotgrid opacity-60" />
          <div className="absolute top-0 right-0 h-[400px] w-[600px] rounded-full bg-[#d4af37]/8 blur-[120px]" />
          <div className="absolute bottom-0 left-0 h-[400px] w-[500px] rounded-full bg-[#0d1b4b]/5 blur-[100px]" />
        </div>

        <nav className="relative z-50 flex w-full items-center justify-between border-b border-[#0d1b4b]/8 bg-white/80 px-6 py-5 shadow-sm backdrop-blur-md lg:px-12">
          <Link href="/" className="group flex items-center">
            <div className="relative overflow-hidden rounded-xl border border-[#0d1b4b]/10 bg-white/80 p-2 shadow-md transition-all duration-500 group-hover:border-[#d4af37]/40 group-hover:shadow-[0_4px_20px_rgba(212,175,55,0.15)]">
              <div className="absolute inset-0 translate-x-[-100%] bg-gradient-to-r from-transparent via-[#d4af37]/10 to-transparent transition-transform duration-1000 group-hover:translate-x-[100%]" />
              <img src="/logo.png" alt="محلي" className="block h-8 w-auto" />
            </div>
            <span className="mr-3 mt-0.5 hidden border-r border-[#0d1b4b]/15 pr-3 text-[10px] uppercase tracking-[0.2em] text-[#0d1b4b]/40 sm:inline-block">
              THE LUXURY OF LOCAL
            </span>
          </Link>

          <Link href="/login" className="text-sm font-bold text-[#0d1b4b]/50 transition-colors duration-200 hover:text-[#0d1b4b]">
            تسجيل الدخول
          </Link>
        </nav>

        <main className="relative z-10 flex min-h-[calc(100vh-69px)] items-center justify-center px-4 py-16">
          <div className="w-full max-w-md">
            {(flash.success || flash.error || flash.status) && (
              <div className="mb-4 space-y-2">
                <FlashBanner message={flash.success} type="success" />
                <FlashBanner message={flash.error} type="error" />
                <FlashBanner message={flash.status} type="info" />
              </div>
            )}
            {children}
          </div>
        </main>
      </div>
    </>
  );
}
