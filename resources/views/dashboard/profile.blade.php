@extends('layouts.dashboard')

@section('title', 'پروفایل کاربری')
@section('page-title', 'پروفایل کاربری')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-4">
            <!-- User Profile Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-0">
                    <div class="p-4 text-center border-bottom position-relative overflow-hidden">
                        <div class="profile-cover-bg"></div>
                        <div class="position-relative">
                            <div class="avatar-container mb-3 mx-auto">
                                <img src="https://ui-avatars.com/api/?name={{ $user->name }}+{{ $user->lastname }}&background=0D8ABC&color=fff&size=128" class="rounded-circle img-fluid avatar-img" alt="{{ $user->name }}">
                                @if($user->is_verified)
                                    <span class="avatar-badge badge bg-success rounded-circle">
                                        <i class="ri-check-line"></i>
                                    </span>
                                @endif
                            </div>
                            <h5 class="mb-0">{{ $user->name }} {{ $user->lastname }}</h5>
                            <p class="text-muted mb-3">{{ $user->phone }}</p>
                            
                            <div class="d-flex justify-content-center gap-2">
                                @if($user->is_verified)
                                    <span class="badge bg-success-subtle text-success d-inline-flex align-items-center px-3 py-2">
                                        <i class="ri-check-line me-1"></i> تایید شده
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning d-inline-flex align-items-center px-3 py-2">
                                        <i class="ri-error-warning-line me-1"></i> تایید نشده
                                    </span>
                                @endif
                                
                                @if($user->is_admin)
                                    <span class="badge bg-primary-subtle text-primary d-inline-flex align-items-center px-3 py-2">
                                        <i class="ri-admin-line me-1"></i> مدیر
                                    </span>
                                @endif
                                
                                @if($user->is_support)
                                    <span class="badge bg-info-subtle text-info d-inline-flex align-items-center px-3 py-2">
                                        <i class="ri-customer-service-2-line me-1"></i> پشتیبان
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-primary-subtle text-primary me-3">
                                        <i class="ri-mail-line"></i>
                                    </div>
                                    <div>ایمیل</div>
                                </div>
                                <div class="text-muted">{{ $user->email ?? 'تنظیم نشده' }}</div>
                            </div>
                            <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-primary-subtle text-primary me-3">
                                        <i class="ri-phone-line"></i>
                                    </div>
                                    <div>تلفن</div>
                                </div>
                                <div class="text-muted">{{ $user->phone }}</div>
                            </div>
                            <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-primary-subtle text-primary me-3">
                                        <i class="ri-telegram-line"></i>
                                    </div>
                                    <div>تلگرام</div>
                                </div>
                                <div class="text-muted">{{ $user->telegram_number ?? 'تنظیم نشده' }}</div>
                            </div>
                            <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-primary-subtle text-primary me-3">
                                        <i class="ri-map-pin-line"></i>
                                    </div>
                                    <div>شهر / استان</div>
                                </div>
                                <div class="text-muted">{{ $user->city }} / {{ $user->province }}</div>
                            </div>
                            <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-primary-subtle text-primary me-3">
                                        <i class="ri-time-line"></i>
                                    </div>
                                    <div>تاریخ عضویت</div>
                                </div>
                                <div class="text-muted">{{ $user->created_at->format('Y/m/d') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Profile Tabs -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="nav-container p-3 border-bottom">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="profileTab" role="tablist">
                            <li class="nav-item mb-2 mb-md-0" role="presentation">
                                <button class="nav-link active px-4 py-2 rounded-pill" id="edit-profile-tab" data-bs-toggle="tab" data-bs-target="#edit-profile" type="button" role="tab" aria-controls="edit-profile" aria-selected="true">
                                    <i class="ri-user-settings-line me-1"></i>ویرایش پروفایل
                                </button>
                            </li>
                            <li class="nav-item mb-2 mb-md-0" role="presentation">
                                <button class="nav-link px-4 py-2 rounded-pill" id="change-password-tab" data-bs-toggle="tab" data-bs-target="#change-password" type="button" role="tab" aria-controls="change-password" aria-selected="false">
                                    <i class="ri-lock-password-line me-1"></i>تغییر رمز عبور
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-4 py-2 rounded-pill" id="account-security-tab" data-bs-toggle="tab" data-bs-target="#account-security" type="button" role="tab" aria-controls="account-security" aria-selected="false">
                                    <i class="ri-shield-keyhole-line me-1"></i>امنیت حساب
                                </button>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="tab-content p-4" id="profileTabContent">
                        <!-- Edit Profile Tab -->
                        <div class="tab-pane fade show active" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
                            <form action="{{ route('dashboard.profile.update') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="نام" required>
                                            <label for="name">نام</label>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}" placeholder="نام خانوادگی" required>
                                            <label for="lastname">نام خانوادگی</label>
                                            @error('lastname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-floating mb-4">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="ایمیل">
                                    <label for="email">ایمیل (اختیاری)</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-floating mb-4">
                                    <input type="text" class="form-control @error('telegram_number') is-invalid @enderror" id="telegram_number" name="telegram_number" value="{{ old('telegram_number', $user->telegram_number) }}" placeholder="شماره تلگرام">
                                    <label for="telegram_number">شماره تلگرام (اختیاری)</label>
                                    <div class="form-text">مثال: @username</div>
                                    @error('telegram_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $user->city) }}" placeholder="شهر" required>
                                            <label for="city">شهر</label>
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control @error('province') is-invalid @enderror" id="province" name="province" value="{{ old('province', $user->province) }}" placeholder="استان" required>
                                            <label for="province">استان</label>
                                            @error('province')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4 py-2">
                                        <i class="ri-save-line me-1"></i>
                                        ذخیره تغییرات
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Change Password Tab -->
                        <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                            <form action="{{ route('dashboard.profile.update-password') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                @method('PUT')
                                
                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="رمز عبور فعلی" required>
                                    <label for="current_password">رمز عبور فعلی</label>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="رمز عبور جدید" required>
                                    <label for="password">رمز عبور جدید</label>
                                    <div class="form-text">حداقل 8 کاراکتر</div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="تکرار رمز عبور جدید" required>
                                    <label for="password_confirmation">تکرار رمز عبور جدید</label>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4 py-2">
                                        <i class="ri-lock-password-line me-1"></i>
                                        تغییر رمز عبور
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Account Security Tab -->
                        <div class="tab-pane fade" id="account-security" role="tabpanel" aria-labelledby="account-security-tab">
                            <div class="security-option p-4 mb-4 bg-light rounded-4">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                    <div class="mb-3 mb-md-0">
                                        <h6 class="mb-1 d-flex align-items-center">
                                            <i class="ri-smartphone-line me-2 text-primary"></i>
                                            تأیید شماره تلفن
                                        </h6>
                                        <p class="text-muted small mb-0">تأیید شماره تلفن برای امنیت حساب شما ضروری است</p>
                                    </div>
                                    <div>
                                        @if($user->is_verified)
                                            <span class="badge bg-success rounded-pill px-3 py-2">تأیید شده</span>
                                        @else
                                            <a href="{{ route('auth.verify') }}" class="btn btn-primary px-4 py-2">
                                                <i class="ri-check-line me-1"></i>
                                                تأیید شماره تلفن
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="security-option p-4 mb-4 bg-light rounded-4">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                    <div class="mb-3 mb-md-0">
                                        <h6 class="mb-1 d-flex align-items-center">
                                            <i class="ri-shield-keyhole-line me-2 text-primary"></i>
                                            فعال‌سازی تأیید دو مرحله‌ای
                                        </h6>
                                        <p class="text-muted small mb-0">با فعال‌سازی این گزینه، هنگام ورود به سیستم یک کد تأیید به تلفن همراه شما ارسال می‌شود</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="twoFactorSwitch">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="security-option p-4 bg-danger-subtle rounded-4">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                    <div class="mb-3 mb-md-0">
                                        <h6 class="mb-1 d-flex align-items-center text-danger">
                                            <i class="ri-delete-bin-line me-2"></i>
                                            حذف حساب کاربری
                                        </h6>
                                        <p class="text-muted small mb-0">حذف حساب کاربری شما تمام اطلاعات و تراکنش‌های شما را از بین خواهد برد</p>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-danger px-3 py-2" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                            <i class="ri-delete-bin-line me-1"></i>
                                            حذف حساب
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteAccountModalLabel">حذف حساب کاربری</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger bg-danger-subtle border-0 rounded-3">
                    <div class="d-flex">
                        <div class="me-3 text-danger">
                            <i class="ri-error-warning-line fs-3"></i>
                        </div>
                        <div>
                            <strong>هشدار:</strong> این عملیات غیرقابل بازگشت است!
                        </div>
                    </div>
                </div>
                <p>آیا از حذف حساب کاربری خود اطمینان دارید؟ با این کار تمامی اطلاعات، کیف پول‌ها و سوابق تراکنش‌های شما حذف خواهد شد.</p>
                <form id="deleteAccountForm">
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="deleteConfirmPassword" placeholder="رمز عبور" required>
                        <label for="deleteConfirmPassword">رمز عبور خود را برای تأیید وارد کنید</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="deleteConfirmCheck" required>
                        <label class="form-check-label" for="deleteConfirmCheck">
                            من تأیید می‌کنم که می‌خواهم حساب کاربری خود را حذف کنم
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                <button type="submit" form="deleteAccountForm" class="btn btn-danger">
                    <i class="ri-delete-bin-line me-1"></i>
                    حذف حساب
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-cover-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 60px;
        background-color: #f0f4f8;
        z-index: 0;
    }
    
    .avatar-container {
        position: relative;
        width: 120px;
        height: 120px;
    }
    
    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .avatar-badge {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
    }
    
    .icon-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .form-floating > .form-control {
        height: calc(3.5rem + 2px);
        line-height: 1.25;
    }
    
    .form-floating > label {
        padding: 1rem 0.75rem;
    }
    
    .security-option {
        transition: all 0.3s ease;
    }
    
    .nav-pills .nav-link.active {
        background-color: #f8f9fa;
        color: #0d6efd;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .nav-pills .nav-link {
        color: #6c757d;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    @media (max-width: 767.98px) {
        .nav-container {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        
        .modal-footer .btn {
            flex: 1;
        }
    }
</style>
@endpush 