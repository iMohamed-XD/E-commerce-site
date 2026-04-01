@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#d4af37] text-start text-base font-bold text-[#0d1b4b] bg-[#d4af37]/10 focus:outline-none focus:text-[#0d1b4b] focus:bg-[#d4af37]/15 focus:border-[#b8922a] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[#0d1b4b]/60 hover:text-[#0d1b4b] hover:bg-[#0d1b4b]/5 hover:border-[#0d1b4b]/20 focus:outline-none focus:text-[#0d1b4b] focus:bg-[#0d1b4b]/5 focus:border-[#0d1b4b]/25 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
