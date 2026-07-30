@extends('layouts.app')

@section('title', 'ورود به سیستم')

@section('content')
<div class="container py-5 my-md-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <!-- Login Card -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" data-aos="fade-up">
                <div class="card-header p-0 bg-primary">
                    <div class="text-center py-4">
                        <img src="{{ asset('logo/Logo-White.webp') }}" alt="{{ config('app.name') }}" class="img-fluid" style="height: 48px;">
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">ورود به سیستم</h2>
                        <p class="text-muted">به حساب کاربری خود دسترسی پیدا کنید</p>
                    </div>

                    <form method="POST" action="{{ route('auth.login.submit') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="phone" class="form-label fw-medium">شماره تلفن</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0 bg-light">
                                    <i class="ri-smartphone-line text-primary"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus placeholder="مثال: 93700000000">
                            </div>
                            @error('phone')
                                <div class="invalid-feedback d-block mt-1">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label fw-medium mb-0">رمز عبور</label>
                                <a href="{{ route('auth.forgot-password') }}" class="small text-primary text-decoration-none">فراموش کرده‌اید؟</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text border-end-0 bg-light">
                                    <i class="ri-lock-line text-primary"></i>
                                </span>
                                <input type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password" placeholder="رمز عبور خود را وارد کنید">
                                <button class="input-group-text border-start-0 bg-light password-toggle" type="button">
                                    <i class="ri-eye-line"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block mt-1">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="remember">مرا به خاطر بسپار</label>
                        </div>
                        
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-3 rounded-pill">
                                <i class="ri-login-circle-line me-2"></i>ورود به حساب کاربری
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center">
                        <p class="mb-0 text-muted">حساب کاربری ندارید؟ <a href="{{ route('auth.register') }}" class="text-primary fw-medium">ثبت نام کنید</a></p>
                    </div>
                </div>
                
                <div class="card-footer bg-light py-4 px-5">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="ri-shield-line text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="col ps-0">
                            <h6 class="mb-1">امنیت بالا</h6>
                            <p class="small text-muted mb-0">تمامی اطلاعات شما با امنیت کامل محافظت می‌شوند</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Support Card -->
            <div class="card border-0 shadow-sm rounded-4 mt-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="ri-customer-service-2-line" style="font-size: 24px;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold mb-1">نیاز به کمک دارید؟</h5>
                            <p class="text-muted mb-0">با پشتیبانی ما تماس بگیرید</p>
                            <a href="{{ route('contact') }}" class="stretched-link text-primary fw-medium">تماس با پشتیبانی</a>
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
        // Password visibility toggle
        const toggleButtons = document.querySelectorAll('.password-toggle');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.closest('.input-group').querySelector('input');
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('ri-eye-line');
                    icon.classList.add('ri-eye-off-line');
                } else {
                    input.type = 'password';
                    icon.classList.remove('ri-eye-off-line');
                    icon.classList.add('ri-eye-line');
                }
            });
        });
    });
</script>
@endpush

@push('styles')
<style>
    body {
        background-color: #f1f5f9;
    }
    
    .card-header {
        position: relative;
        overflow: hidden;
    }
    
    .card-header::after {
        content: "";
        position: absolute;
        bottom: -20px;
        right: -20px;
        width: 150px;
        height: 150px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .card-header::before {
        content: "";
        position: absolute;
        top: -80px;
        left: -60px;
        width: 200px;
        height: 200px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.15);
    }
    
    .password-toggle {
        cursor: pointer;
    }
    
    .btn-primary {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    
    .btn-primary:hover {
        background-color: #3730a3;
        border-color: #3730a3;
    }
    
    .text-primary {
        color: #4f46e5 !important;
    }
    
    a.text-primary:hover {
        color: #3730a3 !important;
    }
    
    @media (max-width: 767.98px) {
        .card-body {
            padding: 1.5rem !important;
        }
    }
</style>
@endpush 