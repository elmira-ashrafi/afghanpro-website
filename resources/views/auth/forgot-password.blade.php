@extends('layouts.app')

@section('title', 'بازیابی رمز عبور')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">بازیابی رمز عبور</h2>
                        <p class="text-muted">شماره تلفن خود را وارد کنید تا کد تأیید برای شما ارسال شود</p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('auth.forgot-password.submit') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="phone" class="form-label">شماره تلفن</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>
                            <div class="form-text">شماره تلفنی که با آن ثبت نام کرده‌اید را وارد کنید</div>
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">ارسال کد تأیید</button>
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