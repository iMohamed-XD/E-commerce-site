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
    :class="{ 'z-[120]': open }"
    x-data="{
        open: false,
        value: @js($initialValue),
        label: @js($initialLabel),
        options: @js($normalizedOptions),
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
                if (form) {
                    form.submit();
                }
            }
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
        class="flex h-full w-full items-center justify-between rounded-xl border border-[#0d1b4b]/15 bg-white px-4 text-sm font-medium leading-none text-[#0d1b4b]/70 shadow-sm transition hover:bg-[#fdfbf4] hover:text-[#0d1b4b] focus:outline-none focus:ring-2 focus:ring-[#d4af37]/40"
        style="height: 100%;"
        @click="open = !open"
        :aria-expanded="open.toString()"
    >
        <span class="min-w-0 flex-1 truncate text-right" x-text="label"></span>
        <svg class="ms-2 h-4 w-4 shrink-0 text-[#0d1b4b]/45" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute start-0 z-[9999] mt-2 w-full rounded-md shadow-xl"
        style="display: none;"
    >
        <div class="rounded-md border border-[#0d1b4b]/10 bg-white py-1 ring-1 ring-black/5">
            <template x-for="option in options" :key="option.value + '-' + option.label">
                <button
                    type="button"
                    class="block w-full px-4 py-2 text-right text-sm leading-5 transition"
                    :class="option.value === value
                        ? 'bg-[#0d1b4b]/7 text-[#0d1b4b] font-semibold'
                        : 'text-[#0d1b4b]/70 hover:bg-[#0d1b4b]/6 hover:text-[#0d1b4b]'"
                    @click="choose(option)"
                    x-text="option.label"
                ></button>
            </template>
        </div>
    </div>
</div>

