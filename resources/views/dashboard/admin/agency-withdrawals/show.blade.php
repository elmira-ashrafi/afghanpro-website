@extends('layouts.admin')

@section('title', 'جزئیات برداشت نقدی')

@section('page-title', 'جزئیات برداشت نقدی از نمایندگی')

@section('content')
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">اطلاعات درخواست</h5>
                    <a href="{{ route('dashboard.admin.agency-withdrawals') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ri-arrow-go-back-line me-1"></i>بازگشت به لیست
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted mb-3">اطلاعات درخواست</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>شماره پیگیری:</span>
                                <span class="text-primary fw-bold">{{ $withdrawal->tracking_number }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>تاریخ درخواست:</span>
                                <span>{{ $withdrawal->created_at->format('Y/m/d H:i') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>وضعیت:</span>
                                <span>
                                    @if($withdrawal->status == 'completed')
                                        <span class="badge bg-success">تکمیل شده</span>
                                    @elseif($withdrawal->status == 'pending')
                                        <span class="badge bg-warning">در انتظار تایید</span>
                                    @elseif($withdrawal->status == 'approved')
                                        <span class="badge bg-info">تایید شده</span>
                                    @elseif($withdrawal->status == 'rejected')
                                        <span class="badge bg-danger">رد شده</span>
                                    @elseif($withdrawal->status == 'cancelled')
                                        <span class="badge bg-secondary">لغو شده</span>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>مبلغ:</span>
                                <span class="fw-bold">{{ number_format($withdrawal->amount) }} {{ $withdrawal->currency_type == 'AFN' ? 'افغانی' : 'دلار' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>کیف پول:</span>
                                <span>{{ $withdrawal->wallet_type == 'afghan_wallet' ? 'کیف پول افغانی' : 'کیف پول دلاری' }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted mb-3">اطلاعات کاربر و دریافت کننده</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>نام کاربر:</span>
                                <span>{{ $withdrawal->user->name }} {{ $withdrawal->user->lastname }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>تلفن کاربر:</span>
                                <span dir="ltr">{{ $withdrawal->user->phone }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>نام دریافت کننده:</span>
                                <span>{{ $withdrawal->full_name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>تلفن دریافت کننده:</span>
                                <span dir="ltr">{{ $withdrawal->phone }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>شهر:</span>
                                <span>{{ $withdrawal->city }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <h6 class="text-uppercase text-muted mb-3">اطلاعات نمایندگی</h6>
                <div class="card mb-4 border">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>نام نمایندگی:</strong> {{ $withdrawal->agency->name }}</p>
                                <p class="mb-2"><strong>آدرس:</strong> {{ $withdrawal->agency->address }}</p>
                                <p class="mb-0"><strong>شهر/استان:</strong> {{ $withdrawal->agency->city }}/{{ $withdrawal->agency->province }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>شماره تماس:</strong> <span dir="ltr">{{ $withdrawal->agency->phone }}</span></p>
                                <p class="mb-2"><strong>مسئول:</strong> {{ $withdrawal->agency->contact_person }}</p>
                                @if(!empty($withdrawal->agency->working_hours))
                                <p class="mb-0"><strong>ساعات کاری:</strong></p>
                                <div class="mt-1">
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
                                                    echo '<div class="small my-1">';
                                                    echo '<strong>' . $daysTranslation[$day] . ':</strong> ';
                                                    
                                                    if (isset($hours['closed']) && $hours['closed']) {
                                                        echo 'تعطیل';
                                                    } elseif (!empty($hours['open']) && !empty($hours['close'])) {
                                                        echo $hours['open'] . ' تا ' . $hours['close'];
                                                    } else {
                                                        echo 'نامشخص';
                                                    }
                                                    
                                                    echo '</div>';
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
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($withdrawal->description)
                <h6 class="text-uppercase text-muted mb-3">توضیحات</h6>
                <div class="card mb-4 border">
                    <div class="card-body">
                        <p class="mb-0">{{ $withdrawal->description }}</p>
                    </div>
                </div>
                @endif
                
                @if($withdrawal->transaction)
                <h6 class="text-uppercase text-muted mb-3">اطلاعات تراکنش</h6>
                <div class="card mb-4 border">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>شناسه تراکنش:</strong> {{ $withdrawal->transaction->id }}</p>
                                <p class="mb-1"><strong>نوع تراکنش:</strong> 
                                    @if($withdrawal->transaction->transaction_type == 'deposit')
                                        واریز
                                    @elseif($withdrawal->transaction->transaction_type == 'withdraw')
                                        برداشت
                                    @elseif($withdrawal->transaction->transaction_type == 'transfer')
                                        انتقال
                                    @elseif($withdrawal->transaction->transaction_type == 'conversion')
                                        تبدیل ارز
                                    @elseif($withdrawal->transaction->transaction_type == 'refund')
                                        برگشت وجه
                                    @endif
                                </p>
                                <p class="mb-0"><strong>تاریخ تراکنش:</strong> {{ $withdrawal->transaction->created_at->format('Y/m/d H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>مبلغ:</strong> {{ number_format($withdrawal->transaction->amount) }} {{ $withdrawal->transaction->currency_type }}</p>
                                <p class="mb-1"><strong>وضعیت:</strong>
                                    @if($withdrawal->transaction->status == 'completed')
                                        <span class="badge bg-success">تکمیل شده</span>
                                    @elseif($withdrawal->transaction->status == 'pending')
                                        <span class="badge bg-warning">در انتظار</span>
                                    @elseif($withdrawal->transaction->status == 'failed')
                                        <span class="badge bg-danger">ناموفق</span>
                                    @elseif($withdrawal->transaction->status == 'cancelled')
                                        <span class="badge bg-secondary">لغو شده</span>
                                    @endif
                                </p>
                                <p class="mb-0"><strong>توضیحات:</strong> {{ $withdrawal->transaction->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">مدیریت درخواست</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.admin.agency-withdrawals.update', $withdrawal->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="status" class="form-label">تغییر وضعیت</label>
                        <select class="form-select" id="status" name="status">
                            <option value="pending" {{ $withdrawal->status == 'pending' ? 'selected' : '' }}>در انتظار تایید</option>
                            <option value="approved" {{ $withdrawal->status == 'approved' ? 'selected' : '' }}>تایید شده</option>
                            <option value="completed" {{ $withdrawal->status == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                            <option value="rejected" {{ $withdrawal->status == 'rejected' ? 'selected' : '' }}>رد شده</option>
                            <option value="cancelled" {{ $withdrawal->status == 'cancelled' ? 'selected' : '' }}>لغو شده</option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading mb-1">راهنمای تغییر وضعیت</h6>
                        <ul class="mb-0 ps-3">
                            <li><strong>در انتظار تایید:</strong> درخواست در حال بررسی است.</li>
                            <li><strong>تایید شده:</strong> درخواست تایید شده و کاربر می‌تواند به نمایندگی مراجعه کند.</li>
                            <li><strong>تکمیل شده:</strong> کاربر وجه را از نمایندگی دریافت کرده است.</li>
                            <li><strong>رد شده / لغو شده:</strong> درخواست رد یا لغو شده و مبلغ به کیف پول کاربر بازگردانده خواهد شد.</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>ذخیره تغییرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 