@extends('layouts.dashboard')

@section('title', 'داشبورد')
@section('page-title', 'داشبورد')

@section('content')
<!-- Welcome and Stats Cards -->
<div class="row g-3 mb-4">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="card bg-primary border-0 overflow-hidden position-relative">
            <div class="card-body p-3 p-md-4">
                <div class="row align-items-center">
                    <div class="col-12 col-md-8 mb-3 mb-md-0">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('logo/Logo-White.webp') }}" alt="AfghanPro" style="height: 32px;" class="d-md-none me-2">
                            <h3 class="fw-bold mb-0 text-white">خوش آمدید، {{ Auth::user()->name }}!</h3>
                        </div>
                        <p class="text-white-50 mb-0">به داشبورد افغان پرو خوش آمدید. از اینجا می‌توانید تمام خدمات مالی خود را مدیریت کنید.</p>
                    </div>
                    <div class="col-md-4 text-end d-none d-md-block">
                        <img src="{{ asset('logo/Logo-White.webp') }}" alt="AfghanPro" style="height: 48px;" class="rounded p-2 bg-white bg-opacity-10">
                    </div>
                </div>
            </div>
            <!-- Decorative Elements -->
            <div class="position-absolute bottom-0 end-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="150" height="150" viewBox="0 0 100 100" preserveAspectRatio="none" class="text-white opacity-10">
                    {{-- <path d="M0,0 L100,0 L100,100 Z" fill="currentColor"></path> --}}
                </svg>
            </div>
            <div class="position-absolute top-0 start-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 100 100" preserveAspectRatio="none" class="text-white opacity-10">
                    {{-- <circle cx="0" cy="0" r="50" fill="currentColor"></circle> --}}
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Wallet & Quick Actions Row -->
<div class="row g-3 mb-4">
    <!-- Afghani Wallet Card -->
    <div class="col-12 col-md-6">
        <div class="card h-100 border-0 shadow-sm wallet-card afn">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between flex-wrap mb-3">
                    <div>
                        <h5 class="card-title text-white mb-2 fs-6">کیف پول افغانی</h5>
                        <h3 class="fw-bold text-white mb-0">{{ number_format($afghanWallet->balance) }} <small class="fs-6">افغانی</small></h3>
                    </div>
                    <div class="wallet-badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 d-inline-flex align-items-center align-self-start">
                        <i class="ri-money-afghani-circle-line me-2"></i>
                        <span>AFN</span>
                    </div>
                </div>
                <p class="text-white-50 mb-3 small">آخرین بروزرسانی: {{ $afghanWallet->updated_at->format('Y/m/d H:i') }}</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('dashboard.wallets') }}" class="btn btn-sm btn-light bg-opacity-25 text-white rounded-pill px-3 shadow-sm" style=" color: #156b6d !important;">
                        <i class="ri-eye-line me-1"></i> جزئیات
                    </a>
                </div>
                <div class="wallet-decoration">
                    <i class="ri-money-afghani-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Dollar Wallet Card -->
    <div class="col-12 col-md-6">
        <div class="card h-100 border-0 shadow-sm wallet-card usd">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between flex-wrap mb-3">
                    <div>
                        <h5 class="card-title text-white mb-2 fs-6">کیف پول دلاری</h5>
                        <h3 class="fw-bold text-white mb-0">{{ number_format($dollarWallet->balance, 2) }} <small class="fs-6">دلار</small></h3>
                    </div>
                    <div class="wallet-badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 d-inline-flex align-items-center align-self-start">
                        <i class="ri-money-dollar-circle-line me-2"></i>
                        <span>USD</span>
                    </div>
                </div>
                <p class="text-white-50 mb-3 small">آخرین بروزرسانی: {{ $dollarWallet->updated_at->format('Y/m/d H:i') }}</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('dashboard.wallets') }}" class="btn btn-sm btn-light bg-opacity-25 text-white rounded-pill px-3 shadow-sm" style=" color: #156b6d !important;">
                        <i class="ri-eye-line me-1"></i> جزئیات
                    </a>
                </div>
                <div class="wallet-decoration">
                    <i class="ri-money-dollar-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Services Section -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-white p-3 p-md-4 d-flex justify-content-between align-items-center">
        <h5 class="m-0 fs-5"><i class="ri-service-line me-2 text-primary"></i>خدمات</h5>
    </div>
    <div class="card-body p-0">
        <div class="row g-0">
            <!-- Services Row -->
            <div class="col-6 col-lg-3 border-end p-1">
                <a href="{{ route('dashboard.shop.index') }}" class="d-flex flex-column align-items-center justify-content-center p-3 p-md-4 text-center text-decoration-none h-100 hover-card">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="ri-store-2-line" style="font-size: 22px;"></i>
                    </div>
                    <h6 class="mb-2 fs-6">خرید اکانت‌های پرمیوم</h6>
                    <p class="small text-secondary mb-0 d-none d-md-block">با پرداخت افغانی یا دلار</p>
                </a>
            </div>
            
        </div>
    </div>
</div>

<!-- Stats and Requests Row -->
<div class="row g-3">
    <!-- Transactions Column -->
    <div class="col-lg-8">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white p-3 p-md-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="m-0 fs-5"><i class="ri-exchange-funds-line me-2 text-primary"></i>تراکنش‌های اخیر</h5>
                <a href="{{ route('dashboard.wallets') }}" class="btn btn-sm btn-outline-primary rounded-pill"><i class="ri-eye-line me-1"></i>مشاهده همه</a>
            </div>
            <div class="card-body p-0">
                <!-- Desktop Transactions Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">تراکنش</th>
                                <th scope="col">مبلغ</th>
                                <th scope="col" class="text-center">وضعیت</th>
                                <th scope="col">تاریخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-box-sm 
                                                @if($transaction->transaction_type == 'deposit') bg-success bg-opacity-10 text-success 
                                                @elseif($transaction->transaction_type == 'withdraw') bg-danger bg-opacity-10 text-danger 
                                                @elseif($transaction->transaction_type == 'transfer') bg-primary bg-opacity-10 text-primary 
                                                @elseif($transaction->transaction_type == 'conversion') bg-warning bg-opacity-10 text-warning 
                                                @elseif($transaction->transaction_type == 'refund') bg-info bg-opacity-10 text-info 
                                                @else bg-secondary bg-opacity-10 text-secondary @endif
                                                rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                                @if($transaction->transaction_type == 'deposit')
                                                    <i class="ri-arrow-down-circle-line"></i>
                                                @elseif($transaction->transaction_type == 'withdraw')
                                                    <i class="ri-arrow-up-circle-line"></i>
                                                @elseif($transaction->transaction_type == 'transfer')
                                                    <i class="ri-exchange-funds-line"></i>
                                                @elseif($transaction->transaction_type == 'conversion')
                                                    <i class="ri-repeat-line"></i>
                                                @elseif($transaction->transaction_type == 'refund')
                                                    <i class="ri-refund-2-line"></i>
                                                @else
                                                    <i class="ri-exchange-line"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fs-6">{{ $transaction->description }}</h6>
                                                <span class="badge 
                                                    @if($transaction->transaction_type == 'deposit') bg-success-subtle text-success 
                                                    @elseif($transaction->transaction_type == 'withdraw') bg-danger-subtle text-danger 
                                                    @elseif($transaction->transaction_type == 'transfer') bg-primary-subtle text-primary 
                                                    @elseif($transaction->transaction_type == 'conversion') bg-warning-subtle text-warning 
                                                    @elseif($transaction->transaction_type == 'refund') bg-info-subtle text-info 
                                                    @else bg-secondary-subtle text-secondary @endif">
                                                    {{ $transaction->transaction_type }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium 
                                            @if($transaction->transaction_type == 'deposit' || $transaction->transaction_type == 'refund') text-success 
                                            @elseif($transaction->transaction_type == 'withdraw') text-danger 
                                            @else text-body @endif">
                                            {{ $transaction->transaction_type == 'deposit' || $transaction->transaction_type == 'refund' ? '+' : ($transaction->transaction_type == 'withdraw' ? '-' : '') }}
                                            {{ number_format($transaction->amount, 2) }} 
                                            <span class="text-secondary">{{ $transaction->currency_type }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-success">تکمیل شده</span>
                                    </td>
                                    <td>
                                        <div class="small text-secondary">{{ $transaction->created_at->format('Y/m/d') }}</div>
                                        <div class="small">{{ $transaction->created_at->format('H:i') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-secondary">
                                        <i class="ri-history-line d-block mb-2" style="font-size: 2rem;"></i>
                                        <p class="mb-0">هیچ تراکنشی یافت نشد</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Transactions List -->
                <div class="d-md-none">
                    <ul class="list-group list-group-flush transaction-list">
                        @forelse($recentTransactions as $transaction)
                            <li class="list-group-item px-3 py-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box-sm 
                                        @if($transaction->transaction_type == 'deposit') bg-success bg-opacity-10 text-success 
                                        @elseif($transaction->transaction_type == 'withdraw') bg-danger bg-opacity-10 text-danger 
                                        @elseif($transaction->transaction_type == 'transfer') bg-primary bg-opacity-10 text-primary 
                                        @elseif($transaction->transaction_type == 'conversion') bg-warning bg-opacity-10 text-warning 
                                        @elseif($transaction->transaction_type == 'refund') bg-info bg-opacity-10 text-info 
                                        @else bg-secondary bg-opacity-10 text-secondary @endif
                                        rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                        @if($transaction->transaction_type == 'deposit')
                                            <i class="ri-arrow-down-circle-line"></i>
                                        @elseif($transaction->transaction_type == 'withdraw')
                                            <i class="ri-arrow-up-circle-line"></i>
                                        @elseif($transaction->transaction_type == 'transfer')
                                            <i class="ri-exchange-funds-line"></i>
                                        @elseif($transaction->transaction_type == 'conversion')
                                            <i class="ri-repeat-line"></i>
                                        @elseif($transaction->transaction_type == 'refund')
                                            <i class="ri-refund-2-line"></i>
                                        @else
                                            <i class="ri-exchange-line"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fs-6">{{ $transaction->description }}</h6>
                                        <span class="badge 
                                            @if($transaction->transaction_type == 'deposit') bg-success-subtle text-success 
                                            @elseif($transaction->transaction_type == 'withdraw') bg-danger-subtle text-danger 
                                            @elseif($transaction->transaction_type == 'transfer') bg-primary-subtle text-primary 
                                            @elseif($transaction->transaction_type == 'conversion') bg-warning-subtle text-warning 
                                            @elseif($transaction->transaction_type == 'refund') bg-info-subtle text-info 
                                            @else bg-secondary-subtle text-secondary @endif">
                                            {{ $transaction->transaction_type }}
                                        </span>
                                    </div>
                                    <div class="text-end ms-auto">
                                        <div class="fw-medium 
                                            @if($transaction->transaction_type == 'deposit' || $transaction->transaction_type == 'refund') text-success 
                                            @elseif($transaction->transaction_type == 'withdraw') text-danger 
                                            @else text-body @endif">
                                            {{ $transaction->transaction_type == 'deposit' || $transaction->transaction_type == 'refund' ? '+' : ($transaction->transaction_type == 'withdraw' ? '-' : '') }}
                                            {{ number_format($transaction->amount, 2) }}
                                        </div>
                                        <div class="small text-secondary">{{ $transaction->currency_type }}</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                    <span class="badge rounded-pill bg-success">تکمیل شده</span>
                                    <div class="text-end small text-secondary">
                                        {{ $transaction->created_at->format('Y/m/d - H:i') }}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center py-5 text-secondary">
                                <i class="ri-history-line d-block mb-3" style="font-size: 2.5rem;"></i>
                                <p class="mb-0">هیچ تراکنشی یافت نشد</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white p-3 p-md-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="m-0 fs-5"><i class="ri-shopping-bag-line me-2 text-primary"></i>سفارش‌های اخیر</h5>
                <a href="{{ route('dashboard.shop.orders') }}" class="btn btn-sm btn-outline-primary rounded-pill"><i class="ri-eye-line me-1"></i>مشاهده همه</a>
            </div>
            <div class="card-body p-0">
                <!-- Desktop Orders Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">شماره سفارش</th>
                                <th scope="col">مبلغ</th>
                                <th scope="col">وضعیت</th>
                                <th scope="col">تاریخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('dashboard.shop.order.show', $order->id) }}" class="text-decoration-none">
                                            <div class="fw-medium text-body">#{{ $order->order_number }}</div>
                                            <div class="small text-secondary">{{ $order->items->count() }} آیتم</div>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="fw-medium">
                                            {{ number_format($order->total_amount) }} <span class="text-secondary">افغانی</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge rounded-pill bg-warning">در انتظار</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge rounded-pill bg-info">در حال پردازش</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge rounded-pill bg-success">تکمیل شده</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge rounded-pill bg-danger">لغو شده</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small text-secondary">{{ $order->created_at->format('Y/m/d') }}</div>
                                        <div class="small">{{ $order->created_at->format('H:i') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-secondary">
                                        <i class="ri-shopping-bag-line d-block mb-2" style="font-size: 2rem;"></i>
                                        <p class="mb-0">هیچ سفارشی یافت نشد</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Orders List -->
                <div class="d-md-none">
                    <ul class="list-group list-group-flush order-list">
                        @forelse($recentOrders as $order)
                            <li class="list-group-item px-3 py-3">
                                <div class="d-flex justify-content-between align-items-top mb-2">
                                    <a href="{{ route('dashboard.shop.order.show', $order->id) }}" class="text-decoration-none">
                                        <div class="fw-medium text-body">#{{ $order->order_number }}</div>
                                        <div class="small text-secondary">{{ $order->items->count() }} آیتم</div>
                                    </a>
                                    <div class="text-end">
                                        <div class="fw-medium">{{ number_format($order->total_amount) }}</div>
                                        <div class="small text-secondary">افغانی</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                    @if($order->status == 'pending')
                                        <span class="badge rounded-pill bg-warning">در انتظار</span>
                                    @elseif($order->status == 'processing')
                                        <span class="badge rounded-pill bg-info">در حال پردازش</span>
                                    @elseif($order->status == 'completed')
                                        <span class="badge rounded-pill bg-success">تکمیل شده</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge rounded-pill bg-danger">لغو شده</span>
                                    @endif
                                    <div class="text-end small text-secondary">
                                        {{ $order->created_at->format('Y/m/d - H:i') }}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center py-5 text-secondary">
                                <i class="ri-shopping-bag-line d-block mb-3" style="font-size: 2.5rem;"></i>
                                <p class="mb-0">هیچ سفارشی یافت نشد</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Requests Column -->
    <div class="col-lg-4">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white p-3 p-md-4">
                <h5 class="m-0 fs-5"><i class="ri-time-line me-2 text-primary"></i>درخواست‌های در انتظار</h5>
            </div>
            <div class="card-body p-0">
                <div class="text-center py-4 text-secondary">
                    <i class="ri-checkbox-circle-line d-block mb-2" style="font-size: 2rem;"></i>
                    <p class="mb-0">هیچ درخواست در انتظاری ندارید</p>
                </div>
            </div>
        </div>
        
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white p-3 p-md-4">
                <h5 class="m-0 fs-5"><i class="ri-flashlight-line me-2 text-primary"></i>اقدامات سریع</h5>
            </div>
            <div class="card-body p-3 p-md-4">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('dashboard.profile') }}#password-section" class="quick-action-btn btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 rounded-4">
                            <i class="ri-shield-keyhole-line mb-2" style="font-size: 20px;"></i>
                            <span>تغییر رمز</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('about') }}#agencies" class="quick-action-btn btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 rounded-4">
                            <i class="ri-map-pin-line mb-2" style="font-size: 20px;"></i>
                            <span>نمایندگی‌ها</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('contact') }}" class="quick-action-btn btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 rounded-4">
                            <i class="ri-customer-service-2-line mb-2" style="font-size: 20px;"></i>
                            <span>پشتیبانی</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Support Card -->
        <div class="card border-0 shadow-sm bg-primary overflow-hidden position-relative">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex align-items-center flex-wrap">
                    <div class="flex-shrink-0 mb-3 mb-md-0 me-md-3">
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ri-customer-service-2-line text-white" style="font-size: 26px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="text-white fw-bold mb-1">پشتیبانی ۷/۲۴</h5>
                        <p class="text-white-50 mb-3 small">سوالی دارید؟ با پشتیبانی تماس بگیرید</p>
                        <a href="{{ route('contact') }}" class="btn btn-sm btn-light rounded-pill px-3 shadow-sm">تماس با پشتیبانی</a>
                    </div>
                </div>
                <!-- Decorative element -->
                <div class="position-absolute top-50 end-0 translate-middle-y">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100" class="text-white opacity-10">
                        {{-- <circle cx="75" cy="50" r="50" fill="currentColor"></circle> --}}
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* General Styles */
    body {
        background-color: #f8f9fa;
    }
    
    /* Wallet Cards */
    .wallet-card {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .wallet-card.afn {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
    }
    
    .wallet-card.usd {
        background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
    }
    
    .wallet-badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .wallet-decoration {
        position: absolute;
        bottom: -15px;
        right: -15px;
        font-size: 8rem;
        opacity: 0.12;
        line-height: 1;
    }
    
    /* Service Icons */
    .icon-box-sm i {
        font-size: 1.25rem;
    }
    
    /* Hover Effects */
    .hover-card {
        transition: all 0.3s ease;
    }
    
    .hover-card:hover {
        transform: translateY(-2px);
        background-color: #f8f9fa;
    }
    
    .quick-action-btn {
        transition: all 0.3s ease;
        border-width: 1px;
        font-size: 0.9rem;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    /* Card Shadows */
    .card {
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 767.98px) {
        .card-header {
            padding: 0.75rem 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .table th, .table td {
            padding: 0.75rem 1rem;
        }
        
        .wallet-decoration {
            font-size: 6rem;
        }
    }
    
    @media (max-width: 575.98px) {
        h3 {
            font-size: 1.4rem;
        }
        
        .wallet-decoration {
            font-size: 5rem;
        }
        
        .icon-box {
            width: 45px !important;
            height: 45px !important;
        }
        
        .icon-box i {
            font-size: 20px !important;
        }
    }
</style>
@endpush 