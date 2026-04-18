<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\Widget;

class BestCategoriesWidget extends Widget
{
    protected static ?int $sort = 4;

    protected string $view = 'filament.widgets.best-categories-widget';

    protected int | string | array $columnSpan = 1;

    protected function getViewData(): array
    {
        return [
            'categories' => $this->getCategories(),
        ];
    }

    private function getCategories(): array
    {
        return Category::query()
            ->with(['products.orderItems'])
            ->get()
            ->map(function (Category $category): array {
                $unitsSold = $category->products->sum(
                    fn ($product): int => $product->orderItems->sum('quantity'),
                );

                $revenue = $category->products->sum(
                    fn ($product): float => (float) $product->orderItems->sum('total'),
                );

                return [
                    'name' => $category->name,
                    'products_count' => $category->products->count(),
                    'units_sold' => $unitsSold,
                    'revenue' => $revenue,
                ];
            })
            ->sortByDesc('units_sold')
            ->take(8)
            ->values()
            ->all();
    }
}