@extends('layout')

@section('title', 'Best Sellers')

@section('breadcrumb')
    <li class="breadcrumb-item active">Best Sellers</li>
@endsection

@section('content')
<div class="container-fluid py-5 px-5">
    <h2 class="mb-4">Best Selling Products</h2>
    <p class="lead text-muted mb-5">Check out our most popular products that customers love!</p>

    <div class="row">
        @forelse($products ?? [] as $product)
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <img src="{{ asset('img/product-' . (($loop->iteration % 18) + 1) . '.png') }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    @endif

                    <span class="badge bg-success position-absolute" style="top: 10px; right: 10px;">Best Seller</span>

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
                                <button class="btn btn-primary" type="submit">Add to Cart</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No best sellers available yet. Check back soon!</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
