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
                            <span class="text-danger">(Out of stock)</span>
                        @endif
                    </option>
                @endforeach
            </select>
        </div>

        <div class="price-display mb-3">
            <h4 class="text-primary">
                Price: <span class="fw-bold">${{ number_format($selectedPrice, 2) }}</span>
            </h4>
        </div>
    @else
        <div class="price-display mb-3">
            <h4 class="text-primary">
                Price: <span class="fw-bold">${{ number_format($selectedPrice, 2) }}</span>
            </h4>
        </div>
    @endif
</div>
