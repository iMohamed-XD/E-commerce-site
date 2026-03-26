<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->shop;
        if (!$shop) {
            return redirect()->route('dashboard');
        }

        $orders = $shop->orders()->with('items.product')->latest()->get();
        return view('orders.index', compact('orders', 'shop'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->shop_id !== auth()->user()->shop->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('orders.index')->with('success', 'تم تحديث حالة الطلب بنجاح!');
    }
}
