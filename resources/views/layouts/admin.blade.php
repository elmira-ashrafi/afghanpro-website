<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل مدیریت - {{ config('app.name') }} | @yield('title', 'مدیریت سیستم')</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Google Vazirmatn Font (Persian) -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #6200ea;
            --secondary-color: #757575;
            --accent-color: #ff6d00;
            --light-color: #f8f9fa;
            --dark-color: #212121;
            --sidebar-width: 260px;
        }
        
        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: #f5f8fa;
            color: #333;
            min-height: 100vh;
        }
        
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            background-color: var(--dark-color);
            width: var(--sidebar-width);
            color: #fff;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-brand img {
            height: 40px;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 1rem;
            border: 3px solid var(--accent-color);
        }
        
        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .nav-item {
            width: 100%;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            border-right: 4px solid var(--accent-color);
        }
        
        .nav-link i {
            margin-left: 0.75rem;
            font-size: 1.2rem;
        }
        
        .main-content {
            margin-right: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: all 0.3s;
            padding: 1.5rem;
        }
        
        .main-header {
            margin-bottom: 2rem;
        }
        
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .stats-card {
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        
        .stats-card.purple {
            background: linear-gradient(135deg, #6200ea, #b388ff);
        }
        
        .stats-card.orange {
            background: linear-gradient(135deg, #ff6d00, #ffab40);
        }
        
        .stats-card.green {
            background: linear-gradient(135deg, #00c853, #69f0ae);
        }
        
        .stats-card.blue {
            background: linear-gradient(135deg, #2962ff, #82b1ff);
        }
        
        .stats-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
        }
        
        .stats-card .stat-icon {
            position: absolute;
            left: 1.5rem;
            bottom: 1.5rem;
            font-size: 3rem;
            opacity: 0.2;
        }
        
        /* Loader Styles */
        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.3s;
        }
        
        .loader {
            width: 50px;
            aspect-ratio: 1;
            display: grid;
            border: 4px solid #0000;
            border-radius: 50%;
            border-right-color: #b388ff;
            animation: l15 1s infinite linear;
        }
        
        .loader::before,
        .loader::after {    
            content: "";
            grid-area: 1/1;
            margin: 2px;
            border: inherit;
            border-radius: 50%;
            animation: l15 2s infinite;
        }
        
        .loader::after {
            margin: 8px;
            animation-duration: 3s;
        }
        
        @keyframes l15{ 
            100%{transform: rotate(1turn)}
        }
        
        .loader-hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        @media (max-width: 992px) {
            .sidebar {
                margin-right: -260px;
            }
            
            .sidebar.active {
                margin-right: 0;
            }
            
            .main-content {
                width: 100%;
                margin-right: 0;
            }
            
            .main-content.active {
                margin-right: var(--sidebar-width);
                width: calc(100% - var(--sidebar-width));
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Loader -->
    <div class="loader-container" id="loaderContainer">
        <div class="loader"></div>
    </div>

    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('dashboard.admin.dashboard') }}">
                    <img src="{{ asset('images/logo-white.png') }}" alt="{{ config('app.name') }} Admin">
                    <div class="mt-2 small">پنل مدیریت</div>
                </a>
            </div>
            
            <div class="sidebar-header">
                <div class="avatar">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}+{{ Auth::user()->lastname }}&background=6200EA&color=fff" alt="{{ Auth::user()->name }}">
                </div>
                <h5 class="mb-0">{{ Auth::user()->name }} {{ Auth::user()->lastname }}</h5>
                <small class="badge bg-danger">مدیر سیستم</small>
            </div>
            
            <div class="sidebar-menu">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('dashboard.admin.dashboard') }}" class="nav-link {{ request()->routeIs('dashboard.admin.dashboard') ? 'active' : '' }}">
                            <i class="ri-dashboard-line"></i>
                            <span>داشبورد</span>
                        </a>
                    </li>
                    
                    <div class="py-2 px-3 mt-2 mb-2 text-uppercase small text-muted">
                        کاربران و مدیریت
                    </div>
                    
                    <li class="nav-item">
                        <a href="{{ route('dashboard.admin.users.index') }}" class="nav-link {{ request()->routeIs('dashboard.admin.users.*') ? 'active' : '' }}">
                            <i class="ri-user-line"></i>
                            <span>کاربران</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard.admin.supporters.index') }}" class="nav-link {{ request()->routeIs('dashboard.admin.supporters.*') ? 'active' : '' }}">
                            <i class="ri-team-line"></i>
                            <span>پشتیبان‌ها</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard.admin.agencies.index') }}" class="nav-link {{ request()->routeIs('dashboard.admin.agencies.*') ? 'active' : '' }}">
                            <i class="ri-map-pin-line"></i>
                            <span>نمایندگی‌ها</span>
                        </a>
                    </li>
                    
                    <div class="py-2 px-3 mt-2 mb-2 text-uppercase small text-muted">
                        مالی
                    </div>
                    
                    <li class="nav-item">
                        <a href="{{ route('dashboard.admin.wallets.index') }}" class="nav-link {{ request()->routeIs('dashboard.admin.wallets.*') ? 'active' : '' }}">
                            <i class="ri-wallet-3-line"></i>
                            <span>کیف پول‌ها</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard.admin.agency-withdrawals') }}" class="nav-link {{ request()->routeIs('dashboard.admin.agency-withdrawals*') ? 'active' : '' }}">
                            <i class="ri-bank-card-line"></i>
                            <span>برداشت‌های نقدی نمایندگی</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard.admin.hesabpay.index') }}" class="nav-link {{ request()->routeIs('dashboard.admin.hesabpay.*') ? 'active' : '' }}">
                            <i class="ri-secure-payment-line"></i>
                            <span>پرداخت‌های حساب پی</span>
                            @if(isset($pendingHesabPayPayments) && $pendingHesabPayPayments > 0)
                                <span class="badge bg-danger ms-auto">{{ $pendingHesabPayPayments }}</span>
                            @endif
                        </a>
                    </li>
                    
                    <div class="py-2 px-3 mt-2 mb-2 text-uppercase small text-muted">
                        فروشگاه
                    </div>
                    
                    <li class="nav-item">
                        <a href="{{ route('dashboard.admin.orders.index') }}" class="nav-link {{ request()->routeIs('dashboard.admin.orders.*') ? 'active' : '' }}">
                            <i class="ri-shopping-bag-3-line"></i>
                            <span>سفارش‌ها</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard.admin.products.index') }}" class="nav-link {{ request()->routeIs('dashboard.admin.products.*') ? 'active' : '' }}">
                            <i class="ri-archive-line"></i>
                            <span>محصولات</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard.admin.product-categories.index') }}" class="nav-link {{ request()->routeIs('dashboard.admin.product-categories.*') ? 'active' : '' }}">
                            <i class="ri-folders-line"></i>
                            <span>دسته‌بندی‌ها</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard.admin.coupons.index') }}" class="nav-link {{ request()->routeIs('dashboard.admin.coupons.*') ? 'active' : '' }}">
                            <i class="ri-price-tag-3-line"></i>
                            <span>کوپن‌های تخفیف</span>
                        </a>
                    </li>
                    
                    <div class="py-2 px-3 mt-2 mb-2 text-uppercase small text-muted">
                        آموزش
                    </div>
                    
                    <li class="nav-item">
                        <a href="{{ route('dashboard.admin.courses.index') }}" class="nav-link {{ request()->routeIs('dashboard.admin.courses.index') || request()->routeIs('dashboard.admin.courses.show') || request()->routeIs('dashboard.admin.courses.edit') ? 'active' : '' }}">
                            <i class="ri-graduation-cap-line"></i>
                            <span>دوره‌های آموزشی</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard.admin.courses.import') }}" class="nav-link {{ request()->routeIs('dashboard.admin.courses.import*') ? 'active' : '' }}">
                            <i class="ri-file-upload-line"></i>
                            <span>وارد کردن دوره‌ها</span>
                        </a>
                    </li>
                    
                    <div class="py-2 px-3 mt-2 mb-2 text-uppercase small text-muted">
                        سیستم
                    </div>
                    
                    <li class="nav-item">
                        <a href="{{ route('dashboard.admin.settings') }}" class="nav-link {{ request()->routeIs('dashboard.admin.settings') ? 'active' : '' }}">
                            <i class="ri-settings-5-line"></i>
                            <span>تنظیمات</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('auth.logout') }}" class="nav-link"
                           onclick="event.preventDefault();
                                   document.getElementById('admin-logout-form').submit();">
                            <i class="ri-logout-box-line"></i>
                            <span>خروج</span>
                        </a>
                        
                        <form id="admin-logout-form" action="{{ route('auth.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="container-fluid">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center main-header">
                    <button type="button" id="sidebarToggle" class="btn d-lg-none">
                        <i class="ri-menu-line"></i>
                    </button>
                    
                    <div>
                        <h4 class="mb-0">@yield('page-title', 'داشبورد مدیریت')</h4>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn position-relative" type="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-notification-3-line"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    5
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                                <li><h6 class="dropdown-header">اعلان‌ها</h6></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard.admin.orders.index') }}">سفارش جدید ثبت شد</a></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard.admin.users.index') }}">ثبت‌نام کاربر جدید</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="{{ route('dashboard.admin.dashboard') }}">مشاهده همه</a></li>
                            </ul>
                        </div>
                        
                        <div class="dropdown ms-3">
                            <button class="btn" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="me-2 d-none d-md-inline">{{ Auth::user()->name }}</span>
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}+{{ Auth::user()->lastname }}&background=6200EA&color=fff" alt="{{ Auth::user()->name }}" class="rounded-circle" width="32" height="32">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('dashboard.profile') }}">پروفایل</a></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard.admin.settings') }}">تنظیمات</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('auth.logout') }}"
                                       onclick="event.preventDefault();
                                               document.getElementById('header-admin-logout-form').submit();">
                                        خروج
                                    </a>
                                    
                                    <form id="header-admin-logout-form" action="{{ route('auth.logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Main Content -->
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar on mobile
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
                document.querySelector('.main-content').classList.toggle('active');
            });
            
            // Hide loader when page is loaded
            setTimeout(function() {
                document.getElementById('loaderContainer').classList.add('loader-hidden');
            }, 500);
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