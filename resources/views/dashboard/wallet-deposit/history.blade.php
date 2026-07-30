@extends('layouts.dashboard')

@section('title', 'تاریخچه شارژ کیف پول')
@section('page-title', 'تاریخچه شارژ کیف پول')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.wallets') }}">کیف پول‌ها</a></li>
                    <li class="breadcrumb-item active" aria-current="page">تاریخچه شارژ</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <!-- Transaction History Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="row align-items-center">
                <div class="col-md-6 mb-2 mb-md-0">
                    <h5 class="card-title m-0 d-flex align-items-center">
                        <i class="ri-history-line me-2 text-primary"></i>
                        تاریخچه شارژ کیف پول
                    </h5>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-md-end align-items-center">
                        <a href="{{ route('dashboard.wallets') }}" class="btn btn-outline-primary btn-sm rounded-pill">
                            <i class="ri-arrow-go-back-line me-1"></i>
                            بازگشت به کیف پول‌ها
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="transaction-history">
                @forelse($depositTransactions as $transaction)
                    <div class="transaction-item">
                        <div class="transaction-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="transaction-icon {{ $transaction->currency_type == 'AFN' ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }}">
                                    @if($transaction->currency_type == 'AFN')
                                        <i class="ri-money-afghani-circle-line"></i>
                                    @else
                                        <i class="ri-money-dollar-circle-line"></i>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="transaction-title mb-0">
                                        {{ $transaction->currency_type == 'AFN' ? 'شارژ کیف پول افغانی' : 'شارژ کیف پول دلاری' }}
                                    </h6>
                                    <div class="transaction-date">
                                        <small>{{ $transaction->created_at->format('Y/m/d') }} - {{ $transaction->created_at->format('H:i') }}</small>
                                    </div>
                                    <div class="d-block d-md-none text-muted mt-1">
                                        <small><i class="ri-arrow-down-s-line"></i> برای مشاهده جزئیات کلیک کنید</small>
                                    </div>
                                </div>
                            </div>
                            <div class="transaction-amount {{ $transaction->currency_type == 'AFN' ? 'text-success' : 'text-primary' }}">
                                <strong>{{ number_format($transaction->amount) }}</strong>
                                <small>{{ $transaction->currency_type == 'AFN' ? 'افغانی' : 'دلار' }}</small>
                            </div>
                        </div>
                        
                        <div class="transaction-details">
                            <div class="transaction-detail-item">
                                <span class="detail-label">وضعیت:</span>
                                <span class="detail-value">
                                    @if($transaction->status == 'completed')
                                        <span class="badge bg-success-subtle text-success">تکمیل شده</span>
                                    @elseif($transaction->status == 'pending')
                                        <span class="badge bg-warning-subtle text-warning">در انتظار</span>
                                    @elseif($transaction->status == 'failed')
                                        <span class="badge bg-danger-subtle text-danger">ناموفق</span>
                                    @elseif($transaction->status == 'cancelled')
                                        <span class="badge bg-secondary-subtle text-secondary">لغو شده</span>
                                    @endif
                                </span>
                            </div>
                            
                            <div class="transaction-detail-item">
                                <span class="detail-label">روش پرداخت:</span>
                                <span class="detail-value">
                                    @if(strpos($transaction->description, 'HisabPay') !== false || strpos($transaction->description, 'حساب پی') !== false)
                                        <span class="badge bg-primary-subtle text-primary">حساب پی</span>
                                    @else
                                        <span class="badge bg-info-subtle text-info">مراجعه به نمایندگی</span>
                                    @endif
                                </span>
                            </div>
                            
                            <div class="transaction-detail-item">
                                <span class="detail-label">شماره پیگیری:</span>
                                <span class="detail-value text-muted">{{ $transaction->reference_id }}</span>
                            </div>
                            
                            <div class="transaction-detail-item">
                                <span class="detail-label">توضیحات:</span>
                                <span class="detail-value text-muted">{{ $transaction->description }}</span>
                            </div>
                            
                            @if($transaction->status == 'pending' && isset($transaction->agency))
                                <div class="agency-info mt-3">
                                    <div class="agency-header">
                                        <i class="ri-store-2-line me-1"></i>
                                        اطلاعات نمایندگی جهت مراجعه
                                    </div>
                                    <div class="agency-details">
                                        <div class="agency-detail-item">
                                            <span class="detail-label">نام نمایندگی:</span>
                                            <span class="detail-value">{{ $transaction->agency->name }}</span>
                                        </div>
                                        
                                        <div class="agency-detail-item">
                                            <span class="detail-label">آدرس:</span>
                                            <span class="detail-value">{{ $transaction->agency->province }}، {{ $transaction->agency->city }}، {{ $transaction->agency->address }}</span>
                                        </div>
                                        
                                        <div class="agency-detail-item">
                                            <span class="detail-label">شماره تماس:</span>
                                            <span class="detail-value">
                                                <a href="tel:{{ $transaction->agency->phone }}" class="text-decoration-none">
                                                    <i class="ri-phone-line me-1"></i>
                                                    {{ $transaction->agency->phone }}
                                                </a>
                                            </span>
                                        </div>
                                        
                                        @if($transaction->agency->working_hours)
                                            <div class="agency-detail-item">
                                                <span class="detail-label">ساعات کاری:</span>
                                                <span class="detail-value">
                                                    @php
                                                    try {
                                                        $workingHours = $transaction->agency->working_hours;
                                                        if (is_string($workingHours)) {
                                                            $workingHours = json_decode($workingHours, true);
                                                        }
                                                        
                                                        $daysTranslation = [
                                                            'saturday' => 'شنبه',
                                                            'sunday' => 'یکشنبه',
                                                            'monday' => 'دوشنبه',
                                                            'tuesday' => 'سه‌شنبه',
                                                            'wednesday' => 'چهارشنبه',
                                                            'thursday' => 'پنجشنبه',
                                                            'friday' => 'جمعه'
                                                        ];
                                                        
                                                        foreach ($daysTranslation as $day => $persianDay) {
                                                            if (isset($workingHours[$day])) {
                                                                if (isset($workingHours[$day]['closed']) && $workingHours[$day]['closed']) {
                                                                    echo "<div>{$persianDay}: تعطیل</div>";
                                                                } else if (isset($workingHours[$day]['open']) && isset($workingHours[$day]['close'])) {
                                                                    echo "<div>{$persianDay}: {$workingHours[$day]['open']} تا {$workingHours[$day]['close']}</div>";
                                                                }
                                                            }
                                                        }
                                                    } catch (\Exception $e) {
                                                        echo "ساعات کاری موجود است";
                                                    }
                                                    @endphp
                                                </span>
                                            </div>
                                        @endif
                                        
                                        <div class="alert alert-warning mt-3 mb-0 d-flex align-items-start" style="border-radius: 0.5rem;">
                                            <i class="ri-information-line me-2 fs-5 pt-1"></i>
                                            <div>
                                                <strong>راهنمای مراجعه:</strong>
                                                <p class="mb-0 small">لطفا با همراه داشتن مدارک شناسایی و کد پیگیری {{ $transaction->reference_id }} به نمایندگی فوق مراجعه نمایید. پس از واریز وجه، کیف پول شما شارژ خواهد شد.</p>
                                            </div>
                                        </div>
                                        
                                        @if($transaction->agency->latitude && $transaction->agency->longitude)
                                            <div class="mt-3">
                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $transaction->agency->latitude }},{{ $transaction->agency->longitude }}" 
                                                   class="btn btn-sm btn-outline-primary w-100" 
                                                   target="_blank">
                                                    <i class="ri-map-pin-line me-1"></i>
                                                    مشاهده موقعیت در نقشه
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state text-center py-5">
                        <div class="empty-state-icon">
                            <i class="ri-history-line"></i>
                        </div>
                        <h6 class="mt-3">هیچ تراکنشی یافت نشد</h6>
                        <p class="text-muted">شما هنوز هیچ تراکنش شارژی انجام نداده‌اید.</p>
                        <div class="mt-4">
                            <a href="{{ route('dashboard.wallets.deposit.afghani') }}" class="btn btn-sm btn-outline-success me-2">
                                <i class="ri-money-afghani-circle-line me-1"></i>
                                شارژ کیف پول افغانی
                            </a>
                            <a href="{{ route('dashboard.wallets.deposit.dollar') }}" class="btn btn-sm btn-outline-primary">
                                <i class="ri-money-dollar-circle-line me-1"></i>
                                شارژ کیف پول دلاری
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        
        @if($depositTransactions->count() > 0)
            <div class="card-footer bg-white py-3">
                {{ $depositTransactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Transaction History Styles */
    .transaction-history {
        display: flex;
        flex-direction: column;
    }
    
    .transaction-item {
        padding: 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: background-color 0.2s;
    }
    
    .transaction-item:hover {
        background-color: rgba(0,0,0,0.01);
    }
    
    .transaction-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 0.75rem;
    }
    
    .transaction-header {
        position: relative;
        padding-right: 20px;
    }
    
    .transaction-details {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px dashed rgba(0,0,0,0.1);
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .transaction-detail-item {
        display: flex;
        flex-direction: column;
    }
    
    .detail-label {
        font-weight: 500;
        color: #6c757d;
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }
    
    .agency-info {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
    }
    
    .agency-header {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.75rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding-bottom: 0.5rem;
    }
    
    .agency-details {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .agency-detail-item {
        display: flex;
        flex-direction: column;
    }
    
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }
    
    .empty-state-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: #6c757d;
    }
    
    .toggle-indicator {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.25rem;
        color: #6c757d;
        transition: transform 0.3s;
    }
    
    .transaction-header.active .toggle-indicator {
        transform: translateY(-50%) rotate(180deg);
    }
    
    @media (max-width: 767.98px) {
        .transaction-header {
            cursor: pointer;
        }
        
        .transaction-header::after {
            content: "\ea4e";
            font-family: 'remixicon';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.25rem;
            color: #6c757d;
        }
        
        .transaction-header.active::after {
            content: "\ea77";
        }
    }
    
    /* Medium screens and up */
    @media (min-width: 768px) {
        .transaction-details {
            grid-template-columns: 1fr 1fr;
        }
        
        .agency-details {
            grid-template-columns: 1fr 1fr;
        }
        
        .transaction-detail-item {
            flex-direction: row;
            align-items: center;
        }
        
        .detail-label {
            min-width: 120px;
            margin-bottom: 0;
        }
        
        .agency-detail-item {
            flex-direction: row;
            align-items: center;
        }
        
        .toggle-indicator {
            display: none;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Make entire transaction item clickable to show/hide details on mobile
        const transactionItems = document.querySelectorAll('.transaction-item');
        
        transactionItems.forEach(item => {
            const header = item.querySelector('.transaction-header');
            const details = item.querySelector('.transaction-details');
            
            // Initialize details visibility based on screen size
            if (window.innerWidth < 768) {
                details.style.display = 'none';
            }
            
            header.addEventListener('click', function(e) {
                // Only toggle on mobile
                if (window.innerWidth < 768) {
                    this.classList.toggle('active');
                    if (details.style.display === 'none') {
                        details.style.display = 'grid';
                    } else {
                        details.style.display = 'none';
                    }
                }
            });
        });
        
        // Reset details visibility on window resize
        window.addEventListener('resize', function() {
            transactionItems.forEach(item => {
                const header = item.querySelector('.transaction-header');
                const details = item.querySelector('.transaction-details');
                
                if (window.innerWidth >= 768) {
                    details.style.display = 'grid';
                    header.classList.remove('active');
                } else {
                    details.style.display = 'none';
                    header.classList.remove('active');
                }
            });
        });
    });
</script>
@endpush 