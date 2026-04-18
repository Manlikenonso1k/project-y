<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class OrdersByCustomerWidget extends Widget
{
    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.orders-by-customer-widget';

    protected int | string | array $columnSpan = 1;

    protected function getViewData(): array
    {
        return [
            'customers' => $this->getCustomers(),
        ];
    }

    private function getCustomers(): array
    {
        $orders = Order::query()
            ->with('user:id,name,email')
            ->get([
                'id',
                'user_id',
                'first_name',
                'last_name',
                'email',
                'total',
                'created_at',
            ]);

        return $orders
            ->groupBy(fn (Order $order): string => $order->user_id
                ? 'user:' . $order->user_id
                : 'guest:' . $order->email)
            ->map(function ($customerOrders): array {
                /** @var Order $firstOrder */
                $firstOrder = $customerOrders->first();
                $displayName = $firstOrder->user?->name
                    ?? trim($firstOrder->first_name . ' ' . $firstOrder->last_name)
                    ?: $firstOrder->email;

                return [
                    'name' => $displayName,
                    'email' => $firstOrder->user?->email ?? $firstOrder->email,
                    'orders_count' => $customerOrders->count(),
                    'total_spent' => (float) $customerOrders->sum('total'),
                    'last_order_at' => Carbon::parse($customerOrders->max('created_at'))->format('M d, Y'),
                ];
            })
            ->sortByDesc('orders_count')
            ->take(8)
            ->values()
            ->all();
    }
}