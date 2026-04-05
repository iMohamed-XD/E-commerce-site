<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $sellersCount = User::where('role', 'seller')->count();
        $shopsCount = Shop::count();
        $productsCount = Product::count();
        
        $feedbacksCount = Feedback::count();
        $averageRating = Feedback::avg('rating') ?? 0;

        $recentFeedbacks = Feedback::with('user.shop')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'sellersCount', 'shopsCount', 'productsCount',
            'feedbacksCount', 'averageRating', 'recentFeedbacks'
        ));
    }
}
