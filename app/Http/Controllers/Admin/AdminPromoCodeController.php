<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;

class AdminPromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::with('shop')
            ->latest()
            ->paginate(20);
            
        return view('admin.promo-codes.index', compact('promoCodes'));
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();
        return redirect()->route('admin.promo-codes.index')->with('success', 'تم حذف كود الخصم بنجاح!');
    }
}
