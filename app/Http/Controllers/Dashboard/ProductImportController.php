<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductCategory;
use App\Models\ProductGallery;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductImportController extends Controller
{
    /**
     * Show the import form
     */
    public function index()
    {
        return view('dashboard.shop.admin.import');
    }

    /**
     * Process the CSV import
     */
    public function import(Request $request)
    {
        // Set maximum execution time to 5 minutes
        ini_set('max_execution_time', 300);
        set_time_limit(300);

        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
            'download_images' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'فایل انتخاب شده معتبر نیست.');
        }

        // Get the uploaded file
        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        // Parse CSV
        $products = $this->parseCSV($path);
        
        if (empty($products)) {
            return redirect()->back()
                ->with('error', 'فایل CSV معتبر نیست یا هیچ محصولی یافت نشد.');
        }

        // Import products
        $stats = $this->importProducts($products, $request->has('download_images'));

        return redirect()->route('dashboard.shop.admin.index')
            ->with('success', "ورود اطلاعات با موفقیت انجام شد. {$stats['imported']} محصول و {$stats['variations']} تغییر وارد شد.");
    }

    /**
     * Parse the CSV file into an array of product data
     */
    private function parseCSV($path)
    {
        $file = fopen($path, 'r');
        if (!$file) {
            return [];
        }

        $headers = fgetcsv($file);
        if (!$headers) {
            fclose($file);
            return [];
        }

        // Convert headers to lowercase for case-insensitive matching
        $headers = array_map('trim', $headers);
        $headers = array_map('strtolower', $headers);

        $products = [];
        $currentProduct = null;
        $currentProductId = null;

        while (($row = fgetcsv($file)) !== false) {
            // Skip empty rows
            if (count(array_filter($row)) === 0) {
                continue;
            }

            // Create associative array with header keys
            $data = [];
            foreach ($headers as $index => $header) {
                $data[$header] = isset($row[$index]) ? trim($row[$index]) : '';
            }

            // Check if this is a parent product or a variation
            $type = $this->getValueFromData($data, ['type']);
            $parent = $this->getValueFromData($data, ['parent']);
            
            if ($type === 'variable' || empty($parent)) {
                // This is a parent product
                $productId = $this->getValueFromData($data, ['id']);
                
                $currentProduct = [
                    'id' => $productId,
                    'name' => $this->getValueFromData($data, ['name']),
                    'description' => $this->getValueFromData($data, ['description']),
                    'image' => $this->getValueFromData($data, ['images', 'image']),
                    'type' => $type ?: 'simple',
                    'price' => $this->getValueFromData($data, ['regular price']),
                    'categories' => $this->getValueFromData($data, ['categories']),
                    'attributes' => $this->parseAttributes($data),
                    'variations' => [],
                ];
                
                $currentProductId = $productId;
                $products[$currentProductId] = $currentProduct;
            } else if ($type === 'variation' || !empty($parent)) {
                // This is a variation
                if ($currentProductId && isset($products[$currentProductId])) {
                    $products[$currentProductId]['variations'][] = [
                        'price' => $this->getValueFromData($data, ['regular price']),
                        'attributes' => $this->parseVariationAttributes($data),
                    ];
                }
            }
        }

        fclose($file);
        return $products;
    }

    /**
     * Import products into the database
     */
    private function importProducts($products, $downloadImages = false)
    {
        $stats = [
            'imported' => 0,
            'variations' => 0,
        ];

        foreach ($products as $productData) {
            try {
                // Create or update the product
                $product = $this->createOrUpdateProduct($productData, $downloadImages);
                
                // Process attributes
                if (!empty($productData['attributes'])) {
                    $this->processAttributes($product, $productData['attributes']);
                }
                
                // Process variations
                if (!empty($productData['variations'])) {
                    $stats['variations'] += $this->processVariations($product, $productData['variations']);
                }
                
                $stats['imported']++;
            } catch (\Exception $e) {
                Log::error('Error importing product: ' . $e->getMessage());
                continue;
            }
        }

        return $stats;
    }

    /**
     * Create or update a product
     */
    private function createOrUpdateProduct($data, $downloadImages = false)
    {
        // Check if product already exists
        $product = null;
        if (!empty($data['id'])) {
            $product = Product::where('id', $data['id'])->first();
        }
        
        if (!$product) {
            // Create new product
            $slug = Str::slug($data['name']);
            if (Product::where('slug', $slug)->exists()) {
                $slug = $slug . '-' . Str::random(5);
            }
            
            $product = Product::create([
                'name' => $data['name'],
                'slug' => $slug,
                'description' => $data['description'] ?? '',
                'short_description' => '',
                'is_variable' => !empty($data['variations']) || $data['type'] === 'variable',
                'status' => 'active',
            ]);
        } else {
            // Update existing product
            $product->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? $product->description,
                'is_variable' => !empty($data['variations']) || $data['type'] === 'variable',
            ]);
        }
        
        // Set product image if provided
        if (!empty($data['image'])) {
            $this->setProductImage($product, $data['image'], $downloadImages);
        }
        
        // Set categories if provided
        if (!empty($data['categories'])) {
            $this->setProductCategories($product, $data['categories']);
        }
        
        return $product;
    }

    /**
     * Process product attributes
     */
    private function processAttributes($product, $attributes)
    {
        // Delete existing attributes
        $product->attributes()->delete();
        
        // Create new attributes
        foreach ($attributes as $name => $values) {
            if (empty($name) || empty($values)) {
                continue;
            }
            
            $product->attributes()->create([
                'name' => $name,
                'values' => $values,
            ]);
        }
    }

    /**
     * Process product variations
     */
    private function processVariations($product, $variations)
    {
        // Delete existing variations
        $product->variations()->delete();
        
        // Create new variations
        $count = 0;
        foreach ($variations as $variation) {
            if (empty($variation['attributes'])) {
                continue;
            }
            
            $product->variations()->create([
                'attributes' => $variation['attributes'],
                'price' => $variation['price'] ?? 0,
                'stock' => -1, // Unlimited stock by default
            ]);
            
            $count++;
        }
        
        return $count;
    }

    /**
     * Set product image from URL
     */
    private function setProductImage($product, $imageUrl, $downloadImages = false)
    {
        if (empty($imageUrl)) {
            return;
        }
        
        try {
            // Check if image URL already exists in database
            $existing = $product->gallery()->where('image_path', $imageUrl)->first();
            if ($existing) {
                return;
            }
            
            if ($downloadImages) {
                // Try to download the image using CURL
                $imageContents = $this->downloadImageWithCurl($imageUrl);
                
                if ($imageContents) {
                    // Make sure the directory exists
                    $directory = 'products/' . $product->id;
                    Storage::disk('public')->makeDirectory($directory);
                    
                    // Create a filename based on the URL
                    $filename = $directory . '/' . Str::random(10) . '.jpg';
                    
                    // Save the image to storage
                    Storage::disk('public')->put($filename, $imageContents);
                    
                    // Create gallery entry with local path
                    $product->gallery()->create([
                        'image_path' => $filename,
                        'sort_order' => 0,
                    ]);
                    
                    // Set thumbnail as well if not set
                    if (empty($product->thumbnail)) {
                        $product->update(['thumbnail' => $filename]);
                    }
                    
                    Log::info('Image downloaded and saved successfully: ' . $imageUrl);
                    return;
                }
            }
            
            // If download is disabled or fails, store the URL directly
            $product->gallery()->create([
                'image_path' => $imageUrl,
                'sort_order' => 0,
            ]);
            
            // Set thumbnail as well if not set
            if (empty($product->thumbnail)) {
                $product->update(['thumbnail' => $imageUrl]);
            }
            
            if ($downloadImages) {
                Log::warning('Failed to download image, storing URL instead: ' . $imageUrl);
            } else {
                Log::info('Image URL stored directly (download disabled): ' . $imageUrl);
            }
        } catch (\Exception $e) {
            Log::error('Error setting product image: ' . $e->getMessage());
        }
    }
    
    /**
     * Download image using cURL with browser-like headers
     */
    private function downloadImageWithCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 180); // Increased timeout to 3 minutes
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); // Added connection timeout
        
        // Set browser-like headers to avoid being blocked
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Accept: image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.9',
            'Referer: https://google.com/'
        ]);
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode >= 200 && $httpCode < 300) {
            return $result;
        }
        
        Log::warning("Failed to download image from $url, HTTP code: $httpCode");
        return null;
    }

    /**
     * Set product categories
     */
    private function setProductCategories($product, $categoryNames)
    {
        if (empty($categoryNames)) {
            return;
        }
        
        $categories = explode(',', $categoryNames);
        $categoryIds = [];
        
        foreach ($categories as $categoryName) {
            $categoryName = trim($categoryName);
            if (empty($categoryName)) {
                continue;
            }
            
            // Find or create category
            $category = ProductCategory::firstOrCreate(
                ['name' => $categoryName],
                [
                    'slug' => Str::slug($categoryName),
                    'is_active' => true,
                ]
            );
            
            $categoryIds[] = $category->id;
        }
        
        if (!empty($categoryIds)) {
            $product->categories()->sync($categoryIds);
        }
    }

    /**
     * Parse product attributes from CSV data
     */
    private function parseAttributes($data)
    {
        $attributes = [];
        
        // Look for attribute fields in format "Attribute X name" and "Attribute X value(s)"
        for ($i = 1; $i <= 10; $i++) {
            $nameKey = strtolower("attribute {$i} name");
            $valueKey = strtolower("attribute {$i} value(s)");
            
            $name = $this->getValueFromData($data, [$nameKey]);
            $value = $this->getValueFromData($data, [$valueKey]);
            
            if (!empty($name) && !empty($value)) {
                // Check if the values contain commas but not pipes
                if (strpos($value, ',') !== false && strpos($value, '|') === false) {
                    // Convert commas to pipes for consistency
                    $values = explode(',', $value);
                } else {
                    // Otherwise use pipes as separator (default)
                    $values = explode('|', $value);
                }
                
                $values = array_map('trim', $values);
                $attributes[$name] = $values;
            }
        }
        
        return $attributes;
    }

    /**
     * Parse variation attributes from CSV data
     */
    private function parseVariationAttributes($data)
    {
        $attributes = [];
        
        // Look for attribute fields in format "Attribute X name" and "Attribute X value(s)"
        for ($i = 1; $i <= 10; $i++) {
            $nameKey = strtolower("attribute {$i} name");
            $valueKey = strtolower("attribute {$i} value(s)");
            
            $name = $this->getValueFromData($data, [$nameKey]);
            $value = $this->getValueFromData($data, [$valueKey]);
            
            if (!empty($name) && !empty($value)) {
                // For variation attributes, we use the value directly
                // No need to handle comma vs pipe here since variations
                // have only one value per attribute
                $attributes[$name] = $value;
            }
        }
        
        return $attributes;
    }

    /**
     * Get a value from data array using multiple possible keys
     */
    private function getValueFromData($data, $possibleKeys)
    {
        foreach ($possibleKeys as $key) {
            $key = strtolower($key);
            if (isset($data[$key]) && !empty($data[$key])) {
                return $data[$key];
            }
        }
        
        return '';
    }
    
    /**
     * Download a sample CSV file
     */
    public function downloadSample()
    {
        $filePath = public_path('samples/product_import_sample.csv');
        
        if (!file_exists($filePath)) {
            return redirect()->route('dashboard.shop.admin.import')
                ->with('error', 'فایل نمونه پیدا نشد.');
        }
        
        return response()->download($filePath, 'product_import_sample.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
} 