@extends('layout')

@section('title', 'About Us')

@section('breadcrumb')
    <li class="breadcrumb-item active">About Us</li>
@endsection

@section('content')
<div class="container-fluid py-5 px-5">
    <div class="row mb-5">
        <div class="col-lg-6">
            <img src="https://via.placeholder.com/500x400" class="img-fluid" alt="About Us">
        </div>
        <div class="col-lg-6 d-flex align-items-center">
            <div>
                <h2 class="mb-4">About Electro Shop</h2>
                <p class="lead mb-4">We are your trusted online electronics retailer with over 10 years of experience in providing quality products and exceptional customer service.</p>
                <p class="mb-4">Our mission is to make the latest technology accessible and affordable for everyone. We offer a wide range of electronics products including smartphones, laptops, accessories, and much more.</p>

                <h5 class="mb-3">Why Choose Us?</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check text-primary me-2"></i> Authentic Products</li>
                    <li class="mb-2"><i class="fas fa-check text-primary me-2"></i> Competitive Prices</li>
                    <li class="mb-2"><i class="fas fa-check text-primary me-2"></i> Fast Shipping</li>
                    <li class="mb-2"><i class="fas fa-check text-primary me-2"></i> Excellent Customer Support</li>
                    <li class="mb-2"><i class="fas fa-check text-primary me-2"></i> Secure Transactions</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="row text-center mb-5 bg-light py-5">
        <div class="col-md-4 mb-4">
            <h3 class="text-primary mb-2">50k+</h3>
            <p class="text-muted">Happy Customers</p>
        </div>
        <div class="col-md-4 mb-4">
            <h3 class="text-primary mb-2">1000+</h3>
            <p class="text-muted">Products Available</p>
        </div>
        <div class="col-md-4 mb-4">
            <h3 class="text-primary mb-2">10+</h3>
            <p class="text-muted">Years Experience</p>
        </div>
    </div>

    <!-- Team Section -->
    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="mb-4 text-center">Our Team</h2>
        </div>
        <div class="col-md-3 text-center mb-4">
            <img src="https://via.placeholder.com/200x200" class="rounded-circle mb-3" alt="Team Member">
            <h5>John Smith</h5>
            <p class="text-muted">CEO & Founder</p>
        </div>
        <div class="col-md-3 text-center mb-4">
            <img src="https://via.placeholder.com/200x200" class="rounded-circle mb-3" alt="Team Member">
            <h5>Sarah Johnson</h5>
            <p class="text-muted">Operations Manager</p>
        </div>
        <div class="col-md-3 text-center mb-4">
            <img src="https://via.placeholder.com/200x200" class="rounded-circle mb-3" alt="Team Member">
            <h5>Mike Davis</h5>
            <p class="text-muted">Head of Sales</p>
        </div>
        <div class="col-md-3 text-center mb-4">
            <img src="https://via.placeholder.com/200x200" class="rounded-circle mb-3" alt="Team Member">
            <h5>Emma Wilson</h5>
            <p class="text-muted">Customer Support Lead</p>
        </div>
    </div>
</div>
@endsection
