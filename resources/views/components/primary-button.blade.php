{{-- components/primary-button --}}
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#0d1b4b] border border-[#0d1b4b] rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-[#1a2d6b] focus:bg-[#1a2d6b] active:bg-[#091338] focus:outline-none focus:ring-2 focus:ring-[#d4af37]/45 focus:ring-offset-2 focus:ring-offset-[#fdfbf4] shadow-md shadow-[#0d1b4b]/20 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
