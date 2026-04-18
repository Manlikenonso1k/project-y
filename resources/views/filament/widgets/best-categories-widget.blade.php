<x-filament-widgets::widget>
    <x-filament::section heading="Best Categories" description="Categories ranked by sold units">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm dark:divide-gray-800">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 font-medium">Category</th>
                        <th class="px-4 py-3 font-medium">Products</th>
                        <th class="px-4 py-3 font-medium">Units Sold</th>
                        <th class="px-4 py-3 font-medium">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($categories as $category)
                        <tr class="bg-white dark:bg-gray-950">
                            <td class="px-4 py-3 font-medium text-gray-950 dark:text-white">{{ $category['name'] }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ number_format($category['products_count']) }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ number_format($category['units_sold']) }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${{ number_format($category['revenue'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                No categories with sales yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>