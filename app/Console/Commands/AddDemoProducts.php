<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductCategory;
use App\Models\ProductVariation;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AddDemoProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:add-demo-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add demo products for the shop';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding demo products to the shop...');

        // Create or update categories
        $categories = $this->createCategories();
        
        // Create demo products
        $this->createDemoProducts($categories);
        
        $this->info('Demo products added successfully!');
        
        // Show counts
        $productsCount = Product::count();
        $variationsCount = ProductVariation::count();
        $categoriesCount = ProductCategory::count();
        
        $this->info("Total Products: {$productsCount}");
        $this->info("Total Variations: {$variationsCount}");
        $this->info("Total Categories: {$categoriesCount}");
        
        return Command::SUCCESS;
    }
    
    /**
     * Create categories
     */
    private function createCategories()
    {
        $categories = [];
        
        // Main categories
        $streamingCategory = ProductCategory::firstOrCreate([
            'name' => 'سرویس‌های استریم',
        ], [
            'slug' => 'streaming-services',
            'icon' => 'ri-movie-line',
            'is_active' => true
        ]);
        
        $storageCategory = ProductCategory::firstOrCreate([
            'name' => 'فضای ذخیره‌سازی',
        ], [
            'slug' => 'storage-services',
            'icon' => 'ri-hard-drive-line',
            'is_active' => true
        ]);
        
        $vpnCategory = ProductCategory::firstOrCreate([
            'name' => 'VPN و پراکسی',
        ], [
            'slug' => 'vpn-services',
            'icon' => 'ri-shield-line',
            'is_active' => true
        ]);
        
        $categories = [
            'streaming' => $streamingCategory,
            'storage' => $storageCategory,
            'vpn' => $vpnCategory
        ];
        
        $this->info('Categories created successfully!');
        
        return $categories;
    }
    
    /**
     * Create demo products
     */
    private function createDemoProducts($categories)
    {
        // Create Netflix product
        $netflix = Product::firstOrCreate([
            'name' => 'اکانت نتفلیکس',
        ], [
            'slug' => 'netflix-account',
            'short_description' => 'اکانت پرمیوم نتفلیکس با قابلیت استفاده در چند دستگاه همزمان',
            'description' => "اکانت اشتراکی نتفلیکس (Netflix) با کیفیت 4K و امکان استفاده در چند دستگاه همزمان

- پشتیبانی و گارانتی برای مدت خریداری شده
- قابلیت تماشا در موبایل، تبلت، کامپیوتر و تلویزیون هوشمند
- دسترسی به تمام محتوای نتفلیکس
- کیفیت تصویر HD و 4K Ultra HD (بسته به پلن)",
            'is_variable' => true,
            'status' => 'active',
        ]);
        
        // Attach categories
        $netflix->categories()->sync([$categories['streaming']->id]);
        
        // Create attributes for Netflix
        $durationAttribute = ProductAttribute::firstOrCreate([
            'product_id' => $netflix->id,
            'name' => 'مدت اشتراک',
        ], [
            'values' => ['یک ماهه', 'سه ماهه', 'شش ماهه', 'یک ساله']
        ]);
        
        $profileAttribute = ProductAttribute::firstOrCreate([
            'product_id' => $netflix->id,
            'name' => 'تعداد پروفایل',
        ], [
            'values' => ['1 پروفایل', '2 پروفایل', '4 پروفایل']
        ]);
        
        // Create variations for Netflix
        $this->createNetflixVariations($netflix);
        
        // Create MEGA product
        $mega = Product::firstOrCreate([
            'name' => 'اکانت MEGA',
        ], [
            'slug' => 'mega-account',
            'short_description' => 'اکانت پرمیوم مگا با فضای ذخیره‌سازی بالا و امنیت فوق‌العاده',
            'description' => "اکانت پرمیوم مگا (MEGA) با فضای ذخیره‌سازی بالا و امنیت فوق‌العاده

- فضای ذخیره‌سازی ابری با رمزگذاری قوی
- قابلیت اشتراک‌گذاری فایل‌ها با رمز
- پشتیبانی از تمام دستگاه‌ها
- امکان همگام‌سازی خودکار فایل‌ها
- دسترسی به فایل‌ها در هر زمان و مکان",
            'is_variable' => true,
            'status' => 'active',
        ]);
        
        // Attach categories
        $mega->categories()->sync([$categories['storage']->id]);
        
        // Create attributes for MEGA
        $storageAttribute = ProductAttribute::firstOrCreate([
            'product_id' => $mega->id,
            'name' => 'فضای ذخیره‌سازی',
        ], [
            'values' => ['400GB', '1TB', '2TB', '8TB']
        ]);
        
        $durationMegaAttribute = ProductAttribute::firstOrCreate([
            'product_id' => $mega->id,
            'name' => 'مدت اشتراک',
        ], [
            'values' => ['یک ماهه', 'سه ماهه', 'یک ساله']
        ]);
        
        // Create variations for MEGA
        $this->createMegaVariations($mega);
        
        // Create NordVPN product
        $nordvpn = Product::firstOrCreate([
            'name' => 'اکانت NordVPN',
        ], [
            'slug' => 'nordvpn-account',
            'short_description' => 'اکانت پرمیوم نورد وی‌پی‌ان با امنیت بالا و سرعت فوق‌العاده',
            'description' => "اکانت پرمیوم نورد وی‌پی‌ان (NordVPN) با امنیت بالا و سرعت فوق‌العاده

- بیش از 5000 سرور در 60 کشور جهان
- سیاست عدم نگهداری لاگ
- امکان استفاده همزمان در چند دستگاه
- محافظت در برابر نشت DNS
- تکنولوژی CyberSec برای مسدود کردن تبلیغات و بدافزارها
- پشتیبانی از OpenVPN، IKEv2/IPsec و NordLynx",
            'is_variable' => true,
            'status' => 'active',
        ]);
        
        // Attach categories
        $nordvpn->categories()->sync([$categories['vpn']->id]);
        
        // Create attributes for NordVPN
        $durationVpnAttribute = ProductAttribute::firstOrCreate([
            'product_id' => $nordvpn->id,
            'name' => 'مدت اشتراک',
        ], [
            'values' => ['یک ماهه', 'شش ماهه', 'یک ساله', 'دو ساله']
        ]);
        
        $deviceAttribute = ProductAttribute::firstOrCreate([
            'product_id' => $nordvpn->id,
            'name' => 'تعداد دستگاه',
        ], [
            'values' => ['3 دستگاه', '6 دستگاه']
        ]);
        
        // Create variations for NordVPN
        $this->createNordVPNVariations($nordvpn);
        
        $this->info('Demo products and variations created successfully!');
    }
    
    /**
     * Create Netflix variations
     */
    private function createNetflixVariations($product)
    {
        // Variation: 1 month, 1 profile
        ProductVariation::firstOrCreate([
            'product_id' => $product->id,
            'attributes' => [
                'مدت اشتراک' => 'یک ماهه',
                'تعداد پروفایل' => '1 پروفایل'
            ],
        ], [
            'price' => 45000,
            'stock' => 100,
            'sku' => 'NETFLIX-1M-1P'
        ]);
        
        // Variation: 1 month, 2 profiles
        ProductVariation::firstOrCreate([
            'product_id' => $product->id,
            'attributes' => [
                'مدت اشتراک' => 'یک ماهه',
                'تعداد پروفایل' => '2 پروفایل'
            ],
        ], [
            'price' => 80000,
            'stock' => 100,
            'sku' => 'NETFLIX-1M-2P'
        ]);
        
        // Variation: 1 month, 4 profiles
        ProductVariation::firstOrCreate([
            'product_id' => $product->id,
            'attributes' => [
                'مدت اشتراک' => 'یک ماهه',
                'تعداد پروفایل' => '4 پروفایل'
            ],
        ], [
            'price' => 150000,
            'stock' => 100,
            'sku' => 'NETFLIX-1M-4P'
        ]);
        
        // Variation: 3 months, 1 profile
        ProductVariation::firstOrCreate([
            'product_id' => $product->id,
            'attributes' => [
                'مدت اشتراک' => 'سه ماهه',
                'تعداد پروفایل' => '1 پروفایل'
            ],
        ], [
            'price' => 120000,
            'stock' => 100,
            'sku' => 'NETFLIX-3M-1P'
        ]);
        
        // Variation: 3 months, 2 profiles
        ProductVariation::firstOrCreate([
            'product_id' => $product->id,
            'attributes' => [
                'مدت اشتراک' => 'سه ماهه',
                'تعداد پروفایل' => '2 پروفایل'
            ],
        ], [
            'price' => 230000,
            'stock' => 100,
            'sku' => 'NETFLIX-3M-2P'
        ]);
        
        // Variation: 1 year, 4 profiles
        ProductVariation::firstOrCreate([
            'product_id' => $product->id,
            'attributes' => [
                'مدت اشتراک' => 'یک ساله',
                'تعداد پروفایل' => '4 پروفایل'
            ],
        ], [
            'price' => 900000,
            'stock' => 50,
            'sku' => 'NETFLIX-1Y-4P'
        ]);
    }
    
    /**
     * Create MEGA variations
     */
    private function createMegaVariations($product)
    {
        // Variation: 1 month, 400GB
        ProductVariation::firstOrCreate([
            'product_id' => $product->id,
            'attributes' => [
                'مدت اشتراک' => 'یک ماهه',
                'فضای ذخیره‌سازی' => '400GB'
            ],
        ], [
            'price' => 60000,
            'stock' => 100,
            'sku' => 'MEGA-1M-400GB'
        ]);
        
        // Variation: 3 months, 400GB
        ProductVariation::firstOrCreate([
            'product_id' => $product->id,
            'attributes' => [
                'مدت اشتراک' => 'سه ماهه',
                'فضای ذخیره‌سازی' => '400GB'
            ],
        ], [
            'price' => 170000,
            'stock' => 100,
            'sku' => 'MEGA-3M-400GB'
        ]);
        
        // Variation: 1 month, 2TB
        ProductVariation::firstOrCreate([
            'product_id' => $product->id,
            'attributes' => [
                'مدت اشتراک' => 'یک ماهه',
                'فضای ذخیره‌سازی' => '2TB'
            ],
        ], [
            'price' => 180000,
            'stock' => 100,
            'sku' => 'MEGA-1M-2TB'
        ]);
        
        // Variation: 1 year, 8TB
        ProductVariation::firstOrCreate([
            'product_id' => $product->id,
            'attributes' => [
                'مدت اشتراک' => 'یک ساله',
                'فضای ذخیره‌سازی' => '8TB'
            ],
        ], [
            'price' => 950000,
            'stock' => 50,
            'sku' => 'MEGA-1Y-8TB'
        ]);
    }
    
    /**
     * Create NordVPN variations
     */
    private function createNordVPNVariations($product)
    {
        // Variation: 1 month, 3 devices
        ProductVariation::firstOrCreate([
            'product_id' => $product->id,
            'attributes' => [
                'مدت اشتراک' => 'یک ماهه',
                'تعداد دستگاه' => '3 دستگاه'
            ],
        ], [
            'price' => 75000,
            'stock' => 100,
            'sku' => 'NORD-1M-3D'
        ]);
        
        // Variation: 6 months, 3 devices
        ProductVariation::firstOrCreate([
            'product_id' => $product->id,
            'attributes' => [
                'مدت اشتراک' => 'شش ماهه',
                'تعداد دستگاه' => '3 دستگاه'
            ],
        ], [
            'price' => 400000,
            'stock' => 100,
            'sku' => 'NORD-6M-3D'
        ]);
        
        // Variation: 1 year, 6 devices
        ProductVariation::firstOrCreate([
            'product_id' => $product->id,
            'attributes' => [
                'مدت اشتراک' => 'یک ساله',
                'تعداد دستگاه' => '6 دستگاه'
            ],
        ], [
            'price' => 650000,
            'stock' => 100,
            'sku' => 'NORD-1Y-6D'
        ]);
        
        // Variation: 2 years, 6 devices
        ProductVariation::firstOrCreate([
            'product_id' => $product->id,
            'attributes' => [
                'مدت اشتراک' => 'دو ساله',
                'تعداد دستگاه' => '6 دستگاه'
            ],
        ], [
            'price' => 1200000,
            'stock' => 50,
            'sku' => 'NORD-2Y-6D'
        ]);
    }
}
