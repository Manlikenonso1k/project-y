@extends('layout')

@section('title', 'Shop')

@section('breadcrumb')
    <li class="breadcrumb-item active">Shop</li>
@endsection

@section('content')
<div class="container-fluid py-5 px-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <h5 class="mb-4">Filter Products</h5>

            <!-- Category Filter -->
            <div class="mb-4">
                <h6>Categories</h6>
                @forelse($categories ?? [] as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}" class="d-flex justify-content-between text-decoration-none mb-2">
                        <span>{{ $cat->name }}</span>
                        <span class="text-muted">{{ $cat->products->count() }}</span>
                    </a>
                @empty
                    <p class="text-muted">No categories</p>
                @endforelse
            </div>

            <!-- Price Filter -->
            <div class="mb-4">
                <h6>Price Range</h6>
                <p class="text-muted small mb-0">Use sorting and categories to browse quickly.</p>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h4>{{ $category->name ?? 'All Products' }}</h4>
                </div>
                <div class="col-md-6 text-md-end">
                    <form method="GET" action="{{ route('shop.index') }}" class="d-flex gap-2">
                        <select name="sort" class="form-select form-select-sm">
                            <option value="">Sort By</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                    </form>
                </div>
            </div>

            <div class="row">
                @forelse($products as $product)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if($product->primary_image_url)
                                <img src="{{ $product->primary_image_url }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="{{ asset('img/product-' . (($loop->iteration % 18) + 1) . '.png') }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                            @endif

                            @if($product->is_featured)
                                <span class="badge bg-danger position-absolute" style="top: 10px; right: 10px;">Featured</span>
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text text-muted small">{{ Str::limit($product->description, 50) }}</p>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <span class="h5 mb-0 text-primary">${{ number_format($product->price, 2) }}</span>
                                        @if($product->original_price)
                                            <small class="text-decoration-line-through text-muted ms-2">${{ number_format($product->original_price, 2) }}</small>
                                        @endif
                                    </div>
                                    <small class="badge bg-info">{{ $product->quantity }} in stock</small>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                                </div>
                            </div>

                            <div class="card-footer bg-white">
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->quantity }}">
                                        <button class="btn btn-primary" type="submit">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">No products found.</div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
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
@endsection
