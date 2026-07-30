@extends('layouts.admin')

@section('title', 'افزودن پشتیبان جدید')
@section('page-title', 'افزودن پشتیبان جدید')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">افزودن پشتیبان جدید</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('dashboard.admin.supporters.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">نام</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="lastname" class="form-label">نام خانوادگی</label>
                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                    @error('lastname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email" class="form-label">ایمیل</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="phone" class="form-label">شماره تماس</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="password" class="form-label">رمز عبور</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">تایید رمز عبور</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="telegram_number" class="form-label">شماره تلگرام (اختیاری)</label>
                    <input type="text" class="form-control @error('telegram_number') is-invalid @enderror" id="telegram_number" name="telegram_number" value="{{ old('telegram_number') }}">
                    @error('telegram_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" {{ old('is_admin') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_admin">
                            دسترسی مدیر سیستم
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info" role="alert">
                <i class="ri-information-line me-1"></i>
                با افزودن این کاربر، دسترسی پشتیبانی به صورت خودکار برای آن فعال می‌شود.
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('dashboard.admin.supporters.index') }}" class="btn btn-secondary">انصراف</a>
                <button type="submit" class="btn btn-primary">ذخیره</button>
            </div>
        </form>
    </div>
</div>
@endsection 