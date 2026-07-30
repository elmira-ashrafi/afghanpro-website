<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AfghanWallet;
use App\Models\Agency;
use App\Models\Coupon;
use App\Models\DollarWallet;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    /**
     * Shop index page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $categories = ProductCategory::with('children')
                        ->where('is_active', true)
                        ->orderBy('name')
                        ->get();
        
        // Create a simple approach for price sorting that avoids complex SQL
        if ($request->has('sort') && in_array($request->input('sort'), ['price-low', 'price-high'])) {
            $sort = $request->input('sort');
            
            // Get all products with their variations
            $allProducts = Product::with(['categories', 'variations'])
                ->where('status', 'active')
                ->get();
            
            // Handle search
            if ($request->has('search')) {
                $search = $request->input('search');
                $allProducts = $allProducts->filter(function($product) use ($search) {
                    return str_contains(strtolower($product->name), strtolower($search)) ||
                           str_contains(strtolower($product->short_description), strtolower($search)) ||
                           str_contains(strtolower($product->description), strtolower($search));
                });
            }
            
            // Calculate min/max price for each product
            $productsWithPrices = $allProducts->map(function($product) {
                $variations = $product->variations;
                $inStockVariations = $variations->filter(function($variation) {
                    return $variation->stock > 0 || $variation->stock == -1;
                });
                
                if ($inStockVariations->isEmpty()) {
                    $minPrice = PHP_INT_MAX;
                    $maxPrice = 0;
                } else {
                    $minPrice = $inStockVariations->min('price') ?? PHP_INT_MAX;
                    $maxPrice = $inStockVariations->max('price') ?? 0;
                }
                
                return [
                    'product' => $product,
                    'min_price' => $minPrice,
                    'max_price' => $maxPrice
                ];
            });
            
            // Sort the products by price
            if ($sort === 'price-low') {
                $sortedProducts = $productsWithPrices->sortBy('min_price');
            } else {
                $sortedProducts = $productsWithPrices->sortByDesc('max_price');
            }
            
            // Extract just the products in the sorted order
            $sortedProductIds = $sortedProducts->pluck('product.id')->toArray();
            
            // Filter to the current page
            $page = $request->input('page', 1);
            $perPage = 12;
            $offset = ($page - 1) * $perPage;
            
            // Get just the IDs for the current page
            $paginatedIds = array_slice($sortedProductIds, $offset, $perPage);
            
            // Create a paginator manually
            $items = [];
            foreach ($paginatedIds as $id) {
                $items[] = $allProducts->firstWhere('id', $id);
            }
            
            $products = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                count($sortedProductIds),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            // No price sorting - use normal query builder approach
            $query = Product::with(['categories', 'variations'])
                ->where('status', 'active');
            
            // Handle search
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('short_description', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }
            
            // Default sorting
            $query->orderBy('created_at', 'desc');
            
            $products = $query->paginate(12);
        }
        
        // Make sure pagination preserves parameters
        if ($request->has('search')) {
            $products->appends(['search' => $request->input('search')]);
        }
        
        if ($request->has('sort')) {
            $products->appends(['sort' => $request->input('sort')]);
        }
        
        return view('dashboard.shop.index', compact('user', 'categories', 'products'));
    }
    
    /**
     * Shop products by category
     */
    public function category($slug, Request $request)
    {
        $user = Auth::user();
        $categories = ProductCategory::with('children')
                        ->where('is_active', true)
                        ->orderBy('name')
                        ->get();
                        
        $category = ProductCategory::where('slug', $slug)->firstOrFail();
        
        // Get all category IDs including children using the helper method
        $categoryIds = $category->getAllChildIds();
        
        // Create a simple approach for price sorting that avoids complex SQL
        if ($request->has('sort') && in_array($request->input('sort'), ['price-low', 'price-high'])) {
            $sort = $request->input('sort');
            
            // Get all products with their min/max prices
            $allProducts = Product::with(['categories', 'variations'])
                ->whereHas('categories', function($q) use ($categoryIds) {
                    $q->whereIn('product_categories.id', $categoryIds);
                })
                ->where('status', 'active')
                ->get();
            
            // Calculate min/max price for each product
            $productsWithPrices = $allProducts->map(function($product) {
                $variations = $product->variations;
                $inStockVariations = $variations->filter(function($variation) {
                    return $variation->stock > 0 || $variation->stock == -1;
                });
                
                if ($inStockVariations->isEmpty()) {
                    $minPrice = PHP_INT_MAX;
                    $maxPrice = 0;
                } else {
                    $minPrice = $inStockVariations->min('price') ?? PHP_INT_MAX;
                    $maxPrice = $inStockVariations->max('price') ?? 0;
                }
                
                return [
                    'product' => $product,
                    'min_price' => $minPrice,
                    'max_price' => $maxPrice
                ];
            });
            
            // Sort the products by price
            if ($sort === 'price-low') {
                $sortedProducts = $productsWithPrices->sortBy('min_price');
            } else {
                $sortedProducts = $productsWithPrices->sortByDesc('max_price');
            }
            
            // Extract just the products in the sorted order
            $sortedProductIds = $sortedProducts->pluck('product.id')->toArray();
            
            // Filter to the current page
            $page = $request->input('page', 1);
            $perPage = 12;
            $offset = ($page - 1) * $perPage;
            
            // Get just the IDs for the current page
            $paginatedIds = array_slice($sortedProductIds, $offset, $perPage);
            
            // Create a paginator manually
            $items = [];
            foreach ($paginatedIds as $id) {
                $items[] = $allProducts->firstWhere('id', $id);
            }
            
            $products = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                count($sortedProductIds),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            // No price sorting - use normal query builder approach
            $query = Product::with(['categories', 'variations'])
                ->whereHas('categories', function($q) use ($categoryIds) {
                    $q->whereIn('product_categories.id', $categoryIds);
                })
                ->where('status', 'active');
            
            // Handle search
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('short_description', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }
            
            // Default sorting
            $query->orderBy('created_at', 'desc');
            
            $products = $query->paginate(12);
        }
        
        // Make sure pagination preserves parameters
        if ($request->has('search')) {
            $products->appends(['search' => $request->input('search')]);
        }
        
        if ($request->has('sort')) {
            $products->appends(['sort' => $request->input('sort')]);
        }
        
        return view('dashboard.shop.category', compact('user', 'categories', 'category', 'products'));
    }
    
    /**
     * Shop product page
     */
    public function product($id)
    {
        $user = Auth::user();
        $afghanWallet = AfghanWallet::where('user_id', $user->id)->firstOrNew(['user_id' => $user->id]);
        
        $product = Product::with(['gallery', 'attributes', 'variations', 'categories'])
                    ->where('status', 'active')
                    ->findOrFail($id);
        
        // Get agencies for payment
        $agencies = Agency::where('is_active', true)->get();
        
        // Calculate cart total
        $cart = Session::get('cart', []);
        $cartTotal = 0;
        foreach ($cart as $item) {
            $cartTotal += $item['total'] ?? 0;
        }
        
        return view('dashboard.shop.product', compact('user', 'afghanWallet', 'product', 'agencies', 'cartTotal'));
    }

    /**
     * Add to cart
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'required_if:is_variable,1|exists:product_variations,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $product = Product::findOrFail($validated['product_id']);
        
        // Initialize cart if it doesn't exist
        if (!Session::has('cart')) {
            Session::put('cart', []);
        }
        
        $cart = Session::get('cart');
        
        if ($product->is_variable) {
            $variation = ProductVariation::findOrFail($validated['variation_id']);
            
            // Create unique cart key for this product variation
            $cartKey = $product->id . '-' . $variation->id;
            
            // Check if item already exists in cart
            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $validated['quantity'];
            } else {
                // Add new cart item
                $cart[$cartKey] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_thumbnail' => $product->thumbnail_url,
                    'variation_id' => $variation->id,
                    'variation_attributes' => $variation->attributes,
                    'price' => $variation->price,
                    'quantity' => $validated['quantity'],
                    'total' => $variation->price * $validated['quantity'],
                ];
            }
        } else {
            // Simple product (not implemented in this system)
            return response()->json([
                'success' => false,
                'message' => 'Simple products are not supported in this system'
            ]);
        }
        
        // Update cart in session
        Session::put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'محصول به سبد خرید افزوده شد',
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Shop cart page
     */
    public function cart()
    {
        $user = Auth::user();
        $afghanWallet = AfghanWallet::where('user_id', $user->id)->first();
        
        // Get cart from session
        $cart = Session::get('cart', []);
        
        // Get coupon if applied
        $coupon = null;
        $couponCode = Session::get('coupon_code');
        
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)
                     ->where('is_active', true)
                     ->first();
        }
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['total'];
        }
        
        $discount = 0;
        if ($coupon && $coupon->isValid($subtotal)) {
            $discount = $coupon->calculateDiscount($subtotal);
        }
        
        $total = $subtotal - $discount;
        
        // Get agencies for payment
        $agencies = Agency::where('is_active', true)->get();
        
        return view('dashboard.shop.cart', compact('user', 'afghanWallet', 'cart', 'coupon', 'subtotal', 'discount', 'total', 'agencies'));
    }
    
    /**
     * Update cart quantity
     */
    public function updateCart(Request $request)
    {
        $validated = $request->validate([
            'cart_key' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);
        
        // Get cart from session
        $cart = Session::get('cart', []);
        
        if (isset($cart[$validated['cart_key']])) {
            $cart[$validated['cart_key']]['quantity'] = $validated['quantity'];
            $cart[$validated['cart_key']]['total'] = $cart[$validated['cart_key']]['price'] * $validated['quantity'];
            
            // Update cart in session
            Session::put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'سبد خرید بروزرسانی شد',
                'item_total' => $cart[$validated['cart_key']]['total'],
                'cart_count' => count($cart)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'آیتم در سبد خرید یافت نشد'
        ]);
    }
    
    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $validated = $request->validate([
            'cart_key' => 'required|string',
        ]);
        
        // Get cart from session
        $cart = Session::get('cart', []);
        
        if (isset($cart[$validated['cart_key']])) {
            unset($cart[$validated['cart_key']]);
            
            // Update cart in session
            Session::put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'محصول از سبد خرید حذف شد',
                'cart_count' => count($cart)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'آیتم در سبد خرید یافت نشد'
        ]);
    }
    
    /**
     * Clear cart
     */
    public function clearCart()
    {
        Session::forget('cart');
        Session::forget('coupon_code');
        
        return response()->json([
            'success' => true,
            'message' => 'سبد خرید خالی شد'
        ]);
    }
    
    /**
     * Apply coupon code
     */
    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'coupon_code' => 'required|string',
        ]);
        
        $coupon = Coupon::where('code', $validated['coupon_code'])
                 ->where('is_active', true)
                 ->first();
        
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'کد تخفیف معتبر نیست',
                'reason' => 'کد تخفیف وارد شده در سیستم وجود ندارد یا غیرفعال شده است'
            ]);
        }
        
        // Get cart from session
        $cart = Session::get('cart', []);
        
        // Calculate subtotal
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['total'];
        }
        
        // Get current user ID
        $userId = Auth::id();
        
        // Check if valid (includes various checks like minimum amount, expiry, etc.)
        $validationResult = $coupon->isValid($subtotal, $userId);
        if ($validationResult !== true) {
            $response = [
                'success' => false,
                'message' => 'این کد تخفیف برای سفارش شما معتبر نیست',
                'reason' => $validationResult['reason']
            ];
            
            // Add additional data from validation result
            foreach ($validationResult as $key => $value) {
                if ($key !== 'valid' && $key !== 'reason') {
                    $response[$key] = $value;
                }
            }
            
            return response()->json($response);
        }
        
        // Calculate discount
        $discount = $coupon->calculateDiscount($subtotal, $userId);
        
        // Save coupon code to session
        Session::put('coupon_code', $validated['coupon_code']);
        
        return response()->json([
            'success' => true,
            'message' => 'کد تخفیف اعمال شد',
            'coupon' => $coupon,
            'discount' => $discount,
            'total' => $subtotal - $discount
        ]);
    }
    
    /**
     * Remove coupon code
     */
    public function removeCoupon()
    {
        Session::forget('coupon_code');
        
        return response()->json([
            'success' => true,
            'message' => 'کد تخفیف حذف شد'
        ]);
    }
    
    /**
     * Shop checkout process
     */
    public function checkout(Request $request)
    {
        try {
            // Debugging all parameters
            Log::info('CHECKOUT DEBUG START ===============================');
            Log::info('Raw Request', $request->all());
            Log::info('Request method: ' . $request->method());
            Log::info('Session cart', ['cart' => Session::get('cart', [])]);
            Log::info('Session user', ['id' => Auth::id()]);
            Log::info('CHECKOUT DEBUG END ===============================');
            
            // For GET requests (debugging only)
            if ($request->isMethod('get')) {
                return redirect()->route('dashboard.shop.cart')
                    ->with('error', 'روش پرداخت نامعتبر است. لطفا از دکمه تکمیل خرید استفاده کنید.');
            }
            
            // STEP 1: Validation
            try {
                $validated = $request->validate([
                    'payment_method' => 'required|in:afghan_wallet,agency_visit',
                    'agency_id' => 'nullable|required_if:payment_method,agency_visit|exists:agencies,id,is_active,1',
                    'notes' => 'nullable|string',
                ]);
                Log::info('Validation passed', $validated);
            } catch (\Exception $e) {
                Log::error('Validation error: ' . $e->getMessage());
                return redirect()->route('dashboard.shop.cart')
                    ->with('error', 'خطا در اعتبارسنجی داده‌ها: ' . $e->getMessage());
            }
            
            // STEP 2: Get user and cart
            try {
                $user = Auth::user();
                if (!$user) {
                    Log::error('User not authenticated');
                    return redirect()->route('auth.login')
                        ->with('error', 'لطفا ابتدا وارد حساب کاربری خود شوید.');
                }
                
                $cart = Session::get('cart', []);
                
                // Check if cart is empty
                if (empty($cart)) {
                    Log::error('Cart is empty');
                    return redirect()->route('dashboard.shop.cart')
                        ->with('error', 'سبد خرید شما خالی است');
                }
                
                Log::info('User and cart retrieved successfully', [
                    'user_id' => $user->id, 
                    'cart_items' => count($cart)
                ]);
            } catch (\Exception $e) {
                Log::error('Error getting user or cart: ' . $e->getMessage());
                return redirect()->route('dashboard.shop.cart')
                    ->with('error', 'خطا در بازیابی اطلاعات: ' . $e->getMessage());
            }
            
            // STEP 3: Calculate totals
            try {
                $subtotal = 0;
                foreach ($cart as $item) {
                    $subtotal += $item['total'];
                }
                
                $discount = 0;
                $couponCode = Session::get('coupon_code');
                if ($couponCode) {
                    $coupon = Coupon::where('code', $couponCode)
                             ->where('is_active', true)
                             ->first();
                             
                    if ($coupon && $coupon->isValid($subtotal)) {
                        $discount = $coupon->calculateDiscount($subtotal);
                    }
                }
                
                $total = $subtotal - $discount;
                
                Log::info('Totals calculated', [
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total' => $total
                ]);
            } catch (\Exception $e) {
                Log::error('Error calculating totals: ' . $e->getMessage());
                return redirect()->route('dashboard.shop.cart')
                    ->with('error', 'خطا در محاسبه مبلغ نهایی: ' . $e->getMessage());
            }
            
            // STEP 4: Check wallet balance if paying with wallet
            try {
                if ($validated['payment_method'] === 'afghan_wallet') {
                    $afghanWallet = AfghanWallet::where('user_id', $user->id)->first();
                    
                    if (!$afghanWallet) {
                        Log::error('Afghan wallet not found for user: ' . $user->id);
                        return redirect()->route('dashboard.shop.cart')
                            ->with('error', 'کیف پول افغانی شما یافت نشد');
                    }
                    
                    Log::info('Wallet check:', [
                        'user_id' => $user->id,
                        'wallet_balance' => $afghanWallet->balance,
                        'total_amount' => $total
                    ]);
                    
                    if ($afghanWallet->balance < $total) {
                        Log::error('Insufficient wallet balance', [
                            'balance' => $afghanWallet->balance,
                            'required' => $total
                        ]);
                        return redirect()->route('dashboard.shop.cart')
                            ->with('error', 'موجودی کیف پول افغانی شما کافی نیست');
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error checking wallet balance: ' . $e->getMessage());
                return redirect()->route('dashboard.shop.cart')
                    ->with('error', 'خطا در بررسی موجودی کیف پول: ' . $e->getMessage());
            }
            
            // STEP 5: Create order using transaction
            try {
                DB::beginTransaction();
                
                // Create order
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => Order::generateOrderNumber(),
                    'status' => 'pending',
                    'total_amount' => $total,
                    'discount_amount' => $discount,
                    'coupon_code' => $couponCode,
                    'payment_status' => $validated['payment_method'] === 'afghan_wallet' ? 'paid' : 'pending',
                    'payment_method' => $validated['payment_method'],
                    'agency_id' => $validated['payment_method'] === 'agency_visit' ? $validated['agency_id'] : null,
                    'notes' => $validated['notes'],
                ]);
                
                Log::info('Order created', ['order_id' => $order->id]);
                
                // Create order items
                foreach ($cart as $item) {
                    $product = Product::find($item['product_id']);
                    $variation = ProductVariation::find($item['variation_id']);
                    
                    // Skip if product or variation not found
                    if (!$product || !$variation) {
                        Log::warning('Product or variation not found:', [
                            'product_id' => $item['product_id'],
                            'variation_id' => $item['variation_id']
                        ]);
                        continue;
                    }
                    
                    // Create order item
                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_variation_id' => $variation->id,
                        'name' => $product->name,
                        'attributes' => $variation->attributes,
                        'quantity' => $item['quantity'],
                        'price' => $variation->price,
                        'total' => $item['total'],
                    ]);
                    
                    Log::info('Order item created', ['order_item_id' => $orderItem->id]);
                    
                    // Update stock only if it's not unlimited (-1)
                    if ($variation->stock != -1) {
                        $variation->decrement('stock', $item['quantity']);
                    }
                }
                
                // STEP 6: Process payment if using wallet
                if ($validated['payment_method'] === 'afghan_wallet') {
                    $afghanWallet = AfghanWallet::where('user_id', $user->id)->first();
                    
                    Log::info('Processing wallet payment:', [
                        'wallet_id' => $afghanWallet->id,
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'amount' => $total
                    ]);
                    
                    // Create transaction
                    $transaction = Transaction::create([
                        'user_id' => $user->id,
                        'amount' => -$total,
                        'currency_type' => 'AFN',
                        'transaction_type' => 'order',
                        'status' => 'completed',
                        'description' => 'پرداخت سفارش #' . $order->order_number,
                        'reference_type' => 'App\Models\Order',
                        'reference_id' => $order->id,
                    ]);
                    
                    Log::info('Transaction created', ['transaction_id' => $transaction->id]);
                    
                    // Deduct from wallet
                    $oldBalance = $afghanWallet->balance;
                    $success = $afghanWallet->decrement('balance', $total);
                    
                    if (!$success) {
                        throw new \Exception('Failed to update wallet balance');
                    }
                    
                    Log::info('Wallet balance updated:', [
                        'old_balance' => $oldBalance,
                        'new_balance' => $afghanWallet->balance,
                        'deducted' => $total
                    ]);
                    
                    // Update order status
                    $order->update([
                        'status' => 'processing',
                        'payment_status' => 'paid'
                    ]);
                    
                    Log::info('Order status updated to processing');
                }
                
                // Increment coupon usage if used
                if ($couponCode) {
                    $coupon = Coupon::where('code', $couponCode)->first();
                    if ($coupon) {
                        $coupon->incrementUsage();
                        Log::info('Coupon usage incremented', ['coupon_code' => $couponCode]);
                    }
                }
                
                DB::commit();
                Log::info('Database transaction committed successfully');
                
                // Clear cart and coupon
                Session::forget('cart');
                Session::forget('coupon_code');
                Log::info('Cart and coupon cleared from session');
                
                return redirect()->route('dashboard.shop.orders')
                    ->with('success', 'سفارش شما با موفقیت ثبت شد');
                    
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Checkout error: ' . $e->getMessage(), [
                    'exception' => $e,
                    'payment_method' => $validated['payment_method'],
                    'user_id' => $user->id,
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return redirect()->route('dashboard.shop.cart')
                    ->with('error', 'خطا در ثبت سفارش: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Unexpected error in checkout: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('dashboard.shop.cart')
                ->with('error', 'خطای غیرمنتظره: ' . $e->getMessage());
        }
    }
    
    /**
     * Shop orders page
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
                 ->orderBy('created_at', 'desc')
                 ->paginate(10);
        
        return view('dashboard.shop.orders', compact('user', 'orders'));
    }
    
    /**
     * Show order details
     */
    public function orderShow($id)
    {
        $user = Auth::user();
        $order = Order::with(['items.product.variations', 'agency'])
                ->where('user_id', $user->id)
                ->findOrFail($id);
                
        return view('dashboard.shop.order-show', compact('user', 'order'));
    }
    
    /**
     * Cancel an order
     */
    public function cancelOrder($id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
                ->where('status', 'pending')
                ->where('payment_status', '!=', 'paid')
                ->findOrFail($id);
                
        // Update the order status to cancelled
        $order->status = 'cancelled';
        $order->save();
        
        return redirect()->route('dashboard.shop.order.show', $order->id)
                ->with('success', 'سفارش با موفقیت لغو شد');
    }
    
    /**
     * Debug products and database
     */
    public function debug()
    {
        // Get product counts
        $counts = [
            'total' => Product::count(),
            'active' => Product::where('status', 'active')->count(),
            'inactive' => Product::where('status', 'inactive')->count(),
            'variable' => Product::where('is_variable', true)->count(),
            'simple' => Product::where('is_variable', false)->count(),
        ];
        
        // Get all products with relations
        $products = Product::with(['categories', 'variations'])
            ->withCount('variations')
            ->get();
            
        // Get categories with product counts
        $categories = ProductCategory::withCount('products')->get();
        
        // Get database tables
        $tables = DB::select('SHOW TABLES');
        
        // Get user
        $user = Auth::user();
        
        return view('dashboard.shop.debug', compact(
            'user', 
            'counts', 
            'products', 
            'categories',
            'tables'
        ));
    }
}
