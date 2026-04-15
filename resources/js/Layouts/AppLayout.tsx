import React, { PropsWithChildren } from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '../components/layout/Navbar';

interface AppLayoutProps extends PropsWithChildren {
  title: string;
  header?: React.ReactNode;
}

export default function AppLayout({ title, header, children }: AppLayoutProps) {
  return (
    <>
      <Head title={title} />

      <div className="min-h-screen text-[#0d1b4b] antialiased">
        <div className="pointer-events-none fixed inset-0 z-0">
          <div className="absolute inset-0 bg-gradient-to-b from-[#eef2ff] via-white to-[#fdfbf4]" />
          <div className="hero-dotgrid absolute inset-0 opacity-60" />
          <div className="absolute right-0 top-0 h-[400px] w-[600px] rounded-full bg-[#d4af37]/8 blur-[120px]" />
          <div className="absolute bottom-0 left-0 h-[400px] w-[500px] rounded-full bg-[#0d1b4b]/5 blur-[100px]" />
        </div>

        <div className="relative z-10 flex min-h-screen flex-col">
          <Navbar />

          {header ? (
            <header className="backdrop-blur-md bg-white/80 border-b border-[#0d1b4b]/8 shadow-sm">
              <div className="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">{header}</div>
            </header>
          ) : null}

          <main className="flex-grow">{children}</main>
        </div>
      </div>
    </>
  );
}
