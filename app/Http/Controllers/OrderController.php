<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        if (!$shop) {
            return redirect()->route('dashboard');
        }

        $orders = $shop->orders()->with('items.product')->latest()->get();
        return view('orders.index', compact('orders', 'shop'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        Gate::authorize('manage', $order);

        $request->validate([
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('orders.index')->with('success', 'تم تحديث حالة الطلب بنجاح!');
    }
}
