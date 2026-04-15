import React, { useMemo, useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import { FlashBanner } from '../../components/ui/FlashBanner';
import { PageHeader } from '../../components/ui/PageHeader';

type ProductOption = { label: string; quantity: number };

type ProductRow = {
  id: number;
  name: string;
  categoryName: string;
  hasOptions: boolean;
  totalStock: number;
  options: ProductOption[];
  extraOptionsCount: number;
  price: number;
  effectivePrice: number;
  hasActiveDiscount: boolean;
  discountPercent: number | null;
  discountActive: boolean;
  imageUrl: string | null;
  editUrl: string;
  destroyUrl: string;
  toggleDiscountUrl: string;
};

type PaginationLink = { url: string | null; label: string; active: boolean };

type ProductPaginator = {
  data: ProductRow[];
  current_page: number;
  last_page: number;
  total: number;
  prev_page_url: string | null;
  next_page_url: string | null;
  links: PaginationLink[];
};

interface ProductsPageProps {
  shop: { name: string };
  products: ProductPaginator;
  filters: {
    perPage: number;
    field: string;
    value: string;
  };
}

const FIELD_OPTIONS = [
  { value: '', label: 'اختر الحقل للتصفية' },
  { value: 'id', label: 'رقم المنتج' },
  { value: 'name', label: 'الاسم' },
  { value: 'description', label: 'الوصف' },
  { value: 'category_name', label: 'الفئة' },
  { value: 'price', label: 'السعر' },
  { value: 'quantity_available', label: 'الكمية المتاحة' },
  { value: 'has_options', label: 'نوع المخزون' },
  { value: 'is_active', label: 'نشط / غير نشط' },
  { value: 'discount_percent', label: 'نسبة الخصم' },
  { value: 'discount_active', label: 'حالة الخصم' },
  { value: 'created_at', label: 'تاريخ الإنشاء' },
];

const PER_PAGE_OPTIONS = [10, 15, 20, 25, 30];

export default function ProductsIndex({ shop, products, filters }: ProductsPageProps) {
  const page = usePage() as any;
  const flash = page.props?.flash ?? {};
  const errors = (page.props?.errors ?? {}) as Record<string, string>;

  const [selectedProducts, setSelectedProducts] = useState<number[]>([]);
  const [showDiscountModal, setShowDiscountModal] = useState(false);
  const [discountPercent, setDiscountPercent] = useState('');

  const productIds = useMemo(() => products.data.map((p) => p.id), [products.data]);

  const toggleAll = (checked: boolean) => {
    setSelectedProducts(checked ? productIds : []);
  };

  const submitBulkAction = (action: 'delete' | 'discount' | 'remove_discount') => {
    if (selectedProducts.length === 0) return;

    if (action === 'discount' && !discountPercent) {
      window.alert('يرجى إدخال نسبة الخصم (0-100).');
      return;
    }

    router.post(
      '/products/bulk-action',
      {
        action,
        product_ids: JSON.stringify(selectedProducts),
        discount_percent: discountPercent || undefined,
      },
      {
        preserveScroll: true,
        onSuccess: () => {
          setShowDiscountModal(false);
          if (action !== 'discount') {
            setSelectedProducts([]);
          }
        },
      },
    );
  };

  return (
    <AppLayout
      title="إدارة المنتجات"
      header={<PageHeader title="إدارة المنتجات" />}
    >
      <Head title="إدارة المنتجات" />

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
                  منتجات متجر: <span className="text-[#d4af37]">{shop.name}</span>
                </h3>
                <p className="mt-1 text-sm text-[#0d1b4b]/45">إجمالي النتائج: {products.total}</p>
              </div>
              <a
                href="/products/create"
                className="rounded-xl border border-[#0d1b4b] bg-[#0d1b4b] px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-md shadow-[#0d1b4b]/20 transition hover:bg-[#1a2d6b]"
              >
                + إضافة منتج جديد
              </a>
            </div>

            <form method="GET" action="/products" className="mt-5 grid grid-cols-1 gap-3 md:grid-cols-[minmax(0,1.05fr)_minmax(0,2.2fr)_minmax(0,1fr)_minmax(0,1.25fr)] md:items-end">
              <div className="filter-field">
                <label htmlFor="products-field-dropdown" className="filter-label text-xs font-bold text-[#0d1b4b]/60">
                  الحقل
                </label>
                <select
                  id="products-field-dropdown"
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
                <label htmlFor="products-value-text" className="filter-label text-xs font-bold text-[#0d1b4b]/60">
                  القيمة
                </label>
                <div className="filter-control">
                  <input
                    id="products-value-text"
                    name="value"
                    defaultValue={filters.value}
                    type="text"
                    className="absolute inset-0 h-full w-full rounded-xl border border-[#0d1b4b]/15 bg-white px-3 text-sm text-[#0d1b4b] placeholder-[#0d1b4b]/35"
                    placeholder="اكتب قيمة البحث أو التصفية"
                  />
                </div>
              </div>

              <div className="filter-field">
                <label htmlFor="products-per-page-dropdown" className="filter-label text-xs font-bold text-[#0d1b4b]/60">
                  عدد النتائج
                </label>
                <select
                  id="products-per-page-dropdown"
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

              <div className="filter-field">
                <span aria-hidden="true" className="filter-spacer">
                  .
                </span>
                <div className="filter-action-row">
                  <button type="submit" className="h-12 flex-1 rounded-xl bg-[#0d1b4b] text-sm font-black text-white transition hover:bg-[#1a2d6b]">
                    تصفية
                  </button>
                  <Link href={`/products?per_page=${filters.perPage}`} className="inline-flex h-12 items-center rounded-xl border border-[#0d1b4b]/15 bg-white px-4 text-sm font-bold text-[#0d1b4b]/70 transition hover:bg-[#fdfbf4]">
                    إعادة ضبط
                  </Link>
                </div>
              </div>
            </form>
          </div>

          {selectedProducts.length > 0 ? (
            <div className="flex flex-col items-center justify-between gap-4 rounded-2xl border border-[#d4af37]/30 bg-white/80 p-4 shadow-lg sm:flex-row">
              <span className="font-black text-[#a07c1e]">
                تم تحديد <span>{selectedProducts.length}</span> منتجات
              </span>
              <div className="flex flex-wrap gap-2">
                <button
                  type="button"
                  onClick={() => setShowDiscountModal(true)}
                  className="rounded-xl bg-[#d4af37] px-4 py-2 text-sm font-black text-[#0d1b4b] shadow-sm transition hover:bg-[#c5a02e]"
                >
                  تفعيل الخصم للمحدد
                </button>
                <button
                  type="button"
                  onClick={() => submitBulkAction('remove_discount')}
                  className="rounded-xl bg-[#0d1b4b]/8 px-4 py-2 text-sm font-bold text-[#0d1b4b] shadow-sm transition hover:bg-[#0d1b4b]/12"
                >
                  إيقاف الخصومات للمحدد
                </button>
                <button
                  type="button"
                  onClick={() => {
                    if (window.confirm('هل أنت متأكد من حذف المنتجات المحددة؟')) {
                      submitBulkAction('delete');
                    }
                  }}
                  className="rounded-xl bg-red-500 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-red-600"
                >
                  حذف المحدد
                </button>
                <button
                  type="button"
                  onClick={() => setSelectedProducts([])}
                  className="rounded-xl border border-[#0d1b4b]/15 bg-white px-4 py-2 text-sm font-bold text-[#0d1b4b]/60 transition hover:bg-[#fdfbf4]"
                >
                  إلغاء التحديد
                </button>
              </div>
            </div>
          ) : null}

          <div className="overflow-hidden rounded-3xl border border-[#0d1b4b]/10 bg-white/70 shadow-xl shadow-[#0d1b4b]/6 backdrop-blur-xl">
            <div className="overflow-x-auto p-6 text-[#0d1b4b]">
              {products.data.length === 0 ? (
                <div className="py-16 text-center">
                  <p className="text-lg text-[#0d1b4b]/45">لا توجد منتجات مطابقة للمرشحات الحالية.</p>
                  <a href="/products/create" className="mt-4 inline-block rounded-xl bg-[#0d1b4b] px-5 py-2.5 text-sm font-black text-white transition hover:bg-[#1a2d6b]">
                    + أضف منتجاً جديداً
                  </a>
                </div>
              ) : (
                <table className="min-w-full divide-y divide-[#0d1b4b]/10">
                  <thead className="bg-[#f4f7ff]">
                    <tr>
                      <th className="px-6 py-3 text-right">
                        <input
                          type="checkbox"
                          checked={selectedProducts.length > 0 && selectedProducts.length === productIds.length}
                          onChange={(event) => toggleAll(event.target.checked)}
                          className="rounded border-[#0d1b4b]/20 bg-white text-[#d4af37] shadow-sm focus:ring-[#d4af37]/30"
                        />
                      </th>
                      <th className="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-[#0d1b4b]/45">المنتج</th>
                      <th className="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-[#0d1b4b]/45">السعر</th>
                      <th className="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-[#0d1b4b]/45">حالة الخصم</th>
                      <th className="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider text-[#0d1b4b]/45">إجراءات</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-[#0d1b4b]/8 bg-white/50">
                    {products.data.map((product) => {
                      const selected = selectedProducts.includes(product.id);
                      return (
                        <tr
                          key={product.id}
                          className={`transition-colors duration-150 ${
                            selected
                              ? 'bg-[#d4af37]/10 ring-1 ring-inset ring-[#d4af37]/35'
                              : 'hover:bg-[#0d1b4b]/4'
                          }`}
                        >
                          <td className="px-6 py-4">
                            <input
                              type="checkbox"
                              checked={selected}
                              onChange={(event) => {
                                if (event.target.checked) {
                                  setSelectedProducts((prev) => [...prev, product.id]);
                                } else {
                                  setSelectedProducts((prev) => prev.filter((id) => id !== product.id));
                                }
                              }}
                              className="rounded border-[#0d1b4b]/20 bg-white text-[#d4af37] shadow-sm focus:ring-[#d4af37]/30"
                            />
                          </td>
                          <td className="whitespace-nowrap px-6 py-4">
                            <div className="flex items-center">
                              <div className="h-10 w-10 flex-shrink-0">
                                {product.imageUrl ? (
                                  <img className="h-10 w-10 rounded-full border border-[#0d1b4b]/15 object-cover shadow" src={product.imageUrl} alt={product.name} />
                                ) : (
                                  <div className="flex h-10 w-10 items-center justify-center rounded-full border border-[#0d1b4b]/12 bg-[#0d1b4b]/5 text-[#0d1b4b]/35">
                                    <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                      />
                                    </svg>
                                  </div>
                                )}
                              </div>
                              <div className="ms-4">
                                <div className="text-sm font-black text-[#0d1b4b]">{product.name}</div>
                                <div className="mt-1 text-xs font-semibold text-[#0d1b4b]/50">الفئة: {product.categoryName}</div>
                                <div className="mt-1 text-xs font-semibold text-[#0d1b4b]/50">
                                  {product.hasOptions ? 'مخزون حسب الخيارات' : 'مخزون بسيط'}
                                </div>
                                <div className="mt-1 text-xs font-semibold text-[#0d1b4b]/50">المتاح: {product.totalStock}</div>
                                {product.hasOptions ? (
                                  <div className="mt-1 space-y-1 text-[11px] text-[#0d1b4b]/45">
                                    {product.options.map((option) => (
                                      <div key={`${product.id}-${option.label}`}>
                                        {option.label}: {option.quantity}
                                      </div>
                                    ))}
                                    {product.extraOptionsCount > 0 ? <div>+{product.extraOptionsCount} خيارات إضافية</div> : null}
                                  </div>
                                ) : null}
                              </div>
                            </div>
                          </td>
                          <td className="whitespace-nowrap px-6 py-4 text-sm text-[#0d1b4b]/50">
                            {product.hasActiveDiscount ? (
                              <>
                                <span className="block text-xs text-[#0d1b4b]/35 line-through">{product.price.toFixed(2)} USD</span>
                                <span className="font-bold text-[#a07c1e]">{product.effectivePrice.toFixed(2)} USD</span>
                                <span className="block text-xs text-[#a07c1e]">(-{product.discountPercent}%)</span>
                              </>
                            ) : (
                              <span className="font-black text-[#0d1b4b]">{product.price.toFixed(2)} USD</span>
                            )}
                          </td>
                          <td className="whitespace-nowrap px-6 py-4">
                            {product.discountPercent ? (
                              <button
                                type="button"
                                onClick={() => router.patch(product.toggleDiscountUrl, {}, { preserveScroll: true })}
                                className={`rounded-full px-3 py-1 text-[10px] font-bold transition ${
                                  product.discountActive
                                    ? 'border border-[#d4af37]/30 bg-[#d4af37]/10 text-[#a07c1e] hover:bg-[#d4af37]/20'
                                    : 'border border-[#0d1b4b]/15 bg-white text-[#0d1b4b]/70 hover:bg-[#fdfbf4]'
                                }`}
                              >
                                {product.discountActive ? 'إيقاف الخصم' : 'تفعيل الخصم'}
                              </button>
                            ) : (
                              <span className="text-[10px] text-[#0d1b4b]/40">لا يوجد خصم</span>
                            )}
                          </td>
                          <td className="whitespace-nowrap px-6 py-4 text-center text-sm font-medium">
                            <a href={product.editUrl} className="ms-3 inline-block font-bold text-[#d4af37] transition hover:text-[#b8922a]">
                              تعديل
                            </a>
                            <button
                              type="button"
                              onClick={() => {
                                if (window.confirm('هل أنت متأكد من الحذف؟')) {
                                  router.delete(product.destroyUrl, { preserveScroll: true });
                                }
                              }}
                              className="ms-2 border-0 bg-transparent font-bold text-red-500 transition hover:text-red-600"
                            >
                              حذف
                            </button>
                          </td>
                        </tr>
                      );
                    })}
                  </tbody>
                </table>
              )}
            </div>
          </div>

          {products.last_page > 1 ? (
            <div className="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-[#0d1b4b]/10 bg-white/70 px-4 py-3">
              <div className="text-sm text-[#0d1b4b]/60">
                الصفحة {products.current_page} من {products.last_page}
              </div>
              <div className="flex items-center gap-1">
                {products.links.map((link, index) => {
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

          {showDiscountModal ? (
            <div className="fixed inset-0 z-50 overflow-y-auto">
              <div className="flex min-h-screen items-center justify-center p-4">
                <div className="fixed inset-0 bg-[#0d1b4b]/45 backdrop-blur-sm" onClick={() => setShowDiscountModal(false)} />
                <div className="relative z-10 w-full max-w-md rounded-2xl border border-[#0d1b4b]/10 bg-white shadow-2xl shadow-[#0d1b4b]/15">
                  <div className="flex items-center justify-between border-b border-[#0d1b4b]/10 px-6 py-4">
                    <h3 className="text-lg font-black text-[#0d1b4b]">تطبيق خصم جماعي</h3>
                    <button type="button" onClick={() => setShowDiscountModal(false)} className="text-[#0d1b4b]/45 transition hover:text-[#0d1b4b]">
                      <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                  <div className="space-y-4 px-6 py-5">
                    <p className="text-xs text-[#0d1b4b]/45">
                      سيتم تطبيق هذا الخصم على <span className="font-bold text-[#a07c1e]">{selectedProducts.length}</span> منتجات محددة.
                    </p>
                    <div>
                      <label className="mb-1 block text-sm font-bold text-[#0d1b4b]/70">نسبة الخصم (%)</label>
                      <input
                        type="number"
                        step="0.01"
                        min="0"
                        max="100"
                        value={discountPercent}
                        onChange={(event) => setDiscountPercent(event.target.value)}
                        className="w-full rounded-xl border border-[#0d1b4b]/15 bg-white px-4 py-2.5 text-sm text-[#0d1b4b] outline-none transition placeholder:text-[#0d1b4b]/30 focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20"
                        placeholder="مثال: 20"
                      />
                    </div>
                  </div>
                  <div className="flex gap-3 border-t border-[#0d1b4b]/10 px-6 py-4">
                    <button
                      type="button"
                      onClick={() => submitBulkAction('discount')}
                      className="flex-1 rounded-xl bg-[#d4af37] py-2.5 text-sm font-black text-[#0d1b4b] transition hover:bg-[#c5a02e]"
                    >
                      تطبيق الخصم
                    </button>
                    <button
                      type="button"
                      onClick={() => setShowDiscountModal(false)}
                      className="flex-1 rounded-xl border border-[#0d1b4b]/15 bg-white py-2.5 text-sm font-bold text-[#0d1b4b]/60 transition hover:bg-[#fdfbf4]"
                    >
                      إلغاء
                    </button>
                  </div>
                </div>
              </div>
            </div>
          ) : null}
        </div>
      </div>
    </AppLayout>
  );
}



















