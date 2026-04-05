<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminFeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with('user.shop')->latest();

        if ($request->has('rating') && $request->rating >= 1 && $request->rating <= 5) {
            $query->where('rating', $request->rating);
        }

        $feedbacks = $query->paginate(20)->withQueryString();
        
        $averageRating = Feedback::avg('rating') ?? 0;
        $totalFeedbacks = Feedback::count();

        return view('admin.feedbacks.index', compact('feedbacks', 'averageRating', 'totalFeedbacks'));
    }
}
