<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\FacetBucketService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly FacetBucketService $facetBucketService = new FacetBucketService,
    ) {}

    /**
     * Shop index — all products, with sidebar filters.
     */
    public function index(Request $request): View
    {
        return $this->buildShopView($request, category: null);
    }

    /**
     * Category page — products scoped to a main category, with sidebar filters.
     */
    public function byCategory(Request $request, Category $category): View
    {
        return $this->buildShopView($request, category: $category);
    }

    /**
     * Single product detail.
     */
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

    // ─────────────────────────────────────────────────────────
    //  Private: shared shop/category listing with faceted filters
    // ─────────────────────────────────────────────────────────

    private function buildShopView(Request $request, ?Category $category): View
    {
        // ── Collect active filter values from the query string ──────────

        $selectedSubcategories = (array) $request->input('subcategory', []);
        $selectedYears         = (array) $request->input('year', []);
        $selectedMileage       = (array) $request->input('mileage', []);
        $selectedHorsepower    = (array) $request->input('horsepower', []);
        $searchTerm            = $request->input('search');
        $sortBy                = $request->input('sort');

        // ── Base query: active products, optionally scoped to category ──

        $baseScope = Product::query()
            ->where('is_active', true)
            ->when($category, fn (Builder $q) => $q->where('category_id', $category->id))
            ->when(filled($searchTerm), fn (Builder $q) => $q->where('name', 'like', '%' . $searchTerm . '%'));

        // ── Build bucket definitions ONCE from the unfiltered base scope ──
        //    (bucket boundaries stay fixed regardless of other filters)

        $mileageBuckets    = $this->facetBucketService->buildBuckets('mileage', clone $baseScope);
        $horsepowerBuckets = $this->facetBucketService->buildBuckets('horsepower', clone $baseScope);

        // ── Full filter query (all filters applied) for the product grid ──

        $fullQuery = clone $baseScope;

        if (! empty($selectedSubcategories)) {
            $fullQuery->whereIn('subcategory', $selectedSubcategories);
        }
        if (! empty($selectedYears)) {
            $fullQuery->whereIn('year', $selectedYears);
        }
        if (! empty($selectedMileage)) {
            $this->facetBucketService->applyBucketFilter($fullQuery, 'mileage', $selectedMileage);
        }
        if (! empty($selectedHorsepower)) {
            $this->facetBucketService->applyBucketFilter($fullQuery, 'horsepower', $selectedHorsepower);
        }

        // ── Sorting ──

        $fullQuery = match ($sortBy) {
            'price-asc'  => $fullQuery->orderBy('price', 'asc'),
            'price-desc' => $fullQuery->orderBy('price', 'desc'),
            'latest'     => $fullQuery->orderBy('created_at', 'desc'),
            default      => $fullQuery->orderBy('created_at', 'desc'),
        };

        $products = $fullQuery->paginate(12)->withQueryString();

        // ── Facet counts (each facet's count excludes its OWN filter) ────

        // Subcategory counts: apply all filters EXCEPT subcategory
        $subcatCountQuery = clone $baseScope;
        if (! empty($selectedYears)) {
            $subcatCountQuery->whereIn('year', $selectedYears);
        }
        if (! empty($selectedMileage)) {
            $this->facetBucketService->applyBucketFilter($subcatCountQuery, 'mileage', $selectedMileage);
        }
        if (! empty($selectedHorsepower)) {
            $this->facetBucketService->applyBucketFilter($subcatCountQuery, 'horsepower', $selectedHorsepower);
        }
        $productCountBySubcategory = (clone $subcatCountQuery)
            ->whereNotNull('subcategory')
            ->select('subcategory', DB::raw('count(*) as total'))
            ->groupBy('subcategory')
            ->orderBy('subcategory')
            ->get()
            ->mapWithKeys(fn ($row): array => [$row->subcategory => (int) $row->total])
            ->all();

        // Year counts: apply all filters EXCEPT year
        $yearCountQuery = clone $baseScope;
        if (! empty($selectedSubcategories)) {
            $yearCountQuery->whereIn('subcategory', $selectedSubcategories);
        }
        if (! empty($selectedMileage)) {
            $this->facetBucketService->applyBucketFilter($yearCountQuery, 'mileage', $selectedMileage);
        }
        if (! empty($selectedHorsepower)) {
            $this->facetBucketService->applyBucketFilter($yearCountQuery, 'horsepower', $selectedHorsepower);
        }
        $productCountByYear = (clone $yearCountQuery)
            ->whereNotNull('year')
            ->select('year', DB::raw('count(*) as total'))
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->mapWithKeys(fn ($row): array => [$row->year => (int) $row->total])
            ->all();

        // Mileage counts: apply all filters EXCEPT mileage
        $mileageCountQuery = clone $baseScope;
        if (! empty($selectedSubcategories)) {
            $mileageCountQuery->whereIn('subcategory', $selectedSubcategories);
        }
        if (! empty($selectedYears)) {
            $mileageCountQuery->whereIn('year', $selectedYears);
        }
        if (! empty($selectedHorsepower)) {
            $this->facetBucketService->applyBucketFilter($mileageCountQuery, 'horsepower', $selectedHorsepower);
        }
        $productCountByMileage = $this->facetBucketService->countBuckets('mileage', $mileageBuckets, $mileageCountQuery);

        // Horsepower counts: apply all filters EXCEPT horsepower
        $hpCountQuery = clone $baseScope;
        if (! empty($selectedSubcategories)) {
            $hpCountQuery->whereIn('subcategory', $selectedSubcategories);
        }
        if (! empty($selectedYears)) {
            $hpCountQuery->whereIn('year', $selectedYears);
        }
        if (! empty($selectedMileage)) {
            $this->facetBucketService->applyBucketFilter($hpCountQuery, 'mileage', $selectedMileage);
        }
        $productCountByHorsepower = $this->facetBucketService->countBuckets('horsepower', $horsepowerBuckets, $hpCountQuery);

        return view('shop', compact(
            'products',
            'category',
            'productCountBySubcategory',
            'productCountByYear',
            'mileageBuckets',
            'productCountByMileage',
            'horsepowerBuckets',
            'productCountByHorsepower',
            'selectedSubcategories',
            'selectedYears',
            'selectedMileage',
            'selectedHorsepower',
        ));
    }
}
