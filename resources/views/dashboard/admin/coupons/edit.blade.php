@extends('layouts.admin')

@section('title', 'ویرایش کوپن تخفیف')
@section('page-title', 'ویرایش کوپن تخفیف')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">ویرایش کوپن تخفیف: {{ $coupon->code }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('dashboard.admin.coupons.update', $coupon->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="code" class="form-label">کد تخفیف</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $coupon->code) }}" required>
                    <small class="form-text">کد تخفیف باید حداقل 3 کاراکتر و بدون فاصله باشد.</small>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="discount_type" class="form-label">نوع تخفیف</label>
                    <select class="form-select @error('discount_type') is-invalid @enderror" id="discount_type" name="discount_type" required>
                        <option value="percentage" {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>درصدی</option>
                        <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>مقدار ثابت</option>
                    </select>
                    @error('discount_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="discount_value" class="form-label">مقدار تخفیف</label>
                    <div class="input-group">
                        <input type="number" step="0.01" class="form-control @error('discount_value') is-invalid @enderror" id="discount_value" name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" required>
                        <span class="input-group-text percentage-text {{ $coupon->discount_type != 'percentage' ? 'd-none' : '' }}">درصد</span>
                        <span class="input-group-text fixed-text {{ $coupon->discount_type != 'fixed' ? 'd-none' : '' }}">افغانی</span>
                        @error('discount_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label for="min_order_amount" class="form-label">حداقل مبلغ سفارش (اختیاری)</label>
                    <div class="input-group">
                        <input type="number" class="form-control @error('min_order_amount') is-invalid @enderror" id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                        <span class="input-group-text">افغانی</span>
                        @error('min_order_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="max_discount_amount" class="form-label">حداکثر مبلغ تخفیف (اختیاری)</label>
                    <div class="input-group">
                        <input type="number" class="form-control @error('max_discount_amount') is-invalid @enderror" id="max_discount_amount" name="max_discount_amount" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}">
                        <span class="input-group-text">افغانی</span>
                        @error('max_discount_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text">فقط برای تخفیف‌های درصدی استفاده می‌شود. در صورت خالی بودن، محدودیتی برای مبلغ تخفیف وجود ندارد.</small>
                </div>
                
                <div class="col-md-6">
                    <label for="usage_limit" class="form-label">حداکثر تعداد استفاده (اختیاری)</label>
                    <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}">
                    <small class="form-text">در صورت خالی بودن، محدودیتی برای استفاده وجود ندارد.</small>
                    @error('usage_limit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="max_uses_per_user" class="form-label">حداکثر استفاده برای هر کاربر (اختیاری)</label>
                    <input type="number" class="form-control @error('max_uses_per_user') is-invalid @enderror" id="max_uses_per_user" name="max_uses_per_user" value="{{ old('max_uses_per_user', $coupon->max_uses_per_user) }}">
                    <small class="form-text">در صورت خالی بودن، هر کاربر میتواند به تعداد نامحدود از این کد استفاده کند.</small>
                    @error('max_uses_per_user')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="starts_at" class="form-label">تاریخ شروع (اختیاری)</label>
                    <input type="date" class="form-control @error('starts_at') is-invalid @enderror" id="starts_at" name="starts_at" value="{{ old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d') : '') }}">
                    <small class="form-text">در صورت خالی بودن، کوپن بلافاصله قابل استفاده است.</small>
                    @error('starts_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="expires_at" class="form-label">تاریخ انقضا (اختیاری)</label>
                    <input type="date" class="form-control @error('expires_at') is-invalid @enderror" id="expires_at" name="expires_at" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}">
                    <small class="form-text">در صورت خالی بودن، کوپن بدون تاریخ انقضا خواهد بود.</small>
                    @error('expires_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="text-muted">تعداد استفاده فعلی:</span>
                        <span>{{ $coupon->usage_count ?? 0 }}</span>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            فعال
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('dashboard.admin.coupons.index') }}" class="btn btn-secondary">انصراف</a>
                <button type="submit" class="btn btn-primary">بروزرسانی</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('discount_type');
        const percentageText = document.querySelector('.percentage-text');
        const fixedText = document.querySelector('.fixed-text');
        
        function updateValueType() {
            if (typeSelect.value === 'percentage') {
                percentageText.classList.remove('d-none');
                fixedText.classList.add('d-none');
            } else {
                percentageText.classList.add('d-none');
                fixedText.classList.remove('d-none');
            }
        }
        
        typeSelect.addEventListener('change', updateValueType);
    });
</script>
@endpush
@endsection 