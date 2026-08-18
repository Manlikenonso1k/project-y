@extends('layout')

@section('title', $product->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
    <li class="breadcrumb-item"><a href="{{ route('category.show', $product->category->slug) }}">{{ $product->category->name }}</a></li>
    <li class="breadcrumb-item active">{{ $product->name }}</li>
@endsection

@section('content')
@php
    $galleryImages = $product->gallery_image_urls;
    $initialPrice = $product->is_variable && $product->variants->count() > 0
        ? $product->variants->first()->price
        : $product->price;
    $initialOriginalPrice = $product->original_price;
    $initialStock = $product->is_variable && $product->variants->count() > 0
        ? $product->variants->first()->stock
        : $product->quantity;
    $initialDiscount = $initialOriginalPrice && $initialOriginalPrice > $initialPrice
        ? $initialOriginalPrice - $initialPrice
        : null;
    $initialDiscountPercent = $initialOriginalPrice && $initialOriginalPrice > $initialPrice && $initialOriginalPrice > 0
        ? round((($initialOriginalPrice - $initialPrice) / $initialOriginalPrice) * 100)
        : null;
@endphp

<div class="container-fluid py-5 px-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-5 mb-4">
            @if(count($galleryImages) > 1)
                <div id="productGallery" class="carousel slide">
                    <div class="carousel-indicators">
                        @foreach($galleryImages as $index => $imageUrl)
                            <button type="button" data-bs-target="#productGallery" data-bs-slide-to="{{ $index }}" class="{{ $loop->first ? 'active' : '' }}" @if($loop->first) aria-current="true" @endif aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner rounded-4 overflow-hidden bg-white shadow-sm">
                        @foreach($galleryImages as $imageUrl)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <img src="{{ $imageUrl }}" class="d-block w-100" alt="{{ $product->name }}" style="max-height: 520px; object-fit: contain; background: #fff;">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productGallery" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productGallery" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    @foreach($galleryImages as $imageUrl)
                        <img src="{{ $imageUrl }}" class="rounded border" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover;">
                    @endforeach
                </div>
            @elseif($product->primary_image_url)
                <img src="{{ $product->primary_image_url }}" class="img-fluid rounded-4 shadow-sm" alt="{{ $product->name }}">
            @else
                <img src="{{ asset('img/product-' . (($product->id % 18) + 1) . '.png') }}" class="img-fluid" alt="{{ $product->name }}">
            @endif
        </div>

        <!-- Product Details -->
        <div class="col-lg-7">
            <h1 class="mb-3">{{ $product->name }}</h1>

            <div class="d-flex align-items-center gap-3 mb-4 flex-wrap" id="product-pricing-block"
                 data-price="{{ $initialPrice }}"
                 data-original-price="{{ $initialOriginalPrice ?? '' }}"
                 data-stock="{{ $initialStock }}">
                <span class="h3 text-primary mb-0" id="product-price">${{ number_format($initialPrice, 2) }}</span>
                <span class="h5 text-decoration-line-through text-muted mb-0 {{ $initialOriginalPrice && $initialOriginalPrice > $initialPrice ? '' : 'd-none' }}" id="product-original-price">
                    @if($initialOriginalPrice && $initialOriginalPrice > $initialPrice)
                        ${{ number_format($initialOriginalPrice, 2) }}
                    @endif
                </span>
                <span class="badge bg-success {{ $initialDiscount ? '' : 'd-none' }}" id="product-discount-badge">
                    @if($initialDiscountPercent)
                        Save {{ $initialDiscountPercent }}% (${{ number_format($initialDiscount, 2) }})
                    @endif
                </span>
                <span class="badge bg-info" id="product-stock">{{ $initialStock }} in stock</span>
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

            <!-- Product Specs -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <h6>Year</h6>
                    @if($product->year)
                        {{ $product->year }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <h6>Manufacturer</h6>
                    @if($product->manufacturer)
                        {{ $product->manufacturer }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <h6>Horsepower</h6>
                    @if($product->horsepower)
                        {{ $product->horsepower }} HP
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <h6>Subcategory</h6>
                    @if($product->subcategory)
                        {{ $product->subcategory }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <h6>Mileage</h6>
                    @if($product->mileage)
                        {{ number_format($product->mileage) }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <h6>GVW</h6>
                    @if($product->gvw)
                        {{ $product->gvw }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <h6>Store</h6>
                    @if($product->store)
                        {{ $product->store }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <h6>Engine</h6>
                    @if($product->engine)
                        {{ $product->engine }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <h6>Transmission</h6>
                    @if($product->transmission)
                        {{ $product->transmission }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <h6>Extra Description</h6>
                    @if($product->extra_description)
                        <p class="mb-0">{{ $product->extra_description }}</p>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </div>
            </div>

            @if($product->url)
            <div class="row mb-4">
                <div class="col-md-12">
                    <h6>Original Listing</h6>
                    <a href="{{ $product->url }}" target="_blank" rel="noopener" class="text-decoration-none">
                        View original listing
                    </a>
                </div>
            </div>
            @endif

            @if($product->youtube_url)
            <div class="row mb-4">
                <div class="col-md-12">
                    <h6>Video</h6>
                    <div class="ratio ratio-16x9">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ parse_url($product->youtube_url, PHP_URL_QUERY) }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            @endif

            <!-- Product Info -->
            <div class="row">
                <div class="col-md-6">
                    <h6>Category</h6>
                    <a href="{{ route('category.show', $product->category->slug) }}" class="text-decoration-none">
                        {{ $product->category->name }}
                    </a>
                </div>
                <div class="col-md-6">
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
                        @if($related->primary_image_url)
                            <img src="{{ $related->primary_image_url }}" class="card-img-top" alt="{{ $related->name }}" style="height: 200px; object-fit: cover;">
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
                const priceEl = document.getElementById('product-price');
                const originalPriceEl = document.getElementById('product-original-price');
                const discountBadgeEl = document.getElementById('product-discount-badge');
                const stockEl = document.getElementById('product-stock');
                const pricingBlock = document.getElementById('product-pricing-block');

                function updatePricingDisplay(detail) {
                    if (!detail) {
                        return;
                    }

                    const price = Number(detail.price ?? 0);
                    const originalPrice = detail.originalPrice !== null && detail.originalPrice !== undefined && detail.originalPrice !== ''
                        ? Number(detail.originalPrice)
                        : null;
                    const stock = detail.stock ?? 0;

                    if (priceEl) {
                        priceEl.textContent = `$${price.toFixed(2)}`;
                    }

                    if (stockEl) {
                        stockEl.textContent = `${stock} in stock`;
                    }

                    if (pricingBlock) {
                        pricingBlock.dataset.price = price;
                        pricingBlock.dataset.stock = stock;
                    }

                    if (originalPriceEl && originalPrice !== null && originalPrice > price) {
                        originalPriceEl.textContent = `$${originalPrice.toFixed(2)}`;
                        originalPriceEl.classList.remove('d-none');
                    } else if (originalPriceEl) {
                        originalPriceEl.classList.add('d-none');
                        originalPriceEl.textContent = '';
                    }

                    if (discountBadgeEl && originalPrice !== null && originalPrice > price) {
                        const discount = originalPrice - price;
                        const percent = originalPrice > 0 ? Math.round((discount / originalPrice) * 100) : 0;
                        discountBadgeEl.textContent = `Save ${percent}% ($${discount.toFixed(2)})`;
                        discountBadgeEl.classList.remove('d-none');
                    } else if (discountBadgeEl) {
                        discountBadgeEl.classList.add('d-none');
                        discountBadgeEl.textContent = '';
                    }
                }

                window.addEventListener('product-pricing-updated', function(event) {
                    updatePricingDisplay(event.detail);
                    if (variantIdInput && event.detail && event.detail.variantId) {
                        variantIdInput.value = event.detail.variantId;
                    }
                });
                
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

                    if (variantIdInput && weightSelect.value) {
                        variantIdInput.value = weightSelect.value;
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
