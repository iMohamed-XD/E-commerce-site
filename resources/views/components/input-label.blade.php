@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-bold text-[#0d1b4b]/70']) }}>
    {{ $value ?? $slot }}
</label>
