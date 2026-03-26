<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->shop;
        if (!$shop) {
            return redirect()->route('dashboard');
        }

        $promoCodes = $shop->promoCodes()->latest()->get();
        return view('promo_codes.index', compact('promoCodes', 'shop'));
    }

    public function store(Request $request)
    {
        $shop = auth()->user()->shop;

        $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code,NULL,id,shop_id,' . $shop->id,
            'discount_percentage' => 'required|numeric|min:0.01|max:100',
        ]);

        $shop->promoCodes()->create([
            'code' => strtoupper($request->code),
            'discount_percentage' => $request->discount_percentage,
            'is_active' => true,
        ]);

        return redirect()->route('promo-codes.index')->with('success', 'تم إضافة كود الخصم بنجاح!');
    }

    public function destroy(PromoCode $promoCode)
    {
        if ($promoCode->shop_id !== auth()->user()->shop->id) {
            abort(403);
        }

        $promoCode->delete();

        return redirect()->route('promo-codes.index')->with('success', 'تم حذف كود الخصم بنجاح!');
    }
    public function toggle(PromoCode $promoCode)
    {
        if ($promoCode->shop_id !== auth()->user()->shop->id) {
            abort(403);
        }

        $promoCode->update(['is_active' => !$promoCode->is_active]);

        return back()->with('success', 'تم تحديث حالة كود الخصم!');
    }
}
