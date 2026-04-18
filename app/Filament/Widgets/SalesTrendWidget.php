<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class SalesTrendWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Sales Trend';

    protected ?string $description = 'Monthly revenue and order volume';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $startDate = now()->subMonths(11)->startOfMonth();
        $orders = Order::query()
            ->where('created_at', '>=', $startDate)
            ->get(['created_at', 'total']);

        $labels = [];
        $revenueSeries = [];
        $orderSeries = [];

        for ($index = 11; $index >= 0; $index--) {
            $month = now()->subMonths($index)->startOfMonth();
            $label = $month->format('M Y');
            $labels[] = $label;

            $monthlyOrders = $orders->filter(
                fn (Order $order): bool => Carbon::parse($order->created_at)->isSameMonth($month),
            );

            $revenueSeries[] = round((float) $monthlyOrders->sum('total'), 2);
            $orderSeries[] = $monthlyOrders->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenueSeries,
                    'borderColor' => 'rgb(245, 158, 11)',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.15)',
                    'fill' => false,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Orders',
                    'data' => $orderSeries,
                    'borderColor' => 'rgb(37, 99, 235)',
                    'backgroundColor' => 'rgba(37, 99, 235, 0.15)',
                    'fill' => false,
                    'tension' => 0.35,
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    protected function getMaxHeight(): ?string
    {
        return '24rem';
    }
}