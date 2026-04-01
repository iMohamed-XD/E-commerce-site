@props([
    'name' => 'theme',
    'selected' => \App\Models\Shop::DEFAULT_THEME,
])

@php
    $themes = \App\Models\Shop::themePresets();
@endphp

<div>
    <x-input-label :for="$name" :value="__('نمط ألوان المتجر')" />
    <p class="mt-1 text-xs text-[#0d1b4b]/45">اختر النمط الذي سيشاهده الزبائن في واجهة متجرك.</p>

    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($themes as $key => $theme)
            <label for="{{ $name }}_{{ $key }}"
                   class="group relative block rounded-2xl border border-[#0d1b4b]/12 bg-white p-3 cursor-pointer hover:border-[#d4af37]/50 transition-all">
                <input id="{{ $name }}_{{ $key }}"
                       type="radio"
                       name="{{ $name }}"
                       value="{{ $key }}"
                       class="sr-only peer"
                       {{ $selected === $key ? 'checked' : '' }}>

                <div class="absolute top-3 left-3 w-4 h-4 rounded-full border-2 border-[#0d1b4b]/25 peer-checked:border-[#d4af37] peer-checked:bg-[#d4af37] transition-all"></div>

                <div class="h-14 rounded-xl border border-[#0d1b4b]/10 overflow-hidden flex">
                    <div class="w-1/2" style="background-color: {{ $theme['primary'] }};"></div>
                    <div class="w-1/2" style="background-color: {{ $theme['accent_soft'] }};"></div>
                </div>

                <div class="mt-2">
                    <p class="text-sm font-black text-[#0d1b4b]">{{ $theme['label'] }}</p>
                    <p class="text-xs text-[#0d1b4b]/45">{{ $theme['description'] }}</p>
                </div>
            </label>
        @endforeach
    </div>

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
