{{-- legal/attributions --}}
<x-guest-layout>
    <x-attributions-page
        title='Attributions'
        :sections="[
            ['title' => 'Shop icon',
            'link'=> 'https://www.flaticon.com/free-icons/shop',
            'content' => 'Shop icons created by Yudhi Restu - Flaticon'],
            ['title' => 'Cart icon',
            'link'=> 'https://www.flaticon.com/free-icons/cart',
            'content' => 'Cart icons created by Freepik - Flaticon'],
            ['title'=>'lirascope.syria-cloud.sy',
            'link' => 'https://lirascope.syria-cloud.sy/en/api-docs',
            'content' => 'USD/SYP market exchange-rate data provided by LiraScope (Syria Cloud).'],
            ]"
        cta-text="العودة للتسجيل"
        cta-link="{{ route('register') }}"
    />
</x-guest-layout>
