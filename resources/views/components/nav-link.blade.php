@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#d4af37] text-sm font-black leading-5 text-[#0d1b4b] focus:outline-none focus:border-[#b8922a] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-bold leading-5 text-[#0d1b4b]/55 hover:text-[#0d1b4b] hover:border-[#d4af37]/50 focus:outline-none focus:text-[#0d1b4b] focus:border-[#d4af37] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
