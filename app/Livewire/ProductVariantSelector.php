<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductVariantSelector extends Component
{
    public Product $product;
    public $selectedVariant = null;
    public $selectedPrice = null;

    public function mount(Product $product)
    {
        $this->product = $product;
        if ($product->is_variable && $product->variants->count() > 0) {
            $this->selectedVariant = $product->variants->first()->id;
            $this->selectedPrice = $product->variants->first()->price;
        } else {
            $this->selectedPrice = $product->price;
        }
    }

    public function selectVariant($variantId)
    {
        $variant = $this->product->variants()->find($variantId);
        if ($variant) {
            $this->selectedVariant = $variantId;
            $this->selectedPrice = $variant->price;
        }
    }

    public function render()
    {
        return view('livewire.product-variant-selector');
    }
}
