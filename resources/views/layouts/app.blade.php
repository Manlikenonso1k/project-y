<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'Vander Haag\'s - Your Trucks & Parts Headquarters')</title>
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
    <x-header />

    @yield('content')

    <x-footer />
</body>
</html>
