@extends('layout')

@section('title', 'Pay with Bitcoin')

@if(empty($errorMessage) && isset($paymentStatus) && $paymentStatus !== 'paid')
    @push('styles')
        <meta http-equiv="refresh" content="30">
    @endpush
@endif

@section('breadcrumb')
    <li class="breadcrumb-item active">Bitcoin Payment</li>
@endsection

@section('content')
<div class="container-fluid py-5 px-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5 text-center">
                    <h2 class="mb-3">Pay with Bitcoin</h2>
                    <p class="text-muted mb-4">Scan the QR code or copy the address below to complete your payment.</p>

                    @if($errorMessage)
                        <div class="alert alert-danger text-start" role="alert">
                            {{ $errorMessage }}
                        </div>
                    @else
                        @if(isset($order))
                            <div class="alert alert-light border text-start mb-4">
                                <p class="mb-1"><strong>Order:</strong> {{ $order->order_number }}</p>
                                <p class="mb-1"><strong>Amount (BTC):</strong> {{ number_format((float) $expectedBtc, 8) }}</p>
                                <p class="mb-0"><strong>Payment Status:</strong> <span class="badge bg-secondary">{{ str_replace('_', ' ', ucfirst($paymentStatus)) }}</span></p>
                            </div>
                        @endif

                        <div class="mb-4">
                            <img src="{{ $qrCodeUrl }}" alt="Bitcoin QR Code" class="img-fluid border rounded p-2 bg-white" style="max-width: 300px;">
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label fw-semibold">Bitcoin Address</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $address }}" readonly>
                            </div>
                        </div>

                        <p class="small text-muted mb-2">Send the exact BTC amount to this one-time order address.</p>
                        <p class="small text-muted mb-4">This page refreshes every 30 seconds until payment is confirmed. You can also refresh manually below.</p>
                    @endif

                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        @if(isset($order))
                            <a href="{{ route('payment.order', $order) }}" class="btn btn-outline-secondary">Refresh Status</a>
                        @endif
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">Back to Home</a>
                        <a href="{{ route('checkout.show') }}" class="btn btn-primary">Back to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
