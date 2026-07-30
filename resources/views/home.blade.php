    @extends('layouts.app')

    @section('title', 'خدمات پرداخت اینترنتی و حواله برای افغانستان')

    @section('content')
    <!-- Hero Section -->
<section class="position-relative overflow-hidden hero-section">
    <!-- Background Shape -->
    <div class="position-absolute top-0 start-0 w-100 h-100 hero-bg" style="background: linear-gradient(135deg, #2c3e50 0%, #1a2530 100%); clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);"></div>
    
    <!-- Hero Content -->
    <div class="container position-relative py-5 text-white">
        <div class="row py-md-5 py-3 align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                <span class="badge bg-accent text-white fw-normal mb-3 px-3 py-2">راه حل مالی مختص افغانستان</span>
                <h1 class="display-4 fw-bold mb-4">افغان پرو</h1>
                <p class="lead fw-normal mb-4">دسترسی به خدمات پرداخت بین‌المللی و راه‌حل‌های مالی پیشرفته</p>
                <p class="mb-5 opacity-75">به دلیل محدودیت‌های پرداخت بین‌المللی، افغان پرو خدمات مالی متنوعی را طراحی کرده تا دغدغه‌های مالی شهروندان افغانستان را برطرف سازد.</p>
                <div class="d-flex flex-wrap gap-3">
                    @auth
                        <a href="{{ route('dashboard.index') }}" class="btn btn-accent btn-lg px-4 py-3 rounded-pill">
                            <i class="ri-dashboard-line me-2"></i> ورود به داشبورد
                        </a>
                    @else
                        <a href="{{ route('auth.register') }}" class="btn btn-accent btn-lg px-4 py-3 rounded-pill">
                            <i class="ri-user-add-line me-2"></i> ثبت نام رایگان
                        </a>
                        <a href="{{ route('auth.login') }}" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill">
                            <i class="ri-login-circle-line me-2"></i> ورود
                        </a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-6 text-center mt-4 mt-lg-0" data-aos="fade-left" data-aos-delay="200">
                <img src="{{ asset('img/Web-1.webp') }}" alt="افغان پرو" class="img-fluid rounded-4 shadow-lg hero-image" style="max-height: 480px;">
            </div>
        </div>
        
        <!-- Floating Stats -->
        <div class="row mt-md-5 mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg py-4 stats-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-body px-3 px-md-4">
                        <div class="row text-center text-dark g-4">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="icon-box bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                        <i class="ri-user-heart-line text-primary" style="font-size: 28px;"></i>
                                    </div>
                                </div>
                                <h3 class="fs-1 fw-bold text-primary mb-1">+<span class="counter">25000</span></h3>
                                <p class="text-secondary">کاربر فعال</p>
                            </div>
                            <div class="col-md-4 border-start border-end border-md-1 border-0 my-4 my-md-0 py-4 py-md-0">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="icon-box bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                        <i class="ri-exchange-dollar-line text-success" style="font-size: 28px;"></i>
                                    </div>
                                </div>
                                <h3 class="fs-1 fw-bold text-success mb-1">+<span class="counter">1.5</span>M</h3>
                                <p class="text-secondary">تراکنش‌های موفق</p>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="icon-box bg-accent bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                        <i class="ri-store-2-line text-accent" style="font-size: 28px;"></i>
                                    </div>
                                </div>
                                <h3 class="fs-1 fw-bold text-accent mb-1">+<span class="counter">50</span></h3>
                                <p class="text-secondary">نمایندگی فعال</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container py-md-5 py-3">
            <div class="row text-center mb-md-5 mb-4" data-aos="fade-up">
                <div class="col-lg-6 mx-auto">
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-normal mb-3 px-3 py-2">خدمات ما</span>
                    <h2 class="display-5 fw-bold mb-3">راه‌حل‌های پرداخت حرفه‌ای</h2>
                    <p class="lead text-secondary">راه‌حل‌های متنوع مالی برای رفع محدودیت‌های پرداخت بین‌المللی</p>
                </div>
            </div>
            
            <div class="row g-4">
                
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-1 feature-card">
                        <div class="rounded-4 bg-white p-3 p-md-4 h-100">
                            <div class="icon-wrapper mb-3 mb-md-4">
                                <div class="icon-circle bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="ri-store-2-line" style="font-size: 28px;"></i>
                                </div>
                            </div>
                            <h5 class="card-title fw-bold mb-3">خرید اکانت‌های پرمیوم</h5>
                            <p class="card-text text-secondary mb-4">خرید انواع اکانت‌های پرمیوم سرویس‌های مختلف با پرداخت افغانی.</p>
                            @auth
                                <a href="{{ route('dashboard.shop.index') }}" class="btn btn-primary btn-sm rounded-pill px-4">
                                    <i class="ri-shopping-basket-line me-1"></i> فروشگاه
                                </a>
                            @else
                                <a href="{{ route('auth.login') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                    <i class="ri-information-line me-1"></i> اطلاعات بیشتر
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5 position-relative overflow-hidden">
        <!-- Background Gradient -->
        <div class="position-absolute start-0 top-0 w-100 h-100 bg-white" style="background-image: radial-gradient(rgba(44, 62, 80, 0.03) 2px, transparent 2px); background-size: 25px 25px;"></div>
        
        <div class="container py-md-5 py-3 position-relative">
            <div class="row text-center mb-md-5 mb-4" data-aos="fade-up">
                <div class="col-lg-6 mx-auto">
                    <span class="badge bg-accent bg-opacity-10 text-accent fw-normal mb-3 px-3 py-2">روند کار</span>
                    <h2 class="display-5 fw-bold mb-3">چگونه کار می‌کند؟</h2>
                    <p class="lead text-secondary">مراحل ساده برای استفاده از خدمات افغان پرو</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="row">
                        <!-- Step 1 -->
                        <div class="col-md-4 mb-4" data-aos="fade-up">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body p-3 p-md-4 text-center">
                                    <div class="step-number bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mb-md-4" style="width: 60px; height: 60px;">
                                        <span class="text-white fs-3 fw-bold">1</span>
                                    </div>
                                    <div class="position-relative mb-3 mb-md-4">
                                        <img src="{{ asset('img/Web-4.webp') }}" class="img-fluid rounded-3" alt="ثبت نام">
                                        <div class="position-absolute top-0 start-0 end-0 bottom-0 bg-primary bg-opacity-10 rounded-3"></div>
                                    </div>
                                    <h4 class="fw-bold mb-2 mb-md-3">ثبت نام و ایجاد حساب</h4>
                                    <p class="text-secondary small">به سادگی ثبت نام کنید و حساب کاربری خود را ایجاد نمایید. کیف پول افغانی و دلاری بصورت خودکار برای شما ساخته می‌شود.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body p-3 p-md-4 text-center">
                                    <div class="step-number bg-accent rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mb-md-4" style="width: 60px; height: 60px;">
                                        <span class="text-white fs-3 fw-bold">2</span>
                                    </div>
                                    <div class="position-relative mb-3 mb-md-4">
                                        <img src="{{ asset('img/Web-5.webp') }}" class="img-fluid rounded-3" alt="شارژ کیف پول">
                                        <div class="position-absolute top-0 start-0 end-0 bottom-0 bg-accent bg-opacity-10 rounded-3"></div>
                                    </div>
                                    <h4 class="fw-bold mb-2 mb-md-3">شارژ کیف پول</h4>
                                    <p class="text-secondary small">کیف پول خود را از طریق مراجعه به نمایندگی‌های ما در سراسر افغانستان یا از طریق حساب پی شارژ کنید.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 3 -->
                        <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body p-3 p-md-4 text-center">
                                    <div class="step-number bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mb-md-4" style="width: 60px; height: 60px;">
                                        <span class="text-white fs-3 fw-bold">3</span>
                                    </div>
                                    <div class="position-relative mb-3 mb-md-4">
                                        <img src="{{ asset('img/Web-6.webp') }}" class="img-fluid rounded-3" alt="استفاده از خدمات">
                                        <div class="position-absolute top-0 start-0 end-0 bottom-0 bg-success bg-opacity-10 rounded-3"></div>
                                    </div>
                                    <h4 class="fw-bold mb-2 mb-md-3">استفاده از خدمات</h4>
                                    <p class="text-secondary small">از خدمات متنوع افغان پرو مانند افزایش اعتبار ترید، ارسال حواله و خرید اکانت‌های پرمیوم استفاده کنید.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Process Line -->
                    <div class="d-none d-md-block">
                        <div class="position-relative my-5" style="height: 2px;">
                            <div class="position-absolute start-0 end-0 top-50 translate-middle-y bg-primary" style="height: 2px;"></div>
                            <div class="position-absolute start-0 top-50 translate-middle-y bg-primary rounded-circle" style="width: 10px; height: 10px;"></div>
                            <div class="position-absolute end-0 top-50 translate-middle-y bg-primary rounded-circle" style="width: 10px; height: 10px;"></div>
                        </div>
                    </div>
                    
                    <!-- Call to Action -->
                    <div class="text-center mt-4 mt-md-5" data-aos="fade-up" data-aos-delay="300">
                        @auth
                            <a href="{{ route('dashboard.index') }}" class="btn btn-primary btn-lg rounded-pill px-4 px-md-5 py-2 py-md-3">
                                <i class="ri-dashboard-line me-2"></i> ورود به داشبورد
                            </a>
                        @else
                            <a href="{{ route('auth.register') }}" class="btn btn-primary btn-lg rounded-pill px-4 px-md-5 py-2 py-md-3">
                                <i class="ri-user-add-line me-2"></i> ثبت نام و شروع
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    @if($featuredProducts && $featuredProducts->count() > 0)
    <section class="py-5 bg-light">
        <div class="container py-md-5 py-3">
            <div class="row text-center mb-md-5 mb-4" data-aos="fade-up">
                <div class="col-lg-6 mx-auto">
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-normal mb-3 px-3 py-2">محصولات برتر</span>
                    <h2 class="display-5 fw-bold mb-3">اکانت‌های پرمیوم و پرطرفدار</h2>
                    <p class="lead text-secondary">برترین محصولات و اکانت‌های خدماتی با قیمت مناسب</p>
                </div>
            </div>
            
            <div class="row g-4">
                @php $megaImages = ['output-28-400x400.jpg', 'output-23-400x400.jpg', 'output-14-400x400.jpg', 'output-3-400x400.jpg']; @endphp
                @foreach($featuredProducts as $product)
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    <div class="card h-100 border-0 shadow-sm rounded-4 product-card">
                        <div class="position-relative">
                            <img src="{{ asset('mega/' . ($megaImages[$loop->index] ?? 'output-28-400x400.jpg')) }}" class="card-img-top rounded-top-4" alt="{{ $product->name }}" style="height: 150px; object-fit: cover;">
                            <div class="position-absolute top-0 start-0 m-2 m-md-3">
                                <span class="badge bg-accent rounded-pill px-2 px-md-3 py-1 py-md-2">محبوب</span>
                            </div>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <h5 class="card-title fw-bold mb-2 mb-md-3">{{ $product->name }}</h5>
                            <p class="card-text text-secondary mb-3 mb-md-4 small" style="min-height: 40px;">{{ $product->short_description }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-accent fw-bold">از {{ $product->variations->min('price') }} افغانی</span>
                                @auth
                                    <a href="{{ route('dashboard.shop.product', $product->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="ri-shopping-basket-line me-1"></i> خرید
                                    </a>
                                @else
                                    <a href="{{ route('auth.login') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="ri-login-circle-line me-1"></i> ورود و خرید
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-4 mt-md-5" data-aos="fade-up">
                @auth
                    <a href="{{ route('dashboard.shop.index') }}" class="btn btn-primary btn-lg rounded-pill px-4 px-md-5 py-2 py-md-3">
                        <i class="ri-store-2-line me-2"></i> مشاهده همه محصولات
                    </a>
                @else
                    <a href="{{ route('auth.login') }}" class="btn btn-primary btn-lg rounded-pill px-4 px-md-5 py-2 py-md-3">
                        <i class="ri-login-circle-line me-2"></i> ورود و مشاهده فروشگاه
                    </a>
                @endauth
            </div>
        </div>
    </section>
    @endif

    <!-- Agencies Section -->
    @if($agencies && $agencies->count() > 0)
    <section class="py-5 position-relative">
        <!-- Background Pattern -->
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: #f7f9fc; clip-path: polygon(0 10%, 100% 0, 100% 90%, 0% 100%);"></div>
        
        <div class="container py-md-5 py-3 position-relative">
            <div class="row text-center mb-md-5 mb-4" data-aos="fade-up">
                <div class="col-lg-6 mx-auto">
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-normal mb-3 px-3 py-2">دسترسی آسان</span>
                    <h2 class="display-5 fw-bold mb-3">نمایندگی‌های ما</h2>
                    <p class="lead text-secondary">در سراسر افغانستان در خدمت شما هستیم</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-6 mb-4" data-aos="fade-right">
                    <div class="card border-0 shadow-lg rounded-4 h-100">
                        <div class="card-body p-0">
                            <div id="map" class="rounded-4" style="height: 300px; height: 350px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="card border-0 shadow-lg rounded-4 h-100">
                        <div class="card-body p-3 p-md-4">
                            <h4 class="fw-bold mb-3 mb-md-4 border-bottom pb-3 d-flex align-items-center">
                                <i class="ri-map-pin-2-fill me-2 text-accent" style="font-size: 20px;"></i>
                                <span>نمایندگی‌های رسمی افغان پرو</span>
                            </h4>
                            <div class="agencies-list" style="max-height: 280px; overflow-y: auto;">
                                @foreach($agencies as $agency)
                                <div class="mb-3 mb-md-4 border-bottom pb-3 pb-md-4">
                                    <div class="d-flex flex-column flex-md-row justify-content-between">
                                        <div>
                                            <h5 class="fw-bold mb-2">{{ $agency->name }}</h5>
                                            <p class="mb-1 text-secondary">
                                                <i class="ri-map-pin-line me-1"></i> {{ $agency->address }}
                                            </p>
                                            <p class="mb-2 small text-primary">
                                                <i class="ri-building-line me-1"></i> {{ $agency->city }}، {{ $agency->province }}
                                            </p>
                                        </div>
                                        <div class="mt-2 mt-md-0">
                                            <a href="tel:{{ $agency->phone }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                                <i class="ri-phone-line me-1"></i>{{ $agency->phone }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 mt-2 mt-md-3">
                                        <span class="badge bg-accent bg-opacity-10 text-accent rounded-pill">
                                            <i class="ri-time-line me-1"></i> ساعت کاری: ۸ صبح - ۶ عصر
                                        </span>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill">
                                            <i class="ri-check-double-line me-1"></i> خدمات کامل
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Testimonials Section -->
    <section class="py-5 bg-light overflow-hidden position-relative">
        <div class="container py-md-5 py-3">
            <div class="row text-center mb-md-5 mb-4" data-aos="fade-up">
                <div class="col-lg-6 mx-auto">
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-normal mb-3 px-3 py-2">نظرات مشتریان</span>
                    <h2 class="display-5 fw-bold mb-3">تجربه کاربران ما</h2>
                    <p class="lead text-secondary">آنچه کاربران ما درباره خدمات افغان پرو می‌گویند</p>
                </div>
            </div>
            
            <div class="row testimonials-carousel">
                <!-- Testimonial 1 -->
                <div class="col-md-4 mb-4" data-aos="fade-up">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex align-items-center mb-3 mb-md-4">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 18px;">A</div>
                                </div>
                                <div class="flex-grow-1 ms-2 ms-md-3">
                                    <h5 class="fw-bold mb-0 mb-md-1 fs-6">احمد رضایی</h5>
                                    <p class="text-secondary mb-0 small">کابل، افغانستان</p>
                                </div>
                                <div class="flex-shrink-0 text-accent d-none d-md-block">
                                    <i class="ri-double-quotes-r" style="font-size: 28px;"></i>
                                </div>
                            </div>
                            <p class="mb-0 text-secondary small">استفاده از خدمات افغان پرو برای شارژ اکانت ترید بسیار ساده و کاربردی بود. در کمتر از ۵ دقیقه اکانت من شارژ شد و بدون مشکل توانستم معاملات خود را انجام دهم.</p>
                            <div class="mt-2 mt-md-3 text-warning">
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm rounded-4 h-100 border-accent border-top border-2 border-md-4">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex align-items-center mb-3 mb-md-4">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-accent text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 18px;">F</div>
                                </div>
                                <div class="flex-grow-1 ms-2 ms-md-3">
                                    <h5 class="fw-bold mb-0 mb-md-1 fs-6">فاطمه حسینی</h5>
                                    <p class="text-secondary mb-0 small">هرات، افغانستان</p>
                                </div>
                                <div class="flex-shrink-0 text-accent d-none d-md-block">
                                    <i class="ri-double-quotes-r" style="font-size: 28px;"></i>
                                </div>
                            </div>
                            <p class="mb-0 text-secondary small">من از طریق افغان پرو توانستم اکانت نتفلیکس خریداری کنم و مشکل پرداخت بین‌المللی من حل شد. پشتیبانی عالی و قیمت مناسب. قطعاً به دوستانم هم پیشنهاد می‌دهم.</p>
                            <div class="mt-2 mt-md-3 text-warning">
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex align-items-center mb-3 mb-md-4">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 18px;">M</div>
                                </div>
                                <div class="flex-grow-1 ms-2 ms-md-3">
                                    <h5 class="fw-bold mb-0 mb-md-1 fs-6">محمد کریمی</h5>
                                    <p class="text-secondary mb-0 small">مزار شریف، افغانستان</p>
                                </div>
                                <div class="flex-shrink-0 text-accent d-none d-md-block">
                                    <i class="ri-double-quotes-r" style="font-size: 28px;"></i>
                                </div>
                            </div>
                            <p class="mb-0 text-secondary small">ارسال حواله از طریق افغان پرو بسیار سریع و مطمئن انجام شد. کارمزد کمتر و سرعت بیشتر نسبت به روش‌های سنتی. از خدمات این سایت کاملاً راضی هستم.</p>
                            <div class="mt-2 mt-md-3 text-warning">
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-half-fill"></i>
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
        
        <div class="container py-md-5 py-3 position-relative">
            <div class="row py-md-5 py-3 align-items-center">
                <div class="col-lg-7 mb-5 mb-lg-0" data-aos="fade-right">
                    <div class="p-lg-5 p-2">
                        <span class="badge bg-accent text-white fw-normal mb-3 px-3 py-2">همین امروز شروع کنید</span>
                        <h2 class="display-4 fw-bold text-white mb-3 mb-md-4">آماده استفاده از خدمات افغان پرو هستید؟</h2>
                        <p class="lead text-white opacity-80 mb-4 mb-md-5">همین امروز ثبت نام کنید و از خدمات متنوع افغان پرو بهره‌مند شوید. کاربری آسان، پشتیبانی تخصصی و تجربه کاربری حرفه‌ای.</p>
                        <div class="d-flex flex-wrap gap-3">
                            @auth
                                <a href="{{ route('dashboard.index') }}" class="btn btn-accent btn-lg px-4 px-md-5 py-2 py-md-3 rounded-pill shadow-lg">
                                    <i class="ri-dashboard-line me-2"></i> ورود به داشبورد
                                </a>
                            @else
                                <a href="{{ route('auth.register') }}" class="btn btn-accent btn-lg px-4 px-md-5 py-2 py-md-3 rounded-pill shadow-lg">
                                    <i class="ri-user-add-line me-2"></i> ثبت نام رایگان
                                </a>
                                <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-4 px-md-5 py-2 py-md-3 rounded-pill">
                                    <i class="ri-customer-service-2-line me-2"></i> تماس با ما
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 text-center mt-4 mt-lg-0" data-aos="fade-left" data-aos-delay="200">
                    <img src="{{ asset('img/Web-7.webp') }}" alt="افغان پرو" class="img-fluid rounded-4 shadow-lg" style="max-height: 400px;">
                </div>
            </div>
        </div>
    </section>
    @endsection

	@push('styles')
<style>
    html, body {
        overflow-x: hidden;
        width: 100%;
        position: relative;
    }
    
    .feature-card {
        transition: all 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
    }
    
    .icon-circle {
        transition: all 0.3s ease;
    }
    
    .card:hover .icon-circle {
        transform: rotateY(180deg);
    }
    
    .step-number {
        transition: all 0.3s ease;
    }
    
    .card:hover .step-number {
        transform: scale(1.1);
    }
    
    .product-card {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .product-card:hover {
        transform: translateY(-10px);
    }
    
    .product-card img {
        transition: all 0.5s ease;
    }
    
    .product-card:hover img {
        transform: scale(1.1);
    }
    
    .agencies-list::-webkit-scrollbar {
        width: 6px;
    }
    
    .agencies-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .agencies-list::-webkit-scrollbar-thumb {
        background: #c0c0c0;
        border-radius: 10px;
    }
    
    .agencies-list::-webkit-scrollbar-thumb:hover {
        background: #a0a0a0;
    }
    
    .counter {
        display: inline-block;
    }
    
    /* Fix mobile overflow issues */
    .container {
        max-width: 100%;
        padding-left: 15px;
        padding-right: 15px;
    }
    
    @media (max-width: 768px) {
        .row {
            margin-left: -10px;
            margin-right: -10px;
        }
        
        [class*="col-"] {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .card-body {
            padding: 15px;
        }
        
        h1.display-4 {
            font-size: 2.5rem;
        }
        
        h2.display-5 {
            font-size: 2rem;
        }
        
        .lead {
            font-size: 1rem;
        }
    }
</style>
@endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Map (Placeholder implementation)
            const mapElement = document.getElementById('map');
            if (mapElement) {
                // This is a placeholder. In a real implementation, use a mapping library like Leaflet or Google Maps
                mapElement.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 bg-light rounded-4"><div class="text-center"><i class="ri-map-2-fill text-primary mb-3" style="font-size: 64px;"></i><p class="lead text-primary fw-bold">نقشه نمایندگی‌های افغان پرو</p><p class="text-secondary">لیست کامل نمایندگی‌ها در سمت راست قابل مشاهده است</p></div></div>';
            }
            
            // Counter animation (requires jQuery)
            if (typeof jQuery !== 'undefined' && jQuery.fn.counterUp) {
                jQuery('.counter').counterUp({
                    delay: 10,
                    time: 1000
                });
            }
            
            // Fix any horizontal scroll issues
            document.body.style.overflow = 'hidden';
            setTimeout(function() {
                document.body.style.overflow = 'auto';
            }, 100);
        });
    </script>
    @endpush
