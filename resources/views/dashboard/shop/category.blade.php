@extends('layouts.dashboard')

@section('title', $category->name)
@section('page-title', $category->name)

@section('content')
<!-- Category Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-body p-0">
                <div class="position-relative">
                    <div class="bg-primary bg-opacity-10 px-4 py-4">
                        <div class="row align-items-center">
                            <div class="col-lg-7">
                                <h2 class="mb-2">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} me-2 text-primary"></i>
                                    @endif
                                    {{ $category->name }}
                                </h2>
                                @if($category->description)
                                    <p class="text-muted mb-0">{{ $category->description }}</p>
                                @endif
                            </div>
                            <div class="col-lg-5 mt-3 mt-lg-0">
                                <form action="{{ route('dashboard.shop.category', $category->slug) }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="جستجو در محصولات...">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="ri-search-line"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Breadcrumb & Navigation -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <nav aria-label="breadcrumb" class="mb-2 mb-md-0">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">داشبورد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.shop.index') }}">فروشگاه</a></li>
                            @if($category->parent)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard.shop.category', $category->parent->slug) }}">
                                        {{ $category->parent->name }}
                                    </a>
                                </li>
                            @endif
                            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                        </ol>
                    </nav>
                    
                    @if($category->children->count() > 0)
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="subcategoriesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-list-check me-1"></i>زیردسته‌ها
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="subcategoriesDropdown">
                            @foreach($category->children as $child)
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard.shop.category', $child->slug) }}">
                                        @if($child->icon)
                                            <i class="{{ $child->icon }} me-1"></i>
                                        @endif
                                        {{ $child->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sorting Options -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-2">
                <div class="category-scrollbar">
                    <ul class="nav nav-pills d-flex flex-nowrap">
                        <li class="nav-item flex-shrink-0">
                            <a class="nav-link {{ !request()->has('sort') ? 'active' : '' }}" href="{{ route('dashboard.shop.category', $category->slug) }}">
                                <i class="ri-apps-line me-1"></i>جدیدترین
                            </a>
                        </li>
                        <li class="nav-item flex-shrink-0">
                            <a class="nav-link {{ request()->get('sort') == 'price-low' ? 'active' : '' }}" href="{{ route('dashboard.shop.category', ['slug' => $category->slug, 'sort' => 'price-low']) }}">
                                <i class="ri-sort-asc me-1"></i>ارزان‌ترین
                            </a>
                        </li>
                        <li class="nav-item flex-shrink-0">
                            <a class="nav-link {{ request()->get('sort') == 'price-high' ? 'active' : '' }}" href="{{ route('dashboard.shop.category', ['slug' => $category->slug, 'sort' => 'price-high']) }}">
                                <i class="ri-sort-desc me-1"></i>گران‌ترین
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products Grid -->
<div class="row g-4">
    @forelse($products as $product)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="card product-card h-100 border-0 shadow-sm {{ $product->getPriceRangeAttribute() == 'ناموجود' ? 'out-of-stock' : '' }}">
                <div class="position-relative overflow-hidden" style="height: 170px;">
                    @if($product->thumbnail)
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" class="card-img-top h-100 w-100 object-fit-cover" alt="{{ $product->name }}">
                    @else
                        <div class="bg-light h-100 d-flex align-items-center justify-content-center">
                            <span class="text-muted fs-3">{{ substr($product->name, 0, 1) }}</span>
                        </div>
                    @endif
                    
                    @if($product->getPriceRangeAttribute() == 'ناموجود')
                        <div class="product-tag unavailable">ناموجود</div>
                    @endif
                </div>
                <div class="card-body d-flex flex-column p-3">
                    <div class="d-flex mb-2">
                        @foreach($product->categories->take(2) as $productCategory)
                            <span class="badge bg-light text-primary me-1 py-1 px-2">{{ $productCategory->name }}</span>
                        @endforeach
                        @if($product->is_variable)
                            <span class="badge bg-primary py-1 px-2 ms-auto">متغیر</span>
                        @endif
                    </div>
                    <h5 class="card-title mb-2">{{ $product->name }}</h5>
                    <p class="card-text text-muted small mb-3 flex-grow-1">{{ \Illuminate\Support\Str::limit($product->short_description, 60) }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <div>
                            @if($product->getPriceRangeAttribute() == 'ناموجود')
                                <span class="text-danger fw-bold">ناموجود</span>
                            @else
                                <span class="text-primary fw-bold">{{ $product->price_range }}</span>
                            @endif
                        </div>
                        <a href="{{ route('dashboard.shop.product', $product->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="ri-shopping-cart-line"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-5 text-center">
                    <div class="mb-3">
                        <i class="ri-shopping-bag-line fs-1 text-muted"></i>
                    </div>
                    <h5>محصولی یافت نشد</h5>
                    <p class="text-muted">در حال حاضر هیچ محصولی در این دسته‌بندی موجود نیست.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $products->links('vendor.pagination.custom') }}
</div>

<!-- Shopping Cart Floating Button -->
<div class="position-fixed bottom-0 end-0 m-4">
    <a href="{{ route('dashboard.shop.cart') }}" class="btn btn-primary rounded-circle shadow-lg p-3">
        <i class="ri-shopping-cart-2-line fs-4"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">
            {{ count(session('cart', [])) }}
        </span>
    </a>
</div>
@endsection

@push('styles')
<style>
    .category-scrollbar {
        overflow-x: auto;
        scrollbar-width: thin;
    }
    
    .category-scrollbar::-webkit-scrollbar {
        height: 5px;
    }
    
    .category-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .category-scrollbar::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 5px;
    }
    
    .nav-pills .nav-link {
        color: #6c757d;
        border-radius: 30px;
        padding: 0.5rem 1rem;
        margin-right: 0.5rem;
        white-space: nowrap;
        transition: all 0.2s;
    }
    
    .nav-pills .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
    }
    
    .product-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 10px;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    
    .product-card.out-of-stock {
        opacity: 0.7;
    }
    
    .product-tag {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(13, 110, 253, 0.85);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
    }
    
    .product-tag.unavailable {
        background-color: rgba(108, 117, 125, 0.85);
    }
    
    .object-fit-cover {
        object-fit: cover;
    }
</style>
@endpush 