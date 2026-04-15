import React, { useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import { FlashBanner } from '../../components/ui/FlashBanner';

type OrderItemRow = {
  id: number;
  productName: string;
  optionLabel: string | null;
  quantity: number;
  unitPriceUsd: number | null;
  unitPriceSyp: number;
};

type OrderRow = {
  id: number;
  status: 'pending' | 'done' | 'canceled' | 'archived';
  statusLabel: string;
  statusClasses: string;
  archivedFromLabel: string | null;
  promoCodeUsed: string | null;
  deliveryEstimateLabel: string | null;
  createdAt: string;
  buyerName: string;
  buyerEmail: string | null;
  buyerPhone: string;
  buyerLocation: string;
  buyerCity: string | null;
  sellerCitySnapshot: string | null;
  paymentMethodLabel: string;
  shamcashTransactionNumber: string | null;
  items: OrderItemRow[];
  subtotalUsd: number | null;
  subtotalSyp: number;
  discountUsd: number;
  discountSyp: number;
  discountedProductsSubtotalUsd: number | null;
  discountedProductsSubtotalSyp: number;
  deliveryFeeUsd: number;
  deliveryFeeSyp: number;
  finalTotalUsd: number | null;
  finalTotalSyp: number;
  canMarkDone: boolean;
  canCancel: boolean;
  canArchive: boolean;
  updateStatusUrl: string;
};

type PaginationLink = { url: string | null; label: string; active: boolean };

type OrderPaginator = {
  data: OrderRow[];
  current_page: number;
  last_page: number;
  total: number;
  links: PaginationLink[];
};

interface OrdersPageProps {
  shop: { name: string };
  orders: OrderPaginator;
  filters: {
    status: string;
    perPage: number;
    field: string;
    value: string;
  };
}

const STATUS_TABS = [
  { key: 'pending', label: 'قيد الانتظار' },
  { key: 'done', label: 'مكتمل' },
  { key: 'canceled', label: 'ملغي' },
  { key: 'archived', label: 'مؤرشف' },
  { key: 'archived_done', label: 'مؤرشف من مكتمل' },
  { key: 'archived_canceled', label: 'مؤرشف من ملغي' },
  { key: 'all', label: 'الكل' },
];

const FIELD_OPTIONS = [
  { value: '', label: 'اختر الحقل للتصفية' },
  { value: 'id', label: 'رقم الطلب' },
  { value: 'buyer_name', label: 'اسم المشتري' },
  { value: 'buyer_phone', label: 'رقم الهاتف' },
  { value: 'buyer_email', label: 'البريد الإلكتروني' },
  { value: 'buyer_address', label: 'العنوان' },
  { value: 'buyer_city', label: 'المدينة' },
  { value: 'delivery_estimate', label: 'مدة التوصيل' },
  { value: 'promo_code_used', label: 'رمز الخصم' },
  { value: 'payment_method', label: 'طريقة الدفع' },
  { value: 'status', label: 'الحالة' },
  { value: 'archived_from_status', label: 'الحالة قبل الأرشفة' },
  { value: 'total_amount', label: 'الإجمالي' },
  { value: 'final_total_usd', label: 'الإجمالي بالدولار' },
  { value: 'final_total_syp', label: 'الإجمالي بالليرة' },
  { value: 'shamcash_transaction_number', label: 'رقم عملية شام كاش' },
  { value: 'created_at', label: 'تاريخ الإنشاء' },
];

const PER_PAGE_OPTIONS = [10, 15, 20, 25, 30];

export default function OrdersIndex({ shop, orders, filters }: OrdersPageProps) {
  const page = usePage() as any;
  const flash = page.props?.flash ?? {};
  const errors = (page.props?.errors ?? {}) as Record<string, string>;
  const [pendingAction, setPendingAction] = useState<string | null>(null);

  const submitStatus = (url: string, status: string, actionKey: string, confirmMessage?: string) => {
    if (confirmMessage && !window.confirm(confirmMessage)) {
      return;
    }

    setPendingAction(actionKey);

    router.patch(
      url,
      { status },
      {
        preserveScroll: true,
        onFinish: () => setPendingAction(null),
      },
    );
  };

  return (
    <AppLayout
      title="إدارة الطلبات"
      header={
        <div className="flex items-center gap-4">
          <a href="/dashboard" className="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition" aria-label="الرجوع إلى لوحة التحكم">
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" />
            </svg>
          </a>
          <h2 className="font-semibold text-xl text-[#0d1b4b] leading-tight">إدارة الطلبات</h2>
        </div>
      }
    >
      <Head title="إدارة الطلبات" />

      <div className="py-12">
        <div className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
          <FlashBanner message={flash.success} type="success" />
          <FlashBanner message={flash.error} type="error" />

          {Object.keys(errors).length > 0 ? (
            <div className="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
              <ul className="list-inside list-disc">
                {Object.values(errors).map((error, idx) => (
                  <li key={`${idx}-${error}`}>{error}</li>
                ))}
              </ul>
            </div>
          ) : null}

          <div className="relative z-40 rounded-3xl border border-[#0d1b4b]/10 bg-white/70 p-6 shadow-xl shadow-[#0d1b4b]/6 backdrop-blur-xl">
            <div className="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
              <div>
                <h3 className="text-2xl font-black text-[#0d1b4b]">
                  طلبات متجر: <span className="text-[#d4af37]">{shop.name}</span>
                </h3>
                <p className="mt-1 text-sm text-[#0d1b4b]/45">
                  يمكنك التصفية حسب الحالة أو أي حقل من حقول الطلب. إجمالي النتائج: {orders.total}
                </p>
              </div>

              <form method="GET" action="/orders" className="inline-flex items-center gap-2">
                <input type="hidden" name="status" value={filters.status} />
                <label htmlFor="orders-per-page-dropdown" className="text-sm font-bold leading-none text-[#0d1b4b]/70">
                  عدد النتائج:
                </label>
                <div className="min-w-[170px]">
                  <select
                    id="orders-per-page-dropdown"
                    name="per_page"
                    defaultValue={String(filters.perPage)}
                    className="h-12 w-full rounded-xl border border-[#0d1b4b]/15 bg-white px-3 text-sm text-[#0d1b4b]"
                  >
                    {PER_PAGE_OPTIONS.map((option) => (
                      <option key={option} value={option}>
                        {option} لكل صفحة
                      </option>
                    ))}
                  </select>
                </div>
                <button type="submit" className="hidden" aria-hidden="true" />
              </form>
            </div>

            <div className="mt-5 flex flex-wrap gap-2">
              {STATUS_TABS.map((tab) => (
                <Link
                  key={tab.key}
                  href={`/orders?status=${tab.key}&per_page=${filters.perPage}`}
                  className={`rounded-xl px-4 py-2 text-sm font-bold transition ${
                    filters.status === tab.key
                      ? 'bg-[#0d1b4b] text-white'
                      : 'border border-[#0d1b4b]/15 bg-white text-[#0d1b4b]/70 hover:bg-[#fdfbf4]'
                  }`}
                >
                  {tab.label}
                </Link>
              ))}
            </div>

            <form method="GET" action="/orders" className="mt-5 grid grid-cols-1 gap-3 md:grid-cols-[minmax(0,1.05fr)_minmax(0,2.2fr)_minmax(0,1.25fr)] md:items-end">
              <input type="hidden" name="status" value={filters.status} />
              <input type="hidden" name="per_page" value={filters.perPage} />

              <div className="filter-field">
                <label htmlFor="orders-field-dropdown" className="filter-label text-xs font-bold text-[#0d1b4b]/60">
                  الحقل
                </label>
                <select
                  id="orders-field-dropdown"
                  name="field"
                  defaultValue={filters.field}
                  className="h-12 w-full rounded-xl border border-[#0d1b4b]/15 bg-white px-3 text-sm text-[#0d1b4b]"
                >
                  {FIELD_OPTIONS.map((option) => (
                    <option key={option.value || 'blank'} value={option.value}>
                      {option.label}
                    </option>
                  ))}
                </select>
              </div>

              <div className="filter-field md:col-span-2">
                <label htmlFor="orders-value-text" className="filter-label text-xs font-bold text-[#0d1b4b]/60">
                  القيمة
                </label>
                <div className="filter-control">
                  <input
                    id="orders-value-text"
                    name="value"
                    defaultValue={filters.value}
                    type="text"
                    className="absolute inset-0 h-full w-full rounded-xl border border-[#0d1b4b]/15 bg-white px-3 text-sm text-[#0d1b4b] placeholder-[#0d1b4b]/35"
                    placeholder="اكتب قيمة البحث أو التصفية"
                  />
                </div>
              </div>

              <div className="filter-field">
                <span aria-hidden="true" className="filter-spacer">
                  .
                </span>
                <div className="filter-action-row">
                  <button type="submit" className="h-12 flex-1 rounded-xl bg-[#0d1b4b] text-sm font-black text-white transition hover:bg-[#1a2d6b]">
                    تصفية
                  </button>
                  <Link
                    href={`/orders?status=${filters.status}&per_page=${filters.perPage}`}
                    className="inline-flex h-12 items-center rounded-xl border border-[#0d1b4b]/15 bg-white px-4 text-sm font-bold text-[#0d1b4b]/70 transition hover:bg-[#fdfbf4]"
                  >
                    إعادة ضبط
                  </Link>
                </div>
              </div>
            </form>
          </div>

          {orders.data.length === 0 ? (
            <div className="rounded-3xl border border-[#0d1b4b]/10 bg-white/70 p-12 text-center shadow-xl shadow-[#0d1b4b]/6 backdrop-blur-xl">
              <svg className="mx-auto mb-4 h-16 w-16 text-[#0d1b4b]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <p className="text-lg text-[#0d1b4b]/45">لا توجد طلبات مطابقة للمرشحات الحالية.</p>
            </div>
          ) : (
            <>
              <div className="space-y-6">
                {orders.data.map((order) => (
                  <div key={order.id} className="overflow-hidden rounded-3xl border border-[#0d1b4b]/10 bg-white/70 shadow-xl shadow-[#0d1b4b]/6 backdrop-blur-xl">
                    <div className="flex flex-col items-start justify-between gap-3 border-b border-[#0d1b4b]/10 bg-[#f4f7ff] px-6 py-4 sm:flex-row sm:items-center">
                      <div className="flex flex-wrap items-center gap-3">
                        <h4 className="text-lg font-black text-[#0d1b4b]">طلب رقم {order.id}</h4>
                        <span className={`rounded-full border px-3 py-1 text-xs font-semibold ${order.statusClasses}`}>{order.statusLabel}</span>
                        {order.archivedFromLabel ? (
                          <span className="rounded-full border border-[#0d1b4b]/20 bg-[#0d1b4b]/5 px-3 py-1 text-xs font-semibold text-[#0d1b4b]/70">
                            الحالة السابقة: {order.archivedFromLabel}
                          </span>
                        ) : null}
                        {order.promoCodeUsed ? (
                          <span className="rounded-full border border-[#0d1b4b]/15 bg-[#0d1b4b]/8 px-3 py-1 text-xs font-semibold text-[#0d1b4b]">
                            رمز خصم: {order.promoCodeUsed}
                          </span>
                        ) : null}
                        {order.deliveryEstimateLabel ? (
                          <span className="rounded-full border border-[#d4af37]/30 bg-[#fff9e8] px-3 py-1 text-xs font-semibold text-[#a07c1e]">
                            {order.deliveryEstimateLabel}
                          </span>
                        ) : null}
                      </div>
                      <div className="text-right text-sm text-[#0d1b4b]/45">{order.createdAt}</div>
                    </div>

                    <div className="grid grid-cols-1 gap-8 p-6 lg:grid-cols-2">
                      <div>
                        <h5 className="mb-3 text-base font-bold text-[#d4af37]">معلومات المشتري</h5>
                        <div className="space-y-2 text-sm">
                          <div className="flex gap-2">
                            <span className="w-24 shrink-0 text-[#0d1b4b]/45">الاسم:</span>
                            <span className="font-semibold text-[#0d1b4b]">{order.buyerName}</span>
                          </div>
                          {order.buyerEmail ? (
                            <div className="flex gap-2">
                              <span className="w-24 shrink-0 text-[#0d1b4b]/45">البريد:</span>
                              <a href={`mailto:${order.buyerEmail}`} className="text-[#d4af37] hover:text-[#b8922a]" dir="ltr">
                                {order.buyerEmail}
                              </a>
                            </div>
                          ) : null}
                          <div className="flex gap-2">
                            <span className="w-24 shrink-0 text-[#0d1b4b]/45">الهاتف:</span>
                            <a href={`tel:${order.buyerPhone}`} className="font-semibold text-[#d4af37] hover:text-[#b8922a]" dir="ltr">
                              {order.buyerPhone}
                            </a>
                          </div>
                          <div className="flex gap-2">
                            <span className="w-24 shrink-0 text-[#0d1b4b]/45">العنوان:</span>
                            <span className="text-[#0d1b4b]/80">{order.buyerLocation}</span>
                          </div>
                          {order.buyerCity ? (
                            <div className="flex gap-2">
                              <span className="w-24 shrink-0 text-[#0d1b4b]/45">المدينة:</span>
                              <span className="text-[#0d1b4b]/80">{order.buyerCity}</span>
                            </div>
                          ) : null}
                          {order.sellerCitySnapshot ? (
                            <div className="flex gap-2">
                              <span className="w-24 shrink-0 text-[#0d1b4b]/45">مدينة المتجر:</span>
                              <span className="text-[#0d1b4b]/80">{order.sellerCitySnapshot}</span>
                            </div>
                          ) : null}
                          {order.deliveryEstimateLabel ? (
                            <div className="flex gap-2">
                              <span className="w-24 shrink-0 text-[#0d1b4b]/45">التوصيل:</span>
                              <span className="font-semibold text-[#0d1b4b]">{order.deliveryEstimateLabel}</span>
                            </div>
                          ) : null}
                          <div className="flex gap-2">
                            <span className="w-24 shrink-0 text-[#0d1b4b]/45">طريقة الدفع:</span>
                            <span className="font-semibold text-[#0d1b4b]">{order.paymentMethodLabel}</span>
                          </div>
                          {order.shamcashTransactionNumber ? (
                            <div className="flex gap-2">
                              <span className="w-24 shrink-0 text-[#0d1b4b]/45">رقم العملية:</span>
                              <span className="font-semibold text-[#0d1b4b]" dir="ltr">
                                #{order.shamcashTransactionNumber.replace(/^#/, '')}
                              </span>
                            </div>
                          ) : null}
                        </div>
                      </div>

                      <div>
                        <h5 className="mb-3 text-base font-bold text-[#d4af37]">المنتجات المطلوبة</h5>
                        <ul className="space-y-2">
                          {order.items.map((item) => (
                            <li key={item.id} className="flex items-start justify-between border-b border-[#0d1b4b]/10 py-3 text-sm last:border-0">
                              <div className="flex flex-col">
                                <span className="font-medium text-[#0d1b4b]">{item.productName}</span>
                                {item.optionLabel ? <span className="text-xs text-[#0d1b4b]/45">الخيار: {item.optionLabel}</span> : null}
                                <span className="text-xs text-[#0d1b4b]/40">الكمية: {item.quantity}</span>
                              </div>
                              <div className="text-left">
                                {item.unitPriceUsd !== null ? (
                                  <span className="block font-black text-[#0d1b4b]">${(item.unitPriceUsd * item.quantity).toFixed(2)}</span>
                                ) : null}
                                <span className="block text-xs font-bold text-[#0d1b4b]/55">
                                  {(item.unitPriceSyp * item.quantity).toLocaleString(undefined, { maximumFractionDigits: 2 })} ل.س
                                </span>
                              </div>
                            </li>
                          ))}
                        </ul>

                        <div className="mt-4 space-y-3 border-t border-[#0d1b4b]/10 pt-4">
                          <SummaryRow title="مجموع المنتجات" usd={order.subtotalUsd} syp={order.subtotalSyp} />

                          {order.discountUsd > 0 || order.discountSyp > 0 ? (
                            <>
                              <SummaryRow title="الخصم" usd={order.discountUsd * -1} syp={order.discountSyp * -1} emphasize="discount" />
                              <SummaryRow title="مجموع المنتجات بعد الخصم" usd={order.discountedProductsSubtotalUsd} syp={order.discountedProductsSubtotalSyp} />
                            </>
                          ) : null}

                          <SummaryRow title="رسوم التوصيل" usd={order.deliveryFeeUsd} syp={order.deliveryFeeSyp} />

                          <div className="flex items-center justify-between gap-4 border-t border-[#0d1b4b]/10 pt-4">
                            <span className="font-black text-[#0d1b4b]">الإجمالي النهائي</span>
                            <div className="text-left">
                              {order.finalTotalUsd !== null ? (
                                <span className="block text-2xl font-black tracking-tight text-[#d4af37]">${order.finalTotalUsd.toFixed(2)}</span>
                              ) : null}
                              <span className="block text-sm font-black text-[#0d1b4b]/60">
                                {order.finalTotalSyp.toLocaleString(undefined, { maximumFractionDigits: 2 })} ل.س
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div className="flex flex-wrap gap-3 border-t border-[#0d1b4b]/10 bg-[#f4f7ff] px-6 py-4">
                      {(() => {
                        const doneKey = `${order.id}:done`;
                        const canceledKey = `${order.id}:canceled`;
                        const archivedKey = `${order.id}:archived`;
                        const isOrderPending = pendingAction?.startsWith(`${order.id}:`) ?? false;

                        return (
                          <>
                            {order.canMarkDone ? (
                              <button
                                type="button"
                                onClick={() => submitStatus(order.updateStatusUrl, 'done', doneKey)}
                                disabled={isOrderPending}
                                className="inline-flex items-center gap-2 rounded-xl bg-[#0d1b4b] px-5 py-2 text-sm font-black text-white transition hover:bg-[#1a2d6b] disabled:cursor-not-allowed disabled:opacity-60"
                              >
                                {pendingAction === doneKey ? 'جارٍ التأكيد...' : 'تأكيد الاكتمال'}
                              </button>
                            ) : null}

                            {order.canCancel ? (
                              <button
                                type="button"
                                onClick={() => submitStatus(order.updateStatusUrl, 'canceled', canceledKey, 'هل أنت متأكد من إلغاء هذا الطلب؟')}
                                disabled={isOrderPending}
                                className="inline-flex items-center gap-2 rounded-xl bg-red-500 px-5 py-2 text-sm font-bold text-white transition hover:bg-red-600 disabled:cursor-not-allowed disabled:opacity-60"
                              >
                                {pendingAction === canceledKey ? 'جارٍ الإلغاء...' : 'إلغاء الطلب'}
                              </button>
                            ) : null}

                            {order.canArchive ? (
                              <button
                                type="button"
                                onClick={() => submitStatus(order.updateStatusUrl, 'archived', archivedKey, 'هل تريد أرشفة هذا الطلب؟')}
                                disabled={isOrderPending}
                                className="inline-flex items-center gap-2 rounded-xl bg-[#0d1b4b]/10 px-5 py-2 text-sm font-black text-[#0d1b4b] transition hover:bg-[#0d1b4b]/15 disabled:cursor-not-allowed disabled:opacity-60"
                              >
                                {pendingAction === archivedKey ? 'جارٍ الأرشفة...' : 'أرشفة الطلب'}
                              </button>
                            ) : null}
                          </>
                        );
                      })()}
                    </div>
                  </div>
                ))}
              </div>

              {orders.last_page > 1 ? (
                <div className="mt-8 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-[#0d1b4b]/10 bg-white/70 px-4 py-3">
                  <div className="text-sm text-[#0d1b4b]/60">
                    الصفحة {orders.current_page} من {orders.last_page}
                  </div>
                  <div className="flex items-center gap-1">
                    {orders.links.map((link, index) => {
                      const isPrev = link.label.includes('Previous') || link.label.includes('السابق');
                      const isNext = link.label.includes('Next') || link.label.includes('التالي');
                      const label = isPrev ? 'السابق' : isNext ? 'التالي' : link.label.replace(/&laquo;|&raquo;/g, '').trim();

                      if (!link.url) {
                        return (
                          <span key={`link-${index}`} className="rounded-lg bg-[#0d1b4b]/5 px-3 py-1.5 text-sm font-bold text-[#0d1b4b]/30">
                            {label}
                          </span>
                        );
                      }

                      return (
                        <Link
                          key={`link-${index}`}
                          href={link.url}
                          className={`rounded-lg px-3 py-1.5 text-sm font-bold ${
                            link.active
                              ? 'bg-[#0d1b4b] text-white'
                              : 'border border-[#0d1b4b]/15 bg-white text-[#0d1b4b] hover:bg-[#fdfbf4]'
                          }`}
                        >
                          {label}
                        </Link>
                      );
                    })}
                  </div>
                </div>
              ) : null}
            </>
          )}
        </div>
      </div>
    </AppLayout>
  );
}

function SummaryRow({
  title,
  usd,
  syp,
  emphasize,
}: {
  title: string;
  usd: number | null;
  syp: number;
  emphasize?: 'discount';
}) {
  const usdText = usd === null ? null : `${usd < 0 ? '-' : ''}$${Math.abs(usd).toFixed(2)}`;
  const sypText = `${syp < 0 ? '-' : ''}${Math.abs(syp).toLocaleString(undefined, { maximumFractionDigits: 2 })} ل.س`;

  const usdClass = emphasize === 'discount' ? 'text-green-700' : 'text-[#0d1b4b]';
  const sypClass = emphasize === 'discount' ? 'text-green-700/70' : 'text-[#0d1b4b]/55';

  return (
    <div className="flex items-center justify-between gap-4 text-sm font-bold">
      <span className="text-[#0d1b4b]/55">{title}</span>
      <div className="text-left">
        {usdText ? <span className={`block ${usdClass}`}>{usdText}</span> : null}
        <span className={`block text-[11px] ${sypClass}`}>{sypText}</span>
      </div>
    </div>
  );
}






