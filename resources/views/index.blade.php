@extends('layouts.app')

@section('title', 'Vander Haag\'s - Your Trucks & Parts Headquarters')

@section('content')
<!-- BEGIN: Hero Section -->
<section class="relative bg-vander-navy h-[350px] overflow-hidden flex flex-col justify-center items-center">
<!-- Background Image -->
<div class="absolute inset-0 z-0 opacity-80" style="background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuCWOivuLAagm-djVSziBsPEDg2nd-JvVUScqVXIycxb4EGZArosPD9sgwxvP-9zBkMhxJOIkpRvZtHu86_wqPwRhvuC5OyUvyeVNGWGZ6arbHS-H2eHIYdiXCEbAqwcDTTMF0vztTvMLlousA59kfv8VTwJBYIP53vDhDBIec95bOX9RC3sssWELknOmeOsKBGaYnFcnoNlphBArIrJ82TjxixDUVVJrS9jmcZz4XIqFz6bg0JRabCfUmMDtS-oX24TMI8fpL1yNnop&quot;); background-size: cover; background-position: center center;"></div>
<div class="relative z-10 w-full max-w-5xl px-4 flex flex-col items-center">
<!-- Icon Navigation (desktop/tablet only) -->
<div class="hidden md:flex space-x-6 mb-8 mt-12">
<a class="group flex flex-col items-center" href="/truckparts.php">
<div class="w-24 h-24 border-2 border-white/50 rounded-xl flex items-center justify-center bg-black/30 group-hover:bg-white/10 transition-colors backdrop-blur-sm p-4">
<img alt="Truck Parts" class="w-full h-full object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuALjGC34k9SfA7gXFU8E3KDO27Glbf0PYtkoVit3di860LFxAVvijgP7t8gHGG_dNYwfrHmEqcdLrbnGxBtMSq9NUr-ORyCusJ64xZ_RJyMpnwFTclESCcMJAYihu9joNJwzg6L_-2tQbAaaUwpZbEejcup0-6OOt0qi23UbqHbcvHaQB-kktzzUnlEbRxZSTPoKmpq-MSpAOHktZD_VsJHIh5ESeDxL8DVPHFZPluf3ua11NK0gaEMpMxI6ARxOonr">
</div>
</a>
<a class="group flex flex-col items-center" href="/equipmentparts.php">
<div class="w-24 h-24 border-2 border-white/50 rounded-xl flex items-center justify-center bg-black/30 group-hover:bg-white/10 transition-colors backdrop-blur-sm p-4">
<img alt="Equipment Parts" class="w-full h-full object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCjuMIi0JQSDW2BYfrQxOb-e7Mbu6s_WOJfut8hCth-n2FNZ3QZ_v3XfLz9jtal_CXDEDLgpQNXWg-N6Ose2H56w7VZhWikbn8PEUVA7j0LRIPbR6bLaJ91zy-wX2FrezcF4WR_X1fkTd-YYIYJ6rebmbnhqTKEMRrRMVUMZxU5G2RscA5aA11s3NRKUVna90dVNNDoLNJR6twIph56VTIciUvwKl64DVMw2Ccmj4p8ErwIqjkbUvtc5IpyGklJVoQl">
</div>
</a>
<a class="group flex flex-col items-center" href="/trucks.php">
<div class="w-24 h-24 border-2 border-white/50 rounded-xl flex items-center justify-center bg-black/30 group-hover:bg-white/10 transition-colors backdrop-blur-sm p-4">
<img alt="Trucks" class="w-full h-full object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBsNoQA3_uV4OcVwC5IAHRtpbZp2bD7ThQPEBLj10BP-ZR7-CHkdXanl3S1W3IGmB6DmxDZhgbtvvkts-ocbDVVkoJtlfyIW6uWMGZQX2QEVnYdml5-qAD4y99puSYZrdRzMBKUPAtspL_y0pWtl2h8KRIyChEeiyoG7MYwGrpZljMzvXYWDihuja4aJGCPWdI-ut_DwpXrckrIBVd2Wm_vPnBOaUPRxeuSos32SK3bSVc8iMmGqmeB4b50AzECftcA">
</div>
</a>
<a class="group flex flex-col items-center" href="/trailers.php">
<div class="w-24 h-24 border-2 border-white/50 rounded-xl flex items-center justify-center bg-black/30 group-hover:bg-white/10 transition-colors backdrop-blur-sm p-4">
<img alt="Trailers" class="w-full h-full object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZ_Pyok-syFVtnMBX9vcm2aq4GSJ3HzG7KPDvrk1K89lKQd_qfvmzB2v6IgvUS1w5upaph5sGw09tpJInNxK-PRWQaIhwD25UmFt5kDxyZfqk8wIsHBOAc2QIrkyjDvw5LZACGQBwOq-einvI9lgfnhKzSfYANP9yDNDZ8Jg4Y9OfaPZjMU2-9RXjj0zzh1rsbMaTYzsppjNYJmaejConRjau70Nl49oN_q3G_kKXdruxTigS1PFkxyNZ6WTXocqaS">
</div>
</a>
<a class="group flex flex-col items-center" href="/equipment.php">
<div class="w-24 h-24 border-2 border-white/50 rounded-xl flex items-center justify-center bg-black/30 group-hover:bg-white/10 transition-colors backdrop-blur-sm p-4">
<img alt="Equipment" class="w-full h-full object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDabWIXi_jJ7qaap31wQe18kmFZYg-iVKM2hw1CDw6crMeuV227dXWWVNubwg0LrBk0_T1twmm61LxCV95zkilc-dG1hpY1RlbTtwhX_zU64E3a1wIZm2Mo-DVawA-S2_U4a18zisF0hQO8FEOiRd1YwtApeqLgPmrAntCeCvVnz1dhkxozpaptB3RLl9wH2srKqUJ_TqVQaDS1W64iIIQ5WzO_uyIHf2g9WmxqRy-HmrJ4a_Jb5G6Z5ommDbFOx0nT">
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
<img alt="87 Years Badge" class="w-40 h-40" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCpZx0bF2o0-uAYLELqXzqu3Ew4ZUKhHnIW52XoRRFM57SuzvZk1EJ_ZNYf6UXKJEllNwDDqh6vhuolAnoa77kpKCXQAdBNbVqJno6M-tg2D377bZMAMXbjEfQrmpcezgX0q59NoRZp6v8bqBqJpcor4cpoU9C9-II_Hg61GmnMfZpwOrcD8CrWzsHvt_FhKo8e312xN_LFxkoysVqk5XOXlt6qAAY_RatX3K0l8-5ZN5CAj5wv0T3TrPg_nr7EafatJwhc-L7OI-F1">
</div>
</section>
<!-- END: Hero Section -->

<!-- MOBILE-ONLY CATEGORY LIST -->
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
<div class="absolute inset-0 z-0 opacity-10 bg-no-repeat bg-center bg-cover" style="background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuCHnv_h3gceDb496nwu3C39vctecz-IXj0Uk1QfTxBxOjwaewfVK_ldPZPbfedg4gNw7HZqBoxyIMV2XsXC3jeALNl1oMMzyjOu4pGP8YTQlYSPPijqDFmX41MiP2nkTCKporpVqH2Qsmb0VfOzWVGekn3LXXaxUKyGnCwuk_vHyXUSihnogzzQz6tXfThbUcyWMv6ir5k8R4Mq6GS7G14VgPGCsuluJ0KTuQfcgRzDVamHNxtCiCAy2fJ7XKe-TQFY&quot;);"></div>
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
<img alt="Testimonial Author" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBpjYns-28Sqz9PfYgF-4Y-u3_DV4sdNW8BxsasT2C-VeWo9Yy2cb18yZUd0r221Uka6aF4Qy4r3b8UoyOrSJoEuqRSNqkWYfzj-mqm_ce-0akjT8eAy5ArbhVdEnfsL-jBYQEYnUhIX5qMyRjEvaUZTjC2jdtbuIwu60CWHg4aME0NmW9OJ1bEk3DOE_tSDZGLbEvxQvEPHc5yAUaLZjQ8Ub-SSbfSJ46nxjMHXHreCx5ceYqRmV4">
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
<img alt="Testimonial Author" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA7VS_Jo3Qxj53Ei0ik3hoyVT41TCatq_Ak3UFxgLZauIzt7DMcvb6igjgOfvjR1TMmPc3WSXguWgchINsR6G1-lEcYlmoBhld9HvDMCKRztVrd4WfUY0IAySCgZLyk1cXdpxydDfAZKxk7vBzGV3ejjhluD0cd-YVHN7Pk5kFczVFrAF0XVzlTiMZWn6o4K6QFhP6hHjPhNhmHQgB2bY2UTB71-GneeiWj8pttmT-eBcYPcrCn5fM">
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
<img alt="Testimonial Author" class="w-full h-full object-contain p-2" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBAXuJOUK7bW9uWof3P7-yrRTNulzUahGPLj5axEcBycLDzCx8bFETlxtzW38Kof_uZdIgvck2wxd2hUZgVd9q9-rsUSbEQ4kLSlbAFhyiun4fII94GenJhUmp23thfHgc1Wg98fYqJIrfAZUz3bXCO6XyxMSRbsaY7B30uyUDzBFz0lFLRyxypHaop9YCDjRFWUaSN8q0aMg481Fvy6I82i5Z6NvSEtuyrwBgzUuVsLNkHeGCmEIs">
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
<img alt="Testimonial Author" class="w-full h-full object-contain p-2" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAgtbmXO5lt--KlimlYMDQ6oJxb4MenfvKagNFSpD9JCLR03W4WOiSSsoxCiosk8JBKe1erXfuUEotBPdefmKxuydLoJKN83Tq5EZZgsNJvYc2dwPp_H-iui7-EAkxn3i3ZyFbl7zcVsfKmsh8Ya2yB4AsCAOmgju5xXn9t-vGdGcG-5viuG0Unoa2rrUvTjCco3m-538ujAIrj03A064lXMwr0K1MHX1dvdPt3m0SEFTSCgu4Titg">
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
<section class="py-16 bg-white relative overflow-hidden"><div id="parts-bg-decor" class="absolute top-0 bottom-0 left-0 w-[40%] bg-cover bg-center z-0" style="background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuBm1dXqQZO5qq0huZaYWEP1SYz_0rWJNVUEO9nDG721uzc6xYdAmFvU77cRpKOHKgUrif-yYa4-mXQ5cPDvm8hnqaTBnStQFInTZTC8gjMY41eE0Je7S1A-seccD4yg2uwsG6LlOF8IeZHDFqHJZoIFHJxLZODvmkiy_w1PNSA-VD5Jk-N309tREBKroOBVdAtLcMH__Lre4D6O89ly2dajJq-QJk44KncBoG4F6_PnxKWZzODpeZTIzrY_dpqrTw0C&quot;); clip-path: ellipse(100% 70% at 0% 50%);"></div>
<div id="parts-content" class="px-4 relative z-10 ml-[42%] pr-12 max-w-5xl">
<h2 id="parts-heading" class="font-condensed text-right" style="font-size: 80px; font-weight: 400; line-height: 25px; color: rgb(23, 55, 83); text-align: right; margin-bottom: 3rem;">PARTS</h2><div class="flex flex-col md:flex-row items-center mb-16">
<div class="w-full md:w-1/3 mb-8 md:mb-0">
<img alt="TPS Distributor of the Year" class="mx-auto -mt-12" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD5Y27vh2bWwL4ZHHgfXjklP_9WywVWUBkNKxQ0LNdw9E9re81AzRA2DhMAfPH8oLXgaKQeHy8HU9zEgZMxVxVJw314qc2IoiHzBkpAV1lFhckRL54CEk9XpL9oRxe83kQlW3t1lLCc_lOW1G1pCYh5sXuOgnLXdiUDx2mRc5sbBGWjXX70xoaxjpM6Zd7WX5BBt4Xj4Ywsx8X08O5k0Tns6XQW1TPqX2Clr0gFfuZ2sl5rOFFnlf4cdk6ZvmAjw6aH" style="width: 150px; height: 120px; object-fit: contain;">
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
<div class="absolute inset-0 z-0 opacity-5" style="background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuDqyM8bftIIsKj7dFpDa-XnPW-Mrt0PpTFyOVlcmpIJO4fyj3dCAWB_b5NXDzJlQ0q41CVNDcot53R5A4sDsqRF0Bz6Ydg3zaw3YUfsEI1qKv6Oa8UndjIo8SGj1MM-U4Hvx-Afij_nNyAWGvmvYazfzzrtWzmhjFCGG-NQ9tyu_D9kCVDxp8ZefJbwsAPWvgTPvXRzCPTseAyzLFMQsjiaAmPzsyB6ncxF4i2VGN7A656fbrUCGw8&quot;); background-repeat: no-repeat; background-position: right center; background-size: 800px;"></div>
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
<img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAvERANuyWu9VFu9QSPCIqmdlOrwkwNVi0C9swP42NhH4Hrdr_LdO80RjNCMGRYfpUKbh-MUNvYbUI0VpGDQln3OK-7T65baaFbR6A8BXQiu1GFcairEW2bPcQWCMXSdnGlO5AO9HuVKYmdtmr-qog8JiJd5JqMSF9Fjg8dvr3RQnMKQR1La13IgiX4syyZICpLfx2jEPwgxmIFiBvzXWILzjh9oUTQrJJP2xpB89iL26s7giXvI7QljyiVQvXMrsh5" alt="Service Locations Map" class="w-full h-auto rounded shadow-lg">
</div></div>
</section>
<!-- END: Service Section -->

<!-- BEGIN: Trucks Equipment Section -->
<section class="relative py-24 text-white overflow-hidden bg-[#2d2d2d]" style="clip-path: polygon(0px 15%, 100% 0px, 100% 100%, 0px 100%);">
  <!-- Background Image at Bottom -->
  <div class="absolute inset-0 z-0 opacity-40 bg-cover bg-bottom" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBDped6QtWszBNSy8exttkSIHiC7X2cLYw9gDnLrBmYjfCfCZ8kJaSki76cIyYwuAM0Hr6DNzh7kZMsyM2Cp-4UmZafuZMAOLSFw6RVwsAkQFDwKa4pUZ2JNqOZEf1x3ulr6RFKK_Ji5i9SJhdh9c6Kb0FUvz26YmB2g6EU3YloL1iS0LzPCNnU5bsDqVrMQOw5XIZqNmqQAwN9BrznajhIX16o8globQrUdsp5dRB6dKp90cnh_nwRVQJR9EGJqyMP');"></div>

  <div class="container mx-auto px-8 relative z-10 flex flex-col md:flex-row items-center">
    <!-- Left Side: Badge -->
    <div class="w-full md:w-1/3 flex justify-center md:justify-start mb-8 md:mb-0">
      <div class="border-4 border-white/20 p-1">
        <img alt="UTA Individual Member of the Year" class="w-full max-w-[280px] h-auto" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB_0CSXST-fWNE6j8wvnNnSWWCZl6ZbPOT2gX6W1_FxQeRE3IibXl_g5uISKBFrAlbjS-P5HsJXGFCC4YzjxKJ71ANHVuCmKIwTMypVObLnp3ZxJwg1q0ScTo5Av1tK2GWJN4NbZDBrtQ06FlNCjpnv_n9KK9ANlrxQgFYGyI7llNggieJOxHxxfrIbJgeVNIR3e8zRyUVa6SrlqkIFmyRwcGAz7aRNXsq7MmB_kxA0qd5pZpkKD89GV8sk7tzaBYVl">
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
@endsection
