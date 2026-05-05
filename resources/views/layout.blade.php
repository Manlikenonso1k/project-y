<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title') - Project X Shop</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Animate CSS -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <!-- Topbar Start -->
    <div class="container-fluid px-5 d-none border-bottom d-lg-block">
        <div class="row gx-0 align-items-center">
            <div class="col-lg-4 text-center text-lg-start mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a href="#" class="text-muted me-2">Help</a><small> / </small>
                    <a href="#" class="text-muted mx-2">Support</a><small> / </small>
                    <a href="#" class="text-muted ms-2">Contact</a>
                </div>
            </div>
            <div class="col-lg-4 text-center d-flex align-items-center justify-content-center">
                <small class="text-dark">Call Us:</small>
                <a href="#" class="text-muted">(+012) 1234 567890</a>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    @if(auth()->check())
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle text-muted ms-2" data-bs-toggle="dropdown"><small><i class="fa fa-home me-2"></i> {{ auth()->user()->name }}</small></a>
                            <div class="dropdown-menu rounded">
                                <a href="{{ route('cart.index') }}" class="dropdown-item">My Cart</a>
                                <a href="{{ route('checkout.show') }}" class="dropdown-item">Checkout</a>
                                <a href="{{ url('/admin') }}" class="dropdown-item">Admin Panel</a>
                            </div>
                        </div>
                    @else
                        <div>
                            <a href="{{ url('/admin/login') }}" class="text-muted ms-2"><small><i class="fa fa-user me-2"></i>Admin</small></a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid py-3 px-5 nav-bar sticky-top">
        <div class="row gx-0 align-items-center">
            <div class="col-lg-2">
                <h1 class="mb-0"><a href="{{ route('home') }}" class="text-primary"><i class="fas fa-bolt text-primary"></i> Project X</a></h1>
            </div>
            <div class="col-lg-8">
                <form class="input-group" method="GET" action="{{ route('shop.index') }}">
                    <input type="text" name="search" class="form-control bg-transparent" placeholder="Search for Products" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </form>
            </div>
            <div class="col-lg-2 text-end">
                <a href="{{ route('cart.index') }}" class="btn btn-primary btn-square"><i class="fa fa-shopping-cart"></i></a>
                <span class="badge bg-danger position-absolute" style="top: 5px; right: 10px;">{{ $cartCount ?? 0 }}</span>
            </div>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Breadcrumb Start -->
    @if(Route::currentRouteName() !== 'home')
    <div class="container-fluid py-5 bg-light">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    @endif
    <!-- Breadcrumb End -->

    <!-- Display Flash Messages -->
    @if($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Content -->
    @yield('content')

    <!-- Footer Start -->
    <div class="bg-dark text-white mt-5 py-5 px-5">
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <h5 class="text-white mb-4">Quick Links</h5>
                <a href="{{ route('home') }}" class="text-decoration-none text-white-50 d-block mb-2">Home</a>
                <a href="{{ route('shop.index') }}" class="text-decoration-none text-white-50 d-block mb-2">Shop</a>
                <a href="{{ route('contact') }}" class="text-decoration-none text-white-50 d-block mb-2">Contact</a>
                <a href="{{ route('about') }}" class="text-decoration-none text-white-50 d-block mb-2">About</a>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <h5 class="text-white mb-4">Account</h5>
                @if(auth()->check())
                    <a href="{{ route('cart.index') }}" class="text-decoration-none text-white-50 d-block mb-2">My Cart</a>
                    <a href="{{ route('checkout.show') }}" class="text-decoration-none text-white-50 d-block mb-2">Checkout</a>
                @else
                    <a href="{{ url('/admin/login') }}" class="text-decoration-none text-white-50 d-block mb-2">Admin Login</a>
                    <a href="{{ route('shop.index') }}" class="text-decoration-none text-white-50 d-block mb-2">Continue Shopping</a>
                @endif
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <h5 class="text-white mb-4">Company</h5>
                <a href="#" class="text-decoration-none text-white-50 d-block mb-2">Support</a>
                <a href="#" class="text-decoration-none text-white-50 d-block mb-2">Privacy Policy</a>
                <a href="#" class="text-decoration-none text-white-50 d-block mb-2">Terms & Conditions</a>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <h5 class="text-white mb-4">Newsletter</h5>
                <p class="text-white-50 mb-3">Subscribe to our newsletter for updates</p>
                <form method="POST" action="#">
                    @csrf
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your Email" required>
                        <button class="btn btn-primary" type="submit">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-dark text-white text-center py-4 px-5">
        <p class="m-0">&copy; 2026 Project X Shop. All Rights Reserved.</p>
    </div>
    <!-- Footer End -->

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
