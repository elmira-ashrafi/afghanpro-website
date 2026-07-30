@extends('layouts.app')

@section('title', 'تنظیم رمز عبور جدید')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">تنظیم رمز عبور جدید</h2>
                        <p class="text-muted">کد تأیید و رمز عبور جدید خود را وارد کنید</p>
                    </div>
                    
                    <form method="POST" action="{{ route('auth.reset-password.submit') }}">
                        @csrf

                        <input type="hidden" name="phone" value="{{ $phone }}">

                        <div class="mb-3">
                            <label for="verification_code" class="form-label">کد تأیید</label>
                            <input type="text" class="form-control @error('verification_code') is-invalid @enderror" id="verification_code" name="verification_code" required autofocus>
                            <div class="form-text">کد 6 رقمی ارسال شده به تلفن همراه خود را وارد کنید</div>
                            @error('verification_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">رمز عبور جدید</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            <div class="form-text">حداقل 8 کاراکتر</div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">تکرار رمز عبور جدید</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">تنظیم رمز عبور جدید</button>
                        </div>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <p class="mb-0">به یاد آوردید؟ <a href="{{ route('auth.login') }}">بازگشت به صفحه ورود</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 