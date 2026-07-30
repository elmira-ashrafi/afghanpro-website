<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AfghanWallet;
use App\Models\DollarWallet;
use App\Models\Agency;
use App\Models\AgencyWithdrawal;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductVariation;
use App\Models\ProductGallery;
use App\Models\ProductCategory;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MoneyTransfer;
use App\Models\TradeAccountRequest;
use App\Models\TradeAccountWithdrawalRequest;
use App\Models\CurrencyConversionRequest;
use App\Models\Transaction;
use App\Models\SystemSetting;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->createUsers();
        // $this->createAgencies();
        // $this->createProductCategories();
        // $this->createProducts();
        // $this->createCoupons();
        // $this->createOrders();
        // $this->createMoneyTransfers();
        // $this->createTradeAccountRequests();
        // $this->createAgencyWithdrawals();
        // $this->createCurrencyConversions();
    }
    
    /**
     * Create test users with wallets
     */
    private function createUsers()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@afghanpro.af',
            'phone' => '93700000001',
            'telegram_number' => '@admin_afghanpro',
            'city' => 'Kabul',
            'province' => 'Kabul',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'is_verified' => true,
        ]);

        // Create wallets for admin
        AfghanWallet::create([
            'user_id' => $admin->id,
            'balance' => 1000000, // 1,000,000 AFN
        ]);

        DollarWallet::create([
            'user_id' => $admin->id,
            'balance' => 10000, // 10,000 USD
        ]);

        // Create support user
        $support = User::create([
            'name' => 'Support',
            'lastname' => 'Agent',
            'email' => 'support@afghanpro.af',
            'phone' => '93700000002',
            'telegram_number' => '@support_afghanpro',
            'city' => 'Kabul',
            'province' => 'Kabul',
            'password' => Hash::make('support123'),
            'is_support' => true,
            'is_verified' => true,
        ]);

        // Create wallets for support
        AfghanWallet::create([
            'user_id' => $support->id,
            'balance' => 50000, // 50,000 AFN
        ]);

        DollarWallet::create([
            'user_id' => $support->id,
            'balance' => 500, // 500 USD
        ]);

        // Create regular users
        // User::factory()
        //     ->count(100)
        //     ->create()
        //     ->each(function ($user) {
        //         // Create Afghan wallet with random balance
        //         AfghanWallet::create([
        //             'user_id' => $user->id,
        //             'balance' => $this->faker()->numberBetween(1000, 100000),
        //         ]);
                
        //         // Create Dollar wallet with random balance
        //         DollarWallet::create([
        //             'user_id' => $user->id,
        //             'balance' => $this->faker()->numberBetween(10, 2000),
        //         ]);
        //     });
    }
    
    /**
     * Create agencies across Afghanistan
     */
    private function createAgencies()
    {
        Agency::factory()->count(5)->create();
    }
    
    /**
     * Create product categories
     */
    private function createProductCategories()
    {
        // Create main categories
        ProductCategory::factory()->count(5)->create();
        
        // Create child categories
        ProductCategory::factory()
            ->count(2)
            ->child()
            ->create();
    }
    
    /**
     * Create products with attributes, variations, and gallery images
     */
    private function createProducts()
    {
        // Ensure the product images directory exists
        $this->setupProductImages();
        
        // Create 100 products
        Product::factory()
            ->count(10)
            ->create()
            ->each(function ($product) {
                // Assign 1-2 categories to each product
                $categoryIds = ProductCategory::inRandomOrder()
                    ->limit($this->faker()->numberBetween(1, 2))
                    ->pluck('id');
                
                $product->categories()->attach($categoryIds);
                
                // Add gallery images
                $this->createProductGallery($product);
                
                if ($product->is_variable) {
                    // For variable products, create attributes and variations
                    $this->createProductVariations($product);
                } else {
                    // For simple products, create a single variation with default attributes
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'attributes' => [], // No need to json_encode, the model will handle it
                        'price' => $this->faker()->numberBetween(100, 5000),
                        'stock' => $this->faker()->numberBetween(5, 100),
                        'sku' => 'SKU-' . $product->id . '-1',
                    ]);
                }
            });
    }
    
    /**
     * Create product variations based on attributes
     */
    private function createProductVariations($product)
    {
        // Create 1-3 attributes for the product
        $attributeCount = $this->faker()->numberBetween(1, 3);
        $attributes = [];
        
        for ($i = 0; $i < $attributeCount; $i++) {
            $attribute = ProductAttribute::factory()->create([
                'product_id' => $product->id,
            ]);
            
            // Since values in ProductAttribute is cast to array, we can access it directly
            $attributes[$attribute->name] = $attribute->values;
        }
        
        // Generate combinations of attribute values for variations
        $combinations = $this->generateAttributeCombinations($attributes);
        
        // Create variations for each combination
        foreach ($combinations as $combination) {
            ProductVariation::create([
                'product_id' => $product->id,
                'attributes' => $combination, // No need to json_encode, the model will handle it
                'price' => $this->faker()->numberBetween(100, 5000),
                'stock' => $this->faker()->numberBetween(5, 100),
                'sku' => 'SKU-' . $product->id . '-' . Str::random(5),
            ]);
        }
    }
    
    /**
     * Generate all possible combinations of attribute values
     */
    private function generateAttributeCombinations($attributes)
    {
        $names = array_keys($attributes);
        $values = array_values($attributes);
        $combinations = [[]];
        
        for ($i = 0; $i < count($values); $i++) {
            $tmp = [];
            foreach ($combinations as $combination) {
                foreach ($values[$i] as $value) {
                    $tmp[] = array_merge($combination, [$names[$i] => $value]);
                }
            }
            $combinations = $tmp;
        }
        
        return $combinations;
    }
    
    /**
     * Create product gallery images
     */
    private function createProductGallery($product)
    {
        $imageCount = $this->faker()->numberBetween(2, 5);
        
        for ($i = 1; $i <= $imageCount; $i++) {
            ProductGallery::create([
                'product_id' => $product->id,
                'image_path' => 'products/' . $this->faker()->numberBetween(1, 20) . '.jpg',
                'sort_order' => $i,
            ]);
        }
    }
    
    /**
     * Setup product images directory and sample images
     */
    private function setupProductImages()
    {
        // Create directory for product images if it doesn't exist
        $directory = public_path('storage/products');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        // Create placeholder images for products if they don't exist
        for ($i = 1; $i <= 20; $i++) {
            $imagePath = $directory . '/' . $i . '.jpg';
            if (!File::exists($imagePath)) {
                // Create a colored placeholder image
                $image = imagecreatetruecolor(800, 600);
                $color = imagecolorallocate($image, 
                    rand(0, 255),  // Red
                    rand(0, 255),  // Green
                    rand(0, 255)   // Blue
                );
                imagefill($image, 0, 0, $color);
                imagejpeg($image, $imagePath);
                imagedestroy($image);
            }
        }
    }
    
    /**
     * Create discount coupons
     */
    private function createCoupons()
    {
        // Create 20 discount coupons
        for ($i = 1; $i <= 20; $i++) {
            Coupon::create([
                'code' => 'DISC' . $this->faker()->unique()->regexify('[A-Z0-9]{6}'),
                'discount_type' => $this->faker()->randomElement(['percentage', 'fixed']),
                'discount_value' => $this->faker()->randomElement(['percentage', 'fixed']) == 'percentage' 
                    ? $this->faker()->numberBetween(5, 30) 
                    : $this->faker()->numberBetween(50, 500),
                'min_order_amount' => $this->faker()->numberBetween(500, 2000),
                'max_discount_amount' => $this->faker()->numberBetween(500, 2000),
                'starts_at' => $this->faker()->dateTimeBetween('-1 month', 'now'),
                'expires_at' => $this->faker()->dateTimeBetween('now', '+3 months'),
                'usage_limit' => $this->faker()->numberBetween(10, 100),
                'usage_count' => 0,
                'max_uses_per_user' => $this->faker()->numberBetween(1, 3),
                'is_active' => true,
            ]);
        }
    }
    
    /**
     * Create orders with order items
     */
    private function createOrders()
    {
        // Get all users and random products for orders
        $users = User::where('is_admin', false)->where('is_support', false)->get();
        $products = Product::all();
        $agencies = Agency::all();
        
        // Create 100 orders
        for ($i = 1; $i <= 100; $i++) {
            $user = $users->random();
            $orderStatus = $this->faker()->randomElement(['pending', 'processing', 'completed', 'cancelled']);
            $paymentStatus = $orderStatus == 'cancelled' ? 'failed' : $this->faker()->randomElement(['pending', 'paid']);
            $paymentMethod = $this->faker()->randomElement(['afghan_wallet', 'agency_visit']);
            
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => $orderStatus,
                'total_amount' => 0, // Will be calculated based on items
                'discount_amount' => 0,
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentMethod,
                'agency_id' => $paymentMethod == 'agency_visit' ? $agencies->random()->id : null,
                'notes' => $this->faker()->sentence,
            ]);
            
            // Add 1-5 random products to the order
            $itemCount = $this->faker()->numberBetween(1, 5);
            $totalAmount = 0;
            
            for ($j = 1; $j <= $itemCount; $j++) {
                $product = $products->random();
                
                if ($product->is_variable) {
                    $variation = $product->variations->random();
                    $price = $variation->price;
                    $attributes = $variation->attributes;
                } else {
                    $variation = $product->variations->first();
                    $price = $variation->price;
                    $attributes = [];
                }
                
                $quantity = $this->faker()->numberBetween(1, 3);
                $totalPrice = $price * $quantity;
                $totalAmount += $totalPrice;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variation_id' => $variation->id,
                    'name' => $product->name,
                    'attributes' => $attributes, // No need to json_encode, the model will handle it
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $totalPrice,
                ]);
            }
            
            // Apply a random discount to some orders
            if ($this->faker()->boolean(30)) {
                $coupon = Coupon::inRandomOrder()->first();
                if ($coupon) {
                    $discountAmount = $coupon->calculateDiscount($totalAmount);
                    $order->discount_amount = $discountAmount;
                    $order->coupon_code = $coupon->code;
                    $totalAmount -= $discountAmount;
                }
            }
            
            // Update the order total
            $order->total_amount = $totalAmount;
            $order->save();
            
            // Create transaction if order is paid
            if ($paymentStatus == 'paid') {
                Transaction::create([
                    'user_id' => $user->id,
                    'afghan_wallet_id' => $paymentMethod == 'afghan_wallet' ? $user->afghanWallet->id : null,
                    'amount' => $totalAmount,
                    'currency_type' => 'AFN',
                    'transaction_type' => 'order',
                    'description' => 'Payment for order #' . $order->order_number,
                    'status' => 'completed',
                    'reference_id' => $order->id,
                    'reference_type' => Order::class,
                ]);
                
                // Deduct from user's wallet if paid through wallet
                if ($paymentMethod == 'afghan_wallet') {
                    $user->afghanWallet->withdraw($totalAmount);
                    $user->afghanWallet->save();
                }
            }
        }
    }
    
    /**
     * Create money transfers
     */
    private function createMoneyTransfers()
    {
        $users = User::where('is_admin', false)->where('is_support', false)->get();
        $supportUsers = User::where('is_support', true)->get();
        $agencies = Agency::all();
        
        // Create 100 money transfers
        for ($i = 1; $i <= 100; $i++) {
            $user = $users->random();
            $status = $this->faker()->randomElement(['pending', 'approved', 'completed', 'rejected', 'cancelled']);
            $paymentMethod = $this->faker()->randomElement(['dollar_wallet', 'agency_visit']);
            $isDomestic = $this->faker()->boolean(70);
            
            $sourceCountry = 'Afghanistan';
            $destinationCountry = $isDomestic 
                ? 'Afghanistan' 
                : $this->faker()->randomElement(['Iran', 'Pakistan', 'Tajikistan', 'Turkey', 'UAE', 'USA', 'UK', 'Germany', 'Canada']);
            
            $amount = $this->faker()->numberBetween(50, 5000);
            
            // Calculate commission
            $commissionRate = $isDomestic ? 2 : ($destinationCountry == 'Pakistan' ? 3 : 5);
            $commissionAmount = ($amount * $commissionRate) / 100;
            
            $moneyTransfer = MoneyTransfer::create([
                'user_id' => $user->id,
                'sender_name' => $user->name . ' ' . $user->lastname,
                'sender_telegram' => $user->telegram_number,
                'source_country' => $sourceCountry,
                'destination_country' => $destinationCountry,
                'destination_city_province' => $this->faker()->city,
                'recipient_name' => $this->faker()->name,
                'recipient_id_passport' => $this->faker()->regexify('[A-Z0-9]{9}'),
                'amount_usd' => $amount,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commissionAmount,
                'payment_method' => $paymentMethod,
                'tracking_number' => MoneyTransfer::generateTrackingNumber(),
                'agency_id' => $paymentMethod == 'agency_visit' ? $agencies->random()->id : null,
                'is_domestic' => $isDomestic,
                'status' => $status,
                'support_user_id' => in_array($status, ['approved', 'completed', 'rejected']) ? $supportUsers->random()->id : null,
                'completed_at' => $status == 'completed' ? now() : null,
            ]);
            
            // Create transaction if status is completed and payment method is wallet
            if ($status == 'completed' && $paymentMethod == 'dollar_wallet') {
                Transaction::create([
                    'user_id' => $user->id,
                    'dollar_wallet_id' => $user->dollarWallet->id,
                    'amount' => $amount + $commissionAmount,
                    'currency_type' => 'USD',
                    'transaction_type' => 'transfer',
                    'description' => 'Money transfer to ' . $destinationCountry . ' - ' . $moneyTransfer->tracking_number,
                    'status' => 'completed',
                    'reference_id' => $moneyTransfer->id,
                    'reference_type' => MoneyTransfer::class,
                ]);
                
                // Deduct from user's dollar wallet
                $user->dollarWallet->withdraw($amount + $commissionAmount);
                $user->dollarWallet->save();
            }
        }
    }
    
    /**
     * Create trade account requests
     */
    private function createTradeAccountRequests()
    {
        $users = User::where('is_admin', false)->where('is_support', false)->get();
        $supportUsers = User::where('is_support', true)->get();
        $agencies = Agency::all();
        $brokers = ['MetaTrader 4', 'MetaTrader 5', 'cTrader', 'TradingView', 'NinjaTrader', 'Interactive Brokers'];
        
        // Create trade account requests
        for ($i = 1; $i <= 50; $i++) {
            $user = $users->random();
            $status = $this->faker()->randomElement(['pending', 'approved', 'completed', 'rejected', 'cancelled']);
            $paymentMethod = $this->faker()->randomElement(['dollar_wallet', 'agency_visit']);
            $amount = $this->faker()->numberBetween(100, 10000);
            $credentialsSubmitted = in_array($status, ['approved', 'completed']);
            
            $tradeRequest = TradeAccountRequest::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'lastname' => $user->lastname,
                'telegram_number' => $user->telegram_number,
                'broker_name' => $this->faker()->randomElement($brokers),
                'city_province' => $user->city . ', ' . $user->province,
                'amount' => $amount,
                'description' => $this->faker()->sentence,
                'payment_method' => $paymentMethod,
                'trade_account_username' => $credentialsSubmitted ? 'trader_' . $this->faker()->userName : null,
                'trade_account_password' => $credentialsSubmitted ? $this->faker()->password : null,
                'status' => $status,
                'tracking_code' => TradeAccountRequest::generateTrackingCode(),
                'credentials_submitted' => $credentialsSubmitted,
                'agency_id' => $paymentMethod == 'agency_visit' ? $agencies->random()->id : null,
                'support_user_id' => in_array($status, ['approved', 'completed', 'rejected']) ? $supportUsers->random()->id : null,
                'completed_at' => $status == 'completed' ? now() : null,
            ]);
            
            // Create transaction if completed and using dollar wallet
            if ($status == 'completed' && $paymentMethod == 'dollar_wallet') {
                Transaction::create([
                    'user_id' => $user->id,
                    'dollar_wallet_id' => $user->dollarWallet->id,
                    'amount' => $amount,
                    'currency_type' => 'USD',
                    'transaction_type' => 'withdraw',
                    'description' => 'Trade account funding - ' . $tradeRequest->tracking_code,
                    'status' => 'completed',
                    'reference_id' => $tradeRequest->id,
                    'reference_type' => TradeAccountRequest::class,
                ]);
                
                // Deduct from user's dollar wallet
                $user->dollarWallet->withdraw($amount);
                $user->dollarWallet->save();
            }
        }
        
        // Create trade account withdrawal requests
        for ($i = 1; $i <= 50; $i++) {
            $user = $users->random();
            $status = $this->faker()->randomElement(['pending', 'approved', 'completed', 'rejected', 'cancelled']);
            $amount = $this->faker()->numberBetween(100, 10000);
            $credentialsSubmitted = in_array($status, ['approved', 'completed']);
            
            $withdrawalRequest = TradeAccountWithdrawalRequest::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'lastname' => $user->lastname,
                'telegram_number' => $user->telegram_number,
                'broker_name' => $this->faker()->randomElement($brokers),
                'city_province' => $user->city . ', ' . $user->province,
                'amount' => $amount,
                'description' => $this->faker()->sentence,
                'trade_account_username' => $credentialsSubmitted ? 'trader_' . $this->faker()->userName : null,
                'trade_account_password' => $credentialsSubmitted ? $this->faker()->password : null,
                'status' => $status,
                'tracking_code' => TradeAccountWithdrawalRequest::generateTrackingCode(),
                'credentials_submitted' => $credentialsSubmitted,
                'support_user_id' => in_array($status, ['approved', 'completed', 'rejected']) ? $supportUsers->random()->id : null,
                'completed_at' => $status == 'completed' ? now() : null,
            ]);
            
            // Create transaction if completed
            if ($status == 'completed') {
                Transaction::create([
                    'user_id' => $user->id,
                    'dollar_wallet_id' => $user->dollarWallet->id,
                    'amount' => $amount,
                    'currency_type' => 'USD',
                    'transaction_type' => 'deposit',
                    'description' => 'Trade account withdrawal - ' . $withdrawalRequest->tracking_code,
                    'status' => 'completed',
                    'reference_id' => $withdrawalRequest->id,
                    'reference_type' => TradeAccountWithdrawalRequest::class,
                ]);
                
                // Add to user's dollar wallet
                $user->dollarWallet->deposit($amount);
                $user->dollarWallet->save();
            }
        }
    }
    
    /**
     * Create agency withdrawals
     */
    private function createAgencyWithdrawals()
    {
        $users = User::where('is_admin', false)->where('is_support', false)->get();
        $supportUsers = User::where('is_support', true)->get();
        $agencies = Agency::all();
        
        // Create agency withdrawals
        for ($i = 1; $i <= 100; $i++) {
            $user = $users->random();
            $status = $this->faker()->randomElement(['pending', 'approved', 'completed', 'rejected', 'cancelled']);
            $currencyType = $this->faker()->randomElement(['AFN', 'USD']);
            $walletType = $currencyType == 'AFN' ? 'afghan_wallet' : 'dollar_wallet';
            
            // Amount depends on currency
            $amount = $currencyType == 'AFN' 
                ? $this->faker()->numberBetween(1000, 50000) 
                : $this->faker()->numberBetween(50, 1000);
            
            $withdrawal = AgencyWithdrawal::create([
                'user_id' => $user->id,
                'agency_id' => $agencies->random()->id,
                'full_name' => $user->name . ' ' . $user->lastname,
                'phone' => $user->phone,
                'city' => $user->city,
                'amount' => $amount,
                'currency_type' => $currencyType,
                'wallet_type' => $walletType,
                'tracking_number' => 'AW' . date('Ymd') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'status' => $status,
                'description' => $this->faker()->sentence,
                'support_user_id' => in_array($status, ['approved', 'completed', 'rejected']) ? $supportUsers->random()->id : null,
                'completed_at' => $status == 'completed' ? now() : null,
            ]);
            
            // Create transaction if completed
            if ($status == 'completed') {
                Transaction::create([
                    'user_id' => $user->id,
                    'afghan_wallet_id' => $walletType == 'afghan_wallet' ? $user->afghanWallet->id : null,
                    'dollar_wallet_id' => $walletType == 'dollar_wallet' ? $user->dollarWallet->id : null,
                    'amount' => $amount,
                    'currency_type' => $currencyType,
                    'transaction_type' => 'withdraw',
                    'description' => 'Agency withdrawal - ' . $withdrawal->tracking_number,
                    'status' => 'completed',
                    'reference_id' => $withdrawal->id,
                    'reference_type' => AgencyWithdrawal::class,
                ]);
                
                // Deduct from user's wallet
                if ($walletType == 'afghan_wallet') {
                    $user->afghanWallet->withdraw($amount);
                    $user->afghanWallet->save();
                } else {
                    $user->dollarWallet->withdraw($amount);
                    $user->dollarWallet->save();
                }
            }
        }
    }
    
    /**
     * Create currency conversion requests
     */
    private function createCurrencyConversions()
    {
        $users = User::where('is_admin', false)->where('is_support', false)->get();
        $admins = User::where('is_admin', true)->get();
        
        // Create currency conversions
        for ($i = 1; $i <= 100; $i++) {
            $user = $users->random();
            $status = $this->faker()->randomElement(['pending', 'approved', 'rejected', 'completed']);
            $fromCurrency = $this->faker()->randomElement(['AFN', 'USD']);
            $toCurrency = $fromCurrency == 'AFN' ? 'USD' : 'AFN';
            
            // Amount depends on currency
            $amount = $fromCurrency == 'AFN' 
                ? $this->faker()->numberBetween(5000, 100000) 
                : $this->faker()->numberBetween(100, 2000);
            
            // Fee percentage
            $feePercentage = $fromCurrency == 'USD' ? 0.5 : 1;
            
            // Conversion rate
            $conversionRate = $fromCurrency == 'USD' ? 83.5 : 0.012;
            
            // Calculate converted amount after fee
            $feeAmount = ($amount * $feePercentage) / 100;
            $amountAfterFee = $amount - $feeAmount;
            $convertedAmount = $amountAfterFee * $conversionRate;
            
            $conversion = CurrencyConversionRequest::create([
                'user_id' => $user->id,
                'from_currency' => $fromCurrency,
                'to_currency' => $toCurrency,
                'amount' => $amount,
                'fee_percentage' => $feePercentage,
                'conversion_rate' => $conversionRate,
                'converted_amount' => $convertedAmount,
                'status' => $status,
                'tracking_code' => 'CC' . date('Ymd') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'admin_id' => in_array($status, ['approved', 'rejected', 'completed']) ? $admins->random()->id : null,
                'admin_notes' => $this->faker()->sentence,
                'user_notes' => $this->faker()->sentence,
                'approved_at' => in_array($status, ['approved', 'completed']) ? now() : null,
                'completed_at' => $status == 'completed' ? now() : null,
            ]);
            
            // Create transactions for completed conversions
            if ($status == 'completed') {
                // Withdrawal transaction from source wallet
                Transaction::create([
                    'user_id' => $user->id,
                    'afghan_wallet_id' => $fromCurrency == 'AFN' ? $user->afghanWallet->id : null,
                    'dollar_wallet_id' => $fromCurrency == 'USD' ? $user->dollarWallet->id : null,
                    'amount' => $amount,
                    'currency_type' => $fromCurrency,
                    'transaction_type' => 'conversion',
                    'description' => "Conversion from {$amount} {$fromCurrency} to {$convertedAmount} {$toCurrency}",
                    'status' => 'completed',
                    'reference_id' => $conversion->id,
                    'reference_type' => CurrencyConversionRequest::class,
                ]);
                
                // Deposit transaction to destination wallet
                Transaction::create([
                    'user_id' => $user->id,
                    'afghan_wallet_id' => $toCurrency == 'AFN' ? $user->afghanWallet->id : null,
                    'dollar_wallet_id' => $toCurrency == 'USD' ? $user->dollarWallet->id : null,
                    'amount' => $convertedAmount,
                    'currency_type' => $toCurrency,
                    'transaction_type' => 'deposit',
                    'description' => "Deposit from currency conversion {$amount} {$fromCurrency} to {$convertedAmount} {$toCurrency}",
                    'status' => 'completed',
                    'reference_id' => $conversion->id,
                    'reference_type' => CurrencyConversionRequest::class,
                ]);
                
                // Update wallets
                if ($fromCurrency == 'AFN') {
                    $user->afghanWallet->withdraw($amount);
                    $user->afghanWallet->save();
                    
                    $user->dollarWallet->deposit($convertedAmount);
                    $user->dollarWallet->save();
                } else {
                    $user->dollarWallet->withdraw($amount);
                    $user->dollarWallet->save();
                    
                    $user->afghanWallet->deposit($convertedAmount);
                    $user->afghanWallet->save();
                }
            }
        }
    }
    
    /**
     * Get a faker instance
     */
    private function faker()
    {
        return \Faker\Factory::create();
    }
}
