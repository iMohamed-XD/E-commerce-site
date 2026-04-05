<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ادعم محلي | طرق التبرع المتاحة</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
        
        <style>
            body { font-family: 'Tajawal', sans-serif !important; }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-[#0d1b4b] antialiased">
        
        <!-- Header -->
        <div class="bg-white border-b border-[#0d1b4b]/10 shadow-sm relative overflow-hidden">
            <div class="absolute inset-0 bg-[#0d1b4b]/5 pointer-events-none"></div>
            <div class="max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8 text-center relative z-10">
                <a href="/" class="inline-block mb-6">
                    <x-application-logo class="h-16 w-auto" />
                </a>
                <h1 class="text-4xl md:text-5xl font-black text-[#0d1b4b] mb-4">ادعم منصة محلي</h1>
                <p class="text-lg text-[#0d1b4b]/60 max-w-2xl mx-auto">
                    منصة محلي هي منصة مجانية 100% تهدف لدعم المشاريع الصغيرة. يمكنك مساعدتنا في استمرار وتطوير المنصة من خلال التبرع عبر طرق الدفع المتاحة أدناه.
                </p>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            @if($paymentMethods->isEmpty())
                <div class="text-center py-20 bg-white rounded-[2rem] border border-[#0d1b4b]/10 shadow-sm">
                    <p class="text-xl font-bold text-[#0d1b4b]/50">لا توجد طرق دفع متاحة حالياً.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($paymentMethods as $pm)
                        <div class="bg-white rounded-[2rem] border border-[#0d1b4b]/10 shadow-sm hover:shadow-xl transition-shadow p-8 flex flex-col items-center text-center">
                            @if($pm->logo_path)
                                <img src="{{ Storage::url($pm->logo_path) }}" class="h-16 object-contain mb-6" alt="{{ $pm->name }}">
                            @else
                                <div class="h-16 w-16 bg-[#0d1b4b]/5 rounded-full flex items-center justify-center mb-6">
                                    <svg class="w-8 h-8 text-[#0d1b4b]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            @endif

                            <h3 class="text-2xl font-black mb-2">{{ $pm->name }}</h3>
                            <div class="bg-[#fdfbf4] border border-[#d4af37]/30 px-6 py-3 rounded-xl mb-6 inline-block">
                                <p class="text-sm font-medium text-[#0d1b4b]/60 mb-1">رقم الحساب / المستفيد</p>
                                <p class="font-bold text-lg" dir="ltr">{{ $pm->account_id }}</p>
                            </div>

                            @if($pm->details)
                                <p class="text-sm text-[#0d1b4b]/60 mb-6">{{ $pm->details }}</p>
                            @endif

                            @if($pm->link)
                                <a href="{{ $pm->link }}" target="_blank" class="w-full mb-6 inline-flex items-center justify-center gap-2 px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-green-500/20 active:scale-95">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    @if(str_contains(strtolower($pm->link), 'wa.me') || str_contains(strtolower($pm->link), 'whatsapp'))
                                        <span>فتح في واتساب</span>
                                    @else
                                        <span>افتح الرابط المباشر</span>
                                    @endif
                                </a>
                            @endif

                            @if($pm->qr_path)
                                <div class="mt-auto">
                                    <p class="text-xs font-bold text-[#0d1b4b]/40 mb-3 uppercase tracking-widest">امسح الكود للدفع</p>
                                    <img src="{{ Storage::url($pm->qr_path) }}" class="w-32 h-32 object-contain mx-auto border p-1 rounded-xl shadow-inner" alt="QR Code">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-[#0d1b4b]/10 py-8 text-center text-[#0d1b4b]/60 text-sm">
            <p>نشكركم على دعمكم المستمر لمنصة محلي.</p>
        </footer>

    </body>
</html>
