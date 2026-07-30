<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>@yield('title') - افغان پرو</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="{{ asset('logo/Favicon-Color.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/Favicon-Color.webp') }}">
    
    <!-- Bootstrap RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    
    <!-- Remixicon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <!-- Google Vazirmatn Font (Persian) -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Custom styles -->
    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #dbeafe;
            --primary-dark: #1e40af;
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #0ea5e9;
            --dark: #0f172a;
            --light: #f8fafc;
            --border-color: #e2e8f0;
            --card-bg: #ffffff;
            --body-bg: #f1f5f9;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --header-height: 70px;
            --mobile-nav-height: 60px;
            --border-radius: 0.5rem;
            --transition-speed: 0.3s;
            --box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --box-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --box-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Vazirmatn', Tahoma, Arial, sans-serif;
            background-color: var(--body-bg);
            color: var(--text-primary);
            overflow-x: hidden;
            min-height: 100vh;
            position: relative;
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--body-bg);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--secondary);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }
        
        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        a {
            color: var(--primary);
            text-decoration: none;
            transition: all var(--transition-speed) ease;
        }
        
        a:hover {
            color: var(--primary-dark);
        }
        
        /* Layout Structure */
        .app-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .app-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--card-bg);
            box-shadow: var(--box-shadow);
            z-index: 1040;
            transition: transform var(--transition-speed) ease;
            display: flex;
            flex-direction: column;
        }
        
        .app-sidebar.collapsed {
            transform: translateX(100%);
        }
        
        .sidebar-header {
            height: var(--header-height);
            padding: 0 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
        }
        
        .sidebar-header .logo {
            height: 36px;
        }
        
        .sidebar-header .close-sidebar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            border: none;
        }
        
        .sidebar-header .close-sidebar:hover {
            background-color: var(--primary);
            color: white;
        }
        
        .sidebar-content {
            flex-grow: 1;
            padding: 1rem 0.75rem;
            overflow-y: auto;
        }
        
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-nav .nav-item {
            margin-bottom: 0.25rem;
        }
        
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: var(--text-secondary);
            border-radius: var(--border-radius);
            transition: all var(--transition-speed) ease;
        }
        
        .sidebar-nav .nav-link:hover {
            background-color: rgba(0, 0, 0, 0.03);
            color: var(--primary);
        }
        
        .sidebar-nav .nav-link.active {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 500;
        }
        
        .sidebar-nav .nav-link i {
            font-size: 1.25rem;
            margin-left: 0.875rem;
            width: 1.5rem;
            text-align: center;
        }
        
        .sidebar-nav .nav-divider {
            height: 1px;
            margin: 1rem 0;
            background-color: var(--border-color);
        }
        
        /* Main Content */
        .app-main {
            flex: 1;
            margin-right: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin var(--transition-speed) ease;
        }
        
        /* Header */
        .app-header {
            height: var(--header-height);
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: var(--box-shadow);
        }
        
        .app-header .menu-toggle {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-light);
            color: var(--primary);
            border-radius: 50%;
            cursor: pointer;
            margin-left: 1rem;
            border: none;
            display: none;
        }
        
        .app-header .menu-toggle:hover {
            background-color: var(--primary);
            color: white;
        }
        
        .app-header .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-primary);
        }
        
        .app-header .header-actions {
            margin-right: auto;
            display: flex;
            align-items: center;
        }
        
        .header-action-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: transparent;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            position: relative;
            margin-left: 0.5rem;
            border: none;
        }
        
        .header-action-btn:hover {
            background-color: var(--primary-light);
            color: var(--primary);
        }
        
        .header-action-btn .badge {
            position: absolute;
            top: 0;
            left: 0;
            transform: translate(-25%, -25%);
            padding: 0.25rem 0.5rem;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            background-color: var(--danger);
            color: white;
            border-radius: 10px;
            border: 2px solid var(--card-bg);
        }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            cursor: pointer;
            margin-right: 0.5rem;
        }
        
        .user-dropdown img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 0.75rem;
            object-fit: cover;
            border: 2px solid var(--border-color);
        }
        
        .user-dropdown .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-dropdown .user-info .user-name {
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.9rem;
        }
        
        .user-dropdown .user-info .user-role {
            color: var(--text-secondary);
            font-size: 0.8rem;
        }
        
        /* Content Area */
        .app-content {
            flex: 1;
            padding: 1.5rem;
            transition: all var(--transition-speed) ease;
        }
        
        /* Cards & UI Components */
        .app-card {
            background-color: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: box-shadow var(--transition-speed) ease;
            border: none;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        
        .app-card:hover {
            box-shadow: var(--box-shadow-md);
        }
        
        .app-card .card-header {
            padding: 1rem 1.25rem;
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
        }
        
        .app-card .card-body {
            padding: 1.25rem;
        }
        
        .app-card .card-footer {
            padding: 1rem 1.25rem;
            background-color: var(--light);
            border-top: 1px solid var(--border-color);
        }
        
        /* Wallet Card */
        .wallet-card {
            position: relative;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1rem;
            color: white;
            overflow: hidden;
            box-shadow: var(--box-shadow-md);
            height: 100%;
            background-size: 400% 400%;
            animation: gradientAnimation 15s ease infinite;
        }
        
        .wallet-card.afn {
            background: linear-gradient(45deg, #1d4ed8, #3b82f6, #60a5fa);
        }
        
        .wallet-card.usd {
            background: linear-gradient(45deg, #7e22ce, #8b5cf6, #a78bfa);
        }
        
        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .wallet-card .wallet-icon {
            position: absolute;
            bottom: -15px;
            left: -15px;
            font-size: 7rem;
            opacity: 0.15;
        }
        
        /* Buttons */
        .btn {
            border-radius: var(--border-radius);
            transition: all var(--transition-speed) ease;
            padding: 0.375rem 1rem;
            font-weight: 500;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-light {
            background-color: var(--light);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        .btn-light:hover {
            background-color: var(--border-color);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        .btn-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border-radius: 50%;
        }
        
        /* Dropdowns */
        .dropdown-menu {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow-lg);
            padding: 0.5rem 0;
            min-width: 200px;
            margin-top: 0.5rem;
        }
        
        .dropdown-menu-lg {
            min-width: 300px;
        }
        
        .dropdown-header {
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
        }
        
        .dropdown-item {
            padding: 0.625rem 1.25rem;
            color: var(--text-secondary);
            transition: all var(--transition-speed) ease;
        }
        
        .dropdown-item:hover {
            background-color: var(--primary-light);
            color: var(--primary);
        }
        
        .dropdown-item i {
            margin-left: 0.5rem;
            font-size: 1.1rem;
        }
        
        .dropdown-divider {
            border-top: 1px solid var(--border-color);
            margin: 0.5rem 0;
        }
        
        /* Loader */
        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity var(--transition-speed) ease;
        }
        
        .loader {
            width: 48px;
            height: 48px;
            border: 3px solid var(--primary-light);
            border-radius: 50%;
            border-top: 3px solid var(--primary);
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
        
        .loader-hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 1030;
            display: none;
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }
        
        .overlay.active {
            display: block;
        }
        
        /* Mobile Navigation */
        .mobile-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--mobile-nav-height);
            background-color: var(--card-bg);
            box-shadow: 0 -1px 10px rgba(0, 0, 0, 0.1);
            z-index: 1025;
            display: none;
            padding: 0 1rem;
        }
        
        .mobile-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            padding: 0.5rem;
            transition: all var(--transition-speed) ease;
        }
        
        .mobile-nav-item.active {
            color: var(--primary);
        }
        
        .mobile-nav-item i {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }
        
        .mobile-nav-item span {
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        /* Alert & Notifications */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--box-shadow);
        }
        
        /* Tables */
        .table-responsive {
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 600;
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            border-top: none;
            background-color: var(--light);
        }
        
        .table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            color: var(--text-secondary);
        }
        
        .table tr:hover td {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            :root {
                --sidebar-width: 240px;
            }
            
            .app-sidebar {
                transform: translateX(100%);
            }
            
            .app-sidebar.active {
                transform: translateX(0);
            }
            
            .app-main {
                margin-right: 0;
            }
            
            .app-header .menu-toggle {
                display: flex;
            }
            
            .user-dropdown .user-info {
                display: none;
            }
        }
        
        @media (max-width: 767.98px) {
            :root {
                --sidebar-width: 100%;
            }
            
            .app-content {
                padding: 1rem;
            }
            
            .mobile-nav {
                display: flex;
            }
            
            .app-content {
                padding-bottom: calc(1rem + var(--mobile-nav-height));
            }
            
            .app-card {
                margin-bottom: 1rem;
            }
            
            .app-card .card-header,
            .app-card .card-body,
            .app-card .card-footer {
                padding: 1rem;
            }
            
            .table th, 
            .table td {
                padding: 0.625rem 0.75rem;
            }
        }
        
        @media (max-width: 575.98px) {
            .app-header .page-title {
                font-size: 1.1rem;
            }
            
            .header-action-btn {
                width: 36px;
                height: 36px;
            }
            
            .user-dropdown img {
                width: 36px;
                height: 36px;
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
    
    <!-- Overlay for mobile sidebar -->
    <div class="overlay" id="overlay"></div>
    
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="app-sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard.index') }}" class="d-flex align-items-center">
                    <img src="{{ asset('logo/Logo-color.webp') }}" alt="AfghanPro" class="logo">
                </a>
                <button class="close-sidebar d-lg-none" id="closeSidebar">
                    <i class="ri-arrow-right-line"></i>
                </button>
            </div>
            
            <div class="sidebar-content">
                <ul class="sidebar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}" href="{{ route('dashboard.index') }}">
                            <i class="ri-dashboard-line"></i>
                            <span>داشبورد</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.wallets') ? 'active' : '' }}" href="{{ route('dashboard.wallets') }}">
                            <i class="ri-wallet-3-line"></i>
                            <span>کیف پول‌ها</span>
                        </a>
                    </li>
                    
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.shop.*') ? 'active' : '' }}" href="{{ route('dashboard.shop.index') }}">
                            <i class="ri-store-2-line"></i>
                            <span>خرید اکانت‌های پرمیوم</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.courses.*') ? 'active' : '' }}" href="{{ route('dashboard.courses.index') }}">
                            <i class="ri-graduation-cap-line"></i>
                            <span>دوره‌های آموزشی</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.courses.my-courses') ? 'active' : '' }}" href="{{ route('dashboard.courses.my-courses') }}">
                            <i class="ri-book-open-line"></i>
                            <span>دوره‌های من</span>
                        </a>
                    </li>
                    
                    <div class="nav-divider"></div>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.profile') ? 'active' : '' }}" href="{{ route('dashboard.profile') }}">
                            <i class="ri-user-settings-line"></i>
                            <span>پروفایل کاربری</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <form action="{{ route('auth.logout') }}" method="POST" id="logout-form">
                            @csrf
                            <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ri-logout-box-line"></i>
                                <span>خروج از حساب</span>
                            </a>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="app-main">
            <!-- Header -->
            <header class="app-header">
                <button class="menu-toggle" id="menuToggle">
                    <i class="ri-menu-line"></i>
                </button>
                
                <h1 class="page-title">@yield('page-title', 'داشبورد')</h1>
                
                <div class="header-actions">
                    <!-- Notifications -->
                    <div class="dropdown">
                        <button class="header-action-btn" type="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-notification-3-line"></i>
                            <span class="badge">3</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg" aria-labelledby="notificationsDropdown">
                            <div class="dropdown-header">اعلان‌ها</div>
                            <div class="dropdown-body" style="max-height: 300px; overflow-y: auto;">
                                <a href="#" class="dropdown-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm bg-primary-light text-primary rounded-circle">
                                                <i class="ri-exchange-dollar-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-medium">درخواست افزایش اعتبار تایید شد</div>
                                            <div class="small text-muted">۲ ساعت پیش</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm bg-success-light text-success rounded-circle">
                                                <i class="ri-wallet-3-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-medium">واریز موفق به کیف پول</div>
                                            <div class="small text-muted">۳ ساعت پیش</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm bg-info-light text-info rounded-circle">
                                                <i class="ri-shopping-cart-2-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-medium">سفارش شما ارسال شد</div>
                                            <div class="small text-muted">دیروز</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-footer">
                                <a href="#" class="text-center d-block py-2 small text-primary">مشاهده همه اعلان‌ها</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shopping Cart -->
                    <div class="dropdown">
                        <button class="header-action-btn" type="button" id="cartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-shopping-cart-2-line"></i>
                            <span class="badge">{{ count(session('cart', [])) }}</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg" aria-labelledby="cartDropdown">
                            <div class="dropdown-header">سبد خرید</div>
                            <div class="dropdown-body" style="max-height: 300px; overflow-y: auto;">
                                @if(count(session('cart', [])) > 0)
                                    @foreach(session('cart', []) as $key => $item)
                                        <div class="d-flex align-items-center p-3 border-bottom">
                                            <div class="flex-shrink-0">
                                                @if(isset($item['product_thumbnail']))
                                                    @if(filter_var($item['product_thumbnail'], FILTER_VALIDATE_URL))
                                                        <img src="{{ $item['product_thumbnail'] }}" alt="{{ $item['product_name'] }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('storage/' . $item['product_thumbnail']) }}" alt="{{ $item['product_name'] }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @endif
                                                @else
                                                    <img src="{{ asset('storage/images/no-image.png') }}" alt="{{ $item['product_name'] }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="fw-medium">{{ $item['product_name'] }}</div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-primary">{{ number_format($item['price']) }} افغانی</span>
                                                    <span class="badge bg-light text-dark">{{ $item['quantity'] }} عدد</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-4 text-muted">
                                        <i class="ri-shopping-basket-line d-block mb-2" style="font-size: 2rem;"></i>
                                        سبد خرید شما خالی است
                                    </div>
                                @endif
                            </div>
                            <div class="dropdown-footer">
                                <a href="{{ route('dashboard.shop.cart') }}" class="btn btn-primary w-100">مشاهده سبد خرید</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <div class="user-dropdown" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}+{{ Auth::user()->lastname }}&background=2563eb&color=fff" alt="{{ Auth::user()->name }}">
                            <div class="user-info">
                                <div class="user-name">{{ Auth::user()->name }} {{ Auth::user()->lastname }}</div>
                                <div class="user-role">کاربر</div>
                            </div>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li class="dropdown-header d-md-none py-2 px-3">
                                <div class="fw-medium">{{ Auth::user()->name }} {{ Auth::user()->lastname }}</div>
                                <div class="small text-muted">{{ Auth::user()->phone }}</div>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('dashboard.profile') }}"><i class="ri-user-settings-line"></i>پروفایل کاربری</a></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard.wallets') }}"><i class="ri-wallet-3-line"></i>کیف پول‌ها</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('auth.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="ri-logout-box-line"></i>خروج</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="app-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-nav">
        <a href="{{ route('dashboard.index') }}" class="mobile-nav-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
            <i class="ri-dashboard-line"></i>
            <span>داشبورد</span>
        </a>
        <a href="{{ route('dashboard.wallets') }}" class="mobile-nav-item {{ request()->routeIs('dashboard.wallets') ? 'active' : '' }}">
            <i class="ri-wallet-3-line"></i>
            <span>کیف پول</span>
        </a>
        <a href="{{ route('dashboard.shop.index') }}" class="mobile-nav-item {{ request()->routeIs('dashboard.shop.*') ? 'active' : '' }}">
            <i class="ri-store-2-line"></i>
            <span>فروشگاه</span>
        </a>
        <a href="{{ route('dashboard.profile') }}" class="mobile-nav-item {{ request()->routeIs('dashboard.profile') ? 'active' : '' }}">
            <i class="ri-user-settings-line"></i>
            <span>پروفایل</span>
        </a>
    </nav>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle for mobile
            const menuToggle = document.getElementById('menuToggle');
            const closeSidebar = document.getElementById('closeSidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.add('active');
                    overlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            }
            
            if (closeSidebar) {
                closeSidebar.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
            
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
            
            // Hide loader when page is loaded
            setTimeout(function() {
                document.getElementById('loaderContainer').classList.add('loader-hidden');
            }, 500);
            
            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize Bootstrap popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
            
            // Handle mobile flash messages from sessionStorage
            handleMobileFlashMessages();
        });
        
        // Function to handle mobile flash messages
        function handleMobileFlashMessages() {
            // Check sessionStorage first (legacy support)
            const successMessage = sessionStorage.getItem('flash_success');
            if (successMessage) {
                showAlert('success', successMessage);
                sessionStorage.removeItem('flash_success');
            }
            
            const errorMessage = sessionStorage.getItem('flash_error');
            if (errorMessage) {
                showAlert('error', errorMessage);
                sessionStorage.removeItem('flash_error');
            }
            
            // Check localStorage for HesabPay messages (new method)
            try {
                const hesabpaySuccess = localStorage.getItem('hesabpay_flash_success');
                const hesabpayError = localStorage.getItem('hesabpay_flash_error');
                const timestamp = localStorage.getItem('hesabpay_flash_timestamp');
                
                // Only show messages if they're fresh (less than 30 seconds old)
                const now = Date.now();
                const messageAge = timestamp ? (now - parseInt(timestamp)) : 0;
                
                if (messageAge < 30000) { // 30 seconds
                    if (hesabpaySuccess) {
                        showAlert('success', hesabpaySuccess);
                        localStorage.removeItem('hesabpay_flash_success');
                    }
                    
                    if (hesabpayError) {
                        showAlert('error', hesabpayError);
                        localStorage.removeItem('hesabpay_flash_error');
                    }
                }
                
                // Clean up old timestamp
                if (timestamp) {
                    localStorage.removeItem('hesabpay_flash_timestamp');
                }
            } catch(e) {
                console.log('localStorage not available for flash messages');
            }
        }
        
        // Function to show alert messages
        function showAlert(type, message) {
            const alertType = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'ri-checkbox-circle-line' : 'ri-error-warning-line';
            
            const alertHtml = `
                <div class="alert ${alertType} alert-dismissible fade show">
                    <i class="${icon} me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            // Find content area and prepend alert
            const contentArea = document.querySelector('.app-content');
            if (contentArea) {
                contentArea.insertAdjacentHTML('afterbegin', alertHtml);
                
                // Auto-hide after 5 seconds
                const newAlert = contentArea.querySelector('.alert');
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(newAlert);
                    bsAlert.close();
                }, 5000);
            }
        }
        
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