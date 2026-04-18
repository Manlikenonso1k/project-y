<x-filament-widgets::widget>
    <x-filament::section heading="Orders by Customer" description="Customers ranked by order count">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm dark:divide-gray-800">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 font-medium">Customer</th>
                        <th class="px-4 py-3 font-medium">Orders</th>
                        <th class="px-4 py-3 font-medium">Spent</th>
                        <th class="px-4 py-3 font-medium">Last Order</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($customers as $customer)
                        <tr class="bg-white dark:bg-gray-950">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-950 dark:text-white">{{ $customer['name'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $customer['email'] }}</div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-950 dark:text-white">{{ number_format($customer['orders_count']) }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${{ number_format($customer['total_spent'], 2) }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $customer['last_order_at'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                No orders yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>