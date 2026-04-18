<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::where('is_active', true)
            ->paginate(12);
        $categories = Category::all();
        
        return view('shop', compact('products', 'categories'));
    }

    public function show(Product $product): View
    {
        $product->increment('views_count');
        $product->refresh();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('single', compact('product', 'relatedProducts'));
    }

    public function byCategory(Category $category): View
    {
        $products = $category->products()
            ->where('is_active', true)
            ->paginate(12);
        $categories = Category::all();

        return view('shop', compact('products', 'categories', 'category'));
    }
}
