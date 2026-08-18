<div>
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Filter Products</strong>
                    @if($activeCount > 0)
                        <span class="badge bg-primary">{{ $activeCount }} active</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($activeCount > 0)
                        <button class="btn btn-outline-danger btn-sm w-100 mb-3" wire:click="clearFilters">
                            Clear All
                        </button>
                    @endif

                    <div class="mb-4">
                        <h6 class="mb-2">Search</h6>
                        <input
                            type="search"
                            wire:model.live.debounce.300ms="search"
                            class="form-control form-control-sm"
                            placeholder="Search make, model, item…"
                        >
                    </div>

                    @if($activeCategory)
                        <a href="{{ route('shop.index') }}" class="d-block small mb-3">
                            &larr; All Products
                        </a>
                    @endif

                    <div class="mb-4">
                        <h6 class="mb-2">Subcategory</h6>
                        @forelse($facets['subcategories'] as $value => $count)
                            <label class="d-block small mb-1">
                                <input type="checkbox" wire:model.live="subcategories" value="{{ $value }}" class="me-1">
                                {{ $value }} ({{ $count }})
                            </label>
                        @empty
                            <p class="text-muted small mb-0">No subcategories.</p>
                        @endforelse
                    </div>

                    <div class="mb-4">
                        <h6 class="mb-2">Year</h6>
                        @forelse($facets['years'] as $value => $count)
                            <label class="d-block small mb-1">
                                <input type="checkbox" wire:model.live="years" value="{{ $value }}" class="me-1">
                                {{ $value }} ({{ $count }})
                            </label>
                        @empty
                            <p class="text-muted small mb-0">No years.</p>
                        @endforelse
                    </div>

                    <div class="mb-4">
                        <h6 class="mb-2">Mileage</h6>
                        @foreach($facets['mileages'] as $value => $label)
                            <label class="d-block small mb-1">
                                <input type="checkbox" wire:model.live="mileages" value="{{ $value }}" class="me-1">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    <div class="mb-4">
                        <h6 class="mb-2">Horsepower</h6>
                        @foreach($facets['horsespowers'] as $value => $label)
                            <label class="d-block small mb-1">
                                <input type="checkbox" wire:model.live="horsespowers" value="{{ $value }}" class="me-1">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>

                    <div class="mb-4">
                        <h6 class="mb-2">Price</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <input
                                    type="number"
                                    wire:model.live.debounce.500ms="priceMin"
                                    class="form-control form-control-sm"
                                    placeholder="Min"
                                    min="0"
                                >
                            </div>
                            <div class="col-6">
                                <input
                                    type="number"
                                    wire:model.live.debounce.500ms="priceMax"
                                    class="form-control form-control-sm"
                                    placeholder="Max"
                                    min="0"
                                >
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="row mb-4 align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0">{{ $activeCategory->name ?? 'All Products' }}</h4>
                    <span class="text-muted small">{{ $products->total() }} truck{{ $products->total() === 1 ? '' : 's' }}</span>
                </div>
                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    <select wire:model.live="sort" class="form-select form-select-sm d-inline-block w-auto">
                        <option value="latest">Latest</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                    </select>
                </div>
            </div>

            <div class="row">
                @forelse($products as $product)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm position-relative">
                            <img
                                src="{{ $product->primary_image_url }}"
                                class="card-img-top"
                                alt="{{ $product->name }}"
                                style="height: 200px; object-fit: cover;"
                            >

                            @if($product->is_featured)
                                <span class="badge bg-danger position-absolute" style="top: 10px; right: 10px;">Featured</span>
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>

                                <p class="mb-2">
                                    @if($product->year)
                                        <span class="badge bg-secondary">{{ $product->year }}</span>
                                    @endif
                                    @if($product->mileage)
                                        <span class="badge bg-light text-dark border">{{ number_format($product->mileage) }} mi</span>
                                    @endif
                                    @if($product->horsepower)
                                        <span class="badge bg-light text-dark border">{{ $product->horsepower }} HP</span>
                                    @endif
                                </p>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 mb-0 text-primary">${{ number_format($product->price, 2) }}</span>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>

                            <div class="card-footer bg-white">
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="quantity" class="form-control" value="1" min="1">
                                        <button class="btn btn-primary" type="submit">Add to Cart</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">No trucks found matching your filters.</div>
                    </div>
                @endforelse
            </div>

            @if($products->hasPages())
                <div class="row mt-4">
                    <div class="col-12">
                        {{ $products->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

        </div>
