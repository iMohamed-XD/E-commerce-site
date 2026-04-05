@props([
    'name' => 'color',
    'selected' => 'navy',
])

@php
    $colors = config('shop_colors');
@endphp

<div>
    <x-input-label :for="$name" :value="__('لون المتجر')" />
    <p class="mt-1 text-xs text-[#0d1b4b]/45">اختر اللون الأساسي الذي سيشاهده الزبائن في واجهة متجرك.</p>

    <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-3">
        @foreach($colors as $key => $colorInfo)
            <label for="{{ $name }}_{{ $key }}"
                   class="group relative block rounded-2xl border border-[#0d1b4b]/12 bg-white p-3 cursor-pointer hover:border-[#d4af37]/50 transition-all">
                <input id="{{ $name }}_{{ $key }}"
                       type="radio"
                       name="{{ $name }}"
                       value="{{ $key }}"
                       class="sr-only peer"
                       {{ $selected === $key ? 'checked' : '' }}>

                <div class="absolute top-2 left-2 w-4 h-4 rounded-full border-2 border-[#0d1b4b]/25 peer-checked:border-[#d4af37] peer-checked:bg-[#d4af37] transition-all"></div>

                <div class="h-10 rounded-xl border border-[#0d1b4b]/10 overflow-hidden mt-1 shadow-inner" style="background-color: {{ $colorInfo['hex'] }};">
                </div>

                <div class="mt-2 text-center">
                    <p class="text-xs font-black text-[#0d1b4b]">{{ $colorInfo['label'] }}</p>
                </div>
            </label>
        @endforeach
    </div>

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
