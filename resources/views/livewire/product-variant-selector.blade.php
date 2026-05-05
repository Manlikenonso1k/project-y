<div class="variant-selector">
    @if ($product->is_variable)
        <div class="form-group mb-4">
            <label for="weight-select" class="form-label fw-bold">Select Weight:</label>
            <select id="weight-select" class="form-select" wire:change="selectVariant($event.target.value)">
                <option value="">-- Choose a weight --</option>
                @foreach ($product->variants as $variant)
                    <option value="{{ $variant->id }}" @selected($selectedVariant == $variant->id)>
                        {{ $variant->weight }}{{ $variant->unit }} - ${{ number_format($variant->price, 2) }}
                        @if ($variant->stock > 0)
                            ({{ $variant->stock }} in stock)
                        @else
                            (Out of stock)
                        @endif
                    </option>
                @endforeach
            </select>
        </div>

        <div class="price-display mb-3">
            <h4 class="text-primary">
                Price: <span class="fw-bold">${{ number_format($selectedPrice, 2) }}</span>
            </h4>
            @if($selectedOriginalPrice && $selectedOriginalPrice > $selectedPrice)
                <div class="text-muted">
                    Original: <span class="text-decoration-line-through">${{ number_format($selectedOriginalPrice, 2) }}</span>
                </div>
            @endif
            <div class="badge bg-info mt-2">{{ $selectedStock }} in stock</div>
        </div>
    @else
        <div class="price-display mb-3">
            <h4 class="text-primary">
                Price: <span class="fw-bold">${{ number_format($selectedPrice, 2) }}</span>
            </h4>
            @if($selectedOriginalPrice && $selectedOriginalPrice > $selectedPrice)
                <div class="text-muted">
                    Original: <span class="text-decoration-line-through">${{ number_format($selectedOriginalPrice, 2) }}</span>
                </div>
            @endif
            <div class="badge bg-info mt-2">{{ $selectedStock }} in stock</div>
        </div>
    @endif
</div>
