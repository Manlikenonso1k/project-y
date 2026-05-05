@extends('layout')

@section('title', 'Contact Us')

@section('breadcrumb')
    <li class="breadcrumb-item active">Contact Us</li>
@endsection

@section('content')
<div class="container-fluid py-5 px-5">
    <div class="row">
        <div class="col-lg-6 mb-5">
            <h2 class="mb-4">Get in Touch</h2>
            <form method="POST" action="#">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name *</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label">Subject *</label>
                    <input type="text" class="form-control" id="subject" name="subject" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message *</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>

        <div class="col-lg-6">
            <h2 class="mb-4">Contact Information</h2>
            <div class="mb-4">
                <h6 class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Address</h6>
                <p>123 Electronics Street<br>Tech City, TC 12345<br>United States</p>
            </div>
            <div class="mb-4">
                <h6 class="mb-2"><i class="fas fa-phone me-2"></i> Phone</h6>
                <p><a href="tel:+0121234567890" class="text-decoration-none">(+012) 1234 567890</a></p>
            </div>
            <div class="mb-4">
                <h6 class="mb-2"><i class="fas fa-envelope me-2"></i> Email</h6>
                <p><a href="mailto:itachi45@proton.me" class="text-decoration-none">itachi45@proton.me</a></p>
            </div>
            <div class="mb-4">
                <h6 class="mb-2"><i class="fas fa-clock me-2"></i> Business Hours</h6>
                <p>Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM<br>Sunday: Closed</p>
            </div>
        </div>
    </div>
</div>
@endsection
