<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b] transition" aria-label="Ø§Ù„Ø±Ø¬ÙˆØ¹ Ø¥Ù„Ù‰ Ù„ÙˆØ­Ø© Ø§Ù„ØªØ­ÙƒÙ…">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-[#0d1b4b] leading-tight">Ø¥Ø¯Ø§Ø±Ø© Ø§Ù„Ù…Ù†ØªØ¬Ø§Øª</h2>
        </div>
    </x-slot>

    <div class="py-12" x-data="productManager()">
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
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-black text-[#0d1b4b]">Ù…Ù†ØªØ¬Ø§Øª Ù…ØªØ¬Ø±: <span class="text-[#d4af37]">{{ $shop->name }}</span></h3>
                        <p class="text-sm text-[#0d1b4b]/45 mt-1">Ø¥Ø¬Ù…Ø§Ù„ÙŠ Ø§Ù„Ù†ØªØ§Ø¦Ø¬: {{ $products->total() }}</p>
                    </div>
                    <a href="{{ route('products.create') }}" class="px-5 py-2.5 bg-[#0d1b4b] border border-[#0d1b4b] rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-[#1a2d6b] shadow-md shadow-[#0d1b4b]/20 transition">
                        + Ø¥Ø¶Ø§ÙØ© Ù…Ù†ØªØ¬ Ø¬Ø¯ÙŠØ¯
                    </a>
                </div>

                <form method="GET" action="{{ route('products.index') }}" class="mt-5 grid grid-cols-1 md:grid-cols-5 gap-3">
                    <div>
                        <label for="products-field-dropdown" class="block text-xs font-bold text-[#0d1b4b]/60 mb-1">Ø§Ù„Ø­Ù‚Ù„</label>
                        <x-filter-dropdown
                            id="products-field-dropdown"
                            name="field"
                            :value="$field"
                            :options="[
                                ['value' => '', 'label' => 'Ø§Ø®ØªØ± Ø§Ù„Ø­Ù‚Ù„ Ù„Ù„ØªØµÙÙŠØ©'],
                                ['value' => 'id', 'label' => 'Ø±Ù‚Ù… Ø§Ù„Ù…Ù†ØªØ¬'],
                                ['value' => 'name', 'label' => 'Ø§Ù„Ø§Ø³Ù…'],
                                ['value' => 'description', 'label' => 'Ø§Ù„ÙˆØµÙ'],
                                ['value' => 'category_name', 'label' => 'Ø§Ù„ÙØ¦Ø©'],
                                ['value' => 'price', 'label' => 'Ø§Ù„Ø³Ø¹Ø±'],
                                ['value' => 'quantity_available', 'label' => 'Ø§Ù„ÙƒÙ…ÙŠØ© Ø§Ù„Ù…ØªØ§Ø­Ø©'],
                                ['value' => 'is_active', 'label' => 'Ù†Ø´Ø· / ØºÙŠØ± Ù†Ø´Ø·'],
                                ['value' => 'discount_percent', 'label' => 'Ù†Ø³Ø¨Ø© Ø§Ù„Ø®ØµÙ…'],
                                ['value' => 'discount_active', 'label' => 'Ø­Ø§Ù„Ø© Ø§Ù„Ø®ØµÙ…'],
                                ['value' => 'created_at', 'label' => 'ØªØ§Ø±ÙŠØ® Ø§Ù„Ø¥Ù†Ø´Ø§Ø¡'],
                            ]"
                            placeholder="Ø§Ø®ØªØ± Ø§Ù„Ø­Ù‚Ù„ Ù„Ù„ØªØµÙÙŠØ©"
                        />
                    </div>

                    <div class="md:col-span-2">
                        <label for="products-value-text" class="block text-xs font-bold text-[#0d1b4b]/60 mb-1">Ø§Ù„Ù‚ÙŠÙ…Ø©</label>
                        <input id="products-value-text" name="value" value="{{ $value }}" type="text" class="w-full bg-white border border-[#0d1b4b]/15 rounded-xl px-3 py-2.5 text-sm text-[#0d1b4b]" placeholder="Ø§ÙƒØªØ¨ Ù‚ÙŠÙ…Ø© Ø§Ù„Ø¨Ø­Ø« Ø£Ùˆ Ø§Ù„ØªØµÙÙŠØ©">
                        <div id="products-value-active-wrap" class="hidden mt-0.5">
                            <x-filter-dropdown
                                id="products-value-active-dropdown"
                                name="value"
                                :value="$value"
                                :options="[
                                    ['value' => '1', 'label' => 'Ù†Ø´Ø·'],
                                    ['value' => '0', 'label' => 'ØºÙŠØ± Ù†Ø´Ø·'],
                                ]"
                                placeholder="Ø§Ø®ØªØ± Ø­Ø§Ù„Ø© Ø§Ù„Ù†Ø´Ø§Ø·"
                            />
                        </div>
                        <div id="products-value-discount-wrap" class="hidden mt-0.5">
                            <x-filter-dropdown
                                id="products-value-discount-dropdown"
                                name="value"
                                :value="$value"
                                :options="[
                                    ['value' => '1', 'label' => 'Ù…ÙØ¹Ù‘Ù„'],
                                    ['value' => '0', 'label' => 'ØºÙŠØ± Ù…ÙØ¹Ù‘Ù„'],
                                ]"
                                placeholder="Ø§Ø®ØªØ± Ø­Ø§Ù„Ø© Ø§Ù„Ø®ØµÙ…"
                            />
                        </div>
                    </div>

                    <div>
                        <label for="products-per-page-dropdown" class="block text-xs font-bold text-[#0d1b4b]/60 mb-1">Ø¹Ø¯Ø¯ Ø§Ù„Ù†ØªØ§Ø¦Ø¬</label>
                        <x-filter-dropdown
                            id="products-per-page-dropdown"
                            name="per_page"
                            :value="(string) $perPage"
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

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-[#0d1b4b] text-white font-black rounded-xl py-2.5 text-sm hover:bg-[#1a2d6b] transition">ØªØµÙÙŠØ©</button>
                        <a href="{{ route('products.index', ['per_page' => $perPage]) }}" class="px-4 py-2.5 border border-[#0d1b4b]/15 rounded-xl text-sm font-bold text-[#0d1b4b]/70 bg-white hover:bg-[#fdfbf4] transition">Ø¥Ø¹Ø§Ø¯Ø© Ø¶Ø¨Ø·</a>
                    </div>
                </form>
            </div>

            <div x-show="selectedProducts.length > 0" x-transition x-cloak class="bg-white/80 border border-[#d4af37]/30 shadow-lg rounded-2xl p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                <span class="font-black text-[#a07c1e]">ØªÙ… ØªØ­Ø¯ÙŠØ¯ <span x-text="selectedProducts.length"></span> Ù…Ù†ØªØ¬Ø§Øª</span>
                <div class="flex flex-wrap gap-2">
                    <button @click="showDiscountModal = true" class="px-4 py-2 bg-[#d4af37] text-[#0d1b4b] rounded-xl text-sm font-black hover:bg-[#c5a02e] shadow-sm transition">ØªÙØ¹ÙŠÙ„ Ø§Ù„Ø®ØµÙ… Ù„Ù„Ù…Ø­Ø¯Ø¯</button>
                    <button @click="submitBulkAction('remove_discount')" class="px-4 py-2 bg-[#0d1b4b]/8 text-[#0d1b4b] rounded-xl text-sm font-bold hover:bg-[#0d1b4b]/12 shadow-sm transition">Ø¥ÙŠÙ‚Ø§Ù Ø§Ù„Ø®ØµÙˆÙ…Ø§Øª Ù„Ù„Ù…Ø­Ø¯Ø¯</button>
                    <button @click="if(confirm('Ù‡Ù„ Ø£Ù†Øª Ù…ØªØ£ÙƒØ¯ Ù…Ù† Ø­Ø°Ù Ø§Ù„Ù…Ù†ØªØ¬Ø§Øª Ø§Ù„Ù…Ø­Ø¯Ø¯Ø©ØŸ')) submitBulkAction('delete')" class="px-4 py-2 bg-red-500 text-white rounded-xl text-sm font-bold hover:bg-red-600 shadow-sm transition">Ø­Ø°Ù Ø§Ù„Ù…Ø­Ø¯Ø¯</button>
                    <button @click="selectedProducts = []" class="px-4 py-2 bg-white border border-[#0d1b4b]/15 text-[#0d1b4b]/60 rounded-xl text-sm font-bold hover:bg-[#fdfbf4] transition">Ø¥Ù„ØºØ§Ø¡ Ø§Ù„ØªØ­Ø¯ÙŠØ¯</button>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-xl border border-[#0d1b4b]/10 overflow-hidden shadow-xl shadow-[#0d1b4b]/6 sm:rounded-3xl">
                <div class="p-6 text-[#0d1b4b] overflow-x-auto">
                    @if($products->isEmpty())
                        <div class="text-center py-16">
                            <p class="text-[#0d1b4b]/45 text-lg">Ù„Ø§ ØªÙˆØ¬Ø¯ Ù…Ù†ØªØ¬Ø§Øª Ù…Ø·Ø§Ø¨Ù‚Ø© Ù„Ù„Ù…Ø±Ø´Ø­Ø§Øª Ø§Ù„Ø­Ø§Ù„ÙŠØ©.</p>
                            <a href="{{ route('products.create') }}" class="mt-4 inline-block px-5 py-2.5 bg-[#0d1b4b] text-white rounded-xl text-sm font-black hover:bg-[#1a2d6b] transition">+ Ø£Ø¶Ù Ù…Ù†ØªØ¬Ø§Ù‹ Ø¬Ø¯ÙŠØ¯Ø§Ù‹</a>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-[#0d1b4b]/10">
                            <thead class="bg-[#f4f7ff]">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right">
                                        <input type="checkbox" @change="toggleAll($event)" class="rounded border-[#0d1b4b]/20 bg-white text-[#d4af37] shadow-sm focus:ring-[#d4af37]/30">
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-[#0d1b4b]/45 uppercase tracking-wider">Ø§Ù„Ù…Ù†ØªØ¬</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-[#0d1b4b]/45 uppercase tracking-wider">Ø§Ù„Ø³Ø¹Ø±</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-[#0d1b4b]/45 uppercase tracking-wider">Ø­Ø§Ù„Ø© Ø§Ù„Ø®ØµÙ…</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-[#0d1b4b]/45 uppercase tracking-wider">Ø¥Ø¬Ø±Ø§Ø¡Ø§Øª</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white/50 divide-y divide-[#0d1b4b]/8">
                                @foreach($products as $product)
                                    <tr :class="selectedProducts.includes({{ $product->id }}) ? 'bg-[#d4af37]/10 ring-1 ring-inset ring-[#d4af37]/35' : 'hover:bg-[#0d1b4b]/4'" class="transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" value="{{ $product->id }}" x-model="selectedProducts" class="rounded border-[#0d1b4b]/20 bg-white text-[#d4af37] shadow-sm focus:ring-[#d4af37]/30 product-checkbox">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($product->image_path)
                                                        <img class="h-10 w-10 rounded-full object-cover shadow border border-[#0d1b4b]/15" src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-[#0d1b4b]/5 flex items-center justify-center text-[#0d1b4b]/35 border border-[#0d1b4b]/12">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ms-4">
                                                    <div class="text-sm font-black text-[#0d1b4b]">{{ $product->name }}</div>
                                                    <div class="text-xs text-[#0d1b4b]/50 font-semibold mt-1">Ø§Ù„ÙØ¦Ø©: {{ $product->category?->name ?? 'ØºÙŠØ± Ù…ØµÙ†Ù' }}</div>
                                                    <div class="text-xs text-[#0d1b4b]/50 font-semibold mt-1">Ø§Ù„Ù…ØªØ§Ø­: {{ (int) $product->quantity_available }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#0d1b4b]/50">
                                            @php $effPrice = $product->effectivePrice(); @endphp
                                            @if($product->hasActiveDiscount())
                                                <span class="line-through text-[#0d1b4b]/35 block text-xs">{{ number_format($product->price, 2) }} Ù„.Ø³</span>
                                                <span class="text-[#a07c1e] font-bold">{{ number_format($effPrice, 2) }} Ù„.Ø³</span>
                                                <span class="text-[#a07c1e] text-xs block">(-{{ $product->discount_percent }}%)</span>
                                            @else
                                                <span class="font-black text-[#0d1b4b]">{{ number_format($product->price, 2) }} Ù„.Ø³</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                             @if($product->discount_percent)
                                                <form action="{{ route('products.toggle_discount', $product) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($product->discount_active)
                                                        <button type="submit" class="px-3 py-1 text-[10px] font-bold rounded-full bg-[#d4af37]/10 text-[#a07c1e] border border-[#d4af37]/30 hover:bg-[#d4af37]/20 transition">Ø¥ÙŠÙ‚Ø§Ù Ø§Ù„Ø®ØµÙ…</button>
                                                    @else
                                                        <button type="submit" class="px-3 py-1 text-[10px] font-bold rounded-full bg-white text-[#0d1b4b]/70 border border-[#0d1b4b]/15 hover:bg-[#fdfbf4] transition">ØªÙØ¹ÙŠÙ„ Ø§Ù„Ø®ØµÙ…</button>
                                                    @endif
                                                </form>
                                            @else
                                                <span class="text-[10px] text-[#0d1b4b]/40">Ù„Ø§ ÙŠÙˆØ¬Ø¯ Ø®ØµÙ…</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            @can('manage', $product)
                                                <a href="{{ route('products.edit', $product) }}" class="text-[#d4af37] hover:text-[#b8922a] inline-block ms-3 font-bold transition">ØªØ¹Ø¯ÙŠÙ„</a>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Ù‡Ù„ Ø£Ù†Øª Ù…ØªØ£ÙƒØ¯ Ù…Ù† Ø§Ù„Ø­Ø°ÙØŸ');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-600 border-0 bg-transparent cursor-pointer font-bold ms-2 transition">Ø­Ø°Ù</button>
                                                </form>
                                            @else
                                                <span class="text-[#0d1b4b]/40 italic">ØºÙŠØ± Ù…ØµØ±Ø­</span>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            @if($products->lastPage() > 1)
                <div class="bg-white/70 border border-[#0d1b4b]/10 rounded-2xl px-4 py-3 flex flex-wrap items-center justify-between gap-3">
                    <div class="text-sm text-[#0d1b4b]/60">Ø§Ù„ØµÙØ­Ø© {{ $products->currentPage() }} Ù…Ù† {{ $products->lastPage() }}</div>
                    <div class="flex items-center gap-1">
                        @if($products->onFirstPage())
                            <span class="px-3 py-1.5 rounded-lg bg-[#0d1b4b]/5 text-[#0d1b4b]/30 text-sm font-bold">Ø§Ù„Ø³Ø§Ø¨Ù‚</span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-[#0d1b4b]/15 bg-white text-[#0d1b4b] text-sm font-bold hover:bg-[#fdfbf4]">Ø§Ù„Ø³Ø§Ø¨Ù‚</a>
                        @endif

                        @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                            <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg text-sm font-bold {{ $page === $products->currentPage() ? 'bg-[#0d1b4b] text-white' : 'border border-[#0d1b4b]/15 bg-white text-[#0d1b4b] hover:bg-[#fdfbf4]' }}">{{ $page }}</a>
                        @endforeach

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-[#0d1b4b]/15 bg-white text-[#0d1b4b] text-sm font-bold hover:bg-[#fdfbf4]">Ø§Ù„ØªØ§Ù„ÙŠ</a>
                        @else
                            <span class="px-3 py-1.5 rounded-lg bg-[#0d1b4b]/5 text-[#0d1b4b]/30 text-sm font-bold">Ø§Ù„ØªØ§Ù„ÙŠ</span>
                        @endif
                    </div>
                </div>
            @endif

            <div x-show="showDiscountModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div x-show="showDiscountModal" x-transition.opacity class="fixed inset-0 bg-[#0d1b4b]/45 backdrop-blur-sm" @click="showDiscountModal = false"></div>

                    <div x-show="showDiscountModal" x-transition class="relative bg-white border border-[#0d1b4b]/10 rounded-2xl shadow-2xl shadow-[#0d1b4b]/15 w-full max-w-md z-10">
                        <div class="px-6 py-4 border-b border-[#0d1b4b]/10 flex items-center justify-between">
                            <h3 class="text-lg font-black text-[#0d1b4b]">ØªØ·Ø¨ÙŠÙ‚ Ø®ØµÙ… Ø¬Ù…Ø§Ø¹ÙŠ</h3>
                            <button @click="showDiscountModal = false" class="text-[#0d1b4b]/45 hover:text-[#0d1b4b]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="px-6 py-5 space-y-4">
                            <p class="text-xs text-[#0d1b4b]/45">Ø³ÙŠØªÙ… ØªØ·Ø¨ÙŠÙ‚ Ù‡Ø°Ø§ Ø§Ù„Ø®ØµÙ… Ø¹Ù„Ù‰ <span class="text-[#a07c1e] font-bold" x-text="selectedProducts.length"></span> Ù…Ù†ØªØ¬Ø§Øª Ù…Ø­Ø¯Ø¯Ø©.</p>
                             <div>
                                <label class="block text-sm font-bold text-[#0d1b4b]/70 mb-1">Ù†Ø³Ø¨Ø© Ø§Ù„Ø®ØµÙ… (%)</label>
                                <input type="number" step="0.01" min="0" max="100" x-model="discountPercent"
                                    class="w-full bg-white border border-[#0d1b4b]/15 text-[#0d1b4b] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4af37]/20 focus:border-[#d4af37] placeholder-[#0d1b4b]/30"
                                    placeholder="Ù…Ø«Ø§Ù„: 20">
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-[#0d1b4b]/10 flex gap-3">
                            <button type="button" @click="submitBulkAction('discount')" class="flex-1 bg-[#d4af37] hover:bg-[#c5a02e] text-[#0d1b4b] font-black py-2.5 rounded-xl text-sm transition">ØªØ·Ø¨ÙŠÙ‚ Ø§Ù„Ø®ØµÙ…</button>
                            <button type="button" @click="showDiscountModal = false" class="flex-1 bg-white hover:bg-[#fdfbf4] border border-[#0d1b4b]/15 text-[#0d1b4b]/60 font-bold py-2.5 rounded-xl text-sm transition">Ø¥Ù„ØºØ§Ø¡</button>
                        </div>
                    </div>
                </div>
            </div>

            <form id="bulkForm" method="POST" action="{{ route('products.bulk_action') }}" class="hidden">
                @csrf
                <input type="hidden" name="action" x-model="bulkAction">
                <input type="hidden" name="product_ids" x-model="JSON.stringify(selectedProducts)">
                <input type="hidden" name="discount_percent" x-model="discountPercent">
            </form>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fieldInput = document.getElementById('products-field-dropdown');
            const textInput = document.getElementById('products-value-text');
            const activeWrap = document.getElementById('products-value-active-wrap');
            const discountWrap = document.getElementById('products-value-discount-wrap');
            const activeInput = document.getElementById('products-value-active-dropdown');
            const discountInput = document.getElementById('products-value-discount-dropdown');

            if (!fieldInput || !textInput || !activeWrap || !discountWrap || !activeInput || !discountInput) {
                return;
            }

            const syncValueInput = () => {
                const fieldName = fieldInput.value;
                const isActiveField = fieldName === 'is_active';
                const isDiscountField = fieldName === 'discount_active';

                textInput.classList.toggle('hidden', isActiveField || isDiscountField);
                textInput.disabled = isActiveField || isDiscountField;

                activeWrap.classList.toggle('hidden', !isActiveField);
                discountWrap.classList.toggle('hidden', !isDiscountField);

                activeInput.disabled = !isActiveField;
                discountInput.disabled = !isDiscountField;
            };

            document.addEventListener('filter-dropdown-change', (event) => {
                if (event.detail?.id === 'products-field-dropdown') {
                    syncValueInput();
                }
            });

            syncValueInput();
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('productManager', () => ({
                selectedProducts: [],
                showDiscountModal: false,
                bulkAction: '',
                discountPercent: '',

                toggleAll(e) {
                    if (e.target.checked) {
                        this.selectedProducts = Array.from(document.querySelectorAll('.product-checkbox')).map(cb => parseInt(cb.value, 10));
                    } else {
                        this.selectedProducts = [];
                    }
                },

                submitBulkAction(action) {
                    this.bulkAction = action;
                    if (action === 'discount' && !this.discountPercent) {
                        alert('ÙŠØ±Ø¬Ù‰ Ø¥Ø¯Ø®Ø§Ù„ Ù†Ø³Ø¨Ø© Ø§Ù„Ø®ØµÙ… (0-100).');
                        return;
                    }
                    this.showDiscountModal = false;
                    setTimeout(() => {
                        document.getElementById('bulkForm').submit();
                    }, 50);
                }
            }));
        });
    </script>
</x-app-layout>

