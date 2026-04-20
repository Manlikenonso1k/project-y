@extends('layout')

@section('title', 'Order Success')

@section('breadcrumb')
    <li class="breadcrumb-item active">Order Success</li>
@endsection

@section('content')
<div class="container-fluid py-5 px-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 60px;"></i>
                    </div>

                    <h2 class="mb-3">Order Placed Successfully!</h2>
                    <p class="lead text-muted mb-4">Thank you for your purchase. Your order has been received and is being processed.</p>

                    <div class="alert alert-light border border-primary mb-4">
                        <p class="mb-2"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                        <p class="mb-0"><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y g:i A') }}</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 border-end">
                            <h6 class="mb-3">Shipping Address</h6>
                            <p class="mb-0">
                                {{ $order->first_name }} {{ $order->last_name }}<br>
                                {{ $order->address }}<br>
                                {{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}<br>
                                {{ $order->country }}<br>
                                {{ $order->email }}<br>
                                {{ $order->phone }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Order Status</h6>
                            <p class="mb-0">
                                <span class="badge bg-warning">{{ ucfirst($order->status) }}</span>
                            </p>
                            <p class="text-muted small mt-3">We'll send you a confirmation email with tracking information once your order ships.</p>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-4">
                        <h6 class="mb-3">Order Items</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="text-start">{{ $item->product->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                            <td>${{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Order Total -->
                    <div class="row justify-content-end mb-4">
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>${{ number_format($order->tax, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Shipping:</span>
                                <span>${{ number_format($order->shipping, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-0">Total:</h6>
                                <h6 class="mb-0 text-primary">${{ number_format($order->total, 2) }}</h6>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="btn-group" role="group">
                        <a href="{{ route('payment.order', $order) }}" class="btn btn-dark">Pay with Bitcoin</a>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">Back to Home</a>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
