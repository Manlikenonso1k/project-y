<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductVariantSelector extends Component
{
    public Product $product;
    public $selectedVariant = null;
    public $selectedPrice = null;
    public $selectedStock = null;
    public $selectedOriginalPrice = null;

    public function mount(Product $product)
    {
        $this->product = $product;
        if ($product->is_variable && $product->variants->count() > 0) {
            $variant = $product->variants->first();
            $this->selectedVariant = $variant->id;
            $this->selectedPrice = $variant->price;
            $this->selectedStock = $variant->stock;
            $this->selectedOriginalPrice = $product->original_price;
        } else {
            $this->selectedPrice = $product->price;
            $this->selectedStock = $product->quantity;
            $this->selectedOriginalPrice = $product->original_price;
        }

        $this->dispatchPricingUpdate();
    }

    public function selectVariant($variantId)
    {
        $variant = $this->product->variants()->find($variantId);
        if ($variant) {
            $this->selectedVariant = $variantId;
            $this->selectedPrice = $variant->price;
            $this->selectedStock = $variant->stock;
            $this->selectedOriginalPrice = $this->product->original_price;
            $this->dispatchPricingUpdate();
        }
    }

    protected function dispatchPricingUpdate(): void
    {
        $this->dispatch('product-pricing-updated',
            price: $this->selectedPrice,
            stock: $this->selectedStock,
            originalPrice: $this->selectedOriginalPrice,
            variantId: $this->selectedVariant,
        );
    }

    public function render()
    {
        return view('livewire.product-variant-selector');
    }
}
