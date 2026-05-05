@extends('layout')

@section('title', $product->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
    <li class="breadcrumb-item"><a href="{{ route('category.show', $product->category->slug) }}">{{ $product->category->name }}</a></li>
    <li class="breadcrumb-item active">{{ $product->name }}</li>
@endsection

@section('content')
<div class="container-fluid py-5 px-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-5 mb-4">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">
            @else
                <img src="{{ asset('img/product-' . (($product->id % 18) + 1) . '.png') }}" class="img-fluid" alt="{{ $product->name }}">
            @endif
        </div>

        <!-- Product Details -->
        <div class="col-lg-7">
            <h1 class="mb-3">{{ $product->name }}</h1>

            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="h3 text-primary mb-0">${{ number_format($product->price, 2) }}</span>
                @if($product->original_price)
                    <span class="h5 text-decoration-line-through text-muted mb-0">${{ number_format($product->original_price, 2) }}</span>
                @endif
                <span class="badge bg-info">{{ $product->quantity }} in stock</span>
            </div>

            <p class="mb-4">{{ $product->description }}</p>

            <!-- Product Variant Selector (if product has variants) -->
            @if($product->is_variable && $product->variants->count() > 0)
                <livewire:product-variant-selector :product="$product" />
            @endif

            <!-- Add to Cart Form -->
            <form method="POST" action="{{ route('cart.add') }}" class="mb-4" id="add-to-cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                @if($product->is_variable && $product->variants->count() > 0)
                    <input type="hidden" name="variant_id" id="variant_id" value="">
                @endif
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="{{ $product->quantity }}" required>
                    </div>
                    <div class="col-md-8">
                        @if($product->quantity > 0)
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary btn-lg w-100" disabled>
                                Out of Stock
                            </button>
                        @endif
                    </div>
                </div>
            </form>

            @if($product->name === 'Wireless Headphones Pro')
                <div class="mb-4">
                    <blockonomics-pay-button
                      uid="f08fdbe86b204569"
                      label="Pay with Crypto">
                    </blockonomics-pay-button>
                    <div class="mt-3">
                        <a href="{{ route('payment.create') }}" class="btn btn-success w-100">
                            Open Crypto QR Payment
                        </a>
                        <small class="text-muted d-block mt-2">If the embedded Blockonomics button does not render, use this fallback to open your payment QR page.</small>
                    </div>
                </div>
            @endif

            <!-- Product Info -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h6>Category</h6>
                    <a href="{{ route('category.show', $product->category->slug) }}" class="text-decoration-none">
                        {{ $product->category->name }}
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <h6>Availability</h6>
                    @if($product->quantity > 0)
                        <span class="badge bg-success">In Stock</span>
                    @else
                        <span class="badge bg-danger">Out of Stock</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">Related Products</h3>
            </div>
            @foreach($relatedProducts as $related)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100">
                        @if($related->image)
                            <img src="{{ asset('storage/' . $related->image) }}" class="card-img-top" alt="{{ $related->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('img/product-' . (($loop->iteration % 18) + 1) . '.png') }}" class="card-img-top" alt="{{ $related->name }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $related->name }}</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 text-primary">${{ number_format($related->price, 2) }}</span>
                                <small class="badge bg-info">{{ $related->quantity }} left</small>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="{{ route('product.show', $related->slug) }}" class="btn btn-sm btn-outline-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
    @if($product->name === 'Wireless Headphones Pro')
        <script src="https://www.blockonomics.co/js/pay_button.js"></script>
    @endif
    
    @if($product->is_variable && $product->variants->count() > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const weightSelect = document.getElementById('weight-select');
                const variantIdInput = document.getElementById('variant_id');
                
                // Listen for Livewire component updates
                window.addEventListener('livewire:update', function(event) {
                    if (weightSelect && weightSelect.value) {
                        variantIdInput.value = weightSelect.value;
                    }
                });
                
                // Also listen for direct select changes
                if (weightSelect) {
                    weightSelect.addEventListener('change', function() {
                        variantIdInput.value = this.value;
                    });
                    
                    // Set initial value
                    if (this.value) {
                        variantIdInput.value = this.value;
                    }
                }
                
                // Validate variant selection before form submit
                const form = document.getElementById('add-to-cart-form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        @if($product->is_variable)
                            if (!variantIdInput.value) {
                                e.preventDefault();
                                alert('Please select a weight/variant before adding to cart');
                                return false;
                            }
                        @endif
                    });
                }
            });
        </script>
    @endif
@endpush
