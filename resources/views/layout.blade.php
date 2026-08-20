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
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&family=Roboto+Condensed:wght@200;400&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Animate CSS -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Vite Assets (for Tailwind / header+footer component styles) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style data-purpose="mobile-only-overrides">
        @media (max-width: 767px) {
            #hero-badge { width: 7rem; height: 7rem; right: 1rem; bottom: -2rem; }
            #hq-heading { flex-direction: column; }
            #hq-heading-text { font-size: 24px; line-height: 33.6px; }
            #parts-bg-decor { display: none; }
            #parts-content { margin-left: 0; padding-left: 1rem; padding-right: 1rem; max-width: 100%; }
            #parts-heading { font-size: 40px; line-height: 1.2; text-align: center; margin-bottom: 2rem; }
            #parts-stats-row { flex-direction: column; gap: 2rem; padding-left: 0; padding-right: 0; }
            #parts-links-grid { grid-template-columns: 1fr; text-align: center; justify-items: center; }
            #footer-browse-grid { grid-template-columns: repeat(3, 1fr); font-size: 13px; }
            #footer-locations-col { border-left: none; border-right: none; padding-left: 0; padding-right: 0; }
            #footer-locations-grid { grid-template-columns: repeat(3, 1fr); font-size: 13px; }
            main .container, section .container { padding-left: 1rem; padding-right: 1rem; }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-white text-vander-text overflow-y-auto" style="height: auto;">
@hasSection('full_page')
    @yield('full_page')
@else
    <!-- Global Header -->
    <x-header />

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

    <!-- Global Footer -->
    <x-footer />

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
@endif
</body>
</html>
