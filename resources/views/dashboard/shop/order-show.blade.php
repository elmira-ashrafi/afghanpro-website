@extends('layouts.dashboard')

@section('title', 'جزئیات سفارش')
@section('page-title', 'جزئیات سفارش')

@section('content')
<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">داشبورد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.shop.index') }}">فروشگاه</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.shop.orders') }}">سفارش‌های من</a></li>
                        <li class="breadcrumb-item active" aria-current="page">جزئیات سفارش #{{ $order->order_number }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

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

<!-- Order Status Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-body p-0">
                @if($order->status == 'completed')
                    <div class="bg-success bg-opacity-10 p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-success rounded-circle p-3 me-3">
                                <i class="ri-check-line text-white fs-3"></i>
                            </div>
                            <div>
                                <h5 class="text-success mb-1">سفارش شما تکمیل شده است</h5>
                                <p class="mb-0">اطلاعات حساب کاربری به آدرس ایمیل شما ارسال شده است.</p>
                            </div>
                        </div>
                    </div>
                @elseif($order->status == 'processing')
                    <div class="bg-info bg-opacity-10 p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-info rounded-circle p-3 me-3">
                                <i class="ri-time-line text-white fs-3"></i>
                            </div>
                            <div>
                                <h5 class="text-info mb-1">سفارش شما در حال پردازش است</h5>
                                <p class="mb-0">به زودی اطلاعات حساب کاربری به آدرس ایمیل شما ارسال خواهد شد.</p>
                            </div>
                        </div>
                    </div>
                @elseif($order->status == 'pending')
                    <div class="bg-warning bg-opacity-10 p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning rounded-circle p-3 me-3">
                                <i class="ri-time-line text-white fs-3"></i>
                            </div>
                            <div>
                                <h5 class="text-warning mb-1">سفارش شما در انتظار بررسی است</h5>
                                <p class="mb-0">کارشناسان ما در اسرع وقت سفارش شما را بررسی خواهند کرد.</p>
                            </div>
                        </div>
                    </div>
                @elseif($order->status == 'cancelled')
                    <div class="bg-danger bg-opacity-10 p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger rounded-circle p-3 me-3">
                                <i class="ri-close-line text-white fs-3"></i>
                            </div>
                            <div>
                                <h5 class="text-danger mb-1">سفارش شما لغو شده است</h5>
                                <p class="mb-0">جهت اطلاعات بیشتر با پشتیبانی تماس بگیرید.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Order Info -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0">اطلاعات سفارش</h5>
            </div>
            <div class="card-body">
                <!-- Desktop View -->
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th width="40%" class="pe-3 py-3">شماره سفارش:</th>
                                    <td class="py-3">
                                        <span class="badge bg-light text-dark">#{{ $order->order_number }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pe-3 py-3">تاریخ سفارش:</th>
                                    <td class="py-3">{{ $order->created_at->format('Y/m/d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th class="pe-3 py-3">وضعیت سفارش:</th>
                                    <td class="py-3">
                                        @if($order->status == 'pending')
                                            <div class="d-flex align-items-center">
                                                <span class="bg-warning rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                                <span>در انتظار بررسی</span>
                                            </div>
                                        @elseif($order->status == 'processing')
                                            <div class="d-flex align-items-center">
                                                <span class="bg-info rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                                <span>در حال پردازش</span>
                                            </div>
                                        @elseif($order->status == 'completed')
                                            <div class="d-flex align-items-center">
                                                <span class="bg-success rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                                <span>تکمیل شده</span>
                                            </div>
                                        @elseif($order->status == 'cancelled')
                                            <div class="d-flex align-items-center">
                                                <span class="bg-danger rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                                <span>لغو شده</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pe-3 py-3">روش پرداخت:</th>
                                    <td class="py-3">
                                        @if($order->payment_method == 'afghan_wallet')
                                            <span class="badge bg-primary rounded-pill">کیف پول افغانی</span>
                                        @elseif($order->payment_method == 'agency_visit')
                                            <span class="badge bg-secondary rounded-pill">مراجعه به نمایندگی</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pe-3 py-3">وضعیت پرداخت:</th>
                                    <td class="py-3">
                                        @if($order->payment_status == 'pending')
                                            <div class="d-flex align-items-center">
                                                <span class="bg-warning rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                                <span>در انتظار پرداخت</span>
                                            </div>
                                        @elseif($order->payment_status == 'paid')
                                            <div class="d-flex align-items-center">
                                                <span class="bg-success rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                                <span>پرداخت شده</span>
                                            </div>
                                        @elseif($order->payment_status == 'failed')
                                            <div class="d-flex align-items-center">
                                                <span class="bg-danger rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                                <span>ناموفق</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @if($order->agency_id && $order->agency)
                                <tr>
                                    <th class="pe-3 py-3">نمایندگی:</th>
                                    <td class="py-3">{{ $order->agency->name }} - {{ $order->agency->address ?? '' }}</td>
                                </tr>
                                @endif
                                @if($order->notes)
                                <tr>
                                    <th class="pe-3 py-3">یادداشت:</th>
                                    <td class="py-3">{{ $order->notes }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="d-md-none">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">شماره سفارش:</span>
                                <span class="badge bg-light text-dark">#{{ $order->order_number }}</span>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">تاریخ سفارش:</span>
                                <span>{{ $order->created_at->format('Y/m/d H:i') }}</span>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">وضعیت سفارش:</span>
                                <div>
                                    @if($order->status == 'pending')
                                        <div class="d-flex align-items-center">
                                            <span class="bg-warning rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                            <span>در انتظار بررسی</span>
                                        </div>
                                    @elseif($order->status == 'processing')
                                        <div class="d-flex align-items-center">
                                            <span class="bg-info rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                            <span>در حال پردازش</span>
                                        </div>
                                    @elseif($order->status == 'completed')
                                        <div class="d-flex align-items-center">
                                            <span class="bg-success rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                            <span>تکمیل شده</span>
                                        </div>
                                    @elseif($order->status == 'cancelled')
                                        <div class="d-flex align-items-center">
                                            <span class="bg-danger rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                            <span>لغو شده</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">روش پرداخت:</span>
                                <div>
                                    @if($order->payment_method == 'afghan_wallet')
                                        <span class="badge bg-primary rounded-pill">کیف پول افغانی</span>
                                    @elseif($order->payment_method == 'agency_visit')
                                        <span class="badge bg-secondary rounded-pill">مراجعه به نمایندگی</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">وضعیت پرداخت:</span>
                                <div>
                                    @if($order->payment_status == 'pending')
                                        <div class="d-flex align-items-center">
                                            <span class="bg-warning rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                            <span>در انتظار پرداخت</span>
                                        </div>
                                    @elseif($order->payment_status == 'paid')
                                        <div class="d-flex align-items-center">
                                            <span class="bg-success rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                            <span>پرداخت شده</span>
                                        </div>
                                    @elseif($order->payment_status == 'failed')
                                        <div class="d-flex align-items-center">
                                            <span class="bg-danger rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                            <span>ناموفق</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($order->agency_id && $order->agency)
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">نمایندگی:</span>
                                <span>{{ $order->agency->name }} - {{ $order->agency->address ?? '' }}</span>
                            </div>
                        </div>
                        @endif
                        @if($order->notes)
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">یادداشت:</span>
                                <span>{{ $order->notes }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Totals -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0">جزئیات مالی</h5>
            </div>
            <div class="card-body">
                <div class="px-3 py-4 bg-light rounded-3 mb-3">
                    <div class="d-flex justify-content-between mb-3">
                        <span>مبلغ کل سفارش:</span>
                        <span>{{ number_format($order->total_amount + $order->discount_amount) }} افغانی</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-3">
                        <span>تخفیف:</span>
                        <span class="text-danger">{{ number_format($order->discount_amount) }} افغانی-</span>
                    </div>
                    @if($order->coupon_code)
                    <div class="d-flex justify-content-between mb-3">
                        <span>کد تخفیف:</span>
                        <span class="badge bg-success">{{ $order->coupon_code }}</span>
                    </div>
                    @endif
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between mb-0 fw-bold">
                        <span>مبلغ نهایی:</span>
                        <span class="text-primary fs-5">{{ number_format($order->total_amount) }} افغانی</span>
                    </div>
                </div>
                
                @if($order->payment_status == 'pending' && $order->status != 'cancelled')
                <div class="alert alert-warning d-flex mb-0">
                    <i class="ri-error-warning-line align-self-center me-3 fs-5"></i>
                    <div>
                        پرداخت این سفارش هنوز انجام نشده است. در صورتی که قصد پرداخت دارید، لطفاً با پشتیبانی تماس بگیرید.
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Order Items -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0">محصولات سفارش داده شده</h5>
            </div>
            <div class="card-body p-0">
                <!-- Desktop View -->
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="border-0">محصول</th>
                                    <th scope="col" class="border-0 text-center">قیمت واحد</th>
                                    <th scope="col" class="border-0 text-center">تعداد</th>
                                    <th scope="col" class="border-0 text-end">قیمت کل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center py-2">
                                            @if($item->product && $item->product->thumbnail)
                                                <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->name }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    <i class="ri-image-line text-muted fs-3"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $item->name }}</h6>
                                                @if(!empty($item->attributes))
                                                    <div class="text-muted small">
                                                        @php
                                                            $attributes = is_array($item->attributes) ? $item->attributes : json_decode($item->attributes, true);
                                                        @endphp
                                                        @if(is_array($attributes))
                                                            @foreach($attributes as $key => $value)
                                                                <span class="me-2">{{ $key }}: <span class="fw-bold">{{ $value }}</span></span>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">{{ number_format($item->price) }} افغانی</td>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-light text-dark rounded-pill px-3">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-end align-middle fw-bold">{{ number_format($item->total) }} افغانی</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="py-4">
                                            <div class="mb-3">
                                                <i class="ri-information-line fs-2 text-muted"></i>
                                            </div>
                                            <h6>هیچ محصولی در این سفارش وجود ندارد</h6>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">جمع کل:</td>
                                    <td class="text-end">{{ number_format($order->total_amount + $order->discount_amount) }} افغانی</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td colspan="3" class="text-end">تخفیف:</td>
                                    <td class="text-end text-danger">{{ number_format($order->discount_amount) }} افغانی-</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">مبلغ نهایی:</td>
                                    <td class="text-end fw-bold text-primary">{{ number_format($order->total_amount) }} افغانی</td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="d-md-none">
                    @forelse($order->items as $item)
                    <div class="card border-0 border-bottom rounded-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                @if($item->product && $item->product->thumbnail)
                                    <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->name }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="ri-image-line text-muted fs-3"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $item->name }}</h6>
                                    @if(!empty($item->attributes))
                                        <div class="text-muted small">
                                            @php
                                                $attributes = is_array($item->attributes) ? $item->attributes : json_decode($item->attributes, true);
                                            @endphp
                                            @if(is_array($attributes))
                                                @foreach($attributes as $key => $value)
                                                    <span class="me-2">{{ $key }}: <span class="fw-bold">{{ $value }}</span></span>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small d-block mb-1">قیمت واحد:</span>
                                    <span>{{ number_format($item->price) }} افغانی</span>
                                </div>
                                <div>
                                    <span class="text-muted small d-block mb-1">تعداد:</span>
                                    <span class="badge bg-light text-dark rounded-pill px-3">{{ $item->quantity }}</span>
                                </div>
                                <div>
                                    <span class="text-muted small d-block mb-1">قیمت کل:</span>
                                    <span class="fw-bold">{{ number_format($item->total) }} افغانی</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="ri-information-line fs-2 text-muted"></i>
                        </div>
                        <h6>هیچ محصولی در این سفارش وجود ندارد</h6>
                    </div>
                    @endforelse

                    <!-- Mobile Order Summary -->
                    <div class="card border-0 bg-light rounded-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>جمع کل:</span>
                                <span>{{ number_format($order->total_amount + $order->discount_amount) }} افغانی</span>
                            </div>
                            @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>تخفیف:</span>
                                <span class="text-danger">{{ number_format($order->discount_amount) }} افغانی-</span>
                            </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>مبلغ نهایی:</span>
                                <span class="text-primary">{{ number_format($order->total_amount) }} افغانی</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex flex-wrap justify-content-between gap-2">
                    <a href="{{ route('dashboard.shop.orders') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-right-line me-1"></i>بازگشت به لیست سفارش‌ها
                    </a>
                    
                    @if($order->status == 'pending' && $order->payment_status != 'paid')
                    <form action="{{ route('dashboard.shop.order.cancel', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('آیا از لغو سفارش اطمینان دارید؟')">
                            <i class="ri-close-circle-line me-1"></i>لغو سفارش
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 