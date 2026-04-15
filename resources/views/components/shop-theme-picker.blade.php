@props([
    'name' => 'color',
    'selected' => 'navy',
])

@php
    $colors = config('shop_colors');
@endphp

<div>
    <style>
        .shop-theme-card {
            transition:
                transform 220ms ease,
                box-shadow 260ms ease,
                border-color 220ms ease,
                background-color 220ms ease;
        }

        .shop-theme-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(13, 27, 75, 0.10);
        }

        .shop-theme-body {
            border-radius: 1rem;
            border: 1px solid rgba(13, 27, 75, 0.12);
            background: #fff;
            padding: 0.75rem;
            transition:
                border-color 240ms ease,
                box-shadow 260ms ease,
                background-color 220ms ease;
        }

        .shop-theme-ring {
            position: absolute;
            inset: 0;
            pointer-events: none;
            border-radius: 1rem;
            opacity: 0;
            transition:
                opacity 220ms ease,
                transform 220ms ease,
                box-shadow 260ms ease;
            transform: scale(0.985);
        }

        .shop-theme-input:checked ~ .shop-theme-ring {
            opacity: 1;
            transform: scale(1);
            box-shadow:
                0 0 0 2px rgba(212, 175, 55, 0.34),
                0 10px 24px rgba(212, 175, 55, 0.22);
        }

        .shop-theme-chip {
            transition:
                transform 280ms cubic-bezier(0.22, 1, 0.36, 1),
                filter 240ms ease;
        }

        .shop-theme-card:hover .shop-theme-chip {
            transform: scale(1.03);
            filter: saturate(1.06);
        }

    </style>

    <x-input-label :for="$name" :value="__('لون المتجر')" />
    <p class="mt-1 text-xs text-[#0d1b4b]/45">اختر اللون الأساسي الذي سيشاهده الزبائن في واجهة متجرك.</p>

    <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-5">
        @foreach($colors as $key => $colorInfo)
            <label
                for="{{ $name }}_{{ $key }}"
                class="shop-theme-card group relative block cursor-pointer rounded-2xl bg-white"
            >
                <input
                    id="{{ $name }}_{{ $key }}"
                    type="radio"
                    name="{{ $name }}"
                    value="{{ $key }}"
                    class="shop-theme-input sr-only peer"
                    {{ $selected === $key ? 'checked' : '' }}
                >

                <div class="shop-theme-body">
                    <div
                        class="shop-theme-chip mt-1 h-10 overflow-hidden rounded-xl border border-[#0d1b4b]/10 shadow-inner"
                        style="background-color: {{ $colorInfo['hex'] }};"
                    ></div>

                    <div class="mt-2 text-center">
                        <p class="text-xs font-black text-[#0d1b4b]">{{ $colorInfo['label'] }}</p>
                    </div>
                </div>

                <span class="shop-theme-ring" aria-hidden="true"></span>
            </label>
        @endforeach
    </div>

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
