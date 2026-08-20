@extends('layout')

@section('title', isset($category) ? $category->name : 'Shop')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <style>
        .filter-panel-title {
            font-family: "Roboto Condensed", sans-serif;
            font-size: 18px; font-weight: 400; line-height: normal;
            color: rgb(6, 103, 210);
            margin-bottom: 5px;
        }
        .chosen-container .chosen-single {
            font-family: "Roboto Condensed", sans-serif;
            font-size: 18px; font-weight: 200; line-height: normal;
            color: rgb(68, 68, 68);
            height: 38px;
            line-height: 36px;
            border-radius: 0;
            background: #fff;
            box-shadow: none;
        }
        .chosen-container .chosen-results li {
            font-family: "Roboto Condensed", sans-serif;
            font-size: 16px; font-weight: 200;
        }
        .filter-accordion-btn {
            font-family: "Roboto Condensed", sans-serif;
            font-size: 16px; font-weight: 200; line-height: 25.6px;
            color: rgb(34, 34, 34);
            background: none; border: none; width: 100%; text-align: left;
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 0;
        }
        .filter-accordion-btn:focus { outline: none; }
        .filter-checkbox-label {
            font-family: "Roboto Condensed", sans-serif;
            font-size: 16px; font-weight: 200; line-height: 24px;
            cursor: pointer;
            color: rgb(6, 103, 210);
        }
        .filter-accordion-btn.collapsed .fa-chevron-down {
            transform: rotate(-90deg);
            transition: transform 0.2s;
        }
        .filter-accordion-btn:not(.collapsed) .fa-chevron-down {
            transform: rotate(0deg);
            transition: transform 0.2s;
        }
        .filter-accordion-panel[hidden] { display: none !important; }
    </style>
    <noscript>
        <style>
            .accordion-collapse.collapse { display: block !important; }
        </style>
    </noscript>
@endpush

@section('breadcrumb')
    @if(isset($category))
        <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
        <li class="breadcrumb-item active">{{ $category->name }}</li>
    @else
        <li class="breadcrumb-item active">Shop</li>
    @endif
@endsection

@section('content')
<div class="container-fluid py-5 px-5">
    <div class="row">
        <!-- ═══════════════════════════════════════════════════════════════
             Sidebar Filters — single GET form, auto-submits on change
             ═══════════════════════════════════════════════════════════════ -->
        <div class="col-lg-3 mb-4">
            {{-- ── TOP PANEL (Unit Type, Manufacturer, Model) ── --}}
            <div class="mb-4">
                @php $allCategories = \App\Models\Category::all(); @endphp
                <div class="mb-3">
                    <div class="filter-panel-title">Unit Type</div>
                    <select id="unit-type-select" class="chosen-select w-100">
                        <option value="{{ route('shop.index') }}">All Unit Types</option>
                        @foreach($allCategories as $cat)
                            <option value="{{ route('category.show', $cat->slug) }}" {{ (isset($category) && $category->id == $cat->id) ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <div class="filter-panel-title">Manufacturer</div>
                    <select class="chosen-select w-100">
                        <option value="">(All)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <div class="filter-panel-title">Model</div>
                    <select class="chosen-select w-100">
                        <option value="">(All)</option>
                    </select>
                </div>

                <div class="d-grid mt-3">
                    <a href="{{ isset($category) ? route('category.show', $category->slug) : route('shop.index') }}" class="btn btn-outline-secondary btn-sm">Clear Filters</a>
                </div>
            </div>

            <form
                method="GET"
                action="{{ isset($category) ? route('category.show', $category->slug) : route('shop.index') }}"
                id="sidebar-filter-form"
            >
                {{-- Preserve search and sort --}}
                @if(request()->filled('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request()->filled('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif

                <h5 class="mb-4 d-none">Filter Products</h5>

                {{-- ── EXISTING FILTERS (Accordions) ── --}}
                <div class="accordion" id="filtersAccordion">

                    {{-- ── Subcategory ── --}}
                    @if(!empty($productCountBySubcategory))
                    @php $hasSubcat = !empty($selectedSubcategories); $subcatOpen = $hasSubcat; @endphp
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingSubcat">
                            <button class="filter-accordion-btn {{ $subcatOpen ? '' : 'collapsed' }}" type="button" data-bs-target="#collapseSubcat" aria-expanded="{{ $subcatOpen ? 'true' : 'false' }}" aria-controls="collapseSubcat">
                                Category <i class="fa fa-chevron-down"></i>
                            </button>
                        </h2>
                        <div id="collapseSubcat" class="accordion-collapse filter-accordion-panel" aria-labelledby="headingSubcat" {{ $subcatOpen ? '' : 'data-collapsed="true"' }}>
                            <div class="accordion-body pt-0 pb-3 px-0">
                                @foreach ($productCountBySubcategory as $subcat => $count)
                                    <div class="form-check">
                                        <input
                                            class="form-check-input filter-checkbox"
                                            type="checkbox"
                                            name="subcategory[]"
                                            value="{{ $subcat }}"
                                            id="subcat-{{ $loop->index }}"
                                            {{ in_array($subcat, $selectedSubcategories ?? []) ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label filter-checkbox-label" for="subcat-{{ $loop->index }}">
                                            {{ $subcat }}
                                            <span class="text-muted">({{ $count }})</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ── Year ── --}}
                    @if(!empty($productCountByYear))
                    @php $hasYear = !empty($selectedYears); $yearOpen = $hasYear; @endphp
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingYear">
                            <button class="filter-accordion-btn {{ $yearOpen ? '' : 'collapsed' }}" type="button" data-bs-target="#collapseYear" aria-expanded="{{ $yearOpen ? 'true' : 'false' }}" aria-controls="collapseYear">
                                Year <i class="fa fa-chevron-down"></i>
                            </button>
                        </h2>
                        <div id="collapseYear" class="accordion-collapse filter-accordion-panel" aria-labelledby="headingYear" {{ $yearOpen ? '' : 'data-collapsed="true"' }}>
                            <div class="accordion-body pt-0 pb-3 px-0">
                                @foreach ($productCountByYear as $year => $count)
                                    <div class="form-check">
                                        <input
                                            class="form-check-input filter-checkbox"
                                            type="checkbox"
                                            name="year[]"
                                            value="{{ $year }}"
                                            id="year-{{ $year }}"
                                            {{ in_array((string) $year, array_map('strval', $selectedYears ?? [])) ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label filter-checkbox-label" for="year-{{ $year }}">
                                            {{ $year }}
                                            <span class="text-muted">({{ $count }})</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ── Mileage ── --}}
                    @php
                        $visibleMileageBuckets = collect($mileageBuckets ?? [])->filter(
                            fn ($b) => ($productCountByMileage[$b['key']] ?? 0) > 0
                        );
                        $hasMileage = !empty($selectedMileage); $mileageOpen = $hasMileage;
                    @endphp
                    @if($visibleMileageBuckets->isNotEmpty())
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingMileage">
                            <button class="filter-accordion-btn {{ $mileageOpen ? '' : 'collapsed' }}" type="button" data-bs-target="#collapseMileage" aria-expanded="{{ $mileageOpen ? 'true' : 'false' }}" aria-controls="collapseMileage">
                                Mileage <i class="fa fa-chevron-down"></i>
                            </button>
                        </h2>
                        <div id="collapseMileage" class="accordion-collapse filter-accordion-panel" aria-labelledby="headingMileage" {{ $mileageOpen ? '' : 'data-collapsed="true"' }}>
                            <div class="accordion-body pt-0 pb-3 px-0">
                                @foreach ($visibleMileageBuckets as $bucket)
                                    @php $mKey = $bucket['key']; @endphp
                                    <div class="form-check">
                                        <input
                                            class="form-check-input filter-checkbox"
                                            type="checkbox"
                                            name="mileage[]"
                                            value="{{ $mKey }}"
                                            id="mileage-{{ $mKey }}"
                                            {{ in_array($mKey, $selectedMileage ?? []) ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label filter-checkbox-label" for="mileage-{{ $mKey }}">
                                            {{ $bucket['label'] }}
                                            <span class="text-muted">({{ $productCountByMileage[$mKey] ?? 0 }})</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ── Horsepower ── --}}
                    @php
                        $visibleHpBuckets = collect($horsepowerBuckets ?? [])->filter(
                            fn ($b) => ($productCountByHorsepower[$b['key']] ?? 0) > 0
                        );
                        $hasHp = !empty($selectedHorsepower); $hpOpen = $hasHp;
                    @endphp
                    @if($visibleHpBuckets->isNotEmpty())
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingHp">
                            <button class="filter-accordion-btn {{ $hpOpen ? '' : 'collapsed' }}" type="button" data-bs-target="#collapseHp" aria-expanded="{{ $hpOpen ? 'true' : 'false' }}" aria-controls="collapseHp">
                                Horsepower <i class="fa fa-chevron-down"></i>
                            </button>
                        </h2>
                        <div id="collapseHp" class="accordion-collapse filter-accordion-panel" aria-labelledby="headingHp" {{ $hpOpen ? '' : 'data-collapsed="true"' }}>
                            <div class="accordion-body pt-0 pb-3 px-0">
                                @foreach ($visibleHpBuckets as $bucket)
                                    @php $hKey = $bucket['key']; @endphp
                                    <div class="form-check">
                                        <input
                                            class="form-check-input filter-checkbox"
                                            type="checkbox"
                                            name="horsepower[]"
                                            value="{{ $hKey }}"
                                            id="horsepower-{{ $hKey }}"
                                            {{ in_array($hKey, $selectedHorsepower ?? []) ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label filter-checkbox-label" for="horsepower-{{ $hKey }}">
                                            {{ $bucket['label'] }}
                                            <span class="text-muted">({{ $productCountByHorsepower[$hKey] ?? 0 }})</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <noscript>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-sm">Apply Filters</button>
                    </div>
                </noscript>
            </form>
        </div>

        <!-- ═══════════════════════════════════════════════════════════════
             Products Grid
             ═══════════════════════════════════════════════════════════════ -->
        <div class="col-lg-9">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h4>{{ $category->name ?? 'All Products' }}</h4>
                </div>
                <div class="col-md-6 text-md-end">
                    <form method="GET" action="{{ isset($category) ? route('category.show', $category->slug) : route('shop.index') }}" class="d-flex gap-2 justify-content-md-end">
                        {{-- Preserve all active sidebar filters when changing sort --}}
                        @foreach(request()->except(['sort', 'page']) as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <select name="sort" class="form-select form-select-sm">
                            <option value="">Sort By</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                    </form>
                </div>
            </div>

            <div class="row">
                @forelse($products as $product)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if($product->primary_image_url)
                                <img src="{{ $product->primary_image_url }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="{{ asset('img/product-' . (($loop->iteration % 18) + 1) . '.png') }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                            @endif

                            @if($product->is_featured)
                                <span class="badge bg-danger position-absolute" style="top: 10px; right: 10px;">Featured</span>
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text text-muted small">{{ Str::limit($product->description, 50) }}</p>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <span class="h5 mb-0 text-primary">${{ number_format($product->price, 2) }}</span>
                                        @if($product->original_price)
                                            <small class="text-decoration-line-through text-muted ms-2">${{ number_format($product->original_price, 2) }}</small>
                                        @endif
                                    </div>
                                    <small class="badge bg-info">{{ $product->quantity }} in stock</small>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                                </div>
                            </div>

                            <div class="card-footer bg-white">
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->quantity }}">
                                        <button class="btn btn-primary" type="submit">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">No products found.</div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="row mt-4">
                    <div class="col-12">
                        {{ $products->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Auto-submit sidebar form on checkbox change --}}
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script>
    // Use jQuery's ready handler — it fires immediately if the DOM is already loaded
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Chosen dropdowns
        if (window.jQuery && window.jQuery.fn.chosen) {
            window.jQuery('.chosen-select').chosen({width: '100%', disable_search_threshold: 10});
        }

        // Wire up the Unit Type dropdown via jQuery so Chosen's change event propagates
        var unitType = document.getElementById('unit-type-select');
        if (unitType) {
            unitType.addEventListener('change', function () {
                if (this.value) window.location.assign(this.value);
            });
        }

        // Manual accordion toggle (works even if Bootstrap collapse misfires)
        document.querySelectorAll('.filter-accordion-btn').forEach(function (btn) {
            var targetSelector = btn.getAttribute('data-bs-target');
            var target = document.querySelector(targetSelector);
            if (!target) return;

            // Panels are only hidden after JavaScript has initialized, so the
            // filter form stays usable if scripts fail to load.
            if (target.dataset.collapsed === 'true') target.hidden = true;

            btn.addEventListener('click', function () {
                var isOpen = !target.hidden;
                target.hidden = isOpen;
                btn.classList.toggle('collapsed', isOpen);
                btn.setAttribute('aria-expanded', String(!isOpen));
            });
        });

        // Auto-submit sidebar form on checkbox change
        var form = document.getElementById('sidebar-filter-form');
        if (!form) return;
        var checkboxes = form.querySelectorAll('.filter-checkbox');
        checkboxes.forEach(function (cb) {
            cb.addEventListener('change', function () {
                form.submit();
            });
        });
    });
</script>
@endpush
@endsection
