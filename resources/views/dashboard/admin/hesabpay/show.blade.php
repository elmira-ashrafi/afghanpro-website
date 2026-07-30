@extends('layouts.dashboard')

@section('title', 'جزئیات پرداخت حساب پی')

@section('content')
    <div class="container-fluid">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        جزئیات پرداخت حساب پی
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('dashboard.admin.hesabpay.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i>
                            بازگشت به لیست
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <!-- Payment details -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">اطلاعات پرداخت</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">شناسه پرداخت</label>
                                    <div class="form-control-plaintext">{{ $payment->id }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">کد پیگیری</label>
                                    <div class="form-control-plaintext">{{ $payment->tracking_code }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">مبلغ</label>
                                    <div class="form-control-plaintext">{{ number_format($payment->amount) }} افغانی</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">وضعیت</label>
                                    <div>
                                        @if($payment->status == 'completed')
                                            <span class="badge bg-success">تکمیل شده</span>
                                        @elseif($payment->status == 'pending')
                                            <span class="badge bg-warning">در انتظار</span>
                                        @elseif($payment->status == 'failed')
                                            <span class="badge bg-danger">ناموفق</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $payment->status }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">شماره تلفن</label>
                                    <div class="form-control-plaintext">{{ $payment->phone_number }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">شماره تراکنش حساب پی</label>
                                    <div class="form-control-plaintext">{{ $payment->transaction_id ?? 'ندارد' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">تاریخ ایجاد</label>
                                    <div class="form-control-plaintext">{{ $payment->created_at->format('Y-m-d H:i:s') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">تاریخ تکمیل</label>
                                    <div class="form-control-plaintext">{{ $payment->completed_at ? $payment->completed_at->format('Y-m-d H:i:s') : 'ندارد' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        @if($payment->description)
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">توضیحات</label>
                                        <div class="form-control-plaintext">{{ $payment->description }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- HesabPay Response Data -->
                @if($payment->response_data)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">اطلاعات دریافتی از حساب پی</h3>
                        </div>
                        <div class="card-body">
                            <pre class="language-json" dir="ltr"><code>{{ $payment->response_data }}</code></pre>
                        </div>
                    </div>
                @endif
                
                <!-- Related Transaction -->
                @if($payment->transaction)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">تراکنش مرتبط</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">شناسه تراکنش</label>
                                        <div class="form-control-plaintext">{{ $payment->transaction->id }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">وضعیت تراکنش</label>
                                        <div>
                                            @if($payment->transaction->status == 'completed')
                                                <span class="badge bg-success">تکمیل شده</span>
                                            @elseif($payment->transaction->status == 'pending')
                                                <span class="badge bg-warning">در انتظار</span>
                                            @elseif($payment->transaction->status == 'failed')
                                                <span class="badge bg-danger">ناموفق</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $payment->transaction->status }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">مبلغ تراکنش</label>
                                        <div class="form-control-plaintext">{{ number_format($payment->transaction->amount) }} افغانی</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">نوع تراکنش</label>
                                        <div class="form-control-plaintext">{{ $payment->transaction->transaction_type }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">توضیحات تراکنش</label>
                                        <div class="form-control-plaintext">{{ $payment->transaction->description }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="col-md-4">
                <!-- User information -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">اطلاعات کاربر</h3>
                    </div>
                    @if($payment->user)
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">نام کاربر</label>
                                <div class="form-control-plaintext">{{ $payment->user->name }} {{ $payment->user->lastname }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ایمیل</label>
                                <div class="form-control-plaintext">{{ $payment->user->email }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">شماره تلفن</label>
                                <div class="form-control-plaintext">{{ $payment->user->phone ?? 'ندارد' }}</div>
                            </div>
                            <a href="{{ route('dashboard.admin.users.show', $payment->user->id) }}" class="btn btn-primary">
                                مشاهده پروفایل کاربر
                            </a>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="alert alert-warning">
                                کاربر مرتبط با این پرداخت یافت نشد.
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Actions -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">عملیات</h3>
                    </div>
                    <div class="card-body">
                        @if($payment->status == 'pending')
                            <form action="{{ route('dashboard.admin.hesabpay.complete', $payment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success mb-2 w-100">
                                    <i class="fas fa-check"></i>
                                    علامت‌گذاری به عنوان تکمیل شده
                                </button>
                            </form>
                            <form action="{{ route('dashboard.admin.hesabpay.fail', $payment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger mb-2 w-100">
                                    <i class="fas fa-times"></i>
                                    علامت‌گذاری به عنوان ناموفق
                                </button>
                            </form>
                        @elseif($payment->status == 'failed')
                            <form action="{{ route('dashboard.admin.hesabpay.complete', $payment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success mb-2 w-100">
                                    <i class="fas fa-check"></i>
                                    علامت‌گذاری به عنوان تکمیل شده
                                </button>
                            </form>
                        @else
                            <div class="alert alert-info">
                                این پرداخت قبلاً تکمیل شده است و نیاز به اقدام ندارد.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 