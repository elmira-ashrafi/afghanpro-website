@extends('layouts.dashboard')

@section('title', $product->name)
@section('page-title', $product->name)

@section('content')
<div class="row g-4">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">داشبورد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.shop.index') }}">فروشگاه</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Product Details -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="row g-0">
                    <!-- Product Gallery -->
                    <div class="col-md-5">
                        <div class="product-gallery p-3">
                            <div class="main-image mb-3 rounded overflow-hidden">
                                @if($product->thumbnail)
                                    <img src="{{ $product->thumbnail_url }}" class="img-fluid w-100" alt="{{ $product->name }}">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                                        <span class="text-muted fs-1">{{ substr($product->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="row g-2">
                                <!-- Main thumbnail -->
                                <div class="col-3">
                                    @if($product->thumbnail)
                                        <img src="{{ $product->thumbnail_url }}" class="img-fluid rounded gallery-thumb active" alt="{{ $product->name }}">
                                    @else
                                        <div class="bg-light rounded gallery-thumb active d-flex align-items-center justify-content-center" style="height: 60px;">
                                            <span class="text-muted">{{ substr($product->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Gallery images -->
                                @foreach($product->gallery as $image)
                                <div class="col-3">
                                    <img src="{{ $image->image_url }}" class="img-fluid rounded gallery-thumb" alt="{{ $product->name }} - تصویر {{ $loop->iteration + 1 }}">
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="col-md-7">
                        <div class="p-4">
                            <h3 class="mb-2">{{ $product->name }}</h3>
                            
                            <div class="mb-3">
                                @foreach($product->categories as $category)
                                    <span class="badge bg-light text-primary me-1">{{ $category->name }}</span>
                                @endforeach
                                @if($product->is_variable)
                                    <span class="badge bg-primary">متغیر</span>
                                @endif
                            </div>
                            
                            <div class="product-short-desc mb-4">
                                <p class="text-muted">{{ $product->short_description }}</p>
                            </div>
                            
                            @if($product->is_variable && $product->attributes->count() > 0)
                            <div class="mb-4">
                                <h5 class="mb-3 border-bottom pb-2">انتخاب ویژگی‌ها:</h5>
                                
                                @foreach($product->attributes as $attribute)
                                <div class="mb-3">
                                    <label for="attribute_{{ $attribute->id }}" class="form-label fw-bold">{{ $attribute->name }}:</label>
                                    <select class="form-select product-attribute" 
                                            id="attribute_{{ $attribute->id }}" 
                                            name="attributes[{{ $attribute->name }}]" 
                                            data-attribute-name="{{ $attribute->name }}">
                                        @foreach($attribute->values as $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="variation-details p-3 bg-light rounded-3 mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1 fw-bold" id="variation-name"></h5>
                                        <p class="text-muted mb-0 small">کد محصول: <span id="variation-sku"></span></p>
                                    </div>
                                    <div class="text-end">
                                        <h4 class="text-primary mb-0"><span id="variation-price"></span> <small>افغانی</small></h4>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="d-grid">
                                <button type="button" class="btn btn-primary btn-lg" id="add-to-cart">
                                    <i class="ri-shopping-cart-line me-2"></i>
                                    افزودن به سبد خرید
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <ul class="nav nav-tabs nav-fill" id="productTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">توضیحات</button>
                    </li>
                    @if($product->is_variable)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab" aria-controls="specs" aria-selected="false">مشخصات</button>
                    </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="howto-tab" data-bs-toggle="tab" data-bs-target="#howto" type="button" role="tab" aria-controls="howto" aria-selected="false">راهنمای استفاده</button>
                    </li>
                </ul>
                
                <div class="tab-content p-3" id="productTabContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <div class="p-2">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                    
                    @if($product->is_variable)
                    <div class="tab-pane fade" id="specs" role="tabpanel" aria-labelledby="specs-tab">
                        <div class="p-2">
                            @if($product->attributes->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ویژگی</th>
                                                <th>مقادیر</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product->attributes as $attribute)
                                            <tr>
                                                <td>{{ $attribute->name }}</td>
                                                <td>{{ implode(' | ', $attribute->values) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p>هیچ مشخصاتی برای این محصول تعریف نشده است.</p>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <div class="tab-pane fade" id="howto" role="tabpanel" aria-labelledby="howto-tab">
                        <div class="p-2">
                            <h5 class="mb-3 fw-bold">راهنمای فعال‌سازی اکانت</h5>
                            <div class="bg-light p-3 rounded-3">
                                <ol class="mb-0">
                                    <li class="mb-2">پس از خرید، اطلاعات حساب کاربری (ایمیل و رمز عبور) به ایمیل شما ارسال می‌شود</li>
                                    <li class="mb-2">به آدرس سایت مورد نظر مراجعه کنید</li>
                                    <li class="mb-2">روی گزینه "ورود" کلیک کنید</li>
                                    <li class="mb-2">اطلاعات حساب کاربری دریافتی را وارد کنید</li>
                                    <li>پس از ورود، می‌توانید از امکانات اکانت پرمیوم استفاده کنید</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Order Summary Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0">خلاصه سبد خرید</h5>
            </div>
            <div class="card-body">
                <!-- Current product selection -->
                <div class="selected-variation mb-3 p-3 border rounded-3 bg-light">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            @if($product->thumbnail)
                                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" width="60" class="rounded">
                            @else
                                <div class="bg-white rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <span class="text-muted">{{ substr($product->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $product->name }}</h6>
                            <p class="mb-0 text-muted small" id="summary-variation"></p>
                        </div>
                    </div>
                </div>
                
                <!-- Existing Cart Items (if any) -->
                @if(count(session('cart', [])) > 0)
                    <h6 class="mb-3 fw-bold">محصولات دیگر در سبد خرید:</h6>
                    <div class="cart-items-container mb-3">
                        @foreach(session('cart', []) as $cartKey => $item)
                            <div class="d-flex align-items-center mb-2 p-2 border-bottom">
                                <div class="me-2">
                                    @if($item['product_thumbnail'])
                                        <img src="{{ $item['product_thumbnail'] }}" alt="{{ $item['product_name'] }}" width="40" class="rounded">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <span class="text-muted small">{{ substr($item['product_name'], 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fs-6">{{ $item['product_name'] }}</h6>
                                    <small class="text-muted">
                                        @foreach($item['variation_attributes'] as $attrName => $attrValue)
                                            {{ $attrValue }}{{ !$loop->last ? ' / ' : '' }}
                                        @endforeach
                                    </small>
                                </div>
                                <div class="ms-2 text-end">
                                    <div class="fw-bold text-primary">{{ number_format($item['price']) }}</div>
                                    <small class="text-muted">x{{ $item['quantity'] }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                <div class="price-details mt-3 bg-light p-3 rounded-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>قیمت محصول انتخاب شده:</span>
                        <span><span id="summary-price">0</span> افغانی</span>
                    </div>
                    
                    @php
                        $cartTotal = 0;
                        foreach(session('cart', []) as $item) {
                            $cartTotal += $item['total'];
                        }
                    @endphp
                    
                    @if(count(session('cart', [])) > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>مجموع سبد خرید فعلی:</span>
                            <span>{{ number_format($cartTotal) }} افغانی</span>
                        </div>
                    @endif
                    
                    <hr class="my-2">
                    <div class="d-flex justify-content-between mb-0 fw-bold">
                        <span>مجموع کل:</span>
                        <span id="total-with-cart" class="text-primary">{{ number_format($cartTotal) }} افغانی</span>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mt-3">
                    <a href="{{ route('dashboard.shop.cart') }}" class="btn btn-primary">
                        <i class="ri-secure-payment-line me-2"></i>
                        تکمیل خرید
                    </a>
                    
                    <a href="{{ route('dashboard.shop.cart') }}" class="btn btn-outline-primary">
                        <i class="ri-shopping-cart-2-line me-2"></i>
                        مشاهده سبد خرید
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Customer Support Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                        <i class="ri-customer-service-2-line text-primary fs-4"></i>
                    </div>
                    <h5 class="mb-0">پشتیبانی</h5>
                </div>
                <p class="mb-3 small">در صورت نیاز به راهنمایی با ما تماس بگیرید:</p>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <a href="#" class="d-flex align-items-center text-decoration-none text-muted">
                            <i class="ri-telegram-line text-primary me-2"></i>
                            <span>@afghansupport</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="d-flex align-items-center text-decoration-none text-muted">
                            <i class="ri-whatsapp-line text-primary me-2"></i>
                            <span>+93700000000</span>
                        </a>
                    </li>
                    <li>
                        <a href="mailto:support@afghanpro.af" class="d-flex align-items-center text-decoration-none text-muted">
                            <i class="ri-mail-line text-primary me-2"></i>
                            <span>support@afghanpro.af</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .gallery-thumb {
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
        height: 60px;
        object-fit: cover;
    }
    
    .gallery-thumb.active {
        border-color: #0d6efd;
    }
    
    .variation-details {
        transition: all 0.3s;
    }
    
    .cart-items-container {
        max-height: 200px;
        overflow-y: auto;
    }
    
    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        padding: 0.8rem 1rem;
    }
    
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom: 2px solid #0d6efd;
        background: transparent;
        font-weight: bold;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Product variations data
        const variations = @json($product->variations);
        const product = @json($product);
        const cartTotal = {{ $cartTotal ?? 0 }};
        
        // DOM Elements
        const variationName = document.getElementById('variation-name');
        const variationSku = document.getElementById('variation-sku');
        const variationPrice = document.getElementById('variation-price');
        const summaryVariation = document.getElementById('summary-variation');
        const summaryPrice = document.getElementById('summary-price');
        const summaryTotal = document.getElementById('summary-total');
        const totalWithCart = document.getElementById('total-with-cart');
        const addToCartBtn = document.getElementById('add-to-cart');
        const galleryThumbs = document.querySelectorAll('.gallery-thumb');
        const mainImage = document.querySelector('.main-image img');
        const productAttributes = document.querySelectorAll('.product-attribute');
        
        let selectedVariation = null;
        
        // Function to update product information based on selected attributes
        function updateProductInfo() {
            if (!product.is_variable || variations.length === 0) {
                return;
            }
            
            // Get all selected attribute values
            const selectedAttributes = {};
            productAttributes.forEach(select => {
                const attributeName = select.getAttribute('data-attribute-name');
                selectedAttributes[attributeName] = select.value;
            });
            
            // Find matching variation
            selectedVariation = variations.find(variation => {
                const variationAttrs = variation.attributes;
                
                // Check if all selected attributes match the variation
                for (const [key, value] of Object.entries(selectedAttributes)) {
                    if (variationAttrs[key] !== value) {
                        return false;
                    }
                }
                return true;
            });
            
            if (selectedVariation) {
                // Format attributes for display
                let attributeDisplay = [];
                for (const [name, value] of Object.entries(selectedVariation.attributes)) {
                    attributeDisplay.push(`${value}`);
                }
                const displayName = attributeDisplay.join(' - ');
                
                // Update variation details
                variationName.textContent = displayName;
                variationSku.textContent = selectedVariation.sku || 'N/A';
                variationPrice.textContent = selectedVariation.price;
                
                // Update order summary
                summaryVariation.textContent = displayName;
                summaryPrice.textContent = selectedVariation.price;
                summaryTotal.textContent = selectedVariation.price;
                
                // Update total with cart
                const currentPrice = parseInt(selectedVariation.price);
                const totalPrice = cartTotal + currentPrice;
                
                if (cartTotal > 0) {
                    totalWithCart.innerHTML = `${number_format(cartTotal)} + ${number_format(currentPrice)} = <strong>${number_format(totalPrice)}</strong> افغانی`;
                } else {
                    totalWithCart.innerHTML = `${number_format(currentPrice)} افغانی`;
                }
                
                // Enable/disable add to cart button based on stock
                if (selectedVariation.stock > 0 || selectedVariation.stock === -1) {
                    addToCartBtn.disabled = false;
                    addToCartBtn.innerHTML = '<i class="ri-shopping-cart-line me-2"></i>افزودن به سبد خرید';
                } else {
                    addToCartBtn.disabled = true;
                    addToCartBtn.innerHTML = '<i class="ri-error-warning-line me-2"></i>ناموجود';
                }
            } else {
                // No matching variation found
                variationName.textContent = 'ترکیب انتخاب شده موجود نیست';
                variationSku.textContent = 'N/A';
                variationPrice.textContent = '0';
                summaryVariation.textContent = 'ترکیب انتخاب شده موجود نیست';
                summaryPrice.textContent = '0';
                summaryTotal.textContent = '0';
                
                // Update total with cart
                if (cartTotal > 0) {
                    totalWithCart.innerHTML = `${number_format(cartTotal)} افغانی`;
                } else {
                    totalWithCart.innerHTML = `0 افغانی`;
                }
                
                addToCartBtn.disabled = true;
                addToCartBtn.innerHTML = '<i class="ri-error-warning-line me-2"></i>ناموجود';
            }
        }
        
        // Helper function to format numbers with commas
        function number_format(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        
        // Event Listeners for product attributes
        productAttributes.forEach(select => {
            select.addEventListener('change', updateProductInfo);
        });
        
        // Gallery functionality
        galleryThumbs.forEach(thumb => {
            thumb.addEventListener('click', function() {
                // Remove active class from all thumbs
                galleryThumbs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked thumb
                this.classList.add('active');
                
                // Update main image
                mainImage.src = this.src;
            });
        });
        
        // Add to cart button click
        addToCartBtn.addEventListener('click', function() {
            if (!selectedVariation) {
                alert('لطفا ویژگی‌های محصول را انتخاب کنید.');
                return;
            }
            
            // Create form data for AJAX request
            const formData = {
                product_id: product.id,
                variation_id: selectedVariation.id,
                quantity: 1,
                is_variable: product.is_variable ? 1 : 0,
                _token: '{{ csrf_token() }}'
            };
            
            // Disable button while processing
            addToCartBtn.disabled = true;
            addToCartBtn.innerHTML = '<i class="ri-loader-4-line me-2 spin"></i>در حال افزودن...';
            
            // Send AJAX request to add to cart
            fetch('{{ route("dashboard.shop.add-to-cart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                // Check if response is ok (status in the range 200-299)
                if (!response.ok) {
                    throw new Error('HTTP status ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    addToCartBtn.innerHTML = '<i class="ri-check-line me-2"></i>به سبد خرید اضافه شد';
                    
                    // Update cart count
                    const cartCountElements = document.querySelectorAll('.cart-count');
                    cartCountElements.forEach(element => {
                        element.textContent = data.cart_count;
                    });
                    
                    // Reset button after delay
                    setTimeout(() => {
                        addToCartBtn.disabled = false;
                        addToCartBtn.innerHTML = '<i class="ri-shopping-cart-line me-2"></i>افزودن به سبد خرید';
                    }, 2000);
                } else {
                    // Show error message
                    alert(data.message || 'خطا در افزودن محصول به سبد خرید');
                    addToCartBtn.disabled = false;
                    addToCartBtn.innerHTML = '<i class="ri-shopping-cart-line me-2"></i>افزودن به سبد خرید';
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                
                alert('خطا در ارتباط با سرور: ' + error.toString());
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = '<i class="ri-shopping-cart-line me-2"></i>افزودن به سبد خرید';
            });
        });
        
        // Initialize variation details
        if (variations.length > 0) {
            updateProductInfo();
        }
    });
</script>
@endpush