@extends('layouts.dashboard')

@section('title', 'سفارش‌های من')
@section('page-title', 'سفارش‌های من')

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
                        <li class="breadcrumb-item active" aria-current="page">سفارش‌های من</li>
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

<!-- Orders List -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">لیست سفارش‌های شما</h5>
                <a href="{{ route('dashboard.shop.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ri-store-2-line me-1"></i>فروشگاه
                </a>
            </div>
            <div class="card-body p-0">
                @if($orders->count() > 0)
                    <!-- Desktop View -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="border-0">شماره سفارش</th>
                                    <th scope="col" class="border-0">تاریخ</th>
                                    <th scope="col" class="border-0">وضعیت</th>
                                    <th scope="col" class="border-0">مبلغ کل</th>
                                    <th scope="col" class="border-0">روش پرداخت</th>
                                    <th scope="col" class="border-0">وضعیت پرداخت</th>
                                    <th scope="col" class="border-0 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td class="align-middle">
                                        <span class="badge bg-light text-dark">#{{ $order->order_number }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <small>{{ $order->created_at->format('Y/m/d') }}</small><br>
                                        <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                    </td>
                                    <td class="align-middle">
                                        @if($order->status == 'pending')
                                            <div class="d-flex align-items-center">
                                                <span class="bg-warning rounded-circle me-1" style="width: 8px; height: 8px;"></span>
                                                <span>در انتظار بررسی</span>
                                            </div>
                                        @elseif($order->status == 'processing')
                                            <div class="d-flex align-items-center">
                                                <span class="bg-info rounded-circle me-1" style="width: 8px; height: 8px;"></span>
                                                <span>در حال پردازش</span>
                                            </div>
                                        @elseif($order->status == 'completed')
                                            <div class="d-flex align-items-center">
                                                <span class="bg-success rounded-circle me-1" style="width: 8px; height: 8px;"></span>
                                                <span>تکمیل شده</span>
                                            </div>
                                        @elseif($order->status == 'cancelled')
                                            <div class="d-flex align-items-center">
                                                <span class="bg-danger rounded-circle me-1" style="width: 8px; height: 8px;"></span>
                                                <span>لغو شده</span>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center">
                                                <span class="bg-secondary rounded-circle me-1" style="width: 8px; height: 8px;"></span>
                                                <span>{{ $order->status }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle text-primary fw-bold">{{ number_format($order->total_amount) }} افغانی</td>
                                    <td class="align-middle">
                                        @if($order->payment_method == 'afghan_wallet')
                                            <span class="badge bg-primary rounded-pill">کیف پول افغانی</span>
                                        @elseif($order->payment_method == 'agency_visit')
                                            <span class="badge bg-secondary rounded-pill">مراجعه به نمایندگی</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill">{{ $order->payment_method }}</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($order->payment_status == 'pending')
                                            <span class="badge bg-warning text-dark rounded-pill">در انتظار پرداخت</span>
                                        @elseif($order->payment_status == 'paid')
                                            <span class="badge bg-success rounded-pill">پرداخت شده</span>
                                        @elseif($order->payment_status == 'failed')
                                            <span class="badge bg-danger rounded-pill">ناموفق</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill">{{ $order->payment_status }}</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('dashboard.shop.order.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="ri-eye-line"></i> مشاهده
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile View -->
                    <div class="d-md-none">
                        @foreach($orders as $order)
                        <div class="card border-0 border-bottom rounded-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-light text-dark">#{{ $order->order_number }}</span>
                                    <small class="text-muted">{{ $order->created_at->format('Y/m/d H:i') }}</small>
                                </div>
                                
                                <div class="mb-2">
                                    @if($order->status == 'pending')
                                        <div class="d-flex align-items-center">
                                            <span class="bg-warning rounded-circle me-1" style="width: 8px; height: 8px;"></span>
                                            <span>در انتظار بررسی</span>
                                        </div>
                                    @elseif($order->status == 'processing')
                                        <div class="d-flex align-items-center">
                                            <span class="bg-info rounded-circle me-1" style="width: 8px; height: 8px;"></span>
                                            <span>در حال پردازش</span>
                                        </div>
                                    @elseif($order->status == 'completed')
                                        <div class="d-flex align-items-center">
                                            <span class="bg-success rounded-circle me-1" style="width: 8px; height: 8px;"></span>
                                            <span>تکمیل شده</span>
                                        </div>
                                    @elseif($order->status == 'cancelled')
                                        <div class="d-flex align-items-center">
                                            <span class="bg-danger rounded-circle me-1" style="width: 8px; height: 8px;"></span>
                                            <span>لغو شده</span>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <span class="bg-secondary rounded-circle me-1" style="width: 8px; height: 8px;"></span>
                                            <span>{{ $order->status }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-primary fw-bold">{{ number_format($order->total_amount) }} افغانی</span>
                                    <div>
                                        @if($order->payment_method == 'afghan_wallet')
                                            <span class="badge bg-primary rounded-pill">کیف پول افغانی</span>
                                        @elseif($order->payment_method == 'agency_visit')
                                            <span class="badge bg-secondary rounded-pill">مراجعه به نمایندگی</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill">{{ $order->payment_method }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($order->payment_status == 'pending')
                                            <span class="badge bg-warning text-dark rounded-pill">در انتظار پرداخت</span>
                                        @elseif($order->payment_status == 'paid')
                                            <span class="badge bg-success rounded-pill">پرداخت شده</span>
                                        @elseif($order->payment_status == 'failed')
                                            <span class="badge bg-danger rounded-pill">ناموفق</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill">{{ $order->payment_status }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('dashboard.shop.order.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="ri-eye-line"></i> مشاهده
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center py-3">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <div class="bg-light d-inline-block p-3 rounded-circle">
                                <i class="ri-shopping-bag-line fs-1 text-muted"></i>
                            </div>
                        </div>
                        <h5>هیچ سفارشی یافت نشد</h5>
                        <p class="text-muted mb-4">شما هنوز سفارشی ثبت نکرده‌اید.</p>
                        <a href="{{ route('dashboard.shop.index') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="ri-store-2-line me-1"></i>مشاهده فروشگاه
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Order Info -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0">راهنمای وضعیت سفارش‌ها</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">وضعیت سفارش:</h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex align-items-center">
                                    <span class="bg-warning rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                    <strong class="me-2">در انتظار بررسی:</strong>
                                    <span class="text-muted small">سفارش شما در صف بررسی قرار دارد.</span>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex align-items-center">
                                    <span class="bg-info rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                    <strong class="me-2">در حال پردازش:</strong>
                                    <span class="text-muted small">سفارش شما در حال پردازش است.</span>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex align-items-center">
                                    <span class="bg-success rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                    <strong class="me-2">تکمیل شده:</strong>
                                    <span class="text-muted small">سفارش شما تکمیل شده و اطلاعات حساب کاربری ارسال شده است.</span>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex align-items-center">
                                    <span class="bg-danger rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                    <strong class="me-2">لغو شده:</strong>
                                    <span class="text-muted small">سفارش شما لغو شده است.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">وضعیت پرداخت:</h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex align-items-center">
                                    <span class="bg-warning rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                    <strong class="me-2">در انتظار پرداخت:</strong>
                                    <span class="text-muted small">پرداخت شما هنوز انجام نشده است.</span>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex align-items-center">
                                    <span class="bg-success rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                    <strong class="me-2">پرداخت شده:</strong>
                                    <span class="text-muted small">پرداخت شما با موفقیت انجام شده است.</span>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex align-items-center">
                                    <span class="bg-danger rounded-circle me-2" style="width: 10px; height: 10px;"></span>
                                    <strong class="me-2">ناموفق:</strong>
                                    <span class="text-muted small">پرداخت شما ناموفق بوده است.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info d-flex align-items-center mt-4 mb-0">
                    <i class="ri-information-line fs-5 me-3"></i>
                    <span>در صورت بروز هرگونه مشکل در سفارش یا عدم دریافت اطلاعات حساب کاربری، با پشتیبانی تماس بگیرید.</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 