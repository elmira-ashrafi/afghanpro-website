@extends('layouts.app')

@section('title', 'تأیید شماره تلفن')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">تأیید شماره تلفن</h2>
                        <p class="text-muted">کد تأیید ارسال شده به شماره {{ auth()->user()->phone }} را وارد کنید</p>
                    </div>

                    @if (session('resent'))
                        <div class="alert alert-success mb-4" role="alert">
                            کد تأیید جدید با موفقیت ارسال شد
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('auth.verify.submit') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="verification_code" class="form-label">کد تأیید</label>
                            <input type="text" class="form-control @error('verification_code') is-invalid @enderror" id="verification_code" name="verification_code" required autofocus>
                            <div class="form-text">کد 6 رقمی ارسال شده به تلفن همراه خود را وارد کنید</div>
                            @error('verification_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">تأیید شماره تلفن</button>
                        </div>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <form action="{{ route('auth.verify.resend') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">ارسال مجدد کد تأیید</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 