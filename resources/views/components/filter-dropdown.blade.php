{{-- components/filter-dropdown --}}
@props([
    'id',
    'name' => null,
    'value' => '',
    'options' => [],
    'placeholder' => 'اختر',
    'autoSubmit' => false,
])

@php
    $normalizedOptions = collect($options)->map(function ($option) {
        $rawValue = is_array($option) ? ($option['value'] ?? '') : $option;
        $rawLabel = is_array($option) ? ($option['label'] ?? $rawValue) : $option;

        return [
            'value' => (string) $rawValue,
            'label' => (string) $rawLabel,
        ];
    })->values()->all();

    $initialValue = (string) ($value ?? '');
    $initialLabel = collect($normalizedOptions)->firstWhere('value', $initialValue)['label'] ?? ($initialValue !== '' ? $initialValue : $placeholder);
@endphp

<div
    id="{{ $id }}-root"
    class="relative h-12"
    style="height: 3rem;"
    x-data="{
        open: false,
        value: @js($initialValue),
        label: @js($initialLabel),
        options: @js($normalizedOptions),
        highlight: 0,
        choose(option) {
            this.value = option.value;
            this.label = option.label;
            this.open = false;

            this.$dispatch('filter-dropdown-change', {
                id: @js($id),
                name: @js($name),
                value: this.value,
                label: this.label,
            });

            if (@js((bool) $autoSubmit)) {
                const form = this.$el.closest('form');
                if (form) form.submit();
            }
        },
        openMenu() {
            this.open = true;
            const idx = this.options.findIndex(o => o.value === this.value);
            this.highlight = idx >= 0 ? idx : 0;
        },
        move(step) {
            if (!this.open) this.openMenu();
            if (!this.options.length) return;
            this.highlight = (this.highlight + step + this.options.length) % this.options.length;
        },
        chooseHighlighted() {
            if (!this.options.length) return;
            this.choose(this.options[this.highlight]);
        }
    }"
    @click.outside="open = false"
    @keydown.escape.window="open = false"
>
    @if($name)
        <input id="{{ $id }}" type="hidden" name="{{ $name }}" x-model="value">
    @else
        <input id="{{ $id }}" type="hidden" x-model="value">
    @endif

    <button
        type="button"
        class="flex h-full w-full items-center justify-between rounded-xl border border-[#0d1b4b]/15 bg-white px-4 text-sm font-semibold text-[#0d1b4b]/80 shadow-sm transition hover:border-[#0d1b4b]/25 hover:bg-[#fdfbf4] focus:border-[#d4af37]/60 focus:outline-none focus:ring-2 focus:ring-[#d4af37]/30"
        @click="open ? open = false : openMenu()"
        @keydown.arrow-down.prevent="move(1)"
        @keydown.arrow-up.prevent="move(-1)"
        @keydown.enter.prevent="open ? chooseHighlighted() : openMenu()"
        :aria-expanded="open.toString()"
    >
        <span class="min-w-0 flex-1 truncate text-right" :class="value ? 'text-[#0d1b4b]' : 'text-[#7f88a8]'" x-text="label"></span>
        <svg class="ms-2 h-4 w-4 shrink-0 text-[#0d1b4b]/45 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        class="absolute start-0 z-[9999] mt-2 w-full"
        style="display:none;"
    >
        <div class="max-h-64 overflow-auto rounded-xl border border-[#0d1b4b]/10 bg-white py-1 shadow-2xl shadow-[#0d1b4b]/15 ring-1 ring-[#0d1b4b]/5">
            <template x-if="!options.length">
                <div class="px-4 py-2 text-right text-sm text-[#0d1b4b]/45">لا توجد خيارات</div>
            </template>

            <template x-for="(option, idx) in options" :key="option.value + '-' + option.label">
                <button
                    type="button"
                    class="block w-full px-4 py-2.5 text-right text-sm transition"
                    :class="option.value === value
                        ? 'bg-[#d4af37]/10 text-[#0d1b4b] font-bold'
                        : (idx === highlight
                            ? 'bg-[#0d1b4b]/6 text-[#0d1b4b] font-semibold'
                            : 'text-[#0d1b4b]/75 hover:bg-[#0d1b4b]/6 hover:text-[#0d1b4b]')"
                    @mouseenter="highlight = idx"
                    @click="choose(option)"
                    x-text="option.label"
                ></button>
            </template>
        </div>
    </div>
</div>
