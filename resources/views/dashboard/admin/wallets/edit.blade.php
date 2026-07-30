@extends('layouts.admin')

@section('title', 'ویرایش موجودی کیف پول')
@section('page-title', 'ویرایش موجودی کیف پول')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">ویرایش موجودی کیف پول {{ $type == 'dollar' ? 'دلاری' : 'افغانی' }}</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">کاربر:</span>
                    <span>{{ $wallet->user->name }} {{ $wallet->user->lastname }}</span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">نوع کیف پول:</span>
                    <span>{{ $type == 'dollar' ? 'دلاری' : 'افغانی' }}</span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">موجودی فعلی:</span>
                    <span>{{ $type == 'dollar' ? number_format($wallet->balance, 2) . ' دلار' : number_format($wallet->balance) . ' افغانی' }}</span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">آخرین بروزرسانی:</span>
                    <span>{{ $wallet->updated_at->format('Y/m/d H:i') }}</span>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="alert alert-warning">
                    <i class="ri-alert-line me-1"></i>
                    <strong>هشدار:</strong> تغییر مستقیم موجودی کیف پول فقط در موارد ضروری و برای اصلاح خطاهای سیستمی استفاده شود.
                </div>
            </div>
        </div>
        
        <hr class="my-4">
        
        <form action="{{ route('dashboard.admin.wallets.update', ['id' => $wallet->id, 'type' => $type]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="current_balance" class="form-label">موجودی فعلی (فقط نمایشی)</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="current_balance" value="{{ $type == 'dollar' ? number_format($wallet->balance, 2) : number_format($wallet->balance) }}" disabled>
                            <span class="input-group-text">{{ $type == 'dollar' ? 'دلار' : 'افغانی' }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="amount" class="form-label">مقدار تغییر</label>
                        <div class="input-group">
                            <input type="number" step="{{ $type == 'dollar' ? '0.01' : '1' }}" min="0" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                            <span class="input-group-text">{{ $type == 'dollar' ? 'دلار' : 'افغانی' }}</span>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">نوع عملیات</label>
                        <div class="d-flex">
                            <div class="form-check me-4">
                                <input class="form-check-input" type="radio" name="operation" id="operation_add" value="add" checked>
                                <label class="form-check-label" for="operation_add">
                                    افزایش موجودی
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="operation" id="operation_subtract" value="subtract">
                                <label class="form-check-label" for="operation_subtract">
                                    کاهش موجودی
                                </label>
                            </div>
                        </div>
                        @error('operation')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">توضیحات تغییر موجودی</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        <div class="form-text">این توضیح در تاریخچه تراکنش‌های کاربر ذخیره خواهد شد.</div>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('dashboard.admin.wallets.index') }}" class="btn btn-secondary">انصراف</a>
                <button type="submit" class="btn btn-primary">بروزرسانی موجودی</button>
            </div>
        </form>
    </div>
</div>
@endsection 