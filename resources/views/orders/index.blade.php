{{-- orders/index --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h2 class="font-semibold text-xl text-[#0d1b4b] leading-tight">إدارة الطلبات</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-2xl font-black text-[#0d1b4b]">طلبات متجر: <span class="text-[#d4af37]">{{ $shop->name }}</span></h3>
                <span class="text-sm text-[#0d1b4b]/45">{{ $orders->count() }} طلب إجمالاً</span>
            </div>

            @if($orders->isEmpty())
                <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl p-12 text-center shadow-xl shadow-[#0d1b4b]/6">
                    <svg class="w-16 h-16 mx-auto text-[#0d1b4b]/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p class="text-[#0d1b4b]/45 text-lg">لم تتلقَ أي طلبات حتى الآن.</p>
                    <p class="text-[#0d1b4b]/40 text-sm mt-2">سيظهر هنا كل طلب يتقدم به المشترون من متجرك.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl overflow-hidden shadow-xl shadow-[#0d1b4b]/6">
                            <!-- Order Header -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center px-6 py-4 border-b border-[#0d1b4b]/10 bg-[#f4f7ff]">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h4 class="text-lg font-black text-[#0d1b4b]">طلب #{{ $order->id }}</h4>
                                    @php
                                        $statusConfig = [
                                            'pending' => ['label' => 'قيد الانتظار', 'classes' => 'bg-[#d4af37]/15 text-[#a07c1e] border-[#d4af37]/35'],
                                            'completed' => ['label' => 'مكتمل', 'classes' => 'bg-green-50 text-green-700 border-green-200'],
                                            'cancelled' => ['label' => 'ملغي', 'classes' => 'bg-red-50 text-red-600 border-red-200'],
                                        ];
                                        $sc = $statusConfig[$order->status] ?? $statusConfig['pending'];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $sc['classes'] }}">{{ $sc['label'] }}</span>
                                    @if($order->promo_code_used)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#0d1b4b]/8 text-[#0d1b4b] border border-[#0d1b4b]/15">
                                            🎟 {{ $order->promo_code_used }}
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-3 sm:mt-0 text-sm text-[#0d1b4b]/45 text-right">
                                    {{ $order->created_at->format('Y-m-d H:i') }}
                                </div>
                            </div>

                            <!-- Order Body -->
                            <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Buyer Info -->
                                <div>
                                    <h5 class="text-base font-bold text-[#d4af37] mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        معلومات المشتري
                                    </h5>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex gap-2">
                                            <span class="text-[#0d1b4b]/45 w-24 shrink-0">الاسم:</span>
                                            <span class="text-[#0d1b4b] font-semibold">{{ $order->buyer_name }}</span>
                                        </div>
                                        @if($order->buyer_email)
                                        <div class="flex gap-2">
                                            <span class="text-[#0d1b4b]/45 w-24 shrink-0">البريد:</span>
                                            <a href="mailto:{{ $order->buyer_email }}" class="text-[#d4af37] hover:text-[#b8922a]" dir="ltr">{{ $order->buyer_email }}</a>
                                        </div>
                                        @endif
                                        <div class="flex gap-2">
                                            <span class="text-[#0d1b4b]/45 w-24 shrink-0">الهاتف:</span>
                                            <a href="tel:{{ $order->buyer_phone }}" class="text-[#d4af37] hover:text-[#b8922a] font-semibold" dir="ltr">{{ $order->buyer_phone }}</a>
                                        </div>
                                        <div class="flex gap-2">
                                            <span class="text-[#0d1b4b]/45 w-24 shrink-0">العنوان:</span>
                                            <span class="text-[#0d1b4b]/80">{{ $order->buyer_address }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Products -->
                                <div>
                                    <h5 class="text-base font-bold text-[#d4af37] mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        المنتجات المطلوبة
                                    </h5>
                                    <ul class="space-y-2">
                                        @foreach($order->items as $item)
                                            <li class="flex justify-between items-start text-sm py-3 border-b border-[#0d1b4b]/10 last:border-0">
                                                <div class="flex flex-col">
                                                    <span class="text-[#0d1b4b] font-medium">{{ $item->product ? $item->product->name : 'منتج محذوف' }}</span>
                                                    <span class="text-[#0d1b4b]/40 text-xs">الكمية: {{ $item->quantity }}</span>
                                                </div>
                                                <div class="text-left">
                                                    @if($item->price_at_time_of_order < ($item->product ? $item->product->price : 0))
                                                        <span class="line-through text-[#0d1b4b]/35 text-[10px] block">{{ number_format(($item->product ? $item->product->price : 0) * $item->quantity, 2) }}</span>
                                                    @endif
                                                    <span class="font-black text-[#0d1b4b]">{{ number_format($item->price_at_time_of_order * $item->quantity, 2) }} ل.س</span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-4 pt-4 border-t border-[#0d1b4b]/10 flex flex-col gap-2">
                                        @php
                                            $subtotal = $order->items->sum(fn($i) => $i->price_at_time_of_order * $i->quantity);
                                            $discountAmount = $subtotal - $order->total_amount;
                                        @endphp
                                        <div class="flex justify-between items-center text-sm">
                                            <div class="text-[#0d1b4b]/45 font-medium italic">المجموع الفرعي:</div>
                                            <div class="text-[#0d1b4b]/80 font-semibold">{{ number_format($subtotal, 2) }} ل.س</div>
                                        </div>
                                        @if($discountAmount > 0)
                                            <div class="flex justify-between items-center text-sm">
                                                <div class="text-[#a07c1e] font-bold flex items-center gap-1">
                                                    🎟 كود الخصم ({{ $order->promo_code_used }}):
                                                </div>
                                                <div class="text-[#a07c1e] font-black">-{{ number_format($discountAmount, 2) }} ل.س</div>
                                            </div>
                                        @endif
                                        <div class="flex justify-between items-center pt-2 border-t border-[#0d1b4b]/10 mt-1">
                                            <div class="text-[#0d1b4b] font-black">إجمالي الطلب:</div>
                                            <div class="text-[#d4af37] font-black text-2xl tracking-tight">{{ number_format($order->total_amount, 2) }} ل.س</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            @if($order->status === 'pending')
                                <div class="px-6 py-4 bg-[#f4f7ff] border-t border-[#0d1b4b]/10 flex flex-wrap gap-3">
                                    @can('manage', $order)
                                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-[#0d1b4b] hover:bg-[#1a2d6b] text-white font-black rounded-xl text-sm transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                تأكيد اكتمال الطلب
                                            </button>
                                        </form>
                                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" onclick="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')" class="inline-flex items-center gap-2 px-5 py-2 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl text-sm transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                إلغاء الطلب
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[#0d1b4b]/40 text-sm italic">إجراءات غير متاحة</span>
                                    @endcan
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
