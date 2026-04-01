<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-red-500 border border-red-500/90 rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400/60 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
