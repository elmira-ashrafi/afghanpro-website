@extends('layouts.dashboard')

@section('title', 'فروشگاه اکانت‌های پرمیوم')
@section('page-title', 'فروشگاه اکانت‌های پرمیوم')

@section('content')
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-2">خرید انواع اکانت‌های پرمیوم</h5>
                        <p class="text-muted mb-0">خرید اکانت‌های پرمیوم سرویس‌های مختلف با بهترین قیمت و پشتیبانی 24 ساعته</p>
                    </div>
                    <div class="col-md-4 mt-3 mt-md-0">
                        <form action="{{ route('dashboard.shop.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="جستجو در محصولات...">
                                <button class="btn btn-outline-primary" type="submit">
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

@if(auth()->user()->isAdmin())
<!-- Admin Actions (visible only for admins) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="ri-admin-line fs-4 text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">مدیریت فروشگاه</h6>
                        <p class="text-muted mb-0">امکانات مدیریت محصولات و سفارشات فروشگاه برای مدیران سایت</p>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ route('dashboard.shop.admin.index') }}" class="btn btn-primary me-2">
                            <i class="ri-shopping-basket-line me-1"></i>مدیریت محصولات
                        </a>
                        <a href="{{ route('dashboard.shop.admin.create') }}" class="btn btn-success me-2">
                            <i class="ri-add-line me-1"></i>افزودن محصول جدید
                        </a>
                        <a href="{{ route('dashboard.shop.admin.categories.index') }}" class="btn btn-info">
                            <i class="ri-list-check me-1"></i>مدیریت دسته‌بندی‌ها
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="alert alert-info mb-4">
    <div class="d-flex">
        <div class="me-3">
            <i class="ri-information-line fs-4"></i>
        </div>
        <div>
            <h6 class="alert-heading mb-1">اطلاعات روش پرداخت</h6>
            <p class="mb-0">
                خرید اکانت‌های پرمیوم فقط با کیف پول افغانی مقدور است یا از طریق مراجعه حضوری به نمایندگی‌ها امکان‌پذیر می‌باشد.
            </p>
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
                            <a class="nav-link {{ !request()->has('sort') ? 'active' : '' }}" href="{{ route('dashboard.shop.index') }}">
                                <i class="ri-apps-line me-1"></i>جدیدترین
                            </a>
                        </li>
                        <li class="nav-item flex-shrink-0">
                            <a class="nav-link {{ request()->get('sort') == 'price-low' ? 'active' : '' }}" href="{{ route('dashboard.shop.index', ['sort' => 'price-low']) }}">
                                <i class="ri-sort-asc me-1"></i>ارزان‌ترین
                            </a>
                        </li>
                        <li class="nav-item flex-shrink-0">
                            <a class="nav-link {{ request()->get('sort') == 'price-high' ? 'active' : '' }}" href="{{ route('dashboard.shop.index', ['sort' => 'price-high']) }}">
                                <i class="ri-sort-desc me-1"></i>گران‌ترین
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <ul class="nav nav-pills mb-3" id="shop-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ !request()->routeIs('dashboard.shop.category') ? 'active' : '' }}" href="{{ route('dashboard.shop.index') }}">
                    <i class="ri-apps-line me-1"></i>همه
                </a>
            </li>
            @foreach($categories->where('parent_id', null) as $category)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ request()->is('dashboard/shop/category/'.$category->slug) ? 'active' : '' }}" href="{{ route('dashboard.shop.category', $category->slug) }}">
                        @if($category->icon)
                            <i class="{{ $category->icon }} me-1"></i>
                        @endif
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<div class="row g-4">
    @forelse($products as $product)
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100 product-card {{ $product->getPriceRangeAttribute() == 'ناموجود' ? 'out-of-stock' : '' }}">
                <div class="position-relative">
                    @if($product->thumbnail)
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" class="card-img-top" alt="{{ $product->name }}">
                    @else
                        <img src="https://placehold.co/600x400/e9ecef/6c757d?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}">
                    @endif
                    
                    @if($loop->index < 4)
                        <div class="product-tag bg-{{ ['primary', 'danger', 'success', 'warning'][$loop->index] }}">
                            {{ ['پرفروش', 'محبوب', 'جدید', 'تخفیف ویژه'][$loop->index] }}
                        </div>
                    @endif
                    
                    @if($product->getPriceRangeAttribute() == 'ناموجود')
                        <div class="product-tag bg-secondary">ناموجود</div>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <div class="mb-2">
                        @foreach($product->categories as $category)
                            <span class="badge bg-info me-1">{{ $category->name }}</span>
                        @endforeach
                        @if($product->is_variable)
                            <span class="badge bg-primary">ویژگی‌های متغیر</span>
                        @endif
                    </div>
                    <p class="card-text text-muted small flex-grow-1">{{ $product->short_description }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($product->getPriceRangeAttribute() == 'ناموجود')
                                <span class="text-danger fs-5 fw-bold">ناموجود</span>
                            @else
                                <span class="text-danger fs-5 fw-bold">{{ $product->price_range }}</span>
                            @endif
                        </div>
                        <a href="{{ route('dashboard.shop.product', $product->id) }}" class="btn btn-sm btn-primary">
                            <i class="ri-shopping-cart-line me-1"></i>
                            مشاهده
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="ri-information-line fs-4"></i>
                    </div>
                    <div>
                        <h6 class="alert-heading mb-1">محصولی یافت نشد</h6>
                        <p class="mb-0">در حال حاضر هیچ محصولی در فروشگاه موجود نیست.</p>
                    </div>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($products->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $products->links('vendor.pagination.custom') }}
</div>
@endif

<!-- Shopping Cart Floating Button -->
<div class="position-fixed bottom-0 end-0 m-4" style="display: none;">
    <a href="{{ route('dashboard.shop.cart') }}" class="btn btn-primary btn-lg rounded-circle shadow">
        <i class="ri-shopping-cart-2-line"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">
            {{ count(session('cart', [])) }}
            <span class="visually-hidden">محصول در سبد خرید</span>
        </span>
    </a>
</div>
@endsection

@push('styles')
<style>
    .product-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .product-card.out-of-stock {
        opacity: 0.7;
    }
    
    .product-tag {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        color: #fff;
    }
    
    .nav-pills .nav-link {
        color: #6c757d;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        margin-right: 0.5rem;
    }
    
    .nav-pills .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
    }
    
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
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Shopping cart floating button logic
        const cartCount = document.querySelector('.cart-count');
        if (cartCount && parseInt(cartCount.textContent.trim()) > 0) {
            cartCount.parentElement.style.display = 'block';
        }
    });
</script>
@endpush 