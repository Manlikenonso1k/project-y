@extends('layout')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<div class="container-fluid py-5 bg-light">
    <div class="row align-items-center g-4 hero-panel">
        <div class="col-lg-6">
            <h1 class="display-5 mb-4">Welcome to Project x Shop</h1>
            <p class="lead mb-4">Discover amazing electronics at unbeatable prices. From smartphones to laptops, find everything you need.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-light btn-lg me-2">Shop Now</a>
            <a href="{{ route('bestseller') }}" class="btn btn-outline-light btn-lg">Best Sellers</a>
        </div>
        <div class="col-lg-6">
            <img src="{{ asset('img/header-img.jpg') }}" class="img-fluid hero-image" alt=" Project X Shop Hero">
        </div>
    </div>
</div>

<!-- Featured Products -->
<div class="container-fluid py-5 px-5">
    <h2 class="section-title">Featured Products</h2>
    <div class="row">
        @forelse($products ?? [] as $product)
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    @if($product->primary_image_url)
                        <img src="{{ $product->primary_image_url }}" class="card-img-top" alt="{{ $product->name }}">
                    @else
                        <img src="{{ asset('img/product-' . (($loop->iteration % 18) + 1) . '.png') }}" class="card-img-top" alt="{{ $product->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($product->description, 60) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0 text-primary">${{ number_format($product->price, 2) }}</span>
                            @if($product->original_price)
                                <small class="text-decoration-line-through text-muted">${{ number_format($product->original_price, 2) }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-primary">View</a>
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center">No featured products available.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Categories -->
<div class="container-fluid py-5 px-5 bg-light">
    <h2 class="section-title">Shop by Category</h2>
    <div class="row">
        @forelse($categories ?? [] as $category)
            <div class="col-md-4 mb-4">
                <a href="{{ route('category.show', $category->slug) }}" class="text-decoration-none">
                    <div class="card h-100 text-center">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" class="card-img-top" alt="{{ $category->name }}">
                        @else
                            <img src="{{ asset('img/product-banner.jpg') }}" class="card-img-top" alt="{{ $category->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text text-muted">{{ $category->products->count() }} products</p>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center">No categories available.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
