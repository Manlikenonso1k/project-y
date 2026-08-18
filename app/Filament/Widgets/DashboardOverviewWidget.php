<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $orders = Order::query()->with('user:id,name,email')->get([
            'id',
            'user_id',
            'first_name',
            'last_name',
            'email',
            'total',
            'created_at',
        ]);

        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total');
        $totalCustomers = $orders
            ->map(fn (Order $order): string => $order->user_id
                ? 'user:' . $order->user_id
                : 'guest:' . $order->email)
            ->unique()
            ->count();

        $bestCategory = $this->getBestCategorySummary();

        return [
            Stat::make('Total Orders', number_format($totalOrders))
                ->description('Unique customers: ' . number_format($totalCustomers))
                ->icon('heroicon-o-shopping-bag')
                ->color('primary'),

            Stat::make('Total Revenue', '$' . number_format((float) $totalRevenue, 2))
                ->description('All completed sales value')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Customers', number_format($totalCustomers))
                ->description('People who placed orders')
                ->icon('heroicon-o-users')
                ->color('info'),

            Stat::make('Best Category', $bestCategory['name'] ?? 'No sales yet')
                ->description(($bestCategory['units_sold'] ?? 0) . ' units sold')
                ->icon('heroicon-o-tag')
                ->color('warning'),
        ];
    }

    private function getBestCategorySummary(): ?array
    {
        // Single query with pre-computed sums to avoid N+1
        $categories = Category::query()
            ->with(['products.orderItems'])
            ->get()
            ->map(fn (Category $category): array => [
                'name' => $category->name,
                'units_sold' => $category->products->sum(fn ($product) =>
                    $product->orderItems->sum('quantity')
                ),
                'revenue' => $category->products->sum(fn ($product) =>
                    (float) $product->orderItems->sum('total')
                ),
            ]);

        return $categories->sortByDesc('units_sold')?->first();
    }
}