@extends('layouts.dashboard')

@section('title', 'سبد خرید')
@section('page-title', 'سبد خرید')

@section('content')
<!-- Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Breadcrumb -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">داشبورد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.shop.index') }}">فروشگاه</a></li>
                        <li class="breadcrumb-item active" aria-current="page">سبد خرید</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Cart Content -->
<div class="row g-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white py-2 border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fs-6">سبد خرید شما</h5>
                @if(count($cart) > 0)
                <button type="button" class="btn btn-sm btn-outline-danger" id="clear-cart-btn">
                    <i class="ri-delete-bin-line me-1"></i>خالی کردن
                </button>
                @endif
            </div>
            <div class="card-body p-0">
                @if(count($cart) > 0)
                <div class="cart-items">
                    @foreach($cart as $cartKey => $item)
                    <div class="cart-item p-2 border-bottom">
                        <div class="row align-items-center g-2">
                            <div class="col-7">
                                <div class="d-flex align-items-center">
                                    @if($item['product_thumbnail'])
                                        @if(filter_var($item['product_thumbnail'], FILTER_VALIDATE_URL))
                                            <img src="{{ $item['product_thumbnail'] }}" alt="{{ $item['product_name'] }}" class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                        @else
                                            <img src="{{ asset('storage/' . $item['product_thumbnail']) }}" alt="{{ $item['product_name'] }}" class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                        @endif
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                            <i class="ri-image-line text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold small">{{ \Illuminate\Support\Str::limit($item['product_name'], 30) }}</div>
                                        @if(isset($item['variation_attributes']) && is_array($item['variation_attributes']))
                                            <small class="text-muted d-block">
                                                @foreach($item['variation_attributes'] as $attrValue)
                                                    {{ $attrValue }}{{ !$loop->last ? '، ' : '' }}
                                                @endforeach
                                            </small>
                                        @endif
                                        <div class="small text-primary">{{ number_format($item['price']) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn btn-sm btn-outline-secondary quantity-btn p-1" data-cart-key="{{ $cartKey }}" data-action="decrease">-</button>
                                    <input type="number" class="form-control form-control-sm quantity-input mx-1" style="width: 35px; text-align: center; padding: 2px;" value="{{ $item['quantity'] }}" min="1" data-cart-key="{{ $cartKey }}">
                                    <button type="button" class="btn btn-sm btn-outline-secondary quantity-btn p-1" data-cart-key="{{ $cartKey }}" data-action="increase">+</button>
                                </div>
                            </div>
                            <div class="col-2 text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn p-1" data-cart-key="{{ $cartKey }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="p-2 text-end">
                    <button type="button" class="btn btn-sm btn-primary" id="update-cart-btn">
                        <i class="ri-refresh-line me-1"></i>بروزرسانی
                    </button>
                </div>
                @else
                <div class="text-center py-4">
                    <div class="mb-3">
                        <div class="bg-light d-inline-flex align-items-center justify-content-center rounded-circle p-3">
                            <i class="ri-shopping-cart-line fs-1 text-muted"></i>
                        </div>
                    </div>
                    <h5>سبد خرید شما خالی است</h5>
                    <p class="text-muted mb-4">به فروشگاه بروید و محصولات مورد نظر خود را به سبد خرید اضافه کنید.</p>
                    <a href="{{ route('dashboard.shop.index') }}" class="btn btn-primary px-4">
                        <i class="ri-shopping-bag-line me-1"></i>مشاهده محصولات
                    </a>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Coupon Card -->
        <div class="card mb-3">
            <div class="card-header bg-white py-2">
                <h5 class="mb-0 fs-6">کد تخفیف</h5>
            </div>
            <div class="card-body p-2">
                <div class="input-group mb-2">
                    <input type="text" class="form-control form-control-sm" id="coupon-code" placeholder="کد تخفیف خود را وارد کنید" value="{{ $coupon ? $coupon->code : '' }}" {{ $coupon ? 'disabled' : '' }}>
                    <button class="btn btn-sm btn-outline-primary" type="button" id="apply-coupon-btn" {{ $coupon ? 'disabled' : '' }}>اعمال</button>
                </div>
                
                @if($coupon)
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small">کد اعمال شده:</div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">{{ $coupon->code }}</span>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="remove-coupon-btn">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                </div>
                @endif
                
                <div id="coupon-message" class="mt-2"></div>
            </div>
        </div>
        
        <!-- Order Summary Card -->
        <div class="card mb-3">
            <div class="card-header bg-white py-2">
                <h5 class="mb-0 fs-6">خلاصه سفارش</h5>
            </div>
            <div class="card-body p-2">
                <div class="d-flex justify-content-between mb-2 small">
                    <span>جمع کل:</span>
                    <span id="subtotal">{{ number_format($subtotal) }} افغانی</span>
                </div>
                <div class="d-flex justify-content-between mb-2 small">
                    <span>تخفیف:</span>
                    <span class="text-danger" id="discount">{{ $discount > 0 ? number_format($discount) . ' افغانی-' : '0 افغانی' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2 small">
                    <span>مالیات:</span>
                    <span>0 افغانی</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between mb-2 fw-bold">
                    <span>مجموع نهایی:</span>
                    <span id="total">{{ number_format($total) }} افغانی</span>
                </div>
            </div>
        </div>
        
        <!-- Payment Info -->
        <div class="card mb-3">
            <div class="card-header bg-white py-2">
                <h5 class="mb-0 fs-6">روش‌های پرداخت</h5>
            </div>
            <div class="card-body p-2 small">
                <p class="mb-0">
                    خرید اکانت‌های پرمیوم فقط با کیف پول افغانی مقدور است یا از طریق مراجعه حضوری به نمایندگی‌ها امکان‌پذیر می‌باشد.
                </p>
            </div>
        </div>
        
        <!-- Payment Methods Card -->
        <div class="card mb-3">
            <div class="card-header bg-white py-2">
                <h5 class="mb-0 fs-6">روش پرداخت</h5>
            </div>
            <div class="card-body p-2">
                <div class="mb-3">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" id="wallet_payment" value="afghan_wallet" checked>
                        <label class="form-check-label small" for="wallet_payment">
                            پرداخت از کیف پول افغانی
                            <small class="text-muted d-block">
                                موجودی فعلی: {{ number_format($afghanWallet->balance ?? 0) }} افغانی
                            </small>
                        </label>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="agency_payment" value="agency_visit">
                        <label class="form-check-label small" for="agency_payment">
                            مراجعه به نمایندگی و پرداخت حضوری
                        </label>
                    </div>
                </div>
                
                <div class="agency-select d-none" id="agencySelectContainer">
                    <label for="agency_id" class="form-label small">انتخاب نمایندگی:</label>
                    <select class="form-select form-select-sm" id="agency_id" name="agency_id">
                        <option value="" selected disabled>انتخاب کنید</option>
                        @foreach($agencies ?? [] as $agency)
                        <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                @if(count($cart) > 0)
                <div class="d-grid gap-2 mt-3">
                    <button type="button" class="btn btn-primary" id="checkout-btn">
                        <i class="ri-secure-payment-line me-1"></i>
                        تکمیل خرید
                    </button>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Guide Card -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white py-2 border-0">
                <h5 class="mb-0 fs-6">راهنمای خرید</h5>
            </div>
            <div class="card-body p-2">
                <div class="rounded-3 bg-warning bg-opacity-10 p-2 mb-0">
                    <h6 class="fw-bold mb-2 small">نکات مهم:</h6>
                    <div class="small">
                        <ul class="mb-0 ps-3">
                            <li class="mb-1">برای خرید با کیف پول، موجودی کیف پول شما باید به اندازه کافی باشد.</li>
                            <li class="mb-1">در صورت انتخاب پرداخت حضوری، سفارش شما به مدت ۴۸ ساعت در انتظار پرداخت می‌ماند.</li>
                            <li>در صورت بروز هرگونه مشکل، با پشتیبانی تماس بگیرید.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Form -->
<form id="checkout-form" action="{{ route('dashboard.shop.checkout') }}" method="POST" class="d-none">
    @csrf
    <input type="hidden" name="payment_method" id="payment_method_input" value="afghan_wallet">
    <input type="hidden" name="agency_id" id="agency_id_input" value="">
    <input type="hidden" name="notes" id="notes_input" value="">
</form>
@endsection

@push('styles')
<style>
    .cart-items {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .cart-item {
        transition: background-color 0.2s;
    }
    
    .cart-item:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    .quantity-input {
        -moz-appearance: textfield;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM elements
        const quantityBtns = document.querySelectorAll('.quantity-btn');
        const quantityInputs = document.querySelectorAll('.quantity-input');
        const removeItemBtns = document.querySelectorAll('.remove-item-btn');
        const clearCartBtn = document.getElementById('clear-cart-btn');
        const updateCartBtn = document.getElementById('update-cart-btn');
        const couponCodeInput = document.getElementById('coupon-code');
        const applyCouponBtn = document.getElementById('apply-coupon-btn');
        const removeCouponBtn = document.getElementById('remove-coupon-btn');
        const couponMessage = document.getElementById('coupon-message');
        const walletPayment = document.getElementById('wallet_payment');
        const agencyPayment = document.getElementById('agency_payment');
        const agencySelectContainer = document.getElementById('agencySelectContainer');
        const agencySelect = document.getElementById('agency_id');
        const checkoutBtn = document.getElementById('checkout-btn');
        const checkoutForm = document.getElementById('checkout-form');
        const paymentMethodInput = document.getElementById('payment_method_input');
        const agencyIdInput = document.getElementById('agency_id_input');
        
        // Initial setup - disable agency_id if wallet payment is selected
        if (walletPayment && walletPayment.checked) {
            agencySelectContainer.classList.add('d-none');
        }
        
        // Payment method selection
        if (walletPayment) {
            walletPayment.addEventListener('change', function() {
                if (this.checked) {
                    agencySelectContainer.classList.add('d-none');
                    paymentMethodInput.value = 'afghan_wallet';
                    agencyIdInput.value = '';
                }
            });
        }
        
        if (agencyPayment) {
            agencyPayment.addEventListener('change', function() {
                if (this.checked) {
                    agencySelectContainer.classList.remove('d-none');
                    paymentMethodInput.value = 'agency_visit';
                }
            });
        }
        
        if (agencySelect) {
            agencySelect.addEventListener('change', function() {
                agencyIdInput.value = this.value;
            });
        }
        
        // Quantity buttons
        quantityBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const cartKey = this.getAttribute('data-cart-key');
                const action = this.getAttribute('data-action');
                const input = document.querySelector(`.quantity-input[data-cart-key="${cartKey}"]`);
                
                let quantity = parseInt(input.value);
                
                if (action === 'increase') {
                    quantity += 1;
                } else if (action === 'decrease' && quantity > 1) {
                    quantity -= 1;
                }
                
                input.value = quantity;
                
                // Update cart via AJAX
                updateCartItem(cartKey, quantity);
            });
        });
        
        // Quantity inputs
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                const cartKey = this.getAttribute('data-cart-key');
                let quantity = parseInt(this.value);
                
                // Ensure minimum quantity of 1
                if (quantity < 1) {
                    quantity = 1;
                    this.value = 1;
                }
                
                // Update cart via AJAX
                updateCartItem(cartKey, quantity);
            });
        });
        
        // Remove item buttons
        removeItemBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const cartKey = this.getAttribute('data-cart-key');
                
                // Confirm deletion
                if (confirm('آیا از حذف این محصول از سبد خرید اطمینان دارید؟')) {
                    removeCartItem(cartKey);
                }
            });
        });
        
        // Clear cart button
        if (clearCartBtn) {
            clearCartBtn.addEventListener('click', function() {
                // Confirm clear
                if (confirm('آیا از خالی کردن سبد خرید اطمینان دارید؟')) {
                    clearCart();
                }
            });
        }
        
        // Update cart button
        if (updateCartBtn) {
            updateCartBtn.addEventListener('click', function() {
                updateAllCartItems();
            });
        }
        
        // Apply coupon button
        if (applyCouponBtn) {
            applyCouponBtn.addEventListener('click', function() {
                const couponCode = couponCodeInput.value.trim();
                
                if (couponCode === '') {
                    showCouponMessage('لطفا کد تخفیف را وارد کنید.', 'warning');
                    return;
                }
                
                applyCoupon(couponCode);
            });
        }
        
        // Remove coupon button
        if (removeCouponBtn) {
            removeCouponBtn.addEventListener('click', function() {
                removeCoupon();
            });
        }
        
        // Checkout button
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', function(e) {
                // Validate payment method
                if (agencyPayment.checked && (!agencySelect.value || agencySelect.value === '')) {
                    alert('لطفا یک نمایندگی را انتخاب کنید.');
                    return;
                }
                
                // Disable the button to prevent double submission
                checkoutBtn.disabled = true;
                checkoutBtn.innerHTML = '<i class="ri-loader-2-line me-2 fa-spin"></i> در حال پردازش...';
                
                // Update payment method based on selection
                paymentMethodInput.value = walletPayment.checked ? 'afghan_wallet' : 'agency_visit';
                
                // Submit checkout form
                checkoutForm.submit();
            });
        }
        
        // Function to update cart item
        function updateCartItem(cartKey, quantity) {
            fetch("{{ route('dashboard.shop.update-cart') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    cart_key: cartKey,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update item total
                    const itemTotal = document.querySelector(`.item-total[data-cart-key="${cartKey}"]`);
                    itemTotal.textContent = numberFormat(data.item_total) + ' افغانی';
                    
                    // Update cart counter
                    updateCartCounter(data.cart_count);
                    
                    // Show toast message
                    showToast('سبد خرید بروزرسانی شد');
                    
                    // Reload page to update totals
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('خطا در بروزرسانی سبد خرید', 'error');
            });
        }
        
        // Function to remove cart item
        function removeCartItem(cartKey) {
            fetch("{{ route('dashboard.shop.remove-from-cart') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    cart_key: cartKey
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart counter
                    updateCartCounter(data.cart_count);
                    
                    // Show toast message
                    showToast('محصول از سبد خرید حذف شد');
                    
                    // Reload page
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('خطا در حذف محصول از سبد خرید', 'error');
            });
        }
        
        // Function to clear cart
        function clearCart() {
            fetch("{{ route('dashboard.shop.clear-cart') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart counter
                    updateCartCounter(0);
                    
                    // Show toast message
                    showToast('سبد خرید خالی شد');
                    
                    // Reload page
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('خطا در خالی کردن سبد خرید', 'error');
            });
        }
        
        // Function to update all cart items
        function updateAllCartItems() {
            // Show loading
            showToast('در حال بروزرسانی سبد خرید...');
            
            // Collect all quantity updates
            const updates = [];
            quantityInputs.forEach(input => {
                updates.push({
                    cart_key: input.getAttribute('data-cart-key'),
                    quantity: parseInt(input.value)
                });
            });
            
            // Update each item one by one
            let completed = 0;
            updates.forEach(update => {
                updateCartItem(update.cart_key, update.quantity);
                completed++;
                
                if (completed === updates.length) {
                    // All updates completed
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            });
        }
        
        // Function to apply coupon
        function applyCoupon(couponCode) {
            fetch("{{ route('dashboard.shop.apply-coupon') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    coupon_code: couponCode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update discount and total
                    document.getElementById('discount').textContent = numberFormat(data.discount) + ' افغانی-';
                    document.getElementById('total').textContent = numberFormat(data.total) + ' افغانی';
                    
                    // Show success message
                    showCouponMessage(data.message, 'success');
                    
                    // Reload page
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    // Show detailed error message
                    let errorMessage = data.message || 'کد تخفیف معتبر نیست';
                    
                    // Check for specific error reasons in the response
                    if (data.reason) {
                        errorMessage += `<br><small class="text-muted">${data.reason}</small>`;
                    } else if (data.min_amount) {
                        errorMessage += `<br><small class="text-muted">حداقل مبلغ خرید برای استفاده از این کد: ${numberFormat(data.min_amount)} افغانی</small>`;
                    } else if (data.expired) {
                        errorMessage += `<br><small class="text-muted">این کد تخفیف منقضی شده است</small>`;
                    } else if (data.max_uses_reached) {
                        errorMessage += `<br><small class="text-muted">تعداد استفاده از این کد تخفیف به پایان رسیده است</small>`;
                    } else if (data.already_used) {
                        errorMessage += `<br><small class="text-muted">شما قبلاً از این کد تخفیف استفاده کرده‌اید</small>`;
                    }
                    
                    showCouponMessage(errorMessage, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showCouponMessage('خطا در اعمال کد تخفیف', 'danger');
            });
        }
        
        // Function to remove coupon
        function removeCoupon() {
            fetch("{{ route('dashboard.shop.remove-coupon') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showCouponMessage(data.message, 'success');
                    
                    // Reload page
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showCouponMessage('خطا در حذف کد تخفیف', 'danger');
            });
        }
        
        // Function to show coupon message
        function showCouponMessage(message, type) {
            if (message.includes('<br>')) {
                // For detailed error messages with reason
                couponMessage.innerHTML = `<div class="alert alert-${type} mb-0">
                    <div class="mb-1">${message.split('<br>')[0]}</div>
                    ${message.split('<br>')[1]}
                </div>`;
            } else {
                // For simple messages
                couponMessage.innerHTML = `<div class="alert alert-${type} mb-0 py-2">${message}</div>`;
            }
        }
        
        // Function to update cart counter
        function updateCartCounter(count) {
            const cartCounter = document.querySelector('.cart-counter');
            if (cartCounter) {
                cartCounter.textContent = count;
                
                if (count > 0) {
                    cartCounter.classList.remove('d-none');
                } else {
                    cartCounter.classList.add('d-none');
                }
            }
        }
        
        // Function to show toast message
        function showToast(message, type = 'success') {
            // You can implement your own toast here
            console.log(`Toast: ${message} (${type})`);
        }
        
        // Function to format numbers with commas
        function numberFormat(number) {
            return new Intl.NumberFormat().format(number);
        }
    });
</script>
@endpush 