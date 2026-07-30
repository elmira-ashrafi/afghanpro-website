@extends('layouts.dashboard')

@section('title', 'جزئیات برداشت نقدی')
@section('page-title', 'جزئیات برداشت نقدی از نمایندگی')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-3 p-md-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="status-icon rounded-circle d-flex align-items-center justify-content-center
                            @if($withdrawal->status == 'completed') bg-success
                            @elseif($withdrawal->status == 'pending') bg-warning
                            @elseif($withdrawal->status == 'approved') bg-info
                            @elseif($withdrawal->status == 'rejected' || $withdrawal->status == 'cancelled') bg-danger
                            @endif
                            text-white" style="width: 42px; height: 42px;">
                            @if($withdrawal->status == 'completed')
                                <i class="ri-check-line fs-4"></i>
                            @elseif($withdrawal->status == 'pending')
                                <i class="ri-time-line fs-4"></i>
                            @elseif($withdrawal->status == 'approved')
                                <i class="ri-thumb-up-line fs-4"></i>
                            @elseif($withdrawal->status == 'rejected' || $withdrawal->status == 'cancelled')
                                <i class="ri-close-line fs-4"></i>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h5 class="mb-0 fs-5">جزئیات برداشت نقدی</h5>
                        <p class="mb-0 text-secondary small">شماره پیگیری: {{ $withdrawal->tracking_number }}</p>
                    </div>
                </div>
                <a href="{{ route('dashboard.wallets.withdraw.agency.history') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                    <i class="ri-arrow-go-back-line me-1"></i>بازگشت
                </a>
            </div>
            <div class="card-body p-3 p-md-4">
                <!-- Status Badge -->
                <div class="text-center mb-4">
                    <div class="d-inline-block position-relative">
                        @if($withdrawal->status == 'completed')
                            <div class="bg-success-subtle text-success px-4 py-2 rounded-pill">
                                <span class="fw-medium">تکمیل شده</span>
                            </div>
                        @elseif($withdrawal->status == 'pending')
                            <div class="bg-warning-subtle text-warning px-4 py-2 rounded-pill">
                                <span class="fw-medium">در انتظار تایید</span>
                            </div>
                        @elseif($withdrawal->status == 'approved')
                            <div class="bg-info-subtle text-info px-4 py-2 rounded-pill">
                                <span class="fw-medium">تایید شده</span>
                            </div>
                        @elseif($withdrawal->status == 'rejected')
                            <div class="bg-danger-subtle text-danger px-4 py-2 rounded-pill">
                                <span class="fw-medium">رد شده</span>
                            </div>
                        @elseif($withdrawal->status == 'cancelled')
                            <div class="bg-secondary-subtle text-secondary px-4 py-2 rounded-pill">
                                <span class="fw-medium">لغو شده</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Withdrawal Amount Display -->
                <div class="card bg-light border-0 mb-4">
                    <div class="card-body text-center py-4">
                        <div class="small text-secondary mb-2">مبلغ برداشت</div>
                        <h3 class="mb-0 fw-bold amount-display" style="font-size: 2rem;">
                            {{ number_format($withdrawal->amount) }}
                            <span class="fs-6 ms-1">{{ $withdrawal->currency_type == 'AFN' ? 'افغانی' : 'دلار' }}</span>
                        </h3>
                        <div class="mt-2 small text-secondary">کیف پول: {{ $withdrawal->wallet_type == 'afghan_wallet' ? 'افغانی' : 'دلاری' }}</div>
                    </div>
                </div>
            
                <!-- Divider -->
                <div class="row g-4">
                    <!-- Request Details -->
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="mb-0"><i class="ri-file-info-line me-2 text-primary"></i>اطلاعات درخواست</h6>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between py-3">
                                        <span class="text-secondary">تاریخ درخواست:</span>
                                        <span>{{ $withdrawal->created_at->format('Y/m/d H:i') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between py-3">
                                        <span class="text-secondary">شماره پیگیری:</span>
                                        <span class="text-primary fw-medium">{{ $withdrawal->tracking_number }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between py-3">
                                        <span class="text-secondary">کیف پول:</span>
                                        <span>{{ $withdrawal->wallet_type == 'afghan_wallet' ? 'کیف پول افغانی' : 'کیف پول دلاری' }}</span>
                                    </li>
                                    @if($withdrawal->description)
                                    <li class="list-group-item py-3">
                                        <div class="text-secondary mb-1">توضیحات:</div>
                                        <p class="mb-0">{{ $withdrawal->description }}</p>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recipient Details -->
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="mb-0"><i class="ri-user-3-line me-2 text-primary"></i>اطلاعات دریافت‌کننده</h6>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between py-3">
                                        <span class="text-secondary">نام و نام خانوادگی:</span>
                                        <span>{{ $withdrawal->full_name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between py-3">
                                        <span class="text-secondary">شماره تماس:</span>
                                        <span dir="ltr">{{ $withdrawal->phone }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between py-3">
                                        <span class="text-secondary">شهر:</span>
                                        <span>{{ $withdrawal->city }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Agency Details -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="mb-0"><i class="ri-building-line me-2 text-primary"></i>اطلاعات نمایندگی</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2 pb-2 border-bottom">
                                                <span class="d-block text-secondary small">نام نمایندگی:</span>
                                                <span class="fw-medium">{{ $withdrawal->agency->name }}</span>
                                            </li>
                                            <li class="mb-2 pb-2 border-bottom">
                                                <span class="d-block text-secondary small">آدرس:</span>
                                                <span>{{ $withdrawal->agency->address }}</span>
                                            </li>
                                            <li>
                                                <span class="d-block text-secondary small">شهر/استان:</span>
                                                <span>{{ $withdrawal->agency->city }}/{{ $withdrawal->agency->province }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2 pb-2 border-bottom">
                                                <span class="d-block text-secondary small">شماره تماس:</span>
                                                <span dir="ltr">{{ $withdrawal->agency->phone }}</span>
                                            </li>
                                            <li class="mb-2 pb-2 border-bottom">
                                                <span class="d-block text-secondary small">مسئول:</span>
                                                <span>{{ $withdrawal->agency->contact_person }}</span>
                                            </li>
                                            @if(!empty($withdrawal->agency->working_hours))
                                            <li>
                                                <span class="d-block text-secondary small">ساعات کاری:</span>
                                                <div class="row g-1 mt-1">
                                                    @php
                                                    try {
                                                        $workingHours = $withdrawal->agency->working_hours;
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
                                                        
                                                        if (is_array($workingHours)) {
                                                            foreach ($workingHours as $day => $hours) {
                                                                if (isset($daysTranslation[$day])) {
                                                                    echo '<div class="col-auto"><span class="badge rounded-pill bg-light text-dark">';
                                                                    echo '<span class="fw-medium me-1">' . $daysTranslation[$day] . ':</span>';
                                                                    
                                                                    if (isset($hours['closed']) && $hours['closed']) {
                                                                        echo 'تعطیل';
                                                                    } elseif (!empty($hours['open']) && !empty($hours['close'])) {
                                                                        echo $hours['open'] . ' تا ' . $hours['close'];
                                                                    } else {
                                                                        echo 'نامشخص';
                                                                    }
                                                                    
                                                                    echo '</span></div>';
                                                                }
                                                            }
                                                        } else {
                                                            echo "ساعات کاری نامشخص";
                                                        }
                                                    } catch (Exception $e) {
                                                        echo "ساعات کاری نامشخص";
                                                    }
                                                    @endphp
                                                </div>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Withdrawal Instructions -->
                <div class="card border-0 mt-4">
                    <div class="card-body p-0">
                        @if($withdrawal->status == 'pending')
                        <div class="alert alert-warning d-flex p-3 mb-0">
                            <div class="me-3 fs-3 text-warning">
                                <i class="ri-time-line"></i>
                            </div>
                            <div>
                                <h6 class="alert-heading mb-1">در انتظار تایید</h6>
                                <p class="mb-0 small">درخواست شما در حال بررسی است. پس از تایید، می‌توانید با مراجعه به نمایندگی انتخاب شده و ارائه شماره پیگیری و کارت شناسایی، مبلغ مورد نظر را دریافت کنید.</p>
                            </div>
                        </div>
                        @elseif($withdrawal->status == 'approved' || $withdrawal->status == 'completed')
                        <div class="alert alert-success d-flex p-3 mb-0">
                            <div class="me-3 fs-3 text-success">
                                <i class="ri-checkbox-circle-line"></i>
                            </div>
                            <div>
                                <h6 class="alert-heading mb-1">آماده دریافت</h6>
                                <p class="mb-0 small">درخواست شما تایید شده است. با مراجعه به نمایندگی انتخاب شده و ارائه شماره پیگیری <strong>{{ $withdrawal->tracking_number }}</strong> و کارت شناسایی معتبر، می‌توانید مبلغ مورد نظر را دریافت کنید.</p>
                            </div>
                        </div>
                        @elseif($withdrawal->status == 'rejected' || $withdrawal->status == 'cancelled')
                        <div class="alert alert-danger d-flex p-3 mb-0">
                            <div class="me-3 fs-3 text-danger">
                                <i class="ri-close-circle-line"></i>
                            </div>
                            <div>
                                <h6 class="alert-heading mb-1">درخواست رد شده</h6>
                                <p class="mb-0 small">متاسفانه درخواست شما تایید نشده است. مبلغ کسر شده به کیف پول شما بازگردانده شده است.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .amount-display {
        line-height: 1.2;
    }
    @media (max-width: 576px) {
        .amount-display {
            font-size: 1.5rem !important;
        }
    }
</style>
@endpush 