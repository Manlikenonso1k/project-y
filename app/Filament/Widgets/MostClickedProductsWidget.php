<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\Widget;

class MostClickedProductsWidget extends Widget
{
    protected static ?int $sort = 5;

    protected string $view = 'filament.widgets.most-clicked-products-widget';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'products' => Product::query()
                ->with('category:id,name')
                ->orderByDesc('views_count')
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get(),
        ];
    }
}