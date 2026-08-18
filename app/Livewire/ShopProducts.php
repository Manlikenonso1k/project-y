<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;

class ShopProducts extends Component
{
    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $sort = 'latest';

    #[Url(history: true)]
    public ?float $priceMin = null;

    #[Url(history: true)]
    public ?float $priceMax = null;

    /** @var array<int, string> */
    #[Url(history: true)]
    public array $subcategories = [];

    /** @var array<int, int> */
    #[Url(history: true)]
    public array $years = [];

    /** @var array<int, string> */
    #[Url(history: true)]
    public array $mileages = [];

    /** @var array<int, string> */
    #[Url(history: true)]
    public array $horsespowers = [];

    public ?int $categoryId = null;

    public function mount(?Category $category = null): void
    {
        if ($category) {
            $this->categoryId = $category->id;
        }
    }

    public function clearFilters(): void
    {
        $this->reset('subcategories', 'years', 'mileages', 'horsespowers', 'priceMin', 'priceMax');
        $this->resetPage();
    }

    public function updated(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = $this->filteredQuery()->paginate(12);

        $facets = [
            'subcategories' => $this->subcategoryFacets(),
            'years' => $this->yearFacets(),
            'mileages' => $this->mileageFacets(),
            'horsespowers' => $this->horsepowerFacets(),
        ];

        $activeCount = count($this->subcategories)
            + count($this->years)
            + count($this->mileages)
            + count($this->horsespowers)
            + (filled($this->priceMin) ? 1 : 0)
            + (filled($this->priceMax) ? 1 : 0);

        return view('livewire.shop-products', [
            'products' => $products,
            'facets' => $facets,
            'activeCount' => $activeCount,
            'categories' => Category::all(),
            'activeCategory' => $this->categoryId ? Category::find($this->categoryId) : null,
        ]);
    }

    protected function filteredQuery(array $excludeFacet = []): Builder
    {
        $query = Product::query()->where('is_active', true);

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->search !== '') {
            $query->where(function (Builder $subQuery): void {
                $subQuery->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%')
                    ->orWhere('manufacturer', 'like', '%'.$this->search.'%')
                    ->orWhere('item_number', 'like', '%'.$this->search.'%');
            });
        }

        if (! in_array('subcategories', $excludeFacet, true) && $this->subcategories !== []) {
            $query->whereIn('subcategory', $this->subcategories);
        }

        if (! in_array('years', $excludeFacet, true) && $this->years !== []) {
            $query->whereIn('year', $this->years);
        }

        if (! in_array('mileages', $excludeFacet, true) && $this->mileages !== []) {
            $this->applyRanges($query, 'mileage', $this->mileages);
        }

        if (! in_array('horsespowers', $excludeFacet, true) && $this->horsespowers !== []) {
            $this->applyRanges($query, 'horsepower', $this->horsespowers);
        }

        if ($this->priceMin !== null) {
            $query->where('price', '>=', $this->priceMin);
        }

        if ($this->priceMax !== null) {
            $query->where('price', '<=', $this->priceMax);
        }

        return match ($this->sort) {
            'price-asc' => $query->orderBy('price'),
            'price-desc' => $query->orderByDesc('price'),
            'latest' => $query->latest(),
            default => $query->latest('id'),
        };
    }

    /**
     * @param  array<int, string>  $values
     */
    protected function applyRanges(Builder $query, string $column, array $values): void
    {
        $query->where(function (Builder $subQuery) use ($column, $values): void {
            foreach ($values as $value) {
                $subQuery->orWhere(function (Builder $rangeQuery) use ($column, $value): void {
                    if ($value === 'na') {
                        $rangeQuery->whereNull($column);

                        return;
                    }

                    $rangeQuery->whereBetween($column, explode('-', $value));
                });
            }
        });
    }

    /**
     * @return array<string, int>
     */
    protected function subcategoryFacets(): array
    {
        return $this->filteredQuery(['subcategories'])
            ->selectRaw('subcategory, count(*) as total')
            ->whereNotNull('subcategory')
            ->groupBy('subcategory')
            ->orderByDesc('total')
            ->pluck('total', 'subcategory')
            ->map(fn ($count): int => (int) $count)
            ->all();
    }

    /**
     * @return array<int, int>
     */
    protected function yearFacets(): array
    {
        return $this->filteredQuery(['years'])
            ->selectRaw('year, count(*) as total')
            ->whereNotNull('year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('total', 'year')
            ->map(fn ($count): int => (int) $count)
            ->all();
    }

    /**
     * @return array<string, string> range key => label (with count)
     */
    protected function mileageFacets(): array
    {
        $ranges = [
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

        return $this->rangeFacets('mileage', $ranges);
    }

    /**
     * @return array<string, string> range key => label (with count)
     */
    protected function horsepowerFacets(): array
    {
        $ranges = [
            'na' => 'N/A',
            '250-299' => '250–299',
            '300-349' => '300–349',
            '350-399' => '350–399',
            '400-449' => '400–449',
            '450-499' => '450–499',
            '500-999' => '500+',
        ];

        return $this->rangeFacets('horsepower', $ranges);
    }

    /**
     * @param  array<string, string>  $ranges
     * @return array<string, string>
     */
    protected function rangeFacets(string $column, array $ranges): array
    {
        $excludeFacet = $column === 'mileage' ? 'mileages' : 'horsespowers';

        $query = $this->filteredQuery([$excludeFacet]);

        $options = [];

        foreach ($ranges as $key => $label) {
            $countQuery = clone $query;

            if ($key === 'na') {
                $countQuery->whereNull($column);
            } else {
                $countQuery->whereBetween($column, explode('-', $key));
            }

            $options[$key] = $label.' ('.$countQuery->count().')';
        }

        return $options;
    }
}