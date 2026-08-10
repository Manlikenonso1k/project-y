<!DOCTYPE html><html lang="en"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Vander Haag's - Your Trucks &amp; Parts Headquarters</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<style data-purpose="custom-fonts">
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Roboto+Condensed:wght@400;700&display=swap');
    body {
      font-family: 'Roboto', sans-serif;
    }
    .font-condensed {
      font-family: 'Roboto Condensed', sans-serif;
    }
  </style>
<!-- ============================================================ -->
<!-- MOBILE-ONLY STYLESHEET                                        -->
<!-- Every rule below is inside @media (max-width: 767px), so it   -->
<!-- can NEVER change anything above 768px (desktop/tablet).       -->
<!-- Do not add rules here without a max-width scope.               -->
<!-- ============================================================ -->
<style data-purpose="mobile-only-overrides">
  @media (max-width: 767px) {
    /* Hero: shrink + reposition the 87-years badge so it doesn't overflow */
    #hero-badge { width: 7rem; height: 7rem; right: 1rem; bottom: -2rem; }

    /* Headquarters heading: stack heading + "About Us" link, shrink font */
    #hq-heading { flex-direction: column; }
    #hq-heading-text { font-size: 24px; line-height: 33.6px; }

    /* Parts section: this is the big one â€” desktop uses an absolute
       decorative background at 40% width plus a 42% left margin on
       the content, which only works on wide screens. On mobile we
       drop the decoration and let content go full width. */
    #parts-bg-decor { display: none; }
    #parts-content { margin-left: 0; padding-left: 1rem; padding-right: 1rem; max-width: 100%; }
    #parts-heading { font-size: 40px; line-height: 1.2; text-align: center; margin-bottom: 2rem; }
    #parts-stats-row { flex-direction: column; gap: 2rem; padding-left: 0; padding-right: 0; }
    #parts-links-grid { grid-template-columns: 1fr; text-align: center; justify-items: center; }

    /* Footer: match the 3-column reference layout instead of the
       desktop's 2-col / 4-col grids, and drop the desktop borders
       between footer columns since they're stacked now. */
    #footer-browse-grid { grid-template-columns: repeat(3, 1fr); font-size: 13px; }
    #footer-locations-col { border-left: none; border-right: none; padding-left: 0; padding-right: 0; }
    #footer-locations-grid { grid-template-columns: repeat(3, 1fr); font-size: 13px; }

    /* General container breathing room on small screens */
    main .container, section .container { padding-left: 1rem; padding-right: 1rem; }
  }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            vander: {
              navy: '#1a365d',
              blue: '#0056b3',
              orange: '#ff9900',
              gray: '#f4f4f4',
              text: '#333333',
              light: '#555555'
            }
          }
        }
      }
    }
  </script>
<link data-snapdom="injected-import" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&amp;display=swap" rel="stylesheet"><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&amp;family=Roboto+Condensed:wght@400;700&amp;display=swap" data-snapdom="injected-import"></head>
<body class="bg-white text-vander-text overflow-y-auto" style="height: auto;">
<!-- BEGIN: Header -->
<header class="bg-vander-navy text-white relative z-50">
<!-- Top Utility Bar (desktop/tablet only â€” unchanged, just hidden below md) -->
<div class="hidden md:flex container mx-auto px-4 py-2 justify-between items-center border-b border-blue-900">
<div class="flex items-center space-x-4">
<button class="flex items-center space-x-2 hover:text-gray-300">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</button>
<a class="flex items-center" href="/">
<img alt="Vander Haag's Logo" class="h-10" src="{{ asset('images/logo.png') }}">
</a>
</div>
<div class="flex-1 max-w-2xl mx-8 relative h-10">
<input class="w-full rounded-l-full py-2 pl-4 pr-10 focus:outline-none font-condensed" placeholder="Search by part #, cross reference, keyword..." style="font-size: 20px; font-weight: 400; height: 40px; color: rgba(0, 0, 0, 0.75);" type="text">
<button class="absolute right-0 top-0 bottom-0 bg-blue-600 px-6 rounded-r-full hover:bg-blue-700 transition-colors">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</button>
</div>
<div class="flex items-center space-x-4 font-condensed" style="font-size: 18px; font-weight: 400; line-height: 75px; color: #fff;">
<a class="hover:text-gray-300" href="/contactus.php">Email Us</a>
<span class="text-gray-400">|</span>
<a class="hover:text-gray-300" href="tel:1-888-940-5030">Call Us</a>
<span class="text-gray-400">|</span>
<a class="hover:text-gray-300" href="/login.php">Sign In/Register</a>
<span class="text-gray-400">|</span>
<a class="flex items-center hover:text-gray-300" href="/cart/cart.php">
<svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="bg-gray-200 text-vander-navy rounded-full px-2 py-0.5 text-xs font-bold font-sans">0</span>
</a>
</div>
</div>
<!-- Main Navigation (desktop/tablet only â€” unchanged, just hidden below md) -->
<nav class="hidden md:flex container mx-auto px-4 justify-between items-center h-12">
<div class="flex items-center space-x-2 text-gray-300 hover:text-white cursor-pointer font-condensed" style="font-size: 18px; font-weight: 400; line-height: 30px; color: #fff;">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">Add Vehicles to Filter</span>
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="flex space-x-8 font-condensed" style="font-size: 22px; font-weight: 400; line-height: 30px; color: #fff;">
<a class="hover:text-gray-300 flex items-center" href="/truckparts.php">Truck Parts <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></a>
<a class="hover:text-gray-300 flex items-center" href="/trucks.php">Units <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></a>
<a class="hover:text-gray-300 flex items-center" href="/equipmentparts.php">Equipment Parts <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></a>
<a class="hover:text-gray-300 flex items-center" href="/service.php">Service/Repair <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></a>
</div>
<div class="flex items-center text-red-400 hover:text-red-300 cursor-pointer font-condensed" style="font-size: 18px; font-weight: 400; line-height: 30px; color: #fff;">
<svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" style="color: rgb(190, 81, 81);"><path clip-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" fill-rule="evenodd"></path></svg>
<span class="">Select a Location</span>
<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: rgb(190, 81, 81);"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
</nav>

<!-- ============================================================ -->
<!-- MOBILE-ONLY HEADER (visible below md / 768px). Reference: home_1_.jpeg -->
<!-- The desktop utility bar + nav above are untouched; this is a  -->
<!-- separate block that only appears on small screens.            -->
<!-- ============================================================ -->
<div class="md:hidden">
  <!-- Row 1: menu, logo, sign in, cart -->
  <div class="flex justify-between items-center px-4 py-2 border-b border-blue-900">
    <div class="flex items-center gap-3">
      <button aria-label="Open menu" id="mobile-menu-toggle">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </button>
      <a href="/"><img alt="Vander Haag's Logo" class="h-8" src="{{ asset('images/logo.png') }}"></a>
    </div>
    <div class="flex items-center gap-2 font-condensed text-sm" style="color:#fff;">
      <a class="hover:text-gray-300" href="/login.php">Sign In/Register</a>
      <span class="text-gray-400">|</span>
      <a class="relative flex items-center hover:text-gray-300" href="/cart/cart.php">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        <span class="bg-gray-200 text-vander-navy rounded-full px-1.5 py-0.5 text-xs font-bold font-sans ml-1">0</span>
      </a>
    </div>
  </div>
  <!-- Row 2: search bar, full width -->
  <div class="px-4 py-3">
    <div class="relative h-10">
      <input class="w-full rounded-l-full py-2 pl-4 pr-10 focus:outline-none font-condensed text-sm" placeholder="Search by part #, cross reference, keyword" style="height:40px; color: rgba(0,0,0,0.75);" type="text">
      <button class="absolute right-0 top-0 bottom-0 bg-blue-600 px-4 rounded-r-full">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </button>
    </div>
  </div>
  <!-- Row 3: Add Vehicles to Filter / Select a Location -->
  <div class="flex justify-between items-center px-4 py-2 font-condensed" style="font-size:16px; font-weight:400; line-height:30px; color:#fff;">
    <div class="flex items-center gap-1">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      <span>Add Vehicles to Filter</span>
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    </div>
  </div>
  <div class="flex justify-end items-center px-4 pb-2 font-condensed" style="font-size:16px; font-weight:400; line-height:30px; color:#fff;">
    <div class="flex items-center gap-1" style="color: rgb(190, 81, 81);">
      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" fill-rule="evenodd"></path></svg>
      <span>Select a Location</span>
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    </div>
  </div>
  <!-- Row 4: Truck Parts / Units / Equipment Parts -->
  <div class="flex justify-around items-center px-2 py-2 border-t border-blue-900 font-condensed" style="font-size:16px; font-weight:400; line-height:30px; color:#fff;">
    <a class="flex items-center gap-1" href="/truckparts.php">Truck Parts <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></a>
    <a class="flex items-center gap-1" href="/trucks.php">Units <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></a>
    <a class="flex items-center gap-1" href="/equipmentparts.php">Equipment Parts <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></a>
  </div>
</div>
</header>
<!-- END: Header -->
<!-- BEGIN: Hero Section -->
<section class="relative bg-vander-navy h-[350px] overflow-hidden flex flex-col justify-center items-center">
<!-- Background Image -->
<div class="absolute inset-0 z-0 opacity-80" style="background-image: url(&quot;{{ asset('images/hero-bg.jpg') }}&quot;); background-size: cover; background-position: center center;"></div>
<div class="relative z-10 w-full max-w-5xl px-4 flex flex-col items-center">
<!-- Icon Navigation (desktop/tablet only) -->
<div class="hidden md:flex space-x-6 mb-8 mt-12">
<a class="group flex flex-col items-center" href="/truckparts.php">
<div class="w-24 h-24 border-2 border-white/50 rounded-xl flex items-center justify-center bg-black/30 group-hover:bg-white/10 transition-colors backdrop-blur-sm p-4">
<img alt="Truck Parts" class="w-full h-full object-contain" src="{{ asset('images/icon-truck-parts.png') }}">
</div>
</a>
<a class="group flex flex-col items-center" href="/equipmentparts.php">
<div class="w-24 h-24 border-2 border-white/50 rounded-xl flex items-center justify-center bg-black/30 group-hover:bg-white/10 transition-colors backdrop-blur-sm p-4">
<img alt="Equipment Parts" class="w-full h-full object-contain" src="{{ asset('images/icon-equipment-parts.png') }}">
</div>
</a>
<a class="group flex flex-col items-center" href="/trucks.php">
<div class="w-24 h-24 border-2 border-white/50 rounded-xl flex items-center justify-center bg-black/30 group-hover:bg-white/10 transition-colors backdrop-blur-sm p-4">
<img alt="Trucks" class="w-full h-full object-contain" src="{{ asset('images/icon-trucks.png') }}">
</div>
</a>
<a class="group flex flex-col items-center" href="/trailers.php">
<div class="w-24 h-24 border-2 border-white/50 rounded-xl flex items-center justify-center bg-black/30 group-hover:bg-white/10 transition-colors backdrop-blur-sm p-4">
<img alt="Trailers" class="w-full h-full object-contain" src="{{ asset('images/icon-trailers.png') }}">
</div>
</a>
<a class="group flex flex-col items-center" href="/equipment.php">
<div class="w-24 h-24 border-2 border-white/50 rounded-xl flex items-center justify-center bg-black/30 group-hover:bg-white/10 transition-colors backdrop-blur-sm p-4">
<img alt="Equipment" class="w-full h-full object-contain" src="{{ asset('images/icon-equipment.png') }}">
</div>
</a>
</div>
<!-- Action Buttons (desktop/tablet only) -->
<div class="hidden md:flex space-x-4 mb-4">
<a class="px-6 py-2 border border-white/60 text-white rounded-full bg-black/40 hover:bg-white/20 transition-colors text-sm font-medium backdrop-blur-sm" href="/service.php">Service / Repair</a>
<a class="px-6 py-2 border border-white/60 text-white rounded-full bg-black/40 hover:bg-white/20 transition-colors text-sm font-medium backdrop-blur-sm" href="/truckequipment.php">Truck Equipment</a>
<a class="px-6 py-2 border border-white/60 text-white rounded-full bg-black/40 hover:bg-white/20 transition-colors text-sm font-medium backdrop-blur-sm" href="/sell-your-truck.php">Sell Your Truck</a>
<a class="px-6 py-2 border border-white/60 text-white rounded-full bg-black/40 hover:bg-white/20 transition-colors text-sm font-medium backdrop-blur-sm" href="/employment.php">Apply For a Job</a>
</div>
</div>
<!-- 87 Years Badge -->
<div id="hero-badge" class="absolute right-12 -bottom-16 z-50">
<img alt="87 Years Badge" class="w-40 h-40" src="{{ asset('images/badge-87-years.png') }}">
</div>
</section>
<!-- END: Hero Section -->

<!-- ============================================================ -->
<!-- MOBILE-ONLY CATEGORY LIST (visible below md / 768px).         -->
<!-- Reference: home_1_.jpeg. Replaces the desktop icon row above  -->
<!-- for small screens only â€” desktop hero is untouched.           -->
<!-- Icon files referenced per your spec: truck-parts.png,         -->
<!-- equipmentparts.png, trucks.png, trailers.png, Equipment.png,  -->
<!-- services.png, "Truck Equipment.png" â€” swap in your hosted     -->
<!-- paths for these where noted below.                            -->
<!-- ============================================================ -->
<div class="md:hidden">
  <a href="/truckparts.php" class="flex items-center justify-between px-4 py-4 bg-gray-100 border-b border-gray-200">
    <div class="flex items-center gap-4">
      <div class="w-10 h-10 rounded-full border border-gray-700 flex items-center justify-center p-2">
        <img src="{{ asset('img/truck-parts.png') }}" alt="Truck Parts" class="w-full h-full object-contain">
      </div>
      <span style="font-family:'Roboto Condensed',sans-serif; font-size:16px; font-weight:400; color:rgb(34,34,34);">Truck Parts</span>
    </div>
    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
  </a>
  <a href="/equipmentparts.php" class="flex items-center justify-between px-4 py-4 bg-white border-b border-gray-200">
    <div class="flex items-center gap-4">
      <div class="w-10 h-10 rounded-full border border-gray-700 flex items-center justify-center p-2">
        <img src="{{ asset('img/equipmentparts.png') }}" alt="Equipment Parts" class="w-full h-full object-contain">
      </div>
      <span style="font-family:'Roboto Condensed',sans-serif; font-size:16px; font-weight:400; color:rgb(34,34,34);">Equipment Parts</span>
    </div>
    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
  </a>
  <a href="/trucks.php" class="flex items-center justify-between px-4 py-4 bg-gray-100 border-b border-gray-200">
    <div class="flex items-center gap-4">
      <div class="w-10 h-10 rounded-full border border-gray-700 flex items-center justify-center p-2">
        <img src="{{ asset('img/Trucks.png') }}" alt="Trucks" class="w-full h-full object-contain">
      </div>
      <span style="font-family:'Roboto Condensed',sans-serif; font-size:16px; font-weight:400; color:rgb(34,34,34);">Trucks</span>
    </div>
    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
  </a>
  <a href="/trailers.php" class="flex items-center justify-between px-4 py-4 bg-white border-b border-gray-200">
    <div class="flex items-center gap-4">
      <div class="w-10 h-10 rounded-full border border-gray-700 flex items-center justify-center p-2">
        <img src="{{ asset('img/Trailers.png') }}" alt="Trailers" class="w-full h-full object-contain">
      </div>
      <span style="font-family:'Roboto Condensed',sans-serif; font-size:16px; font-weight:400; color:rgb(34,34,34);">Trailers</span>
    </div>
    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
  </a>
  <a href="/equipment.php" class="flex items-center justify-between px-4 py-4 bg-gray-100 border-b border-gray-200">
    <div class="flex items-center gap-4">
      <div class="w-10 h-10 rounded-full border border-gray-700 flex items-center justify-center p-2">
        <img src="{{ asset('img/Equipment.png') }}" alt="Equipment" class="w-full h-full object-contain">
      </div>
      <span style="font-family:'Roboto Condensed',sans-serif; font-size:16px; font-weight:400; color:rgb(34,34,34);">Equipment</span>
    </div>
    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
  </a>
  <a href="/service.php" class="flex items-center justify-between px-4 py-4 bg-white border-b border-gray-200">
    <div class="flex items-center gap-4">
      <div class="w-10 h-10 rounded-full border border-gray-700 flex items-center justify-center p-2">
        <img src="{{ asset('img/services.png') }}" alt="Service" class="w-full h-full object-contain">
      </div>
      <span style="font-family:'Roboto Condensed',sans-serif; font-size:16px; font-weight:400; color:rgb(34,34,34);">Service</span>
    </div>
    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
  </a>
  <a href="/truckequipment.php" class="flex items-center justify-between px-4 py-4 bg-gray-100">
    <div class="flex items-center gap-4">
      <div class="w-10 h-10 rounded-full border border-gray-700 flex items-center justify-center p-2">
        <img src="{{ asset('img/Truck Equipment.png') }}" alt="Truck Equipment" class="w-full h-full object-contain">
      </div>
      <span style="font-family:'Roboto Condensed',sans-serif; font-size:16px; font-weight:400; color:rgb(34,34,34);">Truck Equipment</span>
    </div>
    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
  </a>
</div>

<!-- BEGIN: Main Content -->
<main class="relative bg-white py-16">
<div class="absolute inset-0 z-0 opacity-10 bg-no-repeat bg-center bg-cover" style="background-image: url(&quot;{{ asset('images/bg-pattern.jpg') }}&quot;);"></div>
<div class="container mx-auto px-8 relative z-10">
<div class="max-w-4xl mx-auto mb-16 text-center">
<h1 id="hq-heading" class="flex items-center justify-center font-condensed" style="color: rgb(7, 81, 177);"><span id="hq-heading-text" style="font-family: &quot;Roboto Condensed&quot;, sans-serif; font-size: 44px; font-weight: 400; line-height: 61.6px; color: rgb(7, 81, 177);" class="">Your Trucks &amp; Parts Headquarters</span><a class="hover:text-vander-blue ml-4 flex items-center" href="/aboutus.php" style="font-family: &quot;Roboto Condensed&quot;, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; color: rgb(128, 128, 128); cursor: pointer;">About Us <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></a></h1>
<p class="mt-6 mx-auto max-w-3xl font-condensed" style="font-family: &quot;Roboto Condensed&quot;, sans-serif; font-size: 16px; font-weight: 400; text-align: left; line-height: 24px; color: rgb(34, 34, 34);">
        Established in 1939, Vander Haag's is a heavy duty truck salvage company that specializes in quality used, rebuilt, and new truck parts. We are also a commercial truck and trailer dealer and provide full-service heavy duty truck repair. Our mission is to be the North American leader in sustainable heavy-duty solutions.
      </p>
</div>
<!-- Statistics Grid -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-8 text-center relative z-10 max-w-6xl mx-auto border-t border-b border-gray-200 py-12">
<!-- Stat 1 -->
<div class="flex flex-col items-center">
<i class="fa fa-map-marker fa-5x blue" style="color: rgb(7, 81, 177); margin-bottom: 16px;"></i>
<div class="text-xl text-vander-text mb-1 flex items-baseline justify-center"><span class="" style="font-family: Arial, Helvetica, sans-serif; font-size: 23px; font-weight: 700; color: rgb(23, 55, 83); margin-right: 6px;">12</span> <span class="font-condensed" style="font-size: 23px; font-weight: 400; line-height: 32.2px; color: rgb(34, 34, 34);">Locations</span></div>
<div class="text-sm text-gray-500">across the US</div>
</div>
<!-- Stat 2 -->
<div class="flex flex-col items-center">
<i class="fa fa-shield fa-5x blue" style="color: rgb(7, 81, 177); margin-bottom: 16px;"></i>
<div class="text-xl text-vander-text mb-1 font-condensed" style="font-size: 23px; font-weight: 400; line-height: 32.2px; color: rgb(34, 34, 34);">Trusted Industry Leader for</div>
<div class="text-sm text-gray-500"><span class="font-bold text-vander-navy">85+</span> years</div>
</div>
<!-- Stat 3 -->
<div class="flex flex-col items-center">
<i class="fa fa-recycle fa-5x blue" style="color: rgb(7, 81, 177); margin-bottom: 16px;"></i>
<div class="text-xl text-vander-text mb-1"><span class="font-bold text-vander-navy text-2xl">13,794,771 lbs</span> of</div>
<div class="text-sm text-gray-500">recycled parts sold so far in 2026</div>
</div>
<!-- Stat 4 -->
<div class="flex flex-col items-center">
<svg class="w-16 h-16 text-vander-navy mb-4" fill="currentColor" style="color: rgb(7, 81, 177);" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"></path></svg>
<div class="text-xl text-vander-text mb-1"><em class="text-gray-700">Free Shipping</em> on</div>
<div class="text-sm text-gray-500"><span class="font-bold text-vander-navy">38,239</span> items in stock</div>
</div>
<!-- Stat 5 -->
<div class="flex flex-col items-center">
<svg class="w-16 h-16 text-vander-navy mb-4" fill="currentColor" style="color: rgb(7, 81, 177);" viewBox="0 0 20 20"><path clip-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" fill-rule="evenodd"></path></svg>
<div class="text-xl text-vander-text mb-1"><span class="font-bold text-vander-navy text-2xl">96,214</span></div>
<div class="text-sm text-gray-500">units serviced</div>
</div>
</div>
</div>
</main>
<!-- END: Main Content -->
<!-- BEGIN: Testimonials Section -->
<section class="bg-[#0b3366] text-white py-16">
<div class="container mx-auto px-4 text-center">
<h2 class="text-3xl font-light mb-12 flex items-center justify-center">
      TESTIMONIALS 
      <a class="text-sm ml-4 flex items-center hover:text-gray-300" href="/testimonials.php">See all <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></a>
</h2>
<div class="grid grid-cols-1 md:grid-cols-4 gap-8">
<!-- Testimonial 1 -->
<div class="bg-white text-gray-800 rounded-lg p-6 relative pt-12 shadow-lg mt-12">
<div class="absolute -top-12 left-1/2 transform -translate-x-1/2 w-24 h-24 rounded-full border-4 border-white overflow-hidden bg-gray-200">
<img alt="Testimonial Author" class="w-full h-full object-cover" src="{{ asset('images/testimonial-1.jpg') }}">
</div>
<div class="text-[#0751b1] text-4xl font-serif absolute top-12 left-6">"</div>
<p class="italic text-sm text-gray-600 mb-6 relative z-10 px-4 text-center">
          If I ever need parts again, I will not hesitate to do business with these people again. So extremely professional. Thank you so much!
        </p>
<div class="text-[#0751b1] text-4xl font-serif absolute bottom-12 right-6">"</div>
<div class="mt-4 text-right">
<p class="font-bold text-sm">Chad B., <span class="font-normal text-xs text-gray-500">Owner</span></p>
<p class="text-xs text-gray-500">Blanchette Trucking - IL</p>
</div>
</div>
<!-- Testimonial 2 -->
<div class="bg-white text-gray-800 rounded-lg p-6 relative pt-12 shadow-lg mt-12">
<div class="absolute -top-12 left-1/2 transform -translate-x-1/2 w-24 h-24 rounded-full border-4 border-white overflow-hidden bg-gray-200">
<img alt="Testimonial Author" class="w-full h-full object-cover" src="{{ asset('images/testimonial-2.jpg') }}">
</div>
<div class="text-[#0751b1] text-4xl font-serif absolute top-12 left-6">"</div>
<p class="italic text-sm text-gray-600 mb-6 relative z-10 px-4 text-center">
          Excellent place to do business! The salesperson was courteous and very knowledgeable of their inventory. I couldn't have asked for better service!
        </p>
<div class="text-[#0751b1] text-4xl font-serif absolute bottom-12 right-6">"</div>
<div class="mt-4 text-right">
<p class="font-bold text-sm">Donald C., <span class="font-normal text-xs text-gray-500">Vice President</span></p>
<p class="text-xs text-gray-500">Carbine Construction - AL</p>
</div>
</div>
<!-- Testimonial 3 -->
<div class="bg-white text-gray-800 rounded-lg p-6 relative pt-12 shadow-lg mt-12">
<div class="absolute -top-12 left-1/2 transform -translate-x-1/2 w-24 h-24 rounded-full border-4 border-white overflow-hidden bg-white">
<img alt="Testimonial Author" class="w-full h-full object-contain p-2" src="{{ asset('images/testimonial-3.jpg') }}">
</div>
<div class="text-[#0751b1] text-4xl font-serif absolute top-12 left-6">"</div>
<p class="italic text-sm text-gray-600 mb-6 relative z-10 px-4 text-center">
          Best customer service I've experienced in a long time. The technician was wonderful, knowledgeable and friendly. Everyone that I worked with was fantastic!
        </p>
<div class="text-[#0751b1] text-4xl font-serif absolute bottom-12 right-6">"</div>
<div class="mt-4 text-right">
<p class="font-bold text-sm">Julie E., <span class="font-normal text-xs text-gray-500">RV Owner</span></p>
<p class="text-xs text-gray-500">Valued Customer - IA</p>
</div>
</div>
<!-- Testimonial 4 -->
<div class="bg-white text-gray-800 rounded-lg p-6 relative pt-12 shadow-lg mt-12">
<div class="absolute -top-12 left-1/2 transform -translate-x-1/2 w-24 h-24 rounded-full border-4 border-white overflow-hidden bg-white">
<img alt="Testimonial Author" class="w-full h-full object-contain p-2" src="{{ asset('images/testimonial-4.jpg') }}">
</div>
<div class="text-[#0751b1] text-4xl font-serif absolute top-12 left-6">"</div>
<p class="italic text-sm text-gray-600 mb-6 relative z-10 px-4 text-center">
          Purchased the item with air freight for far less than other online stores, and I received it a day earlier than expected. I will be looking to Vander Haag's more often!
        </p>
<div class="text-[#0751b1] text-4xl font-serif absolute bottom-12 right-6">"</div>
<div class="mt-4 text-right">
<p class="font-bold text-sm">Steven H., <span class="font-normal text-xs text-gray-500">Owner</span></p>
<p class="text-xs text-gray-500">Hatch Heavy Haul - TX</p>
</div>
</div>
</div>
</div>
</section>
<!-- END: Testimonials Section -->
<!-- BEGIN: Parts Section -->
<section class="py-16 bg-white relative overflow-hidden"><div id="parts-bg-decor" class="absolute top-0 bottom-0 left-0 w-[40%] bg-cover bg-center z-0" style="background-image: url(&quot;{{ asset('images/parts-bg.jpg') }}&quot;); clip-path: ellipse(100% 70% at 0% 50%);"></div>
<div id="parts-content" class="px-4 relative z-10 ml-[42%] pr-12 max-w-5xl">
<h2 id="parts-heading" class="font-condensed text-right" style="font-size: 80px; font-weight: 400; line-height: 25px; color: rgb(23, 55, 83); text-align: right; margin-bottom: 3rem;">PARTS</h2><div class="flex flex-col md:flex-row items-center mb-16">
<div class="w-full md:w-1/3 mb-8 md:mb-0">
<img alt="TPS Distributor of the Year" class="mx-auto -mt-12" src="{{ asset('images/tps-award.png') }}" style="width: 150px; height: 120px; object-fit: contain;">
</div>
<div id="parts-stats-row" class="w-full md:w-2/3 flex text-center px-8 justify-around">
<div class="flex flex-col items-center">
<i class="fa fa-shopping-cart fa-3x mb-4 text-[#0751b1]"></i>
<p class="font-bold text-xl text-gray-800">332,095 <span class="font-light">Product Listings</span></p>
<p class="text-xs text-gray-500 mt-1">available to purchase online</p>
</div>
<div class="flex flex-col items-center">
<i class="fa fa-tachometer fa-3x mb-4 text-[#0751b1]"></i>
<p class="font-bold text-xl text-gray-800">213 <span class="font-light">New Units</span></p>
<p class="text-xs text-gray-500 mt-1">dismantled every month</p>
</div>
<div class="flex flex-col items-center"><i class="fa fa-phone fa-3x mb-4 text-[#0751b1]"></i><p class="font-bold text-xl text-gray-800">60+ <span class="font-light">In-House</span></p><p class="text-xs text-gray-500 mt-1">dedicated phone support staff</p></div>
</div>
</div>
<div class="max-w-5xl mx-auto text-center mb-12">
<p class="text-gray-600 leading-relaxed">
        Vander Haag's has been recycling <span class="font-bold">used truck parts</span> for over 85 years and is the leader in <span class="font-bold">quality recycled semi truck parts</span> (<a class="text-[#0751b1] hover:underline" href="/sell-your-truck.php">Can we buy your truck? <i class="fa fa-external-link"></i></a>). We have thousands of <span class="font-bold">salvage</span> units on hand and new units arriving daily. From late model <span class="font-bold">hard-to-find parts</span> to components from newer models, we supply parts for <span class="font-bold">all makes and models</span>. When you combine this with our thorough inspection process and the best <span class="font-bold">warranty coverage</span> in the business, you can see that we are determined to get your <span class="font-bold">truck</span> on the road and keep it there! We also carry a large selection of <span class="font-bold">new and aftermarket parts</span>.
      </p>
</div>
<div id="parts-links-grid" class="grid grid-cols-2 md:grid-cols-4 gap-y-4 max-w-4xl mx-auto text-[#0751b1]">
<a class="flex items-center hover:underline" href="/Search-Results.php"><span class="mr-2">Used Parts</span> <i class="fa fa-external-link"></i></a>
<a class="flex items-center hover:underline" href="/Search-Results.php?inventorytype=transmission"><span class="mr-2">Transmissions</span> <i class="fa fa-external-link"></i></a>
<a class="flex items-center hover:underline" href="/Search-Results.php?inventorytype=bumper-assembly-front"><span class="mr-2">Bumpers</span> <i class="fa fa-external-link"></i></a>
<a class="flex items-center hover:underline" href="/Search-Results.php?inventorytype=steering-gear-rack"><span class="mr-2">Steering Gears</span> <i class="fa fa-external-link"></i></a>
<a class="flex items-center hover:underline" href="/Search-Results.php?category=accessories&amp;subcategory=accessories-spray-suppression&amp;inventorytype=accessory-fender&amp;filters%5B0%5D%5B0%5D=NEW"><span class="mr-2">New Parts</span> <i class="fa fa-external-link"></i></a>
<a class="flex items-center hover:underline" href="/Search-Results.php?inventorytype=rear-crr"><span class="mr-2">Differentials</span> <i class="fa fa-external-link"></i></a>
<a class="flex items-center hover:underline" href="/Search-Results.php?inventorytype=hood"><span class="mr-2">Hoods</span> <i class="fa fa-external-link"></i></a>
<a class="flex items-center hover:underline" href="/Search-Results.php?subcategory=cab-and-sleeper-cab-interior"><span class="mr-2">Interior Parts</span> <i class="fa fa-external-link"></i></a>
<a class="flex items-center hover:underline" href="/Search-Results.php?category=engine&amp;subcategory=engine-engine-assembly&amp;inventorytype=engine-assembly&amp;filters%5B0%5D%5B0%5D=REBUILT"><span class="mr-2">Rebuilt</span> <i class="fa fa-external-link"></i></a>
<a class="flex items-center hover:underline" href="/Search-Results.php?inventorytype=engine-assembly"><span class="mr-2">Engines</span> <i class="fa fa-external-link"></i></a>
<a class="flex items-center hover:underline" href="/Search-Results.php?inventorytype=door-assembly-front"><span class="mr-2">Doors</span> <i class="fa fa-external-link"></i></a>
<a class="flex items-center hover:underline" href="/Search-Results.php?inventorytype=fuel-tank"><span class="mr-2">Fuel Tanks</span> <i class="fa fa-external-link"></i></a>
</div>
</div>
</section>
<!-- END: Parts Section -->
<!-- BEGIN: Service Section -->
<section class="py-16 relative border-t border-gray-200 overflow-hidden bg-white">
<div class="absolute inset-0 z-0 opacity-5" style="background-image: url(&quot;{{ asset('images/service-bg.jpg') }}&quot;); background-repeat: no-repeat; background-position: right center; background-size: 800px;"></div>
<div class="container mx-auto px-4 relative z-10 flex flex-col md:flex-row"><div class="w-full md:w-1/2 pr-8">
<h2 class="text-4xl font-light italic text-[#0751b1] mb-6 flex items-center">
        SERVICE / REPAIR 
        <a class="text-sm font-normal text-gray-500 ml-4 hover:text-[#0751b1] flex items-center not-italic" href="/service.php">More <i class="fa fa-external-link ml-1"></i></a>
</h2>
<p class="text-gray-600 leading-relaxed mb-8">
        Vander Haag's prides itself in first-in-class <span class="italic">service work</span> and always puts you, our customer, first. From <span class="italic">diesel engine repair</span> to <span class="italic">body work</span> to <span class="italic">routine maintenance</span> to <span class="italic">frame modification</span>, trust our experienced mechanics for all your <span class="italic">truck service</span> needs. We have over thirty experienced service technicians combined with top-of-the-line diagnostic equipment to provide you the best assistance possible. Stop by one of our <span class="italic">commercial truck service shops</span> to see how we can get you back on the road today!
      </p>
<div class="grid grid-cols-2 gap-x-8 gap-y-3 text-[#0b3366] italic font-medium">
<a class="hover:underline" href="/serviceinfo.php?svcid=1">Air Conditioning</a>
<a class="hover:underline" href="/serviceinfo.php?svcid=6">Diagnostics</a>
<a class="hover:underline" href="/serviceinfo.php?svcid=13">Frame Modifications</a>
<a class="hover:underline" href="/serviceinfo.php?svcid=2">Alignments, Laser</a>
<a class="hover:underline" href="/serviceinfo.php?svcid=7">Differentials</a>
<a class="hover:underline" href="/serviceinfo.php?svcid=14">Hydraulics</a>
<a class="hover:underline" href="/serviceinfo.php?svcid=3">Brake &amp; Wheel End</a>
<a class="hover:underline" href="/serviceinfo.php?svcid=8">DOT &amp; PM Inspection</a>
<a class="hover:underline" href="/serviceinfo.php?svcid=20">Oil Change</a>
<a class="hover:underline" href="/serviceinfo.php?svcid=4">Cab &amp; Body Repair</a>
<a class="hover:underline" href="/serviceinfo.php?svcid=9">Drivelines</a>
<a class="hover:underline" href="/serviceinfo.php?svcid=15">Steering</a>
<a class="hover:underline" href="/serviceinfo.php?svcid=5">Clutches</a>
<a class="hover:underline" href="/serviceinfo.php?svcid=10">Electrical</a>
<a class="hover:underline" href="/serviceinfo.php?svcid=16">Suspension</a>
</div>
</div>
<div class="w-full md:w-1/2 flex items-center justify-center">
<img src="{{ asset('images/service-map.jpg') }}" alt="Service Locations Map" class="w-full h-auto rounded shadow-lg">
</div></div>
</section>
<!-- END: Service Section -->
<!-- BEGIN: Trucks Equipment Section -->
<section class="relative py-24 text-white overflow-hidden bg-[#2d2d2d]" style="clip-path: polygon(0px 15%, 100% 0px, 100% 100%, 0px 100%);">
  <!-- Background Image at Bottom -->
  <div class="absolute inset-0 z-0 opacity-40 bg-cover bg-bottom" style="background-image: url('{{ asset('images/trucks-bg.jpg') }}');"></div>
  
  <div class="container mx-auto px-8 relative z-10 flex flex-col md:flex-row items-center">
    <!-- Left Side: Badge -->
    <div class="w-full md:w-1/3 flex justify-center md:justify-start mb-8 md:mb-0">
      <div class="border-4 border-white/20 p-1">
        <img alt="UTA Individual Member of the Year" class="w-full max-w-[280px] h-auto" src="{{ asset('images/uta-award.jpg') }}">
      </div>
    </div>

    <!-- Right Side: Content -->
    <div class="w-full md:w-2/3 md:pl-12">
      <h2 class="text-4xl md:text-6xl font-condensed font-bold mb-6 tracking-tight">TRUCKS, TRAILERS &amp; EQUIPMENT</h2>
      <p class="text-gray-300 leading-relaxed text-lg font-condensed">
        Vander Haag's holds an inventory of <a class="text-[#89b3e6] hover:underline" href="/trucks.php">used trucks</a> and <a class="text-[#89b3e6] hover:underline" href="/trailers.php">trailers</a> that changes daily. We are an <span class="text-white">authorized dealer</span> for new <span class="text-white">SmithCo, Timpte, Demco, Neville, Jet, Dorsey,</span> and <span class="text-white">Stoughton trailers</span>. We also carry <a class="text-[#89b3e6] hover:underline" href="/equipment.php">construction equipment units</a>. Let one of our experienced sales professionals help you find what's right for you. If you don't see what you're looking for in our extensive inventory, we will <span class="text-white">custom build</span> a unit to your specifications. Have a unit to sell? <a class="text-[#89b3e6] hover:underline" href="/sell-your-truck.php">Fill out our form</a> to get a quote today!
      </p>
    </div>
  </div>
</section>
<!-- END: Trucks Equipment Section -->
<!-- BEGIN: Footer -->
<footer class="bg-white pt-12 pb-0 border-t border-gray-200">
<div class="container mx-auto px-4">
<!-- Also Of Interest -->
<div class="flex items-center mb-8 pb-4 border-b border-gray-200 text-sm">
<span class="text-gray-500 mr-8 whitespace-nowrap">ALSO OF INTEREST</span>
<div class="flex space-x-6 text-gray-700">
<a class="hover:text-[#0751b1]" href="https://www.vanderhaags.com/Search-Results.php?inventorytype=engine-assembly">Quality Inspected Used Engines</a>
<a class="hover:text-[#0751b1]" href="https://www.vanderhaags.com/service.php">Semi Truck Service &amp; Repair</a>
<a class="hover:text-[#0751b1]" href="https://www.vanderhaags.com/Search-Results.php?inventorytype=transmission">Quality Inspected Used/Rebuilt Transmissions</a>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
<!-- Browse Online -->
<div class="md:col-span-1">
<h3 class="text-lg text-gray-700 mb-4 pb-2 border-b border-gray-200">Browse Online</h3>
<div id="footer-browse-grid" class="grid grid-cols-2 gap-x-4 gap-y-2 pr-4" style="font-family: &quot;Roboto Condensed&quot;, sans-serif; font-size: 16px; font-weight: 400; text-align: justify; line-height: 24px; color: rgb(51, 48, 48);">

<a class="hover:underline text-[#333030]" href="/aboutus.php">About Us</a>
<a class="hover:underline text-[#333030]" href="/Search-Results.php?inventorytype=truck">Trucks &amp; Trailers</a>
<a class="hover:underline text-[#333030]" href="/cart/cart.php">Cart</a>
<a class="hover:underline text-[#333030]" href="/help.php">FAQ / Help</a>
<a class="hover:underline text-[#333030]" href="/equipmentparts.php">Equipment Parts</a>
<a class="hover:underline text-[#333030]" href="/Catalog/Flyer.pdf">Catalog</a>
<a class="hover:underline text-[#333030]" href="/contactus.php">Contact Us</a>
<a class="hover:underline text-[#333030]" href="/sell-your-truck.php">We Buy Trucks</a>
<a class="hover:underline text-[#333030]" href="http://www.yesterdaysmemories.us/">Museum</a>
<a class="hover:underline text-[#333030]" href="/employment.php">Employment</a>
<a class="hover:underline text-[#333030]" href="/shippingandreturns.php">Shipping &amp; Returns</a>
<a class="hover:underline text-[#333030]" href="/warranty.php">Warranty</a>
<a class="hover:underline text-[#333030]" href="/account/ordersearch.php">Order Search</a>
<a class="hover:underline text-[#333030]" href="/login.php">Log In / Account</a>
<a class="hover:underline text-[#333030]" href="/service.php">Service</a>
<a class="hover:underline text-[#333030]" href="/truckparts.php">Truck Parts</a>
<a class="hover:underline text-[#333030]" href="tel:1-888-940-5030">888.940.5030</a>
</div>
</div>
<!-- Our Locations -->
<div id="footer-locations-col" class="md:col-span-2 pl-8 border-l border-gray-200 border-r pr-8">
<div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-200">
<h3 class="text-lg text-gray-700">Our Locations</h3>
<a class="text-sm text-[#0751b1] hover:underline flex items-center" href="/map.php">View Map <i class="fa fa-external-link ml-1"></i></a>
</div>
<p class="text-sm text-gray-600 mb-4">
          While Vander Haag's, Inc still holds strong to its family focused values that have been at the core of the business since first opening in 1939, the company now features 12 Midwest locations selling quality used/rebuilt/new truck parts, selling commercial trucks &amp; trailers, and providing full service heavy duty truck repair.
        </p>
<div id="footer-locations-grid" class="grid grid-cols-4 gap-y-2 text-sm text-[#0751b1]">
<a class="hover:underline" href="/location.php?store=Spencer"><i class="fa fa-map-marker text-red-600 mr-1"></i> Spencer, IA</a>
<a class="hover:underline" href="/location.php?store=Des+Moines"><i class="fa fa-map-marker text-red-600 mr-1"></i> Des Moines, IA</a>
<a class="hover:underline" href="/location.php?store=Sioux+Falls"><i class="fa fa-map-marker text-red-600 mr-1"></i> Sioux Falls, SD</a>
<a class="hover:underline" href="/location.php?store=Council+Bluffs"><i class="fa fa-map-marker text-red-600 mr-1"></i> Council Bluffs, IA</a>
<a class="hover:underline" href="/location.php?store=Kansas+City"><i class="fa fa-map-marker text-red-600 mr-1"></i> Kansas City, MO</a>
<a class="hover:underline" href="/location.php?store=Winamac"><i class="fa fa-map-marker text-red-600 mr-1"></i> Winamac, IN</a>
<a class="hover:underline" href="/location.php?store=Indianapolis"><i class="fa fa-map-marker text-red-600 mr-1"></i> Indianapolis, IN</a>
<a class="hover:underline" href="/location.php?store=Columbus"><i class="fa fa-map-marker text-red-600 mr-1"></i> London, OH</a>
<a class="hover:underline" href="/location.php?store=Louisville"><i class="fa fa-map-marker text-red-600 mr-1"></i> Louisville, KY</a>
<a class="hover:underline" href="/location.php?store=Dallas"><i class="fa fa-map-marker text-red-600 mr-1"></i> Dallas, TX</a>
<a class="hover:underline" href="/location.php?store=St+Louis"><i class="fa fa-map-marker text-red-600 mr-1"></i> Bridgeton, MO</a>
<a class="hover:underline" href="/location.php?store=Stephenville"><i class="fa fa-map-marker text-red-600 mr-1"></i> Stephenville, TX</a>
</div>
</div>
<!-- Connect -->
<div class="md:col-span-1">
<div class="flex items-center mb-4 pb-2 border-b border-gray-200">
<h3 class="text-lg text-gray-700 mr-4">Connect</h3>
<div class="flex space-x-2">
<a class="bg-[#3b5998] text-white w-8 h-8 rounded flex items-center justify-center hover:opacity-80" href="https://www.facebook.com/VanderHaagsInc"><i class="fa fa-facebook"></i></a>
<a class="bg-gradient-to-tr from-yellow-400 via-red-500 to-purple-500 text-white w-8 h-8 rounded flex items-center justify-center hover:opacity-80" href="https://www.instagram.com/vanderhaagsinc"><i class="fa fa-instagram"></i></a>
<a class="bg-[#0077b5] text-white w-8 h-8 rounded flex items-center justify-center hover:opacity-80" href="http://www.linkedin.com/company/vander-haag's-inc-?trk=company_name"><i class="fa fa-linkedin"></i></a>
</div>
</div>
<div class="mt-6">
<p class="text-xs text-gray-500 mb-2">Subscribe for promotional content</p>
<input class="w-full bg-gray-200 border-none rounded py-2 px-3 mb-3 text-sm focus:ring-0" placeholder="Email Address" type="email">
<button class="bg-[#0751b1] text-white font-medium py-2 px-6 rounded hover:bg-blue-700 text-sm">Subscribe</button>
</div>
</div>
</div>
</div>
<!-- Copyright -->
<div class="bg-[#0b3366] text-white text-center py-3 text-xs">
    Â© Vander Haag's Inc. 2026 - <a class="hover:underline" href="/privacy.php">Privacy Policy</a>
</div>
</footer>
<!-- END: Footer -->
<!-- Floating Contact Button -->
<div class="fixed bottom-0 right-0 z-50">
<button class="bg-vander-orange hover:bg-orange-600 text-white font-bold py-4 px-6 flex items-center shadow-lg rounded-tl-lg transition-colors">
<svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" fill-rule="evenodd"></path></svg>
      CONTACT
    </button>
</div>
</body></html>
