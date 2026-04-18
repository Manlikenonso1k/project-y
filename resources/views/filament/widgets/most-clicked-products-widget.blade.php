<x-filament-widgets::widget>
    <x-filament::section heading="Most Clicked Products" description="Products ranked by product page views">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm dark:divide-gray-800">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 font-medium">Product</th>
                        <th class="px-4 py-3 font-medium">Category</th>
                        <th class="px-4 py-3 font-medium">Clicks</th>
                        <th class="px-4 py-3 font-medium">Updated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($products as $product)
                        <tr class="bg-white dark:bg-gray-950">
                            <td class="px-4 py-3 font-medium text-gray-950 dark:text-white">{{ $product->name }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $product->category?->name ?? 'Uncategorized' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ number_format($product->views_count) }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $product->updated_at?->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                No product clicks yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>