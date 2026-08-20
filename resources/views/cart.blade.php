@extends('layout')

@section('title', 'Shopping Cart')

@section('breadcrumb')
    <li class="breadcrumb-item active">Shopping Cart</li>
@endsection

@section('content')
<div class="container-fluid py-5 px-5">
    <div class="row">
        <div class="col-lg-8">
            <h2 class="mb-4">Shopping Cart</h2>

            @if($items->isEmpty())
                <div class="alert alert-info">
                    <p class="mb-0">Your cart is empty. <a href="{{ route('shop.index') }}">Continue shopping</a></p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($item->product->primary_image_url)
                                                <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('img/product-' . (($loop->iteration % 18) + 1) . '.png') }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <a href="{{ route('product.show', $item->product->slug) }}" class="text-decoration-none">
                                                    {{ $item->product->name }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('cart.update', $item) }}" class="d-flex align-items-center gap-2">
                                            @csrf
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm" style="width: 60px;">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                        </form>
                                    </td>
                                    <td>${{ number_format($item->getTotal(), 2) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('cart.remove', $item) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-end mb-4">
                    <form method="POST" action="{{ route('cart.clear') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">Clear Cart</button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Cart Summary -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    @if($items->isNotEmpty())
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal:</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax (estimated):</span>
                            <span>${{ number_format($total * 0.1, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping:</span>
                            <span>$10.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0">Total:</h5>
                            <h5 class="mb-0 text-primary">${{ number_format($total + ($total * 0.1) + 10, 2) }}</h5>
                        </div>
                        <a href="{{ route('checkout.show') }}" class="btn btn-primary w-100">Proceed to Checkout</a>
                    @else
                        <p class="text-muted text-center mb-0">Your cart is empty</p>
                    @endif
                </div>
            </div>

            <!-- Continue Shopping -->
            <div class="mt-3">
                <a href="{{ route('shop.index') }}" class="btn btn-outline-primary w-100">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>
@endsection
