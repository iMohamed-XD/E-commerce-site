<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function show()
    {
        $feedback = auth()->user()->feedback;
        return view('dashboard.feedback', compact('feedback'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:20|max:2000',
        ]);

        if (auth()->user()->feedback()->exists()) {
            return back()->withErrors(['لقد أرسلت رأيك مسبقاً']);
        }

        auth()->user()->feedback()->create($request->only('rating', 'content'));

        return redirect()->route('feedback.show')->with('success', 'تم إرسال رأيك بنجاح!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:20|max:2000',
        ]);

        $feedback = auth()->user()->feedback;
        if (!$feedback) {
            abort(404);
        }

        $feedback->update($request->only('rating', 'content'));

        return redirect()->route('feedback.show')->with('success', 'تم تحديث رأيك بنجاح!');
    }
}
