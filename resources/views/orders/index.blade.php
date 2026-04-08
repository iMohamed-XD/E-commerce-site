<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition" aria-label="Ø§Ù„Ø±Ø¬ÙˆØ¹ Ø¥Ù„Ù‰ Ù„ÙˆØ­Ø© Ø§Ù„ØªØ­ÙƒÙ…">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h2 class="font-semibold text-xl text-[#0d1b4b] leading-tight">Ø¥Ø¯Ø§Ø±Ø© Ø§Ù„Ø·Ù„Ø¨Ø§Øª</h2>
            </div>
            <span class="text-sm text-[#0d1b4b]/45">Ø¥Ø¬Ù…Ø§Ù„ÙŠ Ø§Ù„Ù†ØªØ§Ø¦Ø¬: {{ $orders->total() }}</span>
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
                        <h3 class="text-2xl font-black text-[#0d1b4b]">Ø·Ù„Ø¨Ø§Øª Ù…ØªØ¬Ø±: <span class="text-[#d4af37]">{{ $shop->name }}</span></h3>
                        <p class="text-sm text-[#0d1b4b]/45 mt-1">ÙŠÙ…ÙƒÙ†Ùƒ Ø§Ù„ØªØµÙÙŠØ© Ø­Ø³Ø¨ Ø§Ù„Ø­Ø§Ù„Ø© Ø£Ùˆ Ø£ÙŠ Ø­Ù‚Ù„ Ù…Ù† Ø­Ù‚ÙˆÙ„ Ø§Ù„Ø·Ù„Ø¨.</p>
                    </div>

                    <form method="GET" action="{{ route('orders.index') }}" class="flex flex-col sm:flex-row gap-2 sm:items-center">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <label for="orders-per-page-dropdown" class="text-sm font-bold text-[#0d1b4b]/70">Ø¹Ø¯Ø¯ Ø§Ù„Ù†ØªØ§Ø¦Ø¬:</label>
                        <div class="min-w-[170px]">
                            <x-filter-dropdown
                                id="orders-per-page-dropdown"
                                name="per_page"
                                :value="(string) $perPage"
                                :auto-submit="true"
                                :options="[
                                    ['value' => '10', 'label' => '10 Ù„ÙƒÙ„ ØµÙØ­Ø©'],
                                    ['value' => '15', 'label' => '15 Ù„ÙƒÙ„ ØµÙØ­Ø©'],
                                    ['value' => '20', 'label' => '20 Ù„ÙƒÙ„ ØµÙØ­Ø©'],
                                    ['value' => '25', 'label' => '25 Ù„ÙƒÙ„ ØµÙØ­Ø©'],
                                    ['value' => '30', 'label' => '30 Ù„ÙƒÙ„ ØµÙØ­Ø©'],
                                ]"
                                placeholder="Ø¹Ø¯Ø¯ Ø§Ù„Ù†ØªØ§Ø¦Ø¬"
                            />
                        </div>
                    </form>
                </div>

                @php
                    $statusTabs = [
                        'pending' => 'Ù‚ÙŠØ¯ Ø§Ù„Ø§Ù†ØªØ¸Ø§Ø±',
                        'done' => 'Ù…ÙƒØªÙ…Ù„',
                        'canceled' => 'Ù…Ù„ØºÙŠ',
                        'archived' => 'Ù…Ø¤Ø±Ø´Ù',
                        'archived_done' => 'Ù…Ø¤Ø±Ø´Ù Ù…Ù† Ù…ÙƒØªÙ…Ù„',
                        'archived_canceled' => 'Ù…Ø¤Ø±Ø´Ù Ù…Ù† Ù…Ù„ØºÙŠ',
                        'all' => 'Ø§Ù„ÙƒÙ„',
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
                        <label for="orders-field-dropdown" class="block text-xs font-bold text-[#0d1b4b]/60 mb-1">Ø§Ù„Ø­Ù‚Ù„</label>
                        <x-filter-dropdown
                            id="orders-field-dropdown"
                            name="field"
                            :value="$field"
                            :options="[
                                ['value' => '', 'label' => 'Ø§Ø®ØªØ± Ø§Ù„Ø­Ù‚Ù„ Ù„Ù„ØªØµÙÙŠØ©'],
                                ['value' => 'id', 'label' => 'Ø±Ù‚Ù… Ø§Ù„Ø·Ù„Ø¨'],
                                ['value' => 'buyer_name', 'label' => 'Ø§Ø³Ù… Ø§Ù„Ù…Ø´ØªØ±ÙŠ'],
                                ['value' => 'buyer_phone', 'label' => 'Ø±Ù‚Ù… Ø§Ù„Ù‡Ø§ØªÙ'],
                                ['value' => 'buyer_email', 'label' => 'Ø§Ù„Ø¨Ø±ÙŠØ¯ Ø§Ù„Ø¥Ù„ÙƒØªØ±ÙˆÙ†ÙŠ'],
                                ['value' => 'buyer_address', 'label' => 'Ø§Ù„Ø¹Ù†ÙˆØ§Ù†'],
                                ['value' => 'promo_code_used', 'label' => 'Ø±Ù…Ø² Ø§Ù„Ø®ØµÙ…'],
                                ['value' => 'payment_method', 'label' => 'Ø·Ø±ÙŠÙ‚Ø© Ø§Ù„Ø¯ÙØ¹'],
                                ['value' => 'status', 'label' => 'Ø§Ù„Ø­Ø§Ù„Ø©'],
                                ['value' => 'archived_from_status', 'label' => 'Ø§Ù„Ø­Ø§Ù„Ø© Ù‚Ø¨Ù„ Ø§Ù„Ø£Ø±Ø´ÙØ©'],
                                ['value' => 'total_amount', 'label' => 'Ø§Ù„Ø¥Ø¬Ù…Ø§Ù„ÙŠ'],
                                ['value' => 'shamcash_transaction_number', 'label' => 'Ø±Ù‚Ù… Ø¹Ù…Ù„ÙŠØ© Ø´Ø§Ù… ÙƒØ§Ø´'],
                                ['value' => 'created_at', 'label' => 'ØªØ§Ø±ÙŠØ® Ø§Ù„Ø¥Ù†Ø´Ø§Ø¡'],
                            ]"
                            placeholder="Ø§Ø®ØªØ± Ø§Ù„Ø­Ù‚Ù„ Ù„Ù„ØªØµÙÙŠØ©"
                        />
                    </div>

                    <div class="md:col-span-2">
                        <label for="orders-value-text" class="block text-xs font-bold text-[#0d1b4b]/60 mb-1">Ø§Ù„Ù‚ÙŠÙ…Ø©</label>
                        <input id="orders-value-text" name="value" value="{{ $value }}" type="text" class="w-full bg-white border border-[#0d1b4b]/15 rounded-xl px-3 py-2.5 text-sm text-[#0d1b4b]" placeholder="Ø§ÙƒØªØ¨ Ù‚ÙŠÙ…Ø© Ø§Ù„Ø¨Ø­Ø« Ø£Ùˆ Ø§Ù„ØªØµÙÙŠØ©">
                        <div id="orders-value-payment-wrap" class="hidden mt-0.5">
                            <x-filter-dropdown
                                id="orders-value-payment-dropdown"
                                name="value"
                                :value="$value"
                                :options="[
                                    ['value' => 'cod', 'label' => 'Ø§Ù„Ø¯ÙØ¹ Ø¹Ù†Ø¯ Ø§Ù„Ø§Ø³ØªÙ„Ø§Ù…'],
                                    ['value' => 'shamcash', 'label' => 'Ø´Ø§Ù… ÙƒØ§Ø´'],
                                ]"
                                placeholder="Ø§Ø®ØªØ± Ø·Ø±ÙŠÙ‚Ø© Ø§Ù„Ø¯ÙØ¹"
                            />
                        </div>
                        <div id="orders-value-archived-wrap" class="hidden mt-0.5">
                            <x-filter-dropdown
                                id="orders-value-archived-dropdown"
                                name="value"
                                :value="$value"
                                :options="[
                                    ['value' => 'done', 'label' => 'Ù…ÙƒØªÙ…Ù„'],
                                    ['value' => 'canceled', 'label' => 'Ù…Ù„ØºÙŠ'],
                                ]"
                                placeholder="Ø§Ø®ØªØ± Ø§Ù„Ø­Ø§Ù„Ø© Ø§Ù„Ø³Ø§Ø¨Ù‚Ø©"
                            />
                        </div>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-[#0d1b4b] text-white font-black rounded-xl py-2.5 text-sm hover:bg-[#1a2d6b] transition">ØªØµÙÙŠØ©</button>
                        <a href="{{ route('orders.index', ['status' => $status, 'per_page' => $perPage]) }}" class="px-4 py-2.5 border border-[#0d1b4b]/15 rounded-xl text-sm font-bold text-[#0d1b4b]/70 bg-white hover:bg-[#fdfbf4] transition">Ø¥Ø¹Ø§Ø¯Ø© Ø¶Ø¨Ø·</a>
                    </div>
                </form>
            </div>

            @if($orders->isEmpty())
                <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl p-12 text-center shadow-xl shadow-[#0d1b4b]/6">
                    <svg class="w-16 h-16 mx-auto text-[#0d1b4b]/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p class="text-[#0d1b4b]/45 text-lg">Ù„Ø§ ØªÙˆØ¬Ø¯ Ø·Ù„Ø¨Ø§Øª Ù…Ø·Ø§Ø¨Ù‚Ø© Ù„Ù„Ù…Ø±Ø´Ø­Ø§Øª Ø§Ù„Ø­Ø§Ù„ÙŠØ©.</p>
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
                                'pending' => ['label' => 'Ù‚ÙŠØ¯ Ø§Ù„Ø§Ù†ØªØ¸Ø§Ø±', 'classes' => 'bg-[#d4af37]/15 text-[#a07c1e] border-[#d4af37]/35'],
                                'done' => ['label' => 'Ù…ÙƒØªÙ…Ù„', 'classes' => 'bg-green-50 text-green-700 border-green-200'],
                                'canceled' => ['label' => 'Ù…Ù„ØºÙŠ', 'classes' => 'bg-red-50 text-red-600 border-red-200'],
                                'archived' => ['label' => 'Ù…Ø¤Ø±Ø´Ù', 'classes' => 'bg-[#0d1b4b]/8 text-[#0d1b4b]/70 border-[#0d1b4b]/20'],
                            ];
                            $sc = $statusConfig[$normalizedStatus] ?? $statusConfig['pending'];
                        @endphp

                        <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 rounded-3xl overflow-hidden shadow-xl shadow-[#0d1b4b]/6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center px-6 py-4 border-b border-[#0d1b4b]/10 bg-[#f4f7ff]">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h4 class="text-lg font-black text-[#0d1b4b]">Ø·Ù„Ø¨ Ø±Ù‚Ù… {{ $order->id }}</h4>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $sc['classes'] }}">{{ $sc['label'] }}</span>
                                    @if($normalizedStatus === 'archived')
                                        @php
                                            $archivedFrom = match ($order->archived_from_status) {
                                                'done', 'completed' => 'Ù…ÙƒØªÙ…Ù„',
                                                'canceled', 'cancelled' => 'Ù…Ù„ØºÙŠ',
                                                default => 'ØºÙŠØ± Ù…Ø¹Ø±ÙˆÙ',
                                            };
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold border border-[#0d1b4b]/20 bg-[#0d1b4b]/5 text-[#0d1b4b]/70">
                                            Ø§Ù„Ø­Ø§Ù„Ø© Ø§Ù„Ø³Ø§Ø¨Ù‚Ø©: {{ $archivedFrom }}
                                        </span>
                                    @endif
                                    @if($order->promo_code_used)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#0d1b4b]/8 text-[#0d1b4b] border border-[#0d1b4b]/15">
                                            Ø±Ù…Ø² Ø®ØµÙ…: {{ $order->promo_code_used }}
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-3 sm:mt-0 text-sm text-[#0d1b4b]/45 text-right">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                            </div>

                            <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <div>
                                    <h5 class="text-base font-bold text-[#d4af37] mb-3">Ù…Ø¹Ù„ÙˆÙ…Ø§Øª Ø§Ù„Ù…Ø´ØªØ±ÙŠ</h5>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex gap-2"><span class="text-[#0d1b4b]/45 w-24 shrink-0">Ø§Ù„Ø§Ø³Ù…:</span><span class="text-[#0d1b4b] font-semibold">{{ $order->buyer_name }}</span></div>
                                        @if($order->buyer_email)
                                            <div class="flex gap-2"><span class="text-[#0d1b4b]/45 w-24 shrink-0">Ø§Ù„Ø¨Ø±ÙŠØ¯:</span><a href="mailto:{{ $order->buyer_email }}" class="text-[#d4af37] hover:text-[#b8922a]" dir="ltr">{{ $order->buyer_email }}</a></div>
                                        @endif
                                        <div class="flex gap-2"><span class="text-[#0d1b4b]/45 w-24 shrink-0">Ø§Ù„Ù‡Ø§ØªÙ:</span><a href="tel:{{ $order->buyer_phone }}" class="text-[#d4af37] hover:text-[#b8922a] font-semibold" dir="ltr">{{ $order->buyer_phone }}</a></div>
                                        <div class="flex gap-2"><span class="text-[#0d1b4b]/45 w-24 shrink-0">Ø§Ù„Ø¹Ù†ÙˆØ§Ù†:</span><span class="text-[#0d1b4b]/80">{{ $order->buyer_address }}</span></div>
                                        <div class="flex gap-2"><span class="text-[#0d1b4b]/45 w-24 shrink-0">Ø·Ø±ÙŠÙ‚Ø© Ø§Ù„Ø¯ÙØ¹:</span><span class="text-[#0d1b4b] font-semibold">{{ $order->payment_method === 'shamcash' ? 'Ø´Ø§Ù… ÙƒØ§Ø´' : 'Ø§Ù„Ø¯ÙØ¹ Ø¹Ù†Ø¯ Ø§Ù„Ø§Ø³ØªÙ„Ø§Ù…' }}</span></div>
                                        @if($order->payment_method === 'shamcash' && $order->shamcash_transaction_number)
                                            <div class="flex gap-2"><span class="text-[#0d1b4b]/45 w-24 shrink-0">Ø±Ù‚Ù… Ø§Ù„Ø¹Ù…Ù„ÙŠØ©:</span><span class="text-[#0d1b4b] font-semibold" dir="ltr">#{{ ltrim($order->shamcash_transaction_number, '#') }}</span></div>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <h5 class="text-base font-bold text-[#d4af37] mb-3">Ø§Ù„Ù…Ù†ØªØ¬Ø§Øª Ø§Ù„Ù…Ø·Ù„ÙˆØ¨Ø©</h5>
                                    <ul class="space-y-2">
                                        @foreach($order->items as $item)
                                            <li class="flex justify-between items-start text-sm py-3 border-b border-[#0d1b4b]/10 last:border-0">
                                                <div class="flex flex-col">
                                                    <span class="text-[#0d1b4b] font-medium">{{ $item->product ? $item->product->name : 'Ù…Ù†ØªØ¬ Ù…Ø­Ø°ÙˆÙ' }}</span>
                                                    <span class="text-[#0d1b4b]/40 text-xs">Ø§Ù„ÙƒÙ…ÙŠØ©: {{ $item->quantity }}</span>
                                                </div>
                                                <span class="font-black text-[#0d1b4b]">{{ number_format($item->price_at_time_of_order * $item->quantity, 2) }} Ù„.Ø³</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="mt-4 pt-4 border-t border-[#0d1b4b]/10 flex justify-between items-center">
                                        <span class="text-[#0d1b4b] font-black">Ø¥Ø¬Ù…Ø§Ù„ÙŠ Ø§Ù„Ø·Ù„Ø¨:</span>
                                        <span class="text-[#d4af37] font-black text-2xl tracking-tight">{{ number_format($order->total_amount, 2) }} Ù„.Ø³</span>
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
                                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-[#0d1b4b] hover:bg-[#1a2d6b] text-white font-black rounded-xl text-sm transition">ØªØ£ÙƒÙŠØ¯ Ø§Ù„Ø§ÙƒØªÙ…Ø§Ù„</button>
                                        </form>

                                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="canceled">
                                            <button type="submit" onclick="return confirm('Ù‡Ù„ Ø£Ù†Øª Ù…ØªØ£ÙƒØ¯ Ù…Ù† Ø¥Ù„ØºØ§Ø¡ Ù‡Ø°Ø§ Ø§Ù„Ø·Ù„Ø¨ØŸ')" class="inline-flex items-center gap-2 px-5 py-2 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl text-sm transition">Ø¥Ù„ØºØ§Ø¡ Ø§Ù„Ø·Ù„Ø¨</button>
                                        </form>
                                    @endif

                                    @if(in_array($normalizedStatus, ['done', 'canceled'], true))
                                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="archived">
                                            <button type="submit" onclick="return confirm('Ù‡Ù„ ØªØ±ÙŠØ¯ Ø£Ø±Ø´ÙØ© Ù‡Ø°Ø§ Ø§Ù„Ø·Ù„Ø¨ØŸ')" class="inline-flex items-center gap-2 px-5 py-2 bg-[#0d1b4b]/10 hover:bg-[#0d1b4b]/15 text-[#0d1b4b] font-black rounded-xl text-sm transition">Ø£Ø±Ø´ÙØ© Ø§Ù„Ø·Ù„Ø¨</button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($orders->lastPage() > 1)
                    <div class="mt-8 bg-white/70 border border-[#0d1b4b]/10 rounded-2xl px-4 py-3 flex flex-wrap items-center justify-between gap-3">
                        <div class="text-sm text-[#0d1b4b]/60">Ø§Ù„ØµÙØ­Ø© {{ $orders->currentPage() }} Ù…Ù† {{ $orders->lastPage() }}</div>
                        <div class="flex items-center gap-1">
                            @if($orders->onFirstPage())
                                <span class="px-3 py-1.5 rounded-lg bg-[#0d1b4b]/5 text-[#0d1b4b]/30 text-sm font-bold">Ø§Ù„Ø³Ø§Ø¨Ù‚</span>
                            @else
                                <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-[#0d1b4b]/15 bg-white text-[#0d1b4b] text-sm font-bold hover:bg-[#fdfbf4]">Ø§Ù„Ø³Ø§Ø¨Ù‚</a>
                            @endif

                            @foreach($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
                                <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg text-sm font-bold {{ $page === $orders->currentPage() ? 'bg-[#0d1b4b] text-white' : 'border border-[#0d1b4b]/15 bg-white text-[#0d1b4b] hover:bg-[#fdfbf4]' }}">{{ $page }}</a>
                            @endforeach

                            @if($orders->hasMorePages())
                                <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-[#0d1b4b]/15 bg-white text-[#0d1b4b] text-sm font-bold hover:bg-[#fdfbf4]">Ø§Ù„ØªØ§Ù„ÙŠ</a>
                            @else
                                <span class="px-3 py-1.5 rounded-lg bg-[#0d1b4b]/5 text-[#0d1b4b]/30 text-sm font-bold">Ø§Ù„ØªØ§Ù„ÙŠ</span>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fieldInput = document.getElementById('orders-field-dropdown');
            const textInput = document.getElementById('orders-value-text');
            const paymentWrap = document.getElementById('orders-value-payment-wrap');
            const archivedWrap = document.getElementById('orders-value-archived-wrap');
            const paymentInput = document.getElementById('orders-value-payment-dropdown');
            const archivedInput = document.getElementById('orders-value-archived-dropdown');

            if (!fieldInput || !textInput || !paymentWrap || !archivedWrap || !paymentInput || !archivedInput) {
                return;
            }

            const syncValueInput = () => {
                const fieldName = fieldInput.value;
                const isPaymentField = fieldName === 'payment_method';
                const isArchivedField = fieldName === 'archived_from_status';

                textInput.classList.toggle('hidden', isPaymentField || isArchivedField);
                textInput.disabled = isPaymentField || isArchivedField;

                paymentWrap.classList.toggle('hidden', !isPaymentField);
                archivedWrap.classList.toggle('hidden', !isArchivedField);

                paymentInput.disabled = !isPaymentField;
                archivedInput.disabled = !isArchivedField;
            };

            document.addEventListener('filter-dropdown-change', (event) => {
                if (event.detail?.id === 'orders-field-dropdown') {
                    syncValueInput();
                }
            });

            syncValueInput();
        });
    </script>
</x-app-layout>
