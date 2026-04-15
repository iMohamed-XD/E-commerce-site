import React, { PropsWithChildren } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import type { SharedPageProps } from '../types/inertia';

interface MarketingLayoutProps extends PropsWithChildren {
  title: string;
}

export default function MarketingLayout({ title, children }: MarketingLayoutProps) {
  const page = usePage<SharedPageProps>();
  const auth = page.props.auth ?? { user: null };

  return (
    <>
      <Head title={title}>
        <style>{`
          html { scroll-behavior: smooth; }
          .steps-timeline::before {
            content: '';
            position: absolute;
            right: 50%;
            top: 3rem;
            bottom: 3rem;
            width: 1px;
            transform: translateX(50%);
            background: linear-gradient(to bottom, transparent, #d4af3760, #d4af37, #d4af3760, transparent);
          }
          .screenshot-frame {
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94), box-shadow 0.4s ease;
          }
          .screenshot-frame:hover {
            transform: translateY(-6px);
            box-shadow: 0 40px 80px rgba(13, 27, 75, 0.15);
          }
          @keyframes ring-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(212, 175, 55, 0); }
          }
          .step-dot { animation: ring-pulse 3s ease-in-out infinite; }
          @supports (animation-timeline: scroll()) {
            .step-row {
              animation: fade-up linear both;
              animation-timeline: view();
              animation-range: entry 0% entry 30%;
            }
            @keyframes fade-up {
              from { opacity: 0; transform: translateY(40px); }
              to   { opacity: 1; transform: translateY(0); }
            }
          }
        `}</style>
      </Head>

      <div className="relative min-h-screen flex flex-col selection:bg-[#d4af37] selection:text-black">
        <div className="fixed inset-0 z-0">
          <div className="absolute inset-0 bg-gradient-to-b from-[#eef2ff] via-white to-[#fdfbf4]" />
          <div className="absolute inset-0 hero-dotgrid opacity-60" />
          <div className="absolute top-0 right-0 h-[400px] w-[600px] rounded-full bg-[#d4af37]/8 blur-[120px]" />
          <div className="absolute bottom-0 left-0 h-[400px] w-[500px] rounded-full bg-[#0d1b4b]/5 blur-[100px]" />
        </div>

        <nav className="sticky top-0 z-50 flex w-full items-center justify-between border-b border-[#0d1b4b]/8 bg-white/80 p-6 shadow-sm backdrop-blur-md lg:px-12">
          <div className="flex items-center gap-3">
            <Link href="/dashboard" className="group flex items-center">
              <div className="relative overflow-hidden rounded-xl border border-[#0d1b4b]/10 bg-white/80 p-2.5 shadow-md transition-all duration-500 group-hover:border-[#d4af37]/40 group-hover:shadow-[0_4px_20px_rgba(212,175,55,0.15)]">
                <div className="absolute inset-0 translate-x-[-100%] bg-gradient-to-r from-transparent via-[#d4af37]/10 to-transparent transition-transform duration-1000 group-hover:translate-x-[100%]" />
                <img src="/logo.png" alt="محلي" className="block h-9 w-auto transition-all duration-300 hover:brightness-110" />
              </div>
            </Link>
            <span className="mt-0.5 mr-1 hidden border-r border-[#0d1b4b]/15 pr-3 text-[10px] uppercase tracking-[0.2em] text-[#0d1b4b]/40 sm:inline-block">
              THE LUXURY OF LOCAL
            </span>
          </div>

          <div className="flex items-center gap-3 sm:gap-6">
            {auth.user ? (
              <Link href="/dashboard" className="rounded-xl bg-[#0d1b4b] px-6 py-2.5 font-bold text-white shadow-lg shadow-[#0d1b4b]/20 transition hover:bg-[#1a2d6b] active:scale-95">
                لوحة التحكم
              </Link>
            ) : (
              <>
                <Link href="/login" className="px-4 py-2 text-sm font-bold text-[#0d1b4b]/60 transition hover:text-[#0d1b4b]">
                  دخول
                </Link>
                <Link href="/register" className="rounded-xl bg-[#d4af37] px-6 py-2.5 font-black text-[#0d1b4b] shadow-xl shadow-[#d4af37]/25 transition hover:bg-[#c5a02e] active:scale-95">
                  ابدأ مجاناً
                </Link>
              </>
            )}
          </div>
        </nav>

        <div className="relative z-10">{children}</div>
      </div>
    </>
  );
}
