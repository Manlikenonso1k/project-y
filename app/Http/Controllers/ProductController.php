<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $shopQuery = Product::query()->where('is_active', true)
            ->when(request()->filled('search'), fn (Builder $q) => $q->where('name', 'like', '%'.request('search').'%'))
            ->when(request()->filled('year'), fn (Builder $q) => $q->where('year', request('year')))
            ->when(request()->filled('manufacturer'), fn (Builder $q) => $q->where('manufacturer', request('manufacturer')))
            ->when(request()->filled('mileage'), function (Builder $q): void {
                $mileage = (string) request('mileage');

                if ($mileage === 'na') {
                    $q->whereNull('mileage');

                    return;
                }

                $parts = explode('-', $mileage);
                if (count($parts) === 2) {
                    $q->whereBetween('mileage', [(int) $parts[0], (int) $parts[1]]);
                }
            })
            ->when(request()->filled('horsepower'), function (Builder $q): void {
                $hp = (string) request('horsepower');

                if ($hp === 'na') {
                    $q->whereNull('horsepower');

                    return;
                }

                $parts = explode('-', $hp);
                if (count($parts) === 2) {
                    $q->whereBetween('horsepower', [(int) $parts[0], (int) $parts[1]]);
                }
            });

        $products = (clone $shopQuery)
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $productCountBySubcategory = Product::query()
            ->where('is_active', true)
            ->whereNotNull('subcategory')
            ->select('subcategory', DB::raw('count(*) as total'))
            ->groupBy('subcategory')
            ->orderBy('subcategory')
            ->get()
            ->mapWithKeys(fn ($row): array => [$row->subcategory => (int) $row->total])
            ->all();

        $productCountByYear = Product::query()
            ->where('is_active', true)
            ->whereNotNull('year')
            ->select('year', DB::raw('count(*) as total'))
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->mapWithKeys(fn ($row): array => [$row->year => (int) $row->total])
            ->all();

        $productCountByMileage = [];
        $mileageRanges = [
            'na' => 'N/A',
            '0-99999' => '0–99,999',
            '100000-199999' => '100,000–199,999',
            '200000-299999' => '200,000–299,999',
            '300000-399999' => '300,000–399,999',
            '400000-499999' => '400,000–499,999',
            '500000-599999' => '500,000–599,999',
            '600000-699999' => '600,000–699,999',
            '700000-799999' => '700,000–799,999',
            '800000-899999' => '800,000–899,999',
            '900000-999999' => '900,000–999,999',
        ];
        $mileageCounts = Product::query()
            ->where('is_active', true)
            ->selectRaw('SUM(CASE WHEN mileage IS NULL THEN 1 ELSE 0 END) as na')
            ->selectRaw('SUM(CASE WHEN mileage BETWEEN 0 AND 99999 THEN 1 ELSE 0 END) as range_0_99999')
            ->selectRaw('SUM(CASE WHEN mileage BETWEEN 100000 AND 199999 THEN 1 ELSE 0 END) as range_100000_199999')
            ->selectRaw('SUM(CASE WHEN mileage BETWEEN 200000 AND 299999 THEN 1 ELSE 0 END) as range_200000_299999')
            ->selectRaw('SUM(CASE WHEN mileage BETWEEN 300000 AND 399999 THEN 1 ELSE 0 END) as range_300000_399999')
            ->selectRaw('SUM(CASE WHEN mileage BETWEEN 400000 AND 499999 THEN 1 ELSE 0 END) as range_400000_499999')
            ->selectRaw('SUM(CASE WHEN mileage BETWEEN 500000 AND 599999 THEN 1 ELSE 0 END) as range_500000_599999')
            ->selectRaw('SUM(CASE WHEN mileage BETWEEN 600000 AND 699999 THEN 1 ELSE 0 END) as range_600000_699999')
            ->selectRaw('SUM(CASE WHEN mileage BETWEEN 700000 AND 799999 THEN 1 ELSE 0 END) as range_700000_799999')
            ->selectRaw('SUM(CASE WHEN mileage BETWEEN 800000 AND 899999 THEN 1 ELSE 0 END) as range_800000_899999')
            ->selectRaw('SUM(CASE WHEN mileage BETWEEN 900000 AND 999999 THEN 1 ELSE 0 END) as range_900000_999999')
            ->first();

        foreach ($mileageRanges as $key => $label) {
            $column = $key === 'na' ? 'na' : 'range_'.str_replace('-', '_', $key);
            $productCountByMileage[$key] = (int) ($mileageCounts?->{$column} ?? 0);
        }

        $productCountByHorsepower = [];
        $hpRanges = [
            'na' => 'N/A',
            '250-299' => '250–299',
            '300-349' => '300–349',
            '350-399' => '350–399',
            '400-449' => '400–449',
            '450-499' => '450–499',
            '500+' => '500+',
        ];
        $horsepowerCounts = Product::query()
            ->where('is_active', true)
            ->selectRaw('SUM(CASE WHEN horsepower IS NULL THEN 1 ELSE 0 END) as na')
            ->selectRaw('SUM(CASE WHEN horsepower BETWEEN 250 AND 299 THEN 1 ELSE 0 END) as range_250_299')
            ->selectRaw('SUM(CASE WHEN horsepower BETWEEN 300 AND 349 THEN 1 ELSE 0 END) as range_300_349')
            ->selectRaw('SUM(CASE WHEN horsepower BETWEEN 350 AND 399 THEN 1 ELSE 0 END) as range_350_399')
            ->selectRaw('SUM(CASE WHEN horsepower BETWEEN 400 AND 449 THEN 1 ELSE 0 END) as range_400_449')
            ->selectRaw('SUM(CASE WHEN horsepower BETWEEN 450 AND 499 THEN 1 ELSE 0 END) as range_450_499')
            ->selectRaw('SUM(CASE WHEN horsepower >= 500 THEN 1 ELSE 0 END) as range_500_plus')
            ->first();

        foreach ($hpRanges as $key => $label) {
            $column = match ($key) {
                'na' => 'na',
                '500+' => 'range_500_plus',
                default => 'range_'.str_replace('-', '_', $key),
            };

            $productCountByHorsepower[$key] = (int) ($horsepowerCounts?->{$column} ?? 0);
        }

        return view('shop', compact('products', 'productCountBySubcategory', 'productCountByYear', 'productCountByMileage', 'productCountByHorsepower'));
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
        $products = Product::where('is_active', true)
            ->where('category_id', $category->id)
            ->when(request()->filled('search'), fn ($q) => $q->where('name', 'like', '%'.request('search').'%'))
            ->when(request()->filled('year'), fn ($q) => $q->where('year', request('year')))
            ->when(request()->filled('manufacturer'), fn ($q) => $q->where('manufacturer', request('manufacturer')))
            ->when(request()->filled('mileage'), fn ($q) => $q->where(function ($query) {
                $mileage = request('mileage');
                if ($mileage === 'na') {
                    $query->whereNull('mileage');
                } else {
                    $parts = explode('-', $mileage);
                    if (count($parts) === 2) {
                        $query->whereBetween('mileage', $parts);
                    }
                }
            }))
            ->when(request()->filled('horsepower'), fn ($q) => $q->where(function ($query) {
                $hp = request('horsepower');
                if ($hp === 'na') {
                    $query->whereNull('horsepower');
                } else {
                    $parts = explode('-', $hp);
                    if (count($parts) === 2) {
                        $query->whereBetween('horsepower', $parts);
                    }
                }
            }))
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('shop', compact('category', 'products'));
    }
}
