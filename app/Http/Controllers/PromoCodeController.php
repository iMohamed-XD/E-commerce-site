<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PromoCodeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shop = $user->shop;
        if (!$shop) {
            return redirect()->route('dashboard');
        }

        $promoCodes = $shop->promoCodes()->latest()->get()->map(function (PromoCode $promoCode) {
            return [
                'id' => $promoCode->id,
                'code' => $promoCode->code,
                'discountPercentage' => (float) $promoCode->discount_percentage,
                'isActive' => (bool) $promoCode->is_active,
                'toggleUrl' => route('promo-codes.toggle', $promoCode),
                'destroyUrl' => route('promo-codes.destroy', $promoCode),
            ];
        })->values()->all();

        return Inertia::render('PromoCodes/Index', [
            'promoCodes' => $promoCodes,
            'shop' => [
                'name' => $shop->name,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $shop = Auth::user()->shop;

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
        Gate::authorize('manage', $promoCode);

        $promoCode->delete();

        return redirect()->route('promo-codes.index')->with('success', 'تم حذف كود الخصم بنجاح!');
    }

    public function toggle(PromoCode $promoCode)
    {
        Gate::authorize('manage', $promoCode);

        $promoCode->update(['is_active' => !$promoCode->is_active]);

        return back()->with('success', 'تم تحديث حالة كود الخصم!');
    }
}
