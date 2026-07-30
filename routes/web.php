<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ShopController;
use App\Http\Controllers\Dashboard\ShopAdminController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\ProductCategoryController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\SupportController;
use App\Http\Controllers\Dashboard\WalletController;
use App\Http\Controllers\Dashboard\CouponController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HesabPayController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');

// Authentication Routes
Route::prefix('auth')->name('auth.')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    
    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Phone Verification
    Route::get('/verify', [AuthController::class, 'showVerify'])->name('verify');
    Route::post('/verify', [AuthController::class, 'verify'])->name('verify.submit');
    Route::post('/verify/resend', [AuthController::class, 'resendVerificationCode'])->name('verify.resend');
    
    // Password Reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password.submit');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('reset-password-form');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password.submit');
});

// Add a direct 'login' route that points to the same controller
Route::get('/login', [App\Http\Controllers\Auth\AuthController::class, 'showLogin'])->name('login');

// Dashboard Routes (Protected)
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {
    // Main Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    
    // Wallets
    Route::get('/wallets', [DashboardController::class, 'wallets'])->name('wallets');
    
    // Wallet Deposits
    Route::get('/wallets/deposit/afghani', [DashboardController::class, 'depositAfghaniForm'])->name('wallets.deposit.afghani');
    Route::post('/wallets/deposit/afghani', [DashboardController::class, 'depositAfghani'])->name('wallets.deposit.afghani.store');
    Route::get('/wallets/deposit/dollar', [DashboardController::class, 'depositDollarForm'])->name('wallets.deposit.dollar');
    Route::post('/wallets/deposit/dollar', [DashboardController::class, 'depositDollar'])->name('wallets.deposit.dollar.store');
    Route::get('/wallets/deposit/history', [DashboardController::class, 'walletDepositHistory'])->name('wallets.deposit.history');
    
    // Agency Withdrawals
    Route::get('/wallets/withdraw/agency', [DashboardController::class, 'agencyWithdrawalForm'])->name('wallets.withdraw.agency');
    Route::post('/wallets/withdraw/agency', [DashboardController::class, 'storeAgencyWithdrawal'])->name('wallets.withdraw.agency.store');
    Route::get('/wallets/withdraw/agency/history', [DashboardController::class, 'agencyWithdrawalHistory'])->name('wallets.withdraw.agency.history');
    Route::get('/wallets/withdraw/agency/{id}', [DashboardController::class, 'showAgencyWithdrawal'])->name('wallets.withdraw.agency.show');
    
    // Profile
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [DashboardController::class, 'updatePassword'])->name('profile.update-password');
    
    
    // Shop
    Route::prefix('shop')->name('shop.')->group(function () {
        Route::get('/', [ShopController::class, 'index'])->name('index');
        Route::get('/category/{slug}', [ShopController::class, 'category'])->name('category');
        Route::get('/product/{id}', [ShopController::class, 'product'])->name('product');
        
        // Cart and checkout
        Route::get('/cart', [ShopController::class, 'cart'])->name('cart');
        Route::post('/checkout', [ShopController::class, 'checkout'])->name('checkout');
        
        // AJAX cart endpoints
        Route::post('/add-to-cart', [ShopController::class, 'addToCart'])->name('add-to-cart');
        Route::post('/update-cart', [ShopController::class, 'updateCart'])->name('update-cart');
        Route::post('/remove-from-cart', [ShopController::class, 'removeFromCart'])->name('remove-from-cart');
        Route::post('/clear-cart', [ShopController::class, 'clearCart'])->name('clear-cart');
        
        // AJAX coupon endpoints
        Route::post('/apply-coupon', [ShopController::class, 'applyCoupon'])->name('apply-coupon');
        Route::post('/remove-coupon', [ShopController::class, 'removeCoupon'])->name('remove-coupon');
        
        // Orders
        Route::get('/orders', [ShopController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [ShopController::class, 'orderShow'])->name('order.show');
        Route::post('/orders/{id}/cancel', [ShopController::class, 'cancelOrder'])->name('order.cancel');
        
        // Debug endpoint
        Route::get('/debug', [ShopController::class, 'debug'])->name('debug');
        
        // Admin routes - should be protected with admin middleware in a real app
        Route::prefix('admin')->name('admin.')->group(function () {
            // Products management
            Route::get('/', [ShopAdminController::class, 'index'])->name('index');
            Route::get('/create', [ShopAdminController::class, 'create'])->name('create');
            Route::post('/store', [ShopAdminController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [ShopAdminController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [ShopAdminController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [ShopAdminController::class, 'destroy'])->name('destroy');
            
            // Import Products
            Route::get('/import', [App\Http\Controllers\Dashboard\ProductImportController::class, 'index'])->name('import');
            Route::post('/import', [App\Http\Controllers\Dashboard\ProductImportController::class, 'import'])->name('import.process');
            Route::get('/import/sample', [App\Http\Controllers\Dashboard\ProductImportController::class, 'downloadSample'])->name('import.sample');
            
            // Categories management
            Route::prefix('categories')->name('categories.')->group(function () {
                Route::get('/', [ProductCategoryController::class, 'index'])->name('index');
                Route::get('/create', [ProductCategoryController::class, 'create'])->name('create');
                Route::post('/store', [ProductCategoryController::class, 'store'])->name('store');
                Route::get('/edit/{category}', [ProductCategoryController::class, 'edit'])->name('edit');
                Route::put('/update/{category}', [ProductCategoryController::class, 'update'])->name('update');
                Route::delete('/destroy/{category}', [ProductCategoryController::class, 'destroy'])->name('destroy');
                
                // AJAX endpoint for quick category creation
                Route::post('/ajax-store', [ProductCategoryController::class, 'ajaxStore'])->name('ajax-store');
            });
            
            // Orders management
            Route::get('/orders', [ShopAdminController::class, 'orders'])->name('orders');
            Route::get('/orders/{id}', [ShopAdminController::class, 'orderShow'])->name('orders.show');
            Route::put('/orders/{id}', [ShopAdminController::class, 'orderUpdate'])->name('orders.update');
        });
    });
    
    // Courses (User Routes)
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', [App\Http\Controllers\Dashboard\CourseController::class, 'index'])->name('index');
        Route::get('/my-courses', [App\Http\Controllers\Dashboard\CourseController::class, 'myCourses'])->name('my-courses');
        Route::get('/category/{slug}', [App\Http\Controllers\Dashboard\CourseController::class, 'category'])->name('category');
        Route::get('/{slug}', [App\Http\Controllers\Dashboard\CourseController::class, 'show'])->name('show');
        Route::post('/{slug}/enroll', [App\Http\Controllers\Dashboard\CourseController::class, 'enroll'])->name('enroll');
        Route::get('/{slug}/watch/{videoId}', [App\Http\Controllers\Dashboard\CourseController::class, 'watch'])->name('watch');
    });
    
});

// HesabPay Integration Routes
Route::prefix('hesabpay')->name('hesabpay.')->group(function () {
    Route::get('/callback', [App\Http\Controllers\HesabPayController::class, 'callback'])->name('callback');
    Route::post('/webhook', [App\Http\Controllers\HesabPayController::class, 'webhook'])->name('webhook');

	    // Mock payment routes for development/testing
    Route::get('/mock/payment/{tracking_code}', [App\Http\Controllers\HesabPayController::class, 'mockPayment'])->name('mock.payment');
    Route::post('/mock/payment/success/{tracking_code}', [App\Http\Controllers\HesabPayController::class, 'mockPaymentSuccess'])->name('mock.payment.success');
    Route::post('/mock/payment/failure/{tracking_code}', [App\Http\Controllers\HesabPayController::class, 'mockPaymentFailure'])->name('mock.payment.failure');
});

// HesabPay Payment Routes
Route::get('/payment/success', [HesabPayController::class, 'success'])->name('payment.success');
Route::get('/payment/fail', [HesabPayController::class, 'fail'])->name('payment.fail');
Route::get('/payment/callback', [HesabPayController::class, 'callback'])->name('payment.callback');

// Admin Panel Routes
Route::prefix('dashboard/admin')->name('dashboard.admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });
    
    // Support Staff Management
    Route::prefix('supporters')->name('supporters.')->group(function () {
        Route::get('/', [SupportController::class, 'index'])->name('index');
        Route::get('/create', [SupportController::class, 'create'])->name('create');
        Route::post('/store', [SupportController::class, 'store'])->name('store');
        Route::get('/{id}', [SupportController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SupportController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SupportController::class, 'update'])->name('update');
        Route::delete('/{id}', [SupportController::class, 'destroy'])->name('destroy');
    });
    
    // Wallet Management
    Route::prefix('wallets')->name('wallets.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('/{id}/{type}/edit', [WalletController::class, 'edit'])->name('edit');
        Route::put('/{id}/{type}', [WalletController::class, 'update'])->name('update');
        Route::get('/{id}/{type}/transactions', [WalletController::class, 'transactions'])->name('transactions');
    });
    
    
    // Orders Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::put('/{id}', [OrderController::class, 'update'])->name('update');
        Route::put('/{id}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::get('/user/{userId}', [OrderController::class, 'userOrders'])->name('user');
    });
    
    // Products Management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ShopAdminController::class, 'index'])->name('index');
        Route::get('/create', [ShopAdminController::class, 'create'])->name('create');
        Route::post('/store', [ShopAdminController::class, 'store'])->name('store');
        Route::get('/{id}', [ShopAdminController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ShopAdminController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ShopAdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [ShopAdminController::class, 'destroy'])->name('destroy');
    });
    
    // Product Categories
    Route::prefix('product-categories')->name('product-categories.')->group(function () {
        Route::get('/', [ProductCategoryController::class, 'index'])->name('index');
        Route::get('/create', [ProductCategoryController::class, 'create'])->name('create');
        Route::post('/store', [ProductCategoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProductCategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductCategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductCategoryController::class, 'destroy'])->name('destroy');
    });
    
    // Coupons Management
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [CouponController::class, 'index'])->name('index');
        Route::get('/create', [CouponController::class, 'create'])->name('create');
        Route::post('/store', [CouponController::class, 'store'])->name('store');
        Route::get('/{id}', [CouponController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CouponController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CouponController::class, 'update'])->name('update');
        Route::delete('/{id}', [CouponController::class, 'destroy'])->name('destroy');
    });
    
    
    // Agency Management
    Route::prefix('agencies')->name('agencies.')->group(function () {
        Route::get('/', [AdminController::class, 'agencies'])->name('index');
        Route::get('/create', [AdminController::class, 'createAgency'])->name('create');
        Route::post('/store', [AdminController::class, 'storeAgency'])->name('store');
        Route::get('/edit/{id}', [AdminController::class, 'editAgency'])->name('edit');
        Route::put('/update/{id}', [AdminController::class, 'updateAgency'])->name('update');
        Route::delete('/destroy/{id}', [AdminController::class, 'destroyAgency'])->name('destroy');
    });
    
    // System Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    
    // Agency Withdrawals
    Route::get('/agency-withdrawals', [AdminController::class, 'agencyWithdrawals'])->name('agency-withdrawals');
    Route::get('/agency-withdrawals/{id}', [AdminController::class, 'showAgencyWithdrawal'])->name('agency-withdrawals.show');
    Route::post('/agency-withdrawals/{id}/update', [AdminController::class, 'updateAgencyWithdrawalStatus'])->name('agency-withdrawals.update');
    
    // HesabPay Management
    Route::prefix('hesabpay')->name('hesabpay.')->group(function () {
        Route::get('/', [App\Http\Controllers\Dashboard\HesabPayAdminController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Dashboard\HesabPayAdminController::class, 'show'])->name('show');
        Route::post('/{id}/complete', [App\Http\Controllers\Dashboard\HesabPayAdminController::class, 'markCompleted'])->name('complete');
        Route::post('/{id}/fail', [App\Http\Controllers\Dashboard\HesabPayAdminController::class, 'markFailed'])->name('fail');
    });
    
    // Courses Management (Admin)
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', [App\Http\Controllers\Dashboard\CourseAdminController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Dashboard\CourseAdminController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Dashboard\CourseAdminController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Dashboard\CourseAdminController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\Dashboard\CourseAdminController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Dashboard\CourseAdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Dashboard\CourseAdminController::class, 'destroy'])->name('destroy');
        
        // Videos
        Route::post('/{courseId}/videos', [App\Http\Controllers\Dashboard\CourseAdminController::class, 'addVideo'])->name('videos.add');
        Route::delete('/{courseId}/videos/{videoId}', [App\Http\Controllers\Dashboard\CourseAdminController::class, 'deleteVideo'])->name('videos.delete');
        
        // Import
        Route::get('/import/csv', [App\Http\Controllers\Dashboard\CourseImportController::class, 'index'])->name('import');
        Route::post('/import/csv', [App\Http\Controllers\Dashboard\CourseImportController::class, 'import'])->name('import.process');
        Route::get('/import/sample', [App\Http\Controllers\Dashboard\CourseImportController::class, 'downloadSample'])->name('import.sample');
    });
});

// Fallback route
Route::fallback(function () {
    return view('errors.404');
});
