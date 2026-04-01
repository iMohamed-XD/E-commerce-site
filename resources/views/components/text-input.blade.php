@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => '
            bg-white border border-[#0d1b4b]/15 text-[#0d1b4b]
            placeholder-[#0d1b4b]/30
            focus:border-[#d4af37] focus:ring-2 focus:ring-[#d4af37]/20
            rounded-xl shadow-sm
            py-2.5 px-4
            transition-all duration-200
            outline-none
        '
    ]) }}
>
