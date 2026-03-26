<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-100 leading-tight">إدارة الطلبات</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-900 border border-green-600 text-green-200 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gray-100">طلبات متجر: <span class="text-[#d4af37]">{{ $shop->name }}</span></h3>
                <span class="text-sm text-gray-400">{{ $orders->count() }} طلب إجمالاً</span>
            </div>

            @if($orders->isEmpty())
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p class="text-gray-400 text-lg">لم تتلقَ أي طلبات حتى الآن.</p>
                    <p class="text-gray-500 text-sm mt-2">سيظهر هنا كل طلب يتقدم به المشترون من متجرك.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-lg">
                            <!-- Order Header -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center px-6 py-4 border-b border-gray-700 bg-gray-900">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h4 class="text-lg font-bold text-gray-100">طلب #{{ $order->id }}</h4>
                                    @php
                                        $statusConfig = [
                                            'pending' => ['label' => 'قيد الانتظار', 'classes' => 'bg-yellow-900 text-yellow-200 border-yellow-700'],
                                            'completed' => ['label' => 'مكتمل', 'classes' => 'bg-green-900 text-green-200 border-green-700'],
                                            'cancelled' => ['label' => 'ملغي', 'classes' => 'bg-red-900 text-red-200 border-red-700'],
                                        ];
                                        $sc = $statusConfig[$order->status] ?? $statusConfig['pending'];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $sc['classes'] }}">{{ $sc['label'] }}</span>
                                    @if($order->promo_code_used)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-900 text-purple-200 border border-purple-700">
                                            🎟 {{ $order->promo_code_used }}
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-3 sm:mt-0 text-sm text-gray-400 text-right">
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
                                            <span class="text-gray-400 w-24 shrink-0">الاسم:</span>
                                            <span class="text-gray-100 font-semibold">{{ $order->buyer_name }}</span>
                                        </div>
                                        @if($order->buyer_email)
                                        <div class="flex gap-2">
                                            <span class="text-gray-400 w-24 shrink-0">البريد:</span>
                                            <a href="mailto:{{ $order->buyer_email }}" class="text-indigo-400 hover:text-indigo-300" dir="ltr">{{ $order->buyer_email }}</a>
                                        </div>
                                        @endif
                                        <div class="flex gap-2">
                                            <span class="text-gray-400 w-24 shrink-0">الهاتف:</span>
                                            <a href="tel:{{ $order->buyer_phone }}" class="text-indigo-400 hover:text-indigo-300 font-semibold" dir="ltr">{{ $order->buyer_phone }}</a>
                                        </div>
                                        <div class="flex gap-2">
                                            <span class="text-gray-400 w-24 shrink-0">العنوان:</span>
                                            <span class="text-gray-200">{{ $order->buyer_address }}</span>
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
                                            <li class="flex justify-between items-start text-sm py-3 border-b border-gray-700 last:border-0">
                                                <div class="flex flex-col">
                                                    <span class="text-gray-100 font-medium">{{ $item->product ? $item->product->name : 'منتج محذوف' }}</span>
                                                    <span class="text-gray-500 text-xs">الكمية: {{ $item->quantity }}</span>
                                                </div>
                                                <div class="text-left">
                                                    @if($item->price_at_time_of_order < ($item->product ? $item->product->price : 0))
                                                        <span class="line-through text-gray-500 text-[10px] block">{{ number_format(($item->product ? $item->product->price : 0) * $item->quantity, 2) }}</span>
                                                    @endif
                                                    <span class="font-bold text-gray-100">{{ number_format($item->price_at_time_of_order * $item->quantity, 2) }} ل.س</span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-4 pt-4 border-t border-gray-700 flex justify-between items-center">
                                        <div class="text-sm text-gray-400 font-medium italic">إجمالي الطلب:</div>
                                        <div class="text-[#d4af37] font-black text-2xl tracking-tight">{{ number_format($order->total_amount, 2) }} ل.س</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            @if($order->status === 'pending')
                                <div class="px-6 py-4 bg-gray-900 border-t border-gray-700 flex flex-wrap gap-3">
                                    <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-green-700 hover:bg-green-600 text-white font-semibold rounded-lg text-sm transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            تأكيد اكتمال الطلب
                                        </button>
                                    </form>
                                    <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" onclick="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')" class="inline-flex items-center gap-2 px-5 py-2 bg-red-800 hover:bg-red-700 text-white font-semibold rounded-lg text-sm transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            إلغاء الطلب
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
