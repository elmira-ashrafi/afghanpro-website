@extends('layouts.app')

@section('title', 'صفحه پرداخت آزمایشی حساب پی')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm rounded-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-center">
                        <i class="ri-bank-card-line me-2"></i>
                        صفحه پرداخت آزمایشی حساب پی
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-warning mb-4">
                        <h5 class="alert-heading">
                            <i class="ri-error-warning-line me-2"></i>
                            توجه: این یک صفحه پرداخت آزمایشی است
                        </h5>
                        <p class="mb-0">این صفحه تنها برای توسعه و آزمایش استفاده می‌شود. در نسخه نهایی، کاربر به درگاه واقعی حساب پی هدایت خواهد شد.</p>
                    </div>

                    <div class="bg-light p-4 rounded mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted">شماره پیگیری:</span>
                                    <span class="fw-bold">{{ $payment->tracking_code }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted">تاریخ:</span>
                                    <span class="fw-bold">{{ now()->format('Y/m/d H:i') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted">شماره تلفن:</span>
                                    <span class="fw-bold">{{ $payment->phone_number }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted">مبلغ:</span>
                                    <span class="fw-bold">{{ number_format($payment->amount) }} افغانی</span>
                                </div>
                            </div>
                            @if($payment->description)
                            <div class="col-12">
                                <div class="d-flex flex-column">
                                    <span class="text-muted">توضیحات:</span>
                                    <span class="fw-bold">{{ $payment->description }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/hesabpay-logo.png') }}" alt="HesabPay" class="img-fluid" style="max-height: 80px;" onerror="this.src='https://placehold.co/200x80?text=HesabPay'">
                    </div>

                    <div class="d-flex justify-content-between">
                        <form action="{{ route('hesabpay.mock.payment.failure', ['tracking_code' => $payment->tracking_code]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="ri-close-circle-line me-1"></i>
                                انصراف از پرداخت
                            </button>
                        </form>
                        
                        <form action="{{ route('hesabpay.mock.payment.success', ['tracking_code' => $payment->tracking_code]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success px-4">
                                <i class="ri-check-double-line me-1"></i>
                                تایید و پرداخت
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 