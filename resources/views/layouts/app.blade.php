<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'خدمات پرداخت پیشرفته برای افغانستان')</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('logo/Favicon-Color.webp') }}" type="image/webp">
    
    <!-- Google Vazirmatn Font (Persian) -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #f39c12;
            --light-color: #ecf0f1;
            --dark-color: #1a2530;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
        }
        
        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: #f7f9fc;
            color: #2c3e50;
            overflow-x: hidden;
        }
        
        /* Navbar Styles */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            padding: 15px 0;
        }
        
        .navbar-scrolled {
            padding: 8px 0;
        }
        
        .navbar-brand img {
            height: 42px;
            transition: all 0.3s ease;
        }
        
        .navbar-scrolled .navbar-brand img {
            height: 36px;
        }
        
        .navbar-nav .nav-link {
            color: var(--secondary-color);
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            margin: 0 3px;
        }
        
        .navbar-nav .nav-link:hover, 
        .navbar-nav .nav-link.active {
            color: var(--primary-color);
            background-color: rgba(44, 62, 80, 0.05);
        }
        
        .navbar-toggler {
            border: none;
            padding: 6px;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        /* Button Styles */
        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            z-index: -1;
        }
        
        .btn:hover::after {
            height: 100%;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }
        
        .btn-accent:hover {
            background-color: #e67e22;
            border-color: #e67e22;
            color: white;
        }
        
        .btn-outline-accent {
            color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .btn-outline-accent:hover {
            background-color: var(--accent-color);
            color: white;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .card-img-top {
            height: 180px;
            object-fit: cover;
        }
        
        /* Footer Styles */
        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 5rem 0 3rem;
            position: relative;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.02' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.3;
        }
        
        .footer-logo {
            height: 60px;
            margin-bottom: 20px;
        }
        
        .footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer a:hover {
            color: var(--accent-color);
            padding-right: 5px;
        }
        
        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .social-icons a:hover {
            background: var(--accent-color);
            color: white;
            transform: translateY(-3px);
        }
        
        /* Loader Styles */
        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--dark-color);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.27, 1.55);
        }
        
        .loader-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .loader-logo {
            width: 80px;
            margin-bottom: 30px;
            animation: pulse 2s infinite;
        }
        
        .loader {
            width: 48px;
            height: 48px;
            border: 5px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            border-top-color: var(--accent-color);
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to {transform: rotate(360deg);}
        }
        
        @keyframes pulse {
            0% {transform: scale(1);}
            50% {transform: scale(1.1);}
            100% {transform: scale(1);}
        }
        
        .loader-hidden {
            opacity: 0;
            visibility: hidden;
        }
        
        /* Alerts */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Loader -->
    <div class="loader-container" id="loaderContainer">
        <div class="loader-content">
            <img src="{{ asset('logo/Logo-White.webp') }}" alt="{{ config('app.name') }}" class="loader-logo">
            <div class="loader"></div>
        </div>
    </div>
    
    <div id="app">
        <header>
            <nav class="navbar navbar-expand-lg fixed-top">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                        <img src="{{ asset('logo/Logo-color.webp') }}" alt="{{ config('app.name') }}">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="ri-menu-line" style="font-size: 24px;"></i>
                    </button>
                    
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                    <i class="ri-home-4-line me-1"></i> خانه
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                                    <i class="ri-information-line me-1"></i> درباره ما
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                                    <i class="ri-customer-service-2-line me-1"></i> تماس با ما
                                </a>
                            </li>
                        </ul>
                        
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            @guest
                                <li class="nav-item me-2">
                                    <a class="nav-link btn btn-outline-primary px-4" href="{{ route('auth.login') }}">
                                        <i class="ri-login-circle-line me-1"></i> ورود
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link btn btn-accent px-4" href="{{ route('auth.register') }}">
                                        <i class="ri-user-add-line me-1"></i> ثبت نام
                                    </a>
                                </li>
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 14px;">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                        {{ Auth::user()->name }}
                                    </a>
                                    
                                    <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-lg" aria-labelledby="navbarDropdown">
                                        <div class="px-4 py-3 border-bottom">
                                            <span class="d-block text-muted small">خوش آمدید</span>
                                            <span class="fw-bold">{{ Auth::user()->name }}</span>
                                        </div>
                                        <a class="dropdown-item" href="{{ route('dashboard.index') }}">
                                            <i class="ri-dashboard-line me-2"></i> داشبورد
                                        </a>
                                        <a class="dropdown-item" href="{{ route('dashboard.profile') }}">
                                            <i class="ri-user-settings-line me-2"></i> پروفایل
                                        </a>
                                        <a class="dropdown-item" href="{{ route('dashboard.wallets') }}">
                                            <i class="ri-wallet-3-line me-2"></i> کیف پول ها
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="{{ route('auth.logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <i class="ri-logout-circle-r-line me-2"></i> خروج
                                        </a>
                                        
                                        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        
        <main style="padding-top: 80px;">
            @if(session('success'))
                <div class="container mt-4">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="ri-checkbox-circle-line me-2" style="font-size: 24px;"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="container mt-4">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="ri-error-warning-line me-2" style="font-size: 24px;"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </main>
        
        <footer class="footer mt-5">
            <div class="container position-relative z-index-1">
                <div class="row">
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <img src="{{ asset('logo/Logo-White.webp') }}" alt="{{ config('app.name') }}" class="footer-logo">
                        <p class="mb-4 pe-lg-5">راه حل مناسب برای پرداخت‌های بین‌المللی و خدمات مالی برای مردم افغانستان با امنیت بالا و قیمت مناسب.</p>
                        <div class="social-icons">
                            <a href="https://facebook.com" target="_blank"><i class="ri-facebook-fill"></i></a>
                            <a href="https://twitter.com" target="_blank"><i class="ri-twitter-fill"></i></a>
                            <a href="https://instagram.com" target="_blank"><i class="ri-instagram-line"></i></a>
                            <a href="https://t.me" target="_blank"><i class="ri-telegram-fill"></i></a>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2 mb-4 mb-md-0">
                        <h5 class="fw-bold mb-4">لینک‌های سریع</h5>
                        <ul class="list-unstyled">
                            <li class="mb-3"><a href="{{ route('home') }}"><i class="ri-arrow-left-line me-2"></i>خانه</a></li>
                            <li class="mb-3"><a href="{{ route('about') }}"><i class="ri-arrow-left-line me-2"></i>درباره ما</a></li>
                            <li class="mb-3"><a href="{{ route('contact') }}"><i class="ri-arrow-left-line me-2"></i>تماس با ما</a></li>
                        </ul>
                    </div>
                    <div class="col-md-5 col-lg-3 mb-4 mb-md-0">
                        <h5 class="fw-bold mb-4">خدمات ما</h5>
                        <ul class="list-unstyled">
                            <li class="mb-3"><a href="{{ route('dashboard.shop.index') }}"><i class="ri-arrow-left-line me-2"></i>خرید اکانت‌های پرمیوم</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <h5 class="fw-bold mb-4">تماس با ما</h5>
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex align-items-center">
                                <div class="icon-box bg-accent d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; border-radius: 8px;">
                                    <i class="ri-map-pin-line text-white"></i>
                                </div>
                                <span>کابل، افغانستان</span>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <div class="icon-box bg-accent d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; border-radius: 8px;">
                                    <i class="ri-phone-line text-white"></i>
                                </div>
                                <span>+93 700 000 000</span>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <div class="icon-box bg-accent d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; border-radius: 8px;">
                                    <i class="ri-mail-line text-white"></i>
                                </div>
                                <span>info@afghanpro.af</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <hr class="mt-5 mb-4 border-light opacity-10">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <p class="mb-0 small">
                            &copy; {{ date('Y') }} افغان پرو. تمامی حقوق محفوظ است.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <ul class="list-inline mb-0 small">
                            <li class="list-inline-item me-3"><a href="#">قوانین و مقررات</a></li>
                            <li class="list-inline-item"><a href="#">حریم خصوصی</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    
    <script>
        // Initialize AOS animation library
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true
            });
            
            // Hide loader when page is loaded
            setTimeout(function() {
                document.getElementById('loaderContainer').classList.add('loader-hidden');
            }, 800);
            
            // Navbar scroll effect
            const navbar = document.querySelector('.navbar');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
            });
        });
        
        // Show loader on page navigation but handle back button correctly
        window.addEventListener('beforeunload', function(e) {
            // Do not show loader if navigation is caused by back/forward buttons
            if (performance.navigation.type !== 2) { // 2 is TYPE_BACK_FORWARD
                document.getElementById('loaderContainer').classList.remove('loader-hidden');
            }
        });
        
        // Handle page show event (back/forward navigation)
        window.addEventListener('pageshow', function(e) {
            // If the page is loaded from the cache (back/forward navigation)
            if (e.persisted) {
                document.getElementById('loaderContainer').classList.add('loader-hidden');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
