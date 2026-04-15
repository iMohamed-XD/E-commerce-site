import React from 'react';
import { Head, router, useForm, usePage } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import { FlashBanner } from '../../components/ui/FlashBanner';

type PromoCodeRow = {
  id: number;
  code: string;
  discountPercentage: number;
  isActive: boolean;
  toggleUrl: string;
  destroyUrl: string;
};

interface PromoCodesPageProps {
  promoCodes: PromoCodeRow[];
}

export default function PromoCodesIndex({ promoCodes }: PromoCodesPageProps) {
  const page = usePage() as any;
  const flash = page.props?.flash ?? {};
  const errors = (page.props?.errors ?? {}) as Record<string, string>;

  const { data, setData, post, processing, reset } = useForm({
    code: '',
    discount_percentage: '',
  });

  const submit: React.FormEventHandler<HTMLFormElement> = (event) => {
    event.preventDefault();
    post('/promo-codes', {
      preserveScroll: true,
      onSuccess: () => reset(),
    });
  };

  return (
    <AppLayout
      title="كوبونات الخصم"
      header={
        <div className="flex items-center gap-4">
          <a href="/dashboard" className="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition" aria-label="الرجوع إلى لوحة التحكم">
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" />
            </svg>
          </a>
          <h2 className="font-semibold text-xl text-[#0d1b4b] leading-tight">كوبونات الخصم</h2>
        </div>
      }
    >
      <Head title="كوبونات الخصم" />

      <div className="py-12">
        <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div className="space-y-4">
            <FlashBanner message={flash.success} type="success" />
            <FlashBanner message={flash.error} type="error" />
          </div>

          {Object.keys(errors).length > 0 ? (
            <div className="mb-4 mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
              <ul className="list-inside list-disc">
                {Object.values(errors).map((error, idx) => (
                  <li key={`${idx}-${error}`}>{error}</li>
                ))}
              </ul>
            </div>
          ) : null}

          <div className="mb-8 mt-4 overflow-hidden rounded-3xl border border-[#0d1b4b]/10 bg-white/70 shadow-xl shadow-[#0d1b4b]/6 backdrop-blur-xl">
            <div className="border-b border-[#0d1b4b]/10 px-6 py-4">
              <h3 className="text-lg font-black text-[#0d1b4b]">إضافة كوبون خصم جديد</h3>
            </div>
            <div className="p-6">
              <form onSubmit={submit} className="grid grid-cols-1 items-end gap-4 sm:grid-cols-3 lg:gap-6">
                <div className="sm:col-span-1">
                  <label className="mb-1 block text-sm font-bold text-[#0d1b4b]/70">رمز الكوبون</label>
                  <input
                    type="text"
                    name="code"
                    value={data.code}
                    onChange={(event) => setData('code', event.target.value)}
                    required
                    className="block w-full rounded-xl border border-[#0d1b4b]/15 bg-white p-3 font-mono text-sm tracking-wider text-[#0d1b4b] shadow-sm placeholder:text-[#0d1b4b]/30 focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20"
                    placeholder="مثال: EID20"
                  />
                </div>
                <div className="sm:col-span-1">
                  <label className="mb-1 block text-sm font-bold text-[#0d1b4b]/70">نسبة الخصم (%)</label>
                  <input
                    type="number"
                    name="discount_percentage"
                    step="0.01"
                    min="0.01"
                    max="100"
                    value={data.discount_percentage}
                    onChange={(event) => setData('discount_percentage', event.target.value)}
                    required
                    className="block w-full rounded-xl border border-[#0d1b4b]/15 bg-white p-3 text-sm text-[#0d1b4b] shadow-sm placeholder:text-[#0d1b4b]/30 focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20"
                    placeholder="20"
                  />
                </div>
                <div className="sm:col-span-1">
                  <button
                    type="submit"
                    disabled={processing}
                    className="w-full rounded-xl bg-[#0d1b4b] px-4 py-3 text-sm font-black text-white shadow-lg shadow-[#0d1b4b]/20 transition hover:bg-[#1a2d6b] disabled:opacity-60"
                  >
                    {processing ? 'جارٍ الحفظ...' : 'حفظ الكوبون'}
                  </button>
                </div>
              </form>
            </div>
          </div>

          <div className="overflow-hidden rounded-3xl border border-[#0d1b4b]/10 bg-white/70 shadow-xl shadow-[#0d1b4b]/6 backdrop-blur-xl">
            <div className="border-b border-[#0d1b4b]/10 px-6 py-4">
              <h3 className="text-lg font-black text-[#0d1b4b]">كوبوناتك الحالية</h3>
            </div>
            <div className="p-6">
              {promoCodes.length === 0 ? (
                <p className="py-8 text-center text-[#0d1b4b]/45">لا يوجد كوبونات خصم حالياً.</p>
              ) : (
                <div className="overflow-x-auto">
                  <table className="min-w-full divide-y divide-[#0d1b4b]/10">
                    <thead>
                      <tr>
                        <th className="px-4 py-4 text-right text-xs font-black uppercase tracking-wider text-[#0d1b4b]/45">الرمز</th>
                        <th className="px-4 py-4 text-right text-xs font-black uppercase tracking-wider text-[#0d1b4b]/45">نسبة الخصم</th>
                        <th className="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-[#0d1b4b]/45">الحالة</th>
                        <th className="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-[#0d1b4b]/45">إجراءات</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-[#0d1b4b]/10">
                      {promoCodes.map((code) => (
                        <tr key={code.id} className="border-b border-[#0d1b4b]/8 transition-colors hover:bg-[#0d1b4b]/4 last:border-0">
                          <td className="px-4 py-5 font-mono text-lg font-bold text-[#d4af37]">{code.code}</td>
                          <td className="px-4 py-5 text-lg font-black text-[#0d1b4b]">{code.discountPercentage.toFixed(0)}%</td>
                          <td className="px-4 py-5 text-center">
                            <button
                              type="button"
                              onClick={() => router.patch(code.toggleUrl, {}, { preserveScroll: true })}
                              className={`inline-flex items-center rounded-full px-4 py-1.5 transition duration-200 ${
                                code.isActive
                                  ? 'border border-[#d4af37]/30 bg-[#d4af37]/10 text-[#a07c1e] hover:bg-[#d4af37]/20'
                                  : 'border border-red-200 bg-red-50 text-red-600 hover:bg-red-100'
                              }`}
                            >
                              <span className={`me-2 h-2 w-2 rounded-full ${code.isActive ? 'animate-pulse bg-green-500' : 'bg-red-500'}`} />
                              {code.isActive ? 'فعال' : 'غير فعال'}
                            </button>
                          </td>
                          <td className="flex items-center justify-center gap-4 px-4 py-5 text-center">
                            <button
                              type="button"
                              onClick={() => {
                                if (window.confirm('هل أنت متأكد من الحذف؟')) {
                                  router.delete(code.destroyUrl, { preserveScroll: true });
                                }
                              }}
                              className="p-2 text-[#0d1b4b]/35 transition hover:text-red-600"
                              title="حذف"
                            >
                              <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                              </svg>
                            </button>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}






