@extends('layouts.app')

@section('title', 'درباره ما - افغان پرو')

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden">
    <!-- Background Shape -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, #2c3e50 0%, #1a2530 100%); clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);"></div>
    
    <div class="container position-relative py-5 text-white">
        <div class="row py-5 align-items-center">
            <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                <span class="badge bg-accent text-white fw-normal mb-3 px-3 py-2">افغان پرو</span>
                <h1 class="display-4 fw-bold mb-4">درباره ما</h1>
                <p class="lead opacity-80 mb-0">خدمات مالی و پرداخت پیشرفته برای مردم افغانستان</p>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card border-0 shadow-lg rounded-4" data-aos="fade-up">
                    <div class="card-body p-lg-5 p-4">
                        <!-- Services Header -->
                        <div class="text-center mb-5" data-aos="fade-up">
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-normal mb-3 px-3 py-2">خدمات ما</span>
                            <h2 class="display-6 fw-bold mb-3">خدمات حواله افغان پرو</h2>
                            <div class="divider-center">
                                <span class="divider-line"></span>
                                <div class="divider-icon text-primary">
                                    <i class="ri-exchange-dollar-line"></i>
                                </div>
                                <span class="divider-line"></span>
                            </div>
                        </div>
                        
                        <!-- Services Grid -->
                        <div class="row g-4 mb-5">
                            <!-- Service 1 -->
                            <div class="col-md-6" data-aos="fade-up">
                                <div class="service-card p-4 rounded-4 h-100 border bg-light bg-opacity-50">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="ri-global-line" style="font-size: 28px;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-3 fw-bold text-primary">حواله های خارجی</h4>
                                            <p class="text-secondary mb-0">اجرای حواله های خارجی از بیرون از افغانستان به هر نقطه از افغانستان کمتر از ۱ ساعت به صورت تضمینی.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Service 2 -->
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                                <div class="service-card p-4 rounded-4 h-100 border bg-light bg-opacity-50">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="icon-box bg-accent bg-opacity-10 text-accent rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="ri-plane-line" style="font-size: 28px;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-3 fw-bold text-accent">حواله به خارج از افغانستان</h4>
                                            <p class="text-secondary mb-0">اجرای حواله ها از داخل افغانستان به خارج حتی برای کمپنی ها و شرکت ها به صورت مصون که طرف قرارداد شما تخطی در معامله تان انجام ندهد.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Service 3 -->
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                                <div class="service-card p-4 rounded-4 h-100 border bg-light bg-opacity-50">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="ri-map-pin-line" style="font-size: 28px;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-3 fw-bold text-success">حواله های داخلی</h4>
                                            <p class="text-secondary mb-0">اجرای حواله های پولی شما به صورت داخلی از هر شهر و هر نقطه افغانستان به هر شهر و نقطه دیگری از افغانستان عزیزمان.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Service 4 -->
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                                <div class="service-card p-4 rounded-4 h-100 border bg-light bg-opacity-50">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="ri-exchange-funds-line" style="font-size: 28px;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-3 fw-bold text-primary">تبدیل ارز</h4>
                                            <p class="text-secondary mb-0">اجرای حواله های شما در قبال کرنسی های رایج دیگر و پرداخت پول شما به دالر و افغانی.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Service 5 -->
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                                <div class="service-card p-4 rounded-4 h-100 border bg-light bg-opacity-50">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="icon-box bg-accent bg-opacity-10 text-accent rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="ri-wallet-3-line" style="font-size: 28px;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-3 fw-bold text-accent">کیف پول دیجیتال</h4>
                                            <p class="text-secondary mb-0">ایجاد حساب کاربری و کیف پول برای مشتریان مان تا بدون مشکل پول را بین شهرهای افغانستان و دیگر کشور ها از طریق سفارش حواله انتقال دهید با کمترین کمیشن ممکن.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Service 6 -->
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                                <div class="service-card p-4 rounded-4 h-100 border bg-light bg-opacity-50">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="ri-time-line" style="font-size: 28px;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-3 fw-bold text-success">دسترسی ۲۴ ساعته</h4>
                                            <p class="text-secondary mb-0">سفارش حواله به صورت ۲۴ ساعته در ۷ روز هفته بدون محدودیت و لیمیت.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="divider-center my-5" data-aos="fade-up">
                            <span class="divider-line"></span>
                        </div>
                        
                        <!-- Trade Services Header -->
                        <div class="text-center mb-5" data-aos="fade-up">
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-normal mb-3 px-3 py-2">خدمات ترید</span>
                            <h2 class="display-6 fw-bold mb-3">خدمات ترید افغان پرو</h2>
                            <div class="divider-center">
                                <span class="divider-line"></span>
                                <div class="divider-icon text-primary">
                                    <i class="ri-line-chart-line"></i>
                                </div>
                                <span class="divider-line"></span>
                            </div>
                        </div>
                        
                        <!-- Trade Services -->
                        <div class="row g-4 mb-5">
                            <!-- Trade Service 1 -->
                            <div class="col-md-6" data-aos="fade-up">
                                <div class="service-card p-4 rounded-4 h-100 border bg-light bg-opacity-50">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="ri-refund-2-line" style="font-size: 28px;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-3 fw-bold text-primary">برداشت و ازدیاد پول ترید</h4>
                                            <p class="text-secondary mb-0">افغان پرو خدمات را برای تریدر ها در داخل از افغانستان فراهم کرده تا بدون مشکل اکانت های ترید خود را به آسانی با دالر یا افغانی کمتر از ۱ ساعت شارژ کنید.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Trade Service 2 -->
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                                <div class="service-card p-4 rounded-4 h-100 border bg-light bg-opacity-50">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="icon-box bg-accent bg-opacity-10 text-accent rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="ri-global-line" style="font-size: 28px;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-3 fw-bold text-accent">پشتیبانی از بروکرهای جهانی</h4>
                                            <p class="text-secondary mb-0">ساپورت تمامی بروکر های رایج در جهان و افغانستان.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- More Trade Services... Similar format for other trade services -->
                        </div>
                        
                        <div class="divider-center my-5" data-aos="fade-up">
                            <span class="divider-line"></span>
                        </div>
                        
                        <!-- Premium Accounts Header -->
                        <div class="text-center mb-5" data-aos="fade-up">
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-normal mb-3 px-3 py-2">خدمات اکانت</span>
                            <h2 class="display-6 fw-bold mb-3">خرید اکانت های پریمیم جهانی</h2>
                            <div class="divider-center">
                                <span class="divider-line"></span>
                                <div class="divider-icon text-primary">
                                    <i class="ri-vip-crown-line"></i>
                                </div>
                                <span class="divider-line"></span>
                            </div>
                        </div>
                        
                        <!-- Premium Account Services -->
                        <div class="row g-4">
                            <!-- Account Service 1 -->
                            <div class="col-md-6" data-aos="fade-up">
                                <div class="service-card p-4 rounded-4 h-100 border bg-light bg-opacity-50">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="ri-shopping-cart-line" style="font-size: 28px;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-3 fw-bold text-primary">اکانت های پریمیم با قیمت مناسب</h4>
                                            <p class="text-secondary mb-0">با افغان پرو در هر جای افغانستان میتوانید بدون محدودیت و با کمترین قیمت بدون حتی ۱ افغانی کمیشن اکانت های پریمیم هر نوع پلتفرم انلاین که نیاز داشته باشید را در کمتر از ۱ ساعت خریداری کنید.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Account Service 2 -->
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                                <div class="service-card p-4 rounded-4 h-100 border bg-light bg-opacity-50">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="icon-box bg-accent bg-opacity-10 text-accent rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="ri-group-line" style="font-size: 28px;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-3 fw-bold text-accent">اشتراک اکانت ها</h4>
                                            <p class="text-secondary mb-0">حالا افغان پرو خدمات اکانت های اشتراکی بیشتر پلتفرم های آنلاین را برای شما فراهم ساخته تا بتوانبد با یک اکانت تا چندین عضو فامیل و دوستان فقط با پرداخت پول یک اکانت بدون محدودیت استفاده نمایید.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- More account services would follow the same pattern -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 position-relative overflow-hidden">
    <!-- Background Shape -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, #2c3e50 0%, #1a2530 100%);"></div>
    
    <!-- Floating elements -->
    <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden">
        <div class="position-absolute" style="top: 10%; left: 10%; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%; filter: blur(40px);"></div>
        <div class="position-absolute" style="top: 50%; right: 10%; width: 200px; height: 200px; background: rgba(243,156,18,0.1); border-radius: 50%; filter: blur(50px);"></div>
        <div class="position-absolute" style="bottom: 10%; left: 30%; width: 180px; height: 180px; background: rgba(255,255,255,0.03); border-radius: 50%; filter: blur(45px);"></div>
    </div>
    
    <div class="container py-5 position-relative">
        <div class="row py-5 justify-content-center">
            <div class="col-lg-8 text-center" data-aos="fade-up">
                <h2 class="display-5 fw-bold text-white mb-4">آماده همکاری با افغان پرو هستید؟</h2>
                <p class="lead text-white opacity-75 mb-5">همین امروز ثبت نام کنید و از خدمات متنوع ما بهره‌مند شوید</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    @auth
                        <a href="{{ route('dashboard.index') }}" class="btn btn-accent btn-lg px-5 py-3 rounded-pill">
                            <i class="ri-dashboard-line me-2"></i> ورود به داشبورد
                        </a>
                    @else
                        <a href="{{ route('auth.register') }}" class="btn btn-accent btn-lg px-5 py-3 rounded-pill">
                            <i class="ri-user-add-line me-2"></i> ثبت نام رایگان
                        </a>
                        <a href="{{ route('auth.login') }}" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill">
                            <i class="ri-login-circle-line me-2"></i> ورود
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .divider-center {
        display: flex;
        align-items: center;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .divider-line {
        flex-grow: 1;
        height: 1px;
        background-color: rgba(0, 0, 0, 0.1);
    }
    
    .divider-icon {
        padding: 0 15px;
        font-size: 24px;
    }
    
    .service-card {
        transition: all 0.3s ease;
    }
    
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .icon-box {
        transition: all 0.3s ease;
    }
    
    .service-card:hover .icon-box {
        transform: rotateY(180deg);
    }
</style>
@endpush 