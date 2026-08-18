@extends('layout')

@section('title', 'Shop')

@section('breadcrumb')
    <li class="breadcrumb-item active">Shop</li>
@endsection

@section('content')
<div class="container-fluid py-5 px-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <h5 class="mb-4">Filter Products</h5>

            <!-- Subcategory Filter -->
            <div class="mb-4">
                <h6 class="mb-3">Subcategory</h6>
                @php
                    $allSubcategories = array_keys($productCountBySubcategory ?? []);
                @endphp
                @forelse ($allSubcategories as $subcat)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="subcategory[]" value="{{ $subcat }}" id="subcat-{{ $loop->index }}">
                        <label class="form-check-label small" for="subcat-{{ $loop->index }}">
                            ☐ {{ $subcat }}
                            <span class="text-muted">({{ (int) ($productCountBySubcategory[$subcat] ?? 0) }})</span>
                        </label>
                    </div>
                @empty
                    <p class="text-muted small">No subcategories</p>
                @endforelse
            </div>

            <!-- Year Filter -->
            <div class="mb-4">
                <h6 class="mb-3">Year</h6>
                @php
                    $allYears = array_keys($productCountByYear ?? []);
                    sort($allYears);
                @endphp
                @forelse ($allYears as $year)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="year[]" value="{{ $year }}" id="year-{{ $year }}">
                        <label class="form-check-label small" for="year-{{ $year }}">
                            ☐ {{ $year }}
                            <span class="text-muted">({{ (int) ($productCountByYear[$year] ?? 0) }})</span>
                        </label>
                    </div>
                @empty
                    <p class="text-muted small">No years</p>
                @endforelse
            </div>

            <!-- Mileage Filter -->
            <div class="mb-4">
                <h6 class="mb-3">Mileage</h6>
                @php
                $mileageRanges = [
                    'na' => 'N/A',
                    '0-99999' => '0–99,999',
                    '100000-199999' => '100,000–199,999',
                    '200000-299999' => '200,000–299,999',
                    '300000-399999' => '300,000–399,999',
                    '400000-499999' => '400,000–499,999',
                    '500000-599999' => '500,000–599,999',
                    '600000-699999' => '600,000–699,999',
                    '700000-799999' => '700,000–799,999',
                    '800000-899999' => '800,000–899,999',
                    '900000-999999' => '900,000–999,999',
                ];
                @endphp
                @foreach ($mileageRanges as $key => $label)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="mileage" value="{{ $key }}" id="mileage-{{ $key }}">
                    <label class="form-check-label small" for="mileage-{{ $key }}">
                        ☐ {{ $label }} <span class="text-muted">({{ (int) ($productCountByMileage[$key] ?? 0) }})</span>
                    </label>
                </div>
                @endforeach
            </div>

            <!-- Horsepower Filter -->
            <div class="mb-4">
                <h6 class="mb-3">Horsepower</h6>
                @php
                $hpRanges = [
                    'na' => 'N/A',
                    '250-299' => '250–299',
                    '300-349' => '300–349',
                    '350-399' => '350–399',
                    '400-449' => '400–449',
                    '450-499' => '450–499',
                    '500+' => '500+',
                ];
                @endphp
                @foreach ($hpRanges as $key => $label)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="horsepower" value="{{ $key }}" id="horsepower-{{ $key }}">
                    <label class="form-check-label small" for="horsepower-{{ $key }}">
                        ☐ {{ $label }} <span class="text-muted">({{ (int) ($productCountByHorsepower[$key] ?? 0) }})</span>
                    </label>
                </div>
                @endforeach
            </div>

            <!-- Clear All Button -->
            <div class="d-grid gap-2 mt-4">
                <a href="{{ route('shop.index') }}" class="btn btn-outline-primary btn-sm">Clear All Filters</a>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h4>{{ $category->name ?? 'All Products' }}</h4>
                </div>
                <div class="col-md-6 text-md-end">
                    <form method="GET" action="{{ isset($category) ? route('category.show', $category->slug) : route('shop.index') }}" class="d-flex gap-2 justify-content-md-end">
                        @if(request()->filled('price_min'))
                            <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                        @endif
                        @if(request()->filled('price_max'))
                            <input type="hidden" name="price_max" value="{{ request('price_max') }}">
                        @endif
                        <select name="sort" class="form-select form-select-sm">
                            <option value="">Sort By</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                        @if(request()->filled('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
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
@endsection
