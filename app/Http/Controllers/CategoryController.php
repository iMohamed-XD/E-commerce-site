<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shop = Auth::user()->shop;
        if (!$shop) {
            return redirect()->route('dashboard');
        }

        $categories = $shop->categories()->withCount('products')->latest()->get();
        return view('categories.index', compact('categories', 'shop'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $shop = Auth::user()->shop;
        if (!$shop) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $shop->categories()->create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'تم إضافة التصنيف بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Add policy check
        if ($category->shop_id !== Auth::user()->shop->id) {
            abort(403);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'تم حذف التصنيف بنجاح!');
    }
}
