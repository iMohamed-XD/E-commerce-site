<x-guest-layout>
    <x-attributions-page
        title='Icon Attributions'
        :sections="[
            ['title' => 'Shop icon',
            'link'=> 'https://www.flaticon.com/free-icons/shop',
            'content' => 'Shop icons created by Yudhi Restu - Flaticon'],
            ['title' => 'Cart icon',
            'link'=> 'https://www.flaticon.com/free-icons/cart',
            'content' => 'Cart icons created by Freepik - Flaticon'],
            ]"
        cta-text="العودة للتسجيل"
        cta-link="{{ route('register') }}"
    />
</x-guest-layout>
