<header class="bg-vander-navy text-white relative z-50">
<!-- Top Utility Bar (desktop/tablet only) -->
<div class="hidden md:flex container mx-auto px-4 py-2 justify-between items-center border-b border-blue-900">
<div class="flex items-center space-x-4">
<button class="flex items-center space-x-2 hover:text-gray-300">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</button>
<a class="flex items-center" href="/">
<img alt="Vander Haag's Logo" class="h-10" src="{{ asset('images/logo.png') }}">
</a>
</div>
<form class="flex-1 max-w-2xl mx-8 relative h-10" method="GET" action="{{ route('shop.index') }}">
<input name="search" value="{{ request('search') }}" class="w-full rounded-l-full py-2 pl-4 pr-10 focus:outline-none font-condensed bg-white" placeholder="Search by part #, cross reference, keyword..." style="font-size: 20px; font-weight: 400; height: 40px; color: rgba(0, 0, 0, 0.75); background-color: #ffffff;" type="text">
<button type="submit" class="absolute right-0 top-0 bottom-0 bg-blue-600 px-6 rounded-r-full hover:bg-blue-700 transition-colors">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</button>
</form>
<div class="flex items-center space-x-4 font-condensed" style="font-size: 18px; font-weight: 400; line-height: 75px; color: #fff;">
<a class="hover:text-gray-300" href="/contactus.php">Email Us</a>
<span class="text-gray-400">|</span>
<a class="hover:text-gray-300" href="tel:1-888-940-5030">Call Us</a>
<span class="text-gray-400">|</span>
<a class="hover:text-gray-300" href="/login.php">Sign In/Register</a>
<span class="text-gray-400">|</span>
<a class="flex items-center hover:text-gray-300" href="{{ route('cart.index') }}">
<svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="bg-gray-200 text-vander-navy rounded-full px-2 py-0.5 text-xs font-bold font-sans">{{ $cartCount ?? 0 }}</span>
</a>
</div>
</div>
<!-- Main Navigation (desktop/tablet only) -->
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

<!-- MOBILE-ONLY HEADER -->
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
      <a class="relative flex items-center hover:text-gray-300" href="{{ route('cart.index') }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        <span class="bg-gray-200 text-vander-navy rounded-full px-1.5 py-0.5 text-xs font-bold font-sans ml-1">{{ $cartCount ?? 0 }}</span>
      </a>
    </div>
  </div>
  <!-- Row 2: search bar, full width -->
  <form class="px-4 py-3" method="GET" action="{{ route('shop.index') }}">
    <div class="relative h-10">
      <input name="search" value="{{ request('search') }}" class="w-full rounded-l-full py-2 pl-4 pr-10 focus:outline-none font-condensed text-sm bg-white" placeholder="Search by part #, cross reference, keyword" style="height:40px; color: rgba(0,0,0,0.75); background-color: #ffffff;" type="text">
      <button type="submit" class="absolute right-0 top-0 bottom-0 bg-blue-600 px-4 rounded-r-full">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </button>
    </div>
  </form>
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
