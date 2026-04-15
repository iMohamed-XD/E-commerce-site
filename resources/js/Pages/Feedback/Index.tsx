import React from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import { FlashBanner } from '../../components/ui/FlashBanner';

interface FeedbackData {
  rating: number;
  content: string;
}

interface FeedbackPageProps {
  feedback: FeedbackData | null;
}

export default function FeedbackIndex({ feedback }: FeedbackPageProps) {
  const page = usePage() as any;
  const flash = page.props?.flash ?? {};

  const { data, setData, post, put, processing, errors } = useForm({
    rating: feedback?.rating ?? 5,
    content: feedback?.content ?? '',
  });

  const submit: React.FormEventHandler<HTMLFormElement> = (event) => {
    event.preventDefault();

    if (feedback) {
      put('/dashboard/feedback', { preserveScroll: true });
      return;
    }

    post('/dashboard/feedback', { preserveScroll: true });
  };

  return (
    <AppLayout
      title="تقييم المنصة"
      header={
        <div className="flex items-center gap-4">
          <a href="/dashboard" className="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition" aria-label="الرجوع إلى لوحة التحكم">
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" />
            </svg>
          </a>
          <h2 className="font-semibold text-xl text-[#0d1b4b] leading-tight">تقييم المنصة</h2>
        </div>
      }
    >
      <Head title="تقييم المنصة" />

      <div className="py-12">
        <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
          <div className="overflow-hidden rounded-[2rem] border border-[#0d1b4b]/10 bg-white shadow-sm">
            <div className="p-8 sm:p-12">
              <div className="mb-8 space-y-2">
                <FlashBanner message={flash.success} type="success" />
                <FlashBanner message={flash.error} type="error" />
              </div>

              <div className="mb-10 text-center">
                <h3 className="mb-3 text-2xl font-black text-[#0d1b4b]">رأيك يهمنا</h3>
                <p className="text-[#0d1b4b]/60">
                  نسعى دائماً لتطوير منصة محلي. يرجى تزويدنا بتقييمك لتجربة استخدام المنصة.
                </p>
              </div>

              <form onSubmit={submit} className="space-y-8">
                <div className="text-center">
                  <label className="mb-4 block text-sm font-bold text-[#0d1b4b]">تقييمك للمنصة (من 1 إلى 5)</label>
                  <div className="flex flex-row-reverse justify-center gap-2">
                    {[5, 4, 3, 2, 1].map((star) => (
                      <button
                        key={star}
                        type="button"
                        onClick={() => setData('rating', star)}
                        className={`p-2 transition-transform hover:scale-110 ${data.rating >= star ? 'text-[#d4af37]' : 'text-gray-300'}`}
                      >
                        <svg className="h-12 w-12" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                      </button>
                    ))}
                  </div>
                  {errors.rating ? <p className="mt-2 text-sm text-red-600">{errors.rating}</p> : null}
                </div>

                <div>
                  <label htmlFor="content" className="block text-sm font-medium text-gray-700">
                    ملاحظاتك ومقترحاتك (اختياري)
                  </label>
                  <textarea
                    id="content"
                    name="content"
                    rows={5}
                    value={data.content}
                    onChange={(event) => setData('content', event.target.value)}
                    className="mt-2 w-full rounded-xl border border-[#0d1b4b]/20 p-4 text-sm shadow-sm focus:border-[#d4af37] focus:ring-[#d4af37]"
                  />
                  {errors.content ? <p className="mt-2 text-sm text-red-600">{errors.content}</p> : null}
                </div>

                <div className="flex items-center justify-end">
                  <button
                    type="submit"
                    disabled={processing}
                    className="rounded-xl bg-[#0d1b4b] px-8 py-3 font-bold text-white shadow-lg shadow-[#0d1b4b]/20 transition hover:bg-[#0d1b4b]/90 disabled:opacity-60"
                  >
                    {processing ? 'جارٍ الحفظ...' : 'حفظ التقييم'}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}



