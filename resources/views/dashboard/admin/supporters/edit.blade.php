@extends('layouts.admin')

@section('title', 'ویرایش پشتیبان')
@section('page-title', 'ویرایش پشتیبان')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">ویرایش پشتیبان: {{ $supporter->name }} {{ $supporter->lastname }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('dashboard.admin.supporters.update', $supporter->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">نام</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $supporter->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="lastname" class="form-label">نام خانوادگی</label>
                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname', $supporter->lastname) }}" required>
                    @error('lastname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email" class="form-label">ایمیل</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $supporter->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="phone" class="form-label">شماره تماس</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $supporter->phone) }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="password" class="form-label">رمز عبور (اختیاری)</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                    <div class="form-text">در صورت تغییر رمز عبور، فیلد را پر کنید.</div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">تایید رمز عبور</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="telegram_number" class="form-label">شماره تلگرام (اختیاری)</label>
                    <input type="text" class="form-control @error('telegram_number') is-invalid @enderror" id="telegram_number" name="telegram_number" value="{{ old('telegram_number', $supporter->telegram_number) }}">
                    @error('telegram_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" {{ old('is_admin', $supporter->is_admin) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_admin">
                            دسترسی مدیر سیستم
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('dashboard.admin.supporters.index') }}" class="btn btn-secondary">انصراف</a>
                <button type="submit" class="btn btn-primary">بروزرسانی</button>
            </div>
        </form>
    </div>
</div>
@endsection 