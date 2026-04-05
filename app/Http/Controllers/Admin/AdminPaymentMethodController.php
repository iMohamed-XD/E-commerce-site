<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('sort_order')->get();
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'account_id' => 'required|string|max:255',
            'logo' => 'nullable|image|max:1024',
            'qr' => 'nullable|image|max:1024',
            'details' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->only('name', 'account_id', 'details');
        $data['sort_order'] = $request->sort_order ?? 0;
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('payment-methods', 'media');
        }

        if ($request->hasFile('qr')) {
            $data['qr_path'] = $request->file('qr')->store('payment-methods', 'media');
        }

        PaymentMethod::create($data);

        return redirect()->route('admin.payment-methods.index')->with('success', 'تم إضافة طريقة الدفع بنجاح');
    }

    public function update(Request $request, PaymentMethod $pm)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'account_id' => 'required|string|max:255',
            'logo' => 'nullable|image|max:1024',
            'qr' => 'nullable|image|max:1024',
            'details' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->only('name', 'account_id', 'details');
        $data['sort_order'] = $request->sort_order ?? 0;
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            if ($pm->logo_path) {
                Storage::disk('media')->delete($pm->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('payment-methods', 'media');
        }

        if ($request->hasFile('qr')) {
            if ($pm->qr_path) {
                Storage::disk('media')->delete($pm->qr_path);
            }
            $data['qr_path'] = $request->file('qr')->store('payment-methods', 'media');
        }

        $pm->update($data);

        return redirect()->route('admin.payment-methods.index')->with('success', 'تم تحديث طريقة الدفع بنجاح');
    }

    public function destroy(PaymentMethod $pm)
    {
        if ($pm->logo_path) {
            Storage::disk('media')->delete($pm->logo_path);
        }
        if ($pm->qr_path) {
            Storage::disk('media')->delete($pm->qr_path);
        }
        $pm->delete();

        return redirect()->route('admin.payment-methods.index')->with('success', 'تم حذف طريقة الدفع بنجاح');
    }
}
