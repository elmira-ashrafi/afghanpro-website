@extends('layouts.app')

@section('title', 'تماس با ما - افغان پرو')

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden">
    <!-- Background Shape -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, #2c3e50 0%, #1a2530 100%); clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);"></div>
    
    <div class="container position-relative py-5 text-white">
        <div class="row py-5 align-items-center">
            <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                <span class="badge bg-accent text-white fw-normal mb-3 px-3 py-2">پشتیبانی</span>
                <h1 class="display-4 fw-bold mb-4">تماس با ما</h1>
                <p class="lead opacity-80 mb-0">جهت ارتباط با ما می‌توانید از روش‌های زیر استفاده کنید</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Info & Form -->
<section class="py-5 position-relative">
    <div class="position-absolute start-0 top-0 w-100 h-100 bg-white" style="background-image: radial-gradient(rgba(44, 62, 80, 0.03) 2px, transparent 2px); background-size: 25px 25px;"></div>
    
    <div class="container py-5 position-relative">
        <div class="row g-4">
            <!-- Contact Form -->
            <div class="col-lg-8 order-lg-1 order-2" data-aos="fade-up">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-lg-5 p-4">
                        <div class="mb-4 d-flex align-items-center">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="ri-mail-send-line" style="font-size: 24px;"></i>
                            </div>
                            <h3 class="fw-bold mb-0">فرم تماس با ما</h3>
                        </div>
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="ri-checkbox-circle-line me-2" style="font-size: 24px;"></i>
                                    <div>{{ session('success') }}</div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                            @csrf
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-medium">نام و نام خانوادگی <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-user-line text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="نام و نام خانوادگی خود را وارد کنید">
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block mt-1">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-medium">ایمیل <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-mail-line text-primary"></i>
                                        </span>
                                        <input type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="ایمیل خود را وارد کنید">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block mt-1">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="subject" class="form-label fw-medium">موضوع <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-chat-1-line text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required placeholder="موضوع پیام خود را وارد کنید">
                                    </div>
                                    @error('subject')
                                        <div class="invalid-feedback d-block mt-1">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="message" class="form-label fw-medium">پیام <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 align-items-start pt-2">
                                            <i class="ri-message-3-line text-primary"></i>
                                        </span>
                                        <textarea class="form-control border-start-0 ps-0 @error('message') is-invalid @enderror" id="message" name="message" rows="6" required placeholder="متن پیام خود را وارد کنید">{{ old('message') }}</textarea>
                                    </div>
                                    @error('message')
                                        <div class="invalid-feedback d-block mt-1">
                                            <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-primary py-3 px-5 rounded-pill">
                                        <i class="ri-send-plane-fill me-2"></i>ارسال پیام
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="col-lg-4 order-lg-2 order-1 mb-4 mb-lg-0" data-aos="fade-up">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="mb-4 d-flex align-items-center">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="ri-information-line" style="font-size: 24px;"></i>
                            </div>
                            <h3 class="fw-bold mb-0">اطلاعات تماس</h3>
                        </div>
                        
                        <div class="mb-4 contact-info-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="ri-map-pin-line" style="font-size: 20px;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold text-primary mb-1">آدرس دفتر مرکزی</h5>
                                    <p class="text-secondary mb-0">کابل، افغانستان</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4 contact-info-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="icon-box bg-accent bg-opacity-10 text-accent rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="ri-phone-line" style="font-size: 20px;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold text-accent mb-1">شماره تماس</h5>
                                    <p class="text-secondary mb-1">+93 700 000 000</p>
                                    <p class="text-secondary mb-0">+93 744 000 000</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4 contact-info-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="ri-mail-line" style="font-size: 20px;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold text-success mb-1">ایمیل</h5>
                                    <p class="text-secondary mb-1">info@afghanpro.af</p>
                                    <p class="text-secondary mb-0">support@afghanpro.af</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4 contact-info-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="ri-time-line" style="font-size: 20px;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold text-primary mb-1">ساعات کاری</h5>
                                    <p class="text-secondary mb-1">شنبه تا پنجشنبه: 8:00 الی 18:00</p>
                                    <p class="text-secondary mb-0">جمعه: تعطیل</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4 contact-info-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="icon-box bg-accent bg-opacity-10 text-accent rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="ri-global-line" style="font-size: 20px;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-bold text-accent mb-1">شبکه‌های اجتماعی</h5>
                                    <div class="social-icons d-flex gap-2 mt-2">
                                        <a href="#" class="icon-box-sm bg-light rounded-circle d-flex align-items-center justify-content-center text-primary" title="Facebook">
                                            <i class="ri-facebook-fill"></i>
                                        </a>
                                        <a href="#" class="icon-box-sm bg-light rounded-circle d-flex align-items-center justify-content-center text-info" title="Twitter">
                                            <i class="ri-twitter-fill"></i>
                                        </a>
                                        <a href="#" class="icon-box-sm bg-light rounded-circle d-flex align-items-center justify-content-center text-danger" title="Instagram">
                                            <i class="ri-instagram-line"></i>
                                        </a>
                                        <a href="#" class="icon-box-sm bg-light rounded-circle d-flex align-items-center justify-content-center text-primary" title="Telegram">
                                            <i class="ri-telegram-fill"></i>
                                        </a>
                                        <a href="#" class="icon-box-sm bg-light rounded-circle d-flex align-items-center justify-content-center text-success" title="WhatsApp">
                                            <i class="ri-whatsapp-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5">
    <div class="container py-5">
        <div class="row text-center mb-5" data-aos="fade-up">
            <div class="col-lg-6 mx-auto">
                <span class="badge bg-primary bg-opacity-10 text-primary fw-normal mb-3 px-3 py-2">لوکیشن</span>
                <h2 class="display-5 fw-bold mb-3">موقعیت ما</h2>
                <p class="lead text-secondary">دفتر مرکزی افغان پرو در کابل</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12" data-aos="fade-up">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Responsive iframe for Google Maps -->
                    <div class="ratio ratio-21x9">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d207371.97331280514!2d69.02490440224055!3d34.55542522515864!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x38d16f3ea924ac77%3A0xc6527b75bd4e26d6!2sKabul%2C%20Afghanistan!5e0!3m2!1sen!2s!4v1699283846976!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Agencies Section -->
@if($agencies && $agencies->count() > 0)
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="row text-center mb-5" data-aos="fade-up">
            <div class="col-lg-6 mx-auto">
                <span class="badge bg-primary bg-opacity-10 text-primary fw-normal mb-3 px-3 py-2">دسترسی آسان</span>
                <h2 class="display-5 fw-bold mb-3">نمایندگی‌های ما</h2>
                <p class="lead text-secondary">می‌توانید به نمایندگی‌های ما در سراسر افغانستان مراجعه کنید</p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($agencies as $agency)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="card h-100 border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="ri-store-2-line" style="font-size: 20px;"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-0">{{ $agency->name }}</h5>
                        </div>
                        
                        <div class="mb-3 ps-2 border-start border-2 border-primary">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ri-map-pin-line me-2 text-primary"></i>
                                <p class="mb-0 text-secondary">{{ $agency->address }}</p>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <i class="ri-phone-line me-2 text-primary"></i>
                                <p class="mb-0 text-secondary">{{ $agency->phone }}</p>
                            </div>
                            
                            @if($agency->manager_name)
                            <div class="d-flex align-items-center mt-2">
                                <i class="ri-user-line me-2 text-primary"></i>
                                <p class="mb-0 text-secondary">{{ $agency->manager_name }}</p>
                            </div>
                            @endif
                        </div>
                        
                        @if($agency->working_hours)
                        <div class="bg-light p-3 rounded-3">
                            <div class="d-flex align-items-center">
                                <i class="ri-time-line me-2 text-accent"></i>
                                <p class="mb-0 small">
                                    @php
                                    try {
                                        $workingHours = $agency->working_hours;
                                        if (is_string($workingHours)) {
                                            $workingHours = json_decode($workingHours, true);
                                        }
                                        
                                        if (is_array($workingHours)) {
                                            // Check if it's the complex structure with days
                                            if (isset($workingHours['saturday']) || isset($workingHours['monday'])) {
                                                echo 'شنبه تا پنجشنبه: 8:00 الی 18:00 | جمعه: تعطیل';
                                            } else {
                                                echo implode(' - ', $workingHours);
                                            }
                                        } else {
                                            echo $workingHours;
                                        }
                                    } catch (Exception $e) {
                                        echo "لطفا برای اطلاع از ساعات کاری تماس بگیرید";
                                    }
                                    @endphp
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection 

@push('styles')
<style>
    .contact-info-item {
        transition: all 0.3s ease;
    }
    
    .contact-info-item:hover {
        transform: translateX(-5px);
    }
    
    .icon-box {
        transition: all 0.3s ease;
    }
    
    .contact-info-item:hover .icon-box {
        transform: scale(1.1);
    }
    
    .icon-box-sm {
        width: 36px;
        height: 36px;
        transition: all 0.3s ease;
    }
    
    .icon-box-sm:hover {
        transform: translateY(-3px);
    }
    
    .contact-form .form-control {
        padding: 0.75rem 1rem;
    }
    
    .contact-form .form-control:focus {
        box-shadow: none;
        border-color: var(--primary-color);
    }
    
    @media (max-width: 991.98px) {
        .order-lg-2 {
            margin-bottom: 2rem !important;
        }
    }
</style>
@endpush 