<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-[#0d1b4b]/15 rounded-xl font-bold text-xs text-[#0d1b4b]/70 uppercase tracking-widest shadow-sm hover:border-[#0d1b4b]/25 hover:bg-[#fdfbf4] hover:text-[#0d1b4b] focus:outline-none focus:ring-2 focus:ring-[#d4af37]/35 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
