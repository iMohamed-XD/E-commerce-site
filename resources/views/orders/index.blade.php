<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition" aria-label="الرجوع إلى لوحة التحكم">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h2 class="font-semibold text-xl text-[#0d1b4b] leading-tight">إدارة الطلبات</h2>
            </div>
            <span class="text-sm text-[#0d1b4b]/45">إجمالي النتائج: {{ $orders->total() }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl p-6 shadow-xl shadow-[#0d1b4b]/6">
                <div class="flex flex-col lg:flex-row gap-4 lg:items-end lg:justify-between">
                    <div>
                        <h3 class="text-2xl font-black text-[#0d1b4b]">طلبات متجر: <span class="text-[#d4af37]">{{ $shop->name }}</span></h3>
                        <p class="text-sm text-[#0d1b4b]/45 mt-1">يمكنك التصفية حسب الحالة أو أي حقل من حقول الطلب.</p>
                    </div>

                    <form method="GET" action="{{ route('orders.index') }}" class="flex flex-col sm:flex-row gap-2 sm:items-center">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <label for="orders-per-page" class="text-sm font-bold text-[#0d1b4b]/70">عدد النتائج:</label>
                        <div class="relative min-w-[150px]">
                            <select id="orders-per-page" name="per_page" onchange="this.form.submit()" class="appearance-none w-full bg-white border border-[#0d1b4b]/15 rounded-xl ps-3 pe-10 py-2 text-sm font-bold text-[#0d1b4b] focus:outline-none focus:ring-2 focus:ring-[#d4af37]/25">
                                @foreach([10,15,20,25,30] as $size)
                                    <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }} لكل صفحة</option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 end-3 flex items-center text-[#0d1b4b]/50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </div>
                    </form>
                </div>

                @php
                    $statusTabs = [
                        'pending' => 'قيد الانتظار',
                        'done' => 'مكتمل',
                        'canceled' => 'ملغي',
                        'archived' => 'مؤرشف',
                        'archived_done' => 'مؤرشف من مكتمل',
                        'archived_canceled' => 'مؤرشف من ملغي',
                        'all' => 'الكل',
                    ];
                @endphp

                <div class="mt-5 flex flex-wrap gap-2">
                    @foreach($statusTabs as $tabKey => $tabLabel)
                        <a href="{{ route('orders.index', array_merge(request()->except('page'), ['status' => $tabKey])) }}"
                           class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $status === $tabKey ? 'bg-[#0d1b4b] text-white' : 'bg-white border border-[#0d1b4b]/15 text-[#0d1b4b]/70 hover:bg-[#fdfbf4]' }}">
                            {{ $tabLabel }}
                        </a>
                    @endforeach
                </div>

                <form method="GET" action="{{ route('orders.index') }}" class="mt-5 grid grid-cols-1 md:grid-cols-4 gap-3">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">

                    <div>
                        <label for="orders-field" class="block text-xs font-bold text-[#0d1b4b]/60 mb-1">الحقل</label>
                        <div class="relative">
                            <select id="orders-field" name="field" class="appearance-none w-full bg-white border border-[#0d1b4b]/15 rounded-xl ps-3 pe-10 py-2.5 text-sm font-bold text-[#0d1b4b] focus:outline-none focus:ring-2 focus:ring-[#d4af37]/25">
                                <option value="">اختر الحقل للتصفية</option>
                                <option value="id" @selected($field === 'id')>رقم الطلب</option>
                                <option value="buyer_name" @selected($field === 'buyer_name')>اسم المشتري</option>
                                <option value="buyer_phone" @selected($field === 'buyer_phone')>رقم الهاتف</option>
                                <option value="buyer_email" @selected($field === 'buyer_email')>البريد الإلكتروني</option>
                                <option value="buyer_address" @selected($field === 'buyer_address')>العنوان</option>
                                <option value="promo_code_used" @selected($field === 'promo_code_used')>رمز الخصم</option>
                                <option value="payment_method" @selected($field === 'payment_method')>طريقة الدفع</option>
                                <option value="status" @selected($field === 'status')>الحالة</option>
                                <option value="archived_from_status" @selected($field === 'archived_from_status')>الحالة قبل الأرشفة</option>
                                <option value="total_amount" @selected($field === 'total_amount')>الإجمالي</option>
                                <option value="shamcash_transaction_number" @selected($field === 'shamcash_transaction_number')>رقم عملية شام كاش</option>
                                <option value="created_at" @selected($field === 'created_at')>تاريخ الإنشاء</option>
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 end-3 flex items-center text-[#0d1b4b]/50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="orders-value" class="block text-xs font-bold text-[#0d1b4b]/60 mb-1">القيمة</label>
                        <input id="orders-value" name="value" value="{{ $value }}" type="text" class="w-full bg-white border border-[#0d1b4b]/15 rounded-xl px-3 py-2.5 text-sm text-[#0d1b4b]" placeholder="اكتب قيمة البحث أو التصفية">
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-[#0d1b4b] text-white font-black rounded-xl py-2.5 text-sm hover:bg-[#1a2d6b] transition">تصفية</button>
                        <a href="{{ route('orders.index', ['status' => $status, 'per_page' => $perPage]) }}" class="px-4 py-2.5 border border-[#0d1b4b]/15 rounded-xl text-sm font-bold text-[#0d1b4b]/70 bg-white hover:bg-[#fdfbf4] transition">إعادة ضبط</a>
                    </div>
                </form>
            </div>

            @if($orders->isEmpty())
                <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl p-12 text-center shadow-xl shadow-[#0d1b4b]/6">
                    <svg class="w-16 h-16 mx-auto text-[#0d1b4b]/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p class="text-[#0d1b4b]/45 text-lg">لا توجد طلبات مطابقة للمرشحات الحالية.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($orders as $order)
                        @php
                            $normalizedStatus = match ($order->status) {
                                'completed' => 'done',
                                'cancelled' => 'canceled',
                                default => $order->status,
                            };

                            $statusConfig = [
                                'pending' => ['label' => 'قيد الانتظار', 'classes' => 'bg-[#d4af37]/15 text-[#a07c1e] border-[#d4af37]/35'],
                                'done' => ['label' => 'مكتمل', 'classes' => 'bg-green-50 text-green-700 border-green-200'],
                                'canceled' => ['label' => 'ملغي', 'classes' => 'bg-red-50 text-red-600 border-red-200'],
                                'archived' => ['label' => 'مؤرشف', 'classes' => 'bg-[#0d1b4b]/8 text-[#0d1b4b]/70 border-[#0d1b4b]/20'],
                            ];
                            $sc = $statusConfig[$normalizedStatus] ?? $statusConfig['pending'];
                        @endphp

                        <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl overflow-hidden shadow-xl shadow-[#0d1b4b]/6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center px-6 py-4 border-b border-[#0d1b4b]/10 bg-[#f4f7ff]">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h4 class="text-lg font-black text-[#0d1b4b]">طلب رقم {{ $order->id }}</h4>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $sc['classes'] }}">{{ $sc['label'] }}</span>
                                    @if($normalizedStatus === 'archived')
                                        @php
                                            $archivedFrom = match ($order->archived_from_status) {
                                                'done', 'completed' => 'مكتمل',
                                                'canceled', 'cancelled' => 'ملغي',
                                                default => 'غير معروف',
                                            };
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold border border-[#0d1b4b]/20 bg-[#0d1b4b]/5 text-[#0d1b4b]/70">
                                            الحالة السابقة: {{ $archivedFrom }}
                                        </span>
                                    @endif
                                    @if($order->promo_code_used)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#0d1b4b]/8 text-[#0d1b4b] border border-[#0d1b4b]/15">
                                            رمز خصم: {{ $order->promo_code_used }}
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-3 sm:mt-0 text-sm text-[#0d1b4b]/45 text-right">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                            </div>

                            <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <div>
                                    <h5 class="text-base font-bold text-[#d4af37] mb-3">معلومات المشتري</h5>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex gap-2"><span class="text-[#0d1b4b]/45 w-24 shrink-0">الاسم:</span><span class="text-[#0d1b4b] font-semibold">{{ $order->buyer_name }}</span></div>
                                        @if($order->buyer_email)
                                            <div class="flex gap-2"><span class="text-[#0d1b4b]/45 w-24 shrink-0">البريد:</span><a href="mailto:{{ $order->buyer_email }}" class="text-[#d4af37] hover:text-[#b8922a]" dir="ltr">{{ $order->buyer_email }}</a></div>
                                        @endif
                                        <div class="flex gap-2"><span class="text-[#0d1b4b]/45 w-24 shrink-0">الهاتف:</span><a href="tel:{{ $order->buyer_phone }}" class="text-[#d4af37] hover:text-[#b8922a] font-semibold" dir="ltr">{{ $order->buyer_phone }}</a></div>
                                        <div class="flex gap-2"><span class="text-[#0d1b4b]/45 w-24 shrink-0">العنوان:</span><span class="text-[#0d1b4b]/80">{{ $order->buyer_address }}</span></div>
                                        <div class="flex gap-2"><span class="text-[#0d1b4b]/45 w-24 shrink-0">طريقة الدفع:</span><span class="text-[#0d1b4b] font-semibold">{{ $order->payment_method === 'shamcash' ? 'شام كاش' : 'الدفع عند الاستلام' }}</span></div>
                                        @if($order->payment_method === 'shamcash' && $order->shamcash_transaction_number)
                                            <div class="flex gap-2"><span class="text-[#0d1b4b]/45 w-24 shrink-0">رقم العملية:</span><span class="text-[#0d1b4b] font-semibold" dir="ltr">#{{ ltrim($order->shamcash_transaction_number, '#') }}</span></div>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <h5 class="text-base font-bold text-[#d4af37] mb-3">المنتجات المطلوبة</h5>
                                    <ul class="space-y-2">
                                        @foreach($order->items as $item)
                                            <li class="flex justify-between items-start text-sm py-3 border-b border-[#0d1b4b]/10 last:border-0">
                                                <div class="flex flex-col">
                                                    <span class="text-[#0d1b4b] font-medium">{{ $item->product ? $item->product->name : 'منتج محذوف' }}</span>
                                                    <span class="text-[#0d1b4b]/40 text-xs">الكمية: {{ $item->quantity }}</span>
                                                </div>
                                                <span class="font-black text-[#0d1b4b]">{{ number_format($item->price_at_time_of_order * $item->quantity, 2) }} ل.س</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="mt-4 pt-4 border-t border-[#0d1b4b]/10 flex justify-between items-center">
                                        <span class="text-[#0d1b4b] font-black">إجمالي الطلب:</span>
                                        <span class="text-[#d4af37] font-black text-2xl tracking-tight">{{ number_format($order->total_amount, 2) }} ل.س</span>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-4 bg-[#f4f7ff] border-t border-[#0d1b4b]/10 flex flex-wrap gap-3">
                                @can('manage', $order)
                                    @if($normalizedStatus === 'pending')
                                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="done">
                                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-[#0d1b4b] hover:bg-[#1a2d6b] text-white font-black rounded-xl text-sm transition">تأكيد الاكتمال</button>
                                        </form>

                                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="canceled">
                                            <button type="submit" onclick="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')" class="inline-flex items-center gap-2 px-5 py-2 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl text-sm transition">إلغاء الطلب</button>
                                        </form>
                                    @endif

                                    @if(in_array($normalizedStatus, ['done', 'canceled'], true))
                                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="archived">
                                            <button type="submit" onclick="return confirm('هل تريد أرشفة هذا الطلب؟')" class="inline-flex items-center gap-2 px-5 py-2 bg-[#0d1b4b]/10 hover:bg-[#0d1b4b]/15 text-[#0d1b4b] font-black rounded-xl text-sm transition">أرشفة الطلب</button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($orders->lastPage() > 1)
                    <div class="mt-8 bg-white/70 border border-[#0d1b4b]/10 rounded-2xl px-4 py-3 flex flex-wrap items-center justify-between gap-3">
                        <div class="text-sm text-[#0d1b4b]/60">الصفحة {{ $orders->currentPage() }} من {{ $orders->lastPage() }}</div>
                        <div class="flex items-center gap-1">
                            @if($orders->onFirstPage())
                                <span class="px-3 py-1.5 rounded-lg bg-[#0d1b4b]/5 text-[#0d1b4b]/30 text-sm font-bold">السابق</span>
                            @else
                                <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-[#0d1b4b]/15 bg-white text-[#0d1b4b] text-sm font-bold hover:bg-[#fdfbf4]">السابق</a>
                            @endif

                            @foreach($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
                                <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg text-sm font-bold {{ $page === $orders->currentPage() ? 'bg-[#0d1b4b] text-white' : 'border border-[#0d1b4b]/15 bg-white text-[#0d1b4b] hover:bg-[#fdfbf4]' }}">{{ $page }}</a>
                            @endforeach

                            @if($orders->hasMorePages())
                                <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-[#0d1b4b]/15 bg-white text-[#0d1b4b] text-sm font-bold hover:bg-[#fdfbf4]">التالي</a>
                            @else
                                <span class="px-3 py-1.5 rounded-lg bg-[#0d1b4b]/5 text-[#0d1b4b]/30 text-sm font-bold">التالي</span>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
