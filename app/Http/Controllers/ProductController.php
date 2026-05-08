<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = $this->filteredProducts()->paginate(12)->withQueryString();
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
        $products = $this->filteredProducts($category)->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('shop', compact('products', 'categories', 'category'));
    }

    private function filteredProducts(?Category $category = null): Builder
    {
        $query = Product::query()
            ->where('is_active', true);

        if ($category !== null) {
            $query->where('category_id', $category->id);
        }

        $search = trim((string) request('search', ''));
        if ($search !== '') {
            $query->where(function (Builder $subQuery) use ($search): void {
                $subQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $priceMin = request()->filled('price_min') ? (float) request('price_min') : null;
        $priceMax = request()->filled('price_max') ? (float) request('price_max') : null;

        if ($priceMin !== null) {
            $query->where('price', '>=', $priceMin);
        }

        if ($priceMax !== null) {
            $query->where('price', '<=', $priceMax);
        }

        $sort = request('sort');

        if ($sort === 'price-asc') {
            $query->orderBy('price');
        } elseif ($sort === 'price-desc') {
            $query->orderByDesc('price');
        } elseif ($sort === 'latest') {
            $query->latest();
        } else {
            $query->latest('id');
        }

        return $query;
    }
}
