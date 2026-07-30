@extends('layouts.dashboard')

@section('title', 'شارژ کیف پول افغانی')
@section('page-title', 'شارژ کیف پول افغانی')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="ri-bank-card-line me-2 text-primary"></i>
                        فرم شارژ کیف پول افغانی
                    </h5>
                </div>
                <div class="card-body p-lg-4">
                    <form action="{{ route('dashboard.wallets.deposit.afghani.store') }}" method="POST" id="depositForm" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" min="1" step="1" value="{{ old('amount', 1) }}" required>
                                    <label for="amount">مبلغ (افغانی)</label>
                                    <div class="form-text mt-1">
                                        <i class="ri-information-line"></i>
                                        حداقل مبلغ شارژ: 1 افغانی
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                        <option value="">انتخاب کنید</option>
                                        <option value="hesab_pay" {{ old('payment_method') == 'hesab_pay' ? 'selected' : '' }}>حساب پی (پرداخت آنلاین)</option>
                                        <option value="agency_visit" {{ old('payment_method') == 'agency_visit' ? 'selected' : '' }}>مراجعه به نمایندگی</option>
                                    </select>
                                    <label for="payment_method">روش پرداخت</label>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div id="hisabPaySection" class="payment-section mb-4 bg-light p-3 rounded-3" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="7XXXXXXXX">
                                        <label for="phone_number">شماره موبایل متصل به حساب پی</label>
                                        @error('phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text mt-1 d-flex align-items-center">
                                        <span class="badge bg-primary fs-6 me-1">+93</span>
                                        بدون صفر اول وارد کنید
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info mt-3 d-flex border-0">
                                <div class="me-3 text-info">
                                    <i class="ri-information-line fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="alert-heading mb-1">پرداخت با حساب پی</h6>
                                    <p class="mb-0">پس از تکمیل فرم، به درگاه پرداخت حساب پی منتقل خواهید شد. پس از پرداخت موفق، کیف پول شما به صورت خودکار شارژ می‌شود.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div id="agencyVisitSection" class="payment-section mb-4 bg-light p-3 rounded-3" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select @error('agency_id') is-invalid @enderror" id="agency_id" name="agency_id">
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
                            
                            <div class="alert alert-info mt-3 d-flex border-0">
                                <div class="me-3 text-info">
                                    <i class="ri-information-line fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="alert-heading mb-1">مراجعه به نمایندگی</h6>
                                    <p class="mb-0">پس از ثبت درخواست، کد پیگیری دریافت خواهید کرد. با مراجعه به نمایندگی و ارائه کد پیگیری و پرداخت وجه، کیف پول شما شارژ خواهد شد.</p>
                                </div>
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
                                <div class="wallet-icon-large me-3 bg-success-subtle text-success rounded-circle">
                                    <i class="ri-money-afghani-circle-line"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">کیف پول افغانی</h5>
                                    <h3 class="mb-0 d-flex align-items-center">
                                        {{ number_format($afghanWallet->balance) }}
                                        <span class="fs-6 ms-2 text-muted">افغانی</span>
                                    </h3>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning d-flex align-items-center border-0">
                                <i class="ri-information-line fs-5 me-2"></i>
                                <span>شارژ کیف پول افغانی از طریق حساب پی یا مراجعه به نمایندگی‌های ما امکان‌پذیر است.</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 d-flex align-items-center border-bottom">
                            <i class="ri-question-line me-2 text-primary"></i>
                            <h5 class="mb-0">راهنمای شارژ کیف پول</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <h6 class="d-flex align-items-center"><i class="ri-bank-card-line me-2 text-primary"></i>شارژ از طریق حساب پی:</h6>
                                <div class="list-group list-group-flush mt-3">
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">1</span>
                                        مبلغ مورد نظر را وارد کنید
                                    </div>
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">2</span>
                                        روش پرداخت "حساب پی" را انتخاب کنید
                                    </div>
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">3</span>
                                        شماره موبایل متصل به حساب پی خود را وارد کنید
                                    </div>
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">4</span>
                                        به درگاه پرداخت حساب پی منتقل شوید و پرداخت را انجام دهید
                                    </div>
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">5</span>
                                        پس از پرداخت موفق، کیف پول شما بلافاصله شارژ می‌شود
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h6 class="d-flex align-items-center"><i class="ri-store-2-line me-2 text-primary"></i>شارژ از طریق مراجعه به نمایندگی:</h6>
                                <div class="list-group list-group-flush mt-3">
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">1</span>
                                        مبلغ مورد نظر را وارد کنید
                                    </div>
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">2</span>
                                        روش پرداخت "مراجعه به نمایندگی" را انتخاب کنید
                                    </div>
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">3</span>
                                        نمایندگی مورد نظر خود را انتخاب کنید
                                    </div>
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">4</span>
                                        درخواست را ثبت کنید و کد پیگیری دریافت نمایید
                                    </div>
                                    <div class="list-group-item d-flex align-items-center border-0 ps-0 pe-3 py-2">
                                        <span class="badge bg-primary rounded-circle me-2">5</span>
                                        با مراجعه به نمایندگی و ارائه کد پیگیری و پرداخت وجه، کیف پول شما شارژ خواهد شد
                                    </div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethodSelect = document.getElementById('payment_method');
        const hisabPaySection = document.getElementById('hisabPaySection');
        const agencyVisitSection = document.getElementById('agencyVisitSection');
        const phoneNumberInput = document.getElementById('phone_number');
        const agencyIdSelect = document.getElementById('agency_id');
        
        function updateSections() {
            const selectedMethod = paymentMethodSelect.value;
            
            // Hide all sections first
            document.querySelectorAll('.payment-section').forEach(section => {
                section.style.display = 'none';
            });
            
            // Make all fields optional first
            phoneNumberInput.required = false;
            agencyIdSelect.required = false;
            
            // Show relevant section based on selection
            if (selectedMethod === 'hesab_pay') {
                hisabPaySection.style.display = 'block';
                phoneNumberInput.required = true;
            } else if (selectedMethod === 'agency_visit') {
                agencyVisitSection.style.display = 'block';
                agencyIdSelect.required = true;
            }
        }
        
        // Initialize sections
        updateSections();
        
        // Add event listener for payment method changes
        paymentMethodSelect.addEventListener('change', updateSections);
    });
</script>
@endpush

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
    
    .payment-section {
        transition: all 0.3s ease;
    }
    
    .form-floating > .form-control {
        height: calc(3.5rem + 2px);
        line-height: 1.25;
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