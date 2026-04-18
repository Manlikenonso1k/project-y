<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $products = Product::where('is_featured', true)
            ->where('is_active', true)
            ->limit(8)
            ->get();
        
        $categories = Category::withCount('products')->get();
        
        return view('index', compact('products', 'categories'));
    }

    public function contact(): View
    {
        return view('contact');
    }

    public function about(): View
    {
        return view('about');
    }

    public function bestSeller(): View
    {
        $products = Product::where('is_active', true)
            ->orderBy('id')
            ->limit(12)
            ->get();
        
        return view('bestseller', compact('products'));
    }
}
