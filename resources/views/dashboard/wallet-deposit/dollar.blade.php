@extends('layouts.dashboard')

@section('title', 'شارژ کیف پول دلاری')
@section('page-title', 'شارژ کیف پول دلاری')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="ri-bank-card-line me-2 text-primary"></i>
                        فرم شارژ کیف پول دلاری
                    </h5>
                </div>
                <div class="card-body p-lg-4">
                    <form action="{{ route('dashboard.wallets.deposit.dollar.store') }}" method="POST" id="depositForm" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" min="1" step="0.01" value="{{ old('amount', 10) }}" required>
                                    <label for="amount">مبلغ (دلار)</label>
                                    <div class="form-text mt-1">
                                        <i class="ri-information-line"></i>
                                        حداقل مبلغ شارژ: 1 دلار
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" disabled required>
                                        <option value="agency_visit" selected>مراجعه به نمایندگی</option>
                                    </select>
                                    <label for="payment_method">روش پرداخت</label>
                                    <input type="hidden" name="payment_method" value="agency_visit">
                                    <div class="form-text mt-1">
                                        <i class="ri-information-line"></i>
                                        برای شارژ کیف پول دلاری، تنها مراجعه به نمایندگی امکان‌پذیر است.
                                    </div>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select @error('agency_id') is-invalid @enderror" id="agency_id" name="agency_id" required>
                                        <option value="">انتخاب کنید</option>
                                        @foreach($agencies as $agency)
                                            <option value="{{ $agency->id }}" {{ old('agency_id') == $agency->id ? 'selected' : '' }}>
                                                {{ $agency->name }} - {{ $agency->city }}, {{ $agency->province }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="agency_id">انتخاب نمایندگی</label>
                                    @error('agency_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info d-flex bg-info-subtle border-0 rounded-3 mb-4">
                            <div class="me-3 text-info">
                                <i class="ri-information-line fs-4"></i>
                            </div>
                            <div>
                                <h6 class="alert-heading mb-1">شارژ کیف پول دلاری</h6>
                                <p class="mb-0">برای شارژ کیف پول دلاری، پس از ثبت درخواست، کد پیگیری دریافت خواهید کرد. با مراجعه به نمایندگی انتخاب شده و ارائه کد پیگیری و پرداخت وجه به دلار، کیف پول شما شارژ خواهد شد.</p>
                            </div>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <textarea class="form-control" id="description" name="description" rows="3" style="height: 100px;">{{ old('description') }}</textarea>
                            <label for="description">توضیحات (اختیاری)</label>
                        </div>
                        
                        <div class="d-flex flex-wrap justify-content-end gap-2">
                            <a href="{{ route('dashboard.wallets') }}" class="btn btn-light px-4">
                                <i class="ri-arrow-go-back-line me-1"></i>
                                بازگشت
                            </a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="ri-bank-card-line me-1"></i>
                                ثبت درخواست شارژ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-5">
            <div class="row g-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="wallet-icon-large me-3 bg-primary-subtle text-primary rounded-circle">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">کیف پول دلاری</h5>
                                    <h3 class="mb-0 d-flex align-items-center">
                                        {{ number_format($dollarWallet->balance, 2) }}
                                        <span class="fs-6 ms-2 text-muted">دلار</span>
                                    </h3>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning d-flex align-items-center border-0">
                                <i class="ri-information-line fs-5 me-2"></i>
                                <span>شارژ کیف پول دلاری تنها از طریق مراجعه حضوری به نمایندگی‌های ما امکان‌پذیر است.</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 d-flex align-items-center border-bottom">
                            <i class="ri-question-line me-2 text-primary"></i>
                            <h5 class="mb-0">راهنمای شارژ کیف پول دلاری</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <h6 class="d-flex align-items-center">
                                    <i class="ri-store-2-line me-2 text-primary"></i>
                                    مراحل شارژ کیف پول دلاری:
                                </h6>
                                <div class="list-group list-group-flush mt-3">
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">1</span>
                                        مبلغ مورد نظر را به دلار وارد کنید
                                    </div>
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">2</span>
                                        نمایندگی مورد نظر خود را انتخاب کنید
                                    </div>
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">3</span>
                                        درخواست را ثبت کنید و کد پیگیری دریافت کنید
                                    </div>
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">4</span>
                                        به نمایندگی مراجعه کنید و کد پیگیری را ارائه دهید
                                    </div>
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">5</span>
                                        مبلغ را به دلار پرداخت کنید
                                    </div>
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">6</span>
                                        پس از تایید نمایندگی، کیف پول دلاری شما شارژ خواهد شد
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info bg-info-subtle border-0 rounded-3 d-flex">
                                <i class="ri-information-line fs-5 me-2 text-info"></i>
                                <div>
                                    <strong>نکته مهم:</strong> تمامی تراکنش‌های دلاری در سیستم ما نیاز به تایید پرسنل نمایندگی دارد و پردازش آن‌ها بین 1 تا 24 ساعت زمان می‌برد.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .wallet-icon-large {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }
    
    .form-floating > .form-control {
        height: calc(3.5rem + 2px);
        line-height: 1.25;
    }
    
    .form-floating > textarea.form-control {
        height: 100px;
    }
    
    .form-floating > label {
        padding: 1rem 0.75rem;
    }
    
    .alert {
        border-radius: .5rem;
    }
    
    .rounded-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 25px;
        height: 25px;
    }
    
    @media (max-width: 767.98px) {
        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .justify-content-end {
            justify-content: center !important;
        }
    }
</style>
@endpush 