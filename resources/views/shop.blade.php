@extends('layout')

@section('title', isset($category) ? $category->name : 'Shop')

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

                <h5 class="mb-4">Filter Products</h5>

                {{-- ── Subcategory (labeled "Category" per reference design) ── --}}
                @if(!empty($productCountBySubcategory))
                <div class="mb-4">
                    <h6 class="mb-3">Category</h6>
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
                            <label class="form-check-label small" for="subcat-{{ $loop->index }}">
                                {{ $subcat }}
                                <span class="text-muted">({{ $count }})</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                @endif

                {{-- ── Year ── --}}
                @if(!empty($productCountByYear))
                <div class="mb-4">
                    <h6 class="mb-3">Year</h6>
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
                            <label class="form-check-label small" for="year-{{ $year }}">
                                {{ $year }}
                                <span class="text-muted">({{ $count }})</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                @endif

                {{-- ── Mileage (dynamic buckets) ── --}}
                @php
                    $visibleMileageBuckets = collect($mileageBuckets ?? [])->filter(
                        fn ($b) => ($productCountByMileage[$b['key']] ?? 0) > 0
                    );
                @endphp
                @if($visibleMileageBuckets->isNotEmpty())
                <div class="mb-4">
                    <h6 class="mb-3">Mileage</h6>
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
                            <label class="form-check-label small" for="mileage-{{ $mKey }}">
                                {{ $bucket['label'] }}
                                <span class="text-muted">({{ $productCountByMileage[$mKey] ?? 0 }})</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                @endif

                {{-- ── Horsepower (dynamic buckets) ── --}}
                @php
                    $visibleHpBuckets = collect($horsepowerBuckets ?? [])->filter(
                        fn ($b) => ($productCountByHorsepower[$b['key']] ?? 0) > 0
                    );
                @endphp
                @if($visibleHpBuckets->isNotEmpty())
                <div class="mb-4">
                    <h6 class="mb-3">Horsepower</h6>
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
                            <label class="form-check-label small" for="horsepower-{{ $hKey }}">
                                {{ $bucket['label'] }}
                                <span class="text-muted">({{ $productCountByHorsepower[$hKey] ?? 0 }})</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                @endif

                {{-- ── Buttons ── --}}
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-sm">Apply Filters</button>
                    <a href="{{ isset($category) ? route('category.show', $category->slug) : route('shop.index') }}" class="btn btn-outline-secondary btn-sm">Clear All Filters</a>
                </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
