<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $sellersCount = User::where('role', 'seller')->count();
        $ordersCount = Order::where('status', 'completed')->count();
        $avgRating = Feedback::avg('rating') ?: 5.0;

        return view('landing', compact('sellersCount', 'ordersCount', 'avgRating'));
    }
}
