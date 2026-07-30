@extends('layouts.app')

@section('title', 'ثبت نام')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card border-0 shadow-lg rounded-4" data-aos="fade-up">
                <div class="card-body p-lg-5 p-4">
                    <div class="text-center mb-5">
                        <div class="mb-4">
                            <img src="{{ asset('logo/Logo-color.webp') }}" alt="{{ config('app.name') }}" style="height: 50px;">
                        </div>
                        <h2 class="fw-bold">ایجاد حساب کاربری جدید</h2>
                        <p class="text-secondary">عضو افغان پرو شوید و از خدمات متنوع ما بهره‌مند شوید</p>
                    </div>
                    
                    <form method="POST" action="{{ route('auth.register.submit') }}">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-medium">نام</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-user-line text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="نام خود را وارد کنید">
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block mt-1">
                                        <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="lastname" class="form-label fw-medium">نام خانوادگی</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-user-line text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname" placeholder="نام خانوادگی خود را وارد کنید">
                                </div>
                                @error('lastname')
                                    <div class="invalid-feedback d-block mt-1">
                                        <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12">
                                <label for="phone" class="form-label fw-medium">شماره تلفن</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-smartphone-line text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required autocomplete="phone" placeholder="مثال: 93700000000">
                                </div>
                                <div class="form-text"><i class="ri-information-line me-1"></i>از این شماره برای ورود به سیستم استفاده خواهید کرد.</div>
                                @error('phone')
                                    <div class="invalid-feedback d-block mt-1">
                                        <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12">
                                <label for="telegram_number" class="form-label fw-medium">شماره تلگرام (اختیاری)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-telegram-line text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('telegram_number') is-invalid @enderror" id="telegram_number" name="telegram_number" value="{{ old('telegram_number') }}" autocomplete="telegram_number" placeholder="مثال: @username">
                                </div>
                                @error('telegram_number')
                                    <div class="invalid-feedback d-block mt-1">
                                        <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="city" class="form-label fw-medium">شهر</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-building-line text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required autocomplete="city" placeholder="شهر خود را وارد کنید">
                                </div>
                                @error('city')
                                    <div class="invalid-feedback d-block mt-1">
                                        <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="province" class="form-label fw-medium">استان</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-map-pin-line text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('province') is-invalid @enderror" id="province" name="province" value="{{ old('province') }}" required autocomplete="province" placeholder="استان خود را وارد کنید">
                                </div>
                                @error('province')
                                    <div class="invalid-feedback d-block mt-1">
                                        <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12">
                                <label for="email" class="form-label fw-medium">ایمیل (اختیاری)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-mail-line text-primary"></i>
                                    </span>
                                    <input type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="ایمیل خود را وارد کنید">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block mt-1">
                                        <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-medium">رمز عبور</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-lock-line text-primary"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password" placeholder="رمز عبور خود را وارد کنید">
                                    <button class="input-group-text bg-light border-start-0 password-toggle" type="button">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                                <div class="form-text"><i class="ri-shield-check-line me-1"></i>حداقل 8 کاراکتر باشد.</div>
                                @error('password')
                                    <div class="invalid-feedback d-block mt-1">
                                        <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-medium">تکرار رمز عبور</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-lock-line text-primary"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0 ps-0" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="رمز عبور را مجدداً وارد کنید">
                                    <button class="input-group-text bg-light border-start-0 password-toggle" type="button">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror" id="terms" name="terms" required>
                                    <label class="form-check-label text-secondary" for="terms">
                                        <span>با <a href="#" class="text-primary">قوانین و شرایط استفاده</a> از خدمات افغان پرو موافق هستم</span>
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback d-block mt-1">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary py-3 rounded-pill">
                                        <i class="ri-user-add-line me-2"></i>ثبت نام و ایجاد حساب
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <p class="mb-0 text-secondary">قبلاً ثبت نام کرده‌اید؟ <a href="{{ route('auth.login') }}" class="text-primary fw-medium">ورود به حساب</a></p>
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