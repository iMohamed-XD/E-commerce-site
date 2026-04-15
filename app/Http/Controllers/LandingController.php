<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class LandingController extends Controller
{
    public function index(): InertiaResponse|\Illuminate\Http\RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $sellersCount = User::where('role', 'seller')->count();
        $ordersCount = Order::whereIn('status', ['done', 'completed'])->count();
        $avgRating = Feedback::avg('rating') ?: 5.0;

        return Inertia::render('Landing', compact('sellersCount', 'ordersCount', 'avgRating'));
    }
}
