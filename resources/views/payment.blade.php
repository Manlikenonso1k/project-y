@extends('layout')

@section('title', 'Pay with Bitcoin')

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
                        <div class="mb-4">
                            <img src="{{ $qrCodeUrl }}" alt="Bitcoin QR Code" class="img-fluid border rounded p-2 bg-white" style="max-width: 300px;">
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label fw-semibold">Bitcoin Address</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $address }}" readonly>
                            </div>
                        </div>

                        <p class="small text-muted mb-4">Send BTC to this one-time address from your wallet. No KYC step is required on your checkout flow to generate the address.</p>
                    @endif

                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">Back to Home</a>
                        <a href="{{ route('checkout.show') }}" class="btn btn-primary">Back to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
