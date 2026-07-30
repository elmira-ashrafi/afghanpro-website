@extends('layouts.dashboard')

@section('title', 'برداشت نقدی از نمایندگی')
@section('page-title', 'برداشت نقدی از نمایندگی')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-3 p-md-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="ri-money-dollar-box-line me-2 text-primary"></i>برداشت نقدی از نمایندگی</h5>
                <a href="{{ route('dashboard.wallets') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                    <i class="ri-arrow-go-back-line me-1"></i>بازگشت
                </a>
            </div>
            <div class="card-body p-3 p-md-4">
                <form action="{{ route('dashboard.wallets.withdraw.agency.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="alert alert-info d-flex p-3 mb-4">
                        <div class="me-3 fs-3 text-info">
                            <i class="ri-information-line"></i>
                        </div>
                        <div>
                            <h6 class="alert-heading mb-1">راهنمای برداشت نقدی</h6>
                            <p class="mb-0 small">با تکمیل این فرم، مبلغ مورد نظر از کیف پول شما کسر و به یکی از نمایندگی‌های ما منتقل می‌شود. سپس می‌توانید با مراجعه حضوری و ارائه کد پیگیری، وجه نقد را دریافت کنید.</p>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <!-- اطلاعات شخصی -->
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">اطلاعات دریافت‌کننده</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="full_name" class="form-label">نام و نام خانوادگی</label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name', $user->name . ' ' . $user->lastname) }}">
                            @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="phone" class="form-label">شماره تماس</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" dir="ltr">
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label for="city" class="form-label">شهر</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $user->city) }}">
                            @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- اطلاعات مالی -->
                        <div class="col-12 mt-4">
                            <h6 class="border-bottom pb-2 mb-3">اطلاعات مالی</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="wallet_type" class="form-label">انتخاب کیف پول</label>
                            <select class="form-select @error('wallet_type') is-invalid @enderror" id="wallet_type" name="wallet_type">
                                <option value="afghan_wallet" {{ old('wallet_type') == 'afghan_wallet' ? 'selected' : '' }}>
                                    کیف پول افغانی ({{ number_format($afghanWallet->balance) }} افغانی)
                                </option>
                                <option value="dollar_wallet" {{ old('wallet_type') == 'dollar_wallet' ? 'selected' : '' }}>
                                    کیف پول دلاری ({{ number_format($dollarWallet->balance, 2) }} دلار)
                                </option>
                            </select>
                            @error('wallet_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="currency_type" class="form-label">نوع ارز</label>
                            <select class="form-select @error('currency_type') is-invalid @enderror" id="currency_type" name="currency_type">
                                <option value="AFN" {{ old('currency_type') == 'AFN' ? 'selected' : '' }}>افغانی (AFN)</option>
                                <option value="USD" {{ old('currency_type') == 'USD' ? 'selected' : '' }}>دلار (USD)</option>
                            </select>
                            @error('currency_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label for="amount" class="form-label">مبلغ برداشت</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" dir="ltr">
                                <span class="input-group-text" id="currency-label">افغانی</span>
                                @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text" id="balance-info">موجودی قابل برداشت: {{ number_format($afghanWallet->balance) }} افغانی</div>
                        </div>
                        
                        <!-- انتخاب نمایندگی -->
                        <div class="col-12 mt-4">
                            <h6 class="border-bottom pb-2 mb-3">انتخاب نمایندگی</h6>
                        </div>
                        
                        <div class="col-12">
                            <label for="agency_id" class="form-label">نمایندگی مورد نظر</label>
                            <select class="form-select @error('agency_id') is-invalid @enderror" id="agency_id" name="agency_id">
                                <option value="" selected disabled>لطفا یک نمایندگی انتخاب کنید</option>
                                @foreach($agencies as $agency)
                                <option value="{{ $agency->id }}" {{ old('agency_id') == $agency->id ? 'selected' : '' }}>
                                    {{ $agency->name }} - {{ $agency->city }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text">لطفا نمایندگی که می‌خواهید پول را از آن دریافت کنید انتخاب کنید.</div>
                            @error('agency_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label for="description" class="form-label">توضیحات (اختیاری)</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning mt-4 p-3">
                        <div class="d-flex">
                            <div class="me-3 fs-3 text-warning">
                                <i class="ri-alert-line"></i>
                            </div>
                            <p class="mb-0 small">با ثبت این درخواست، مبلغ مورد نظر به صورت موقت از کیف پول شما کسر می‌شود. پس از تایید، می‌توانید با مراجعه به نمایندگی انتخاب شده و ارائه کد پیگیری و مدارک شناسایی، مبلغ را به صورت نقدی دریافت کنید.</p>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary py-2">
                            <i class="ri-check-line me-1"></i>ثبت درخواست برداشت
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currencyTypeSelect = document.getElementById('currency_type');
        const walletTypeSelect = document.getElementById('wallet_type');
        const currencyLabel = document.getElementById('currency-label');
        const balanceInfo = document.getElementById('balance-info');
        const afghanBalance = "{{ number_format($afghanWallet->balance) }} افغانی";
        const dollarBalance = "{{ number_format($dollarWallet->balance, 2) }} دلار";
        
        function updateCurrencyDisplay() {
            if (currencyTypeSelect.value === 'AFN') {
                currencyLabel.textContent = 'افغانی';
                balanceInfo.textContent = `موجودی قابل برداشت: ${afghanBalance}`;
            } else {
                currencyLabel.textContent = 'دلار';
                balanceInfo.textContent = `موجودی قابل برداشت: ${dollarBalance}`;
            }
        }
        
        // Update currency label when currency type changes
        currencyTypeSelect.addEventListener('change', function() {
            if (this.value === 'AFN') {
                walletTypeSelect.value = 'afghan_wallet';
            } else {
                walletTypeSelect.value = 'dollar_wallet';
            }
            updateCurrencyDisplay();
        });
        
        // Update currency type when wallet type changes
        walletTypeSelect.addEventListener('change', function() {
            if (this.value === 'afghan_wallet') {
                currencyTypeSelect.value = 'AFN';
            } else {
                currencyTypeSelect.value = 'USD';
            }
            updateCurrencyDisplay();
        });
        
        // Initial setup
        updateCurrencyDisplay();
    });
</script>
@endpush 