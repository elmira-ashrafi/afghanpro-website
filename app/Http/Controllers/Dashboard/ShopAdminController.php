<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductCategory;
use App\Models\ProductVariation;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopAdminController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Product::with('categories');
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }
        
        // Apply category filter
        if ($request->filled('category_id')) {
            $categoryId = $request->category_id;
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('product_categories.id', $categoryId);
            });
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $products = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get categories for the filter dropdown
        $categories = ProductCategory::where('is_active', true)
                        ->orderBy('name')
                        ->get();
        
        return view('dashboard.shop.admin.index', compact('user', 'products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $user = Auth::user();
        $categories = ProductCategory::where('is_active', true)
                        ->orderBy('name')
                        ->get();
        
        return view('dashboard.shop.admin.product-create', compact('user', 'categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:products',
                'short_description' => 'nullable|string',
                'description' => 'nullable|string',
                'product_type' => 'required|in:simple,variable',
                'status' => 'required|in:active,inactive',
                'thumbnail' => 'nullable|image|max:2048',
                'gallery.*' => 'nullable|image|max:2048',
                'categories' => 'nullable|array',
                'categories.*' => 'exists:product_categories,id',
                'attributes' => 'nullable|array',
                'variations' => 'nullable|array',
            ]);
            
            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }
            
            // Create product
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'short_description' => $validated['short_description'] ?? null,
                'description' => $validated['description'] ?? null,
                'is_variable' => $validated['product_type'] === 'variable',
                'status' => $validated['status'],
            ]);
            
            // Upload thumbnail
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('products', 'public');
                $product->update(['thumbnail' => $thumbnailPath]);
            }
            
            // Attach categories
            if (!empty($validated['categories'])) {
                $product->categories()->attach($validated['categories']);
            }
            
            // Upload gallery images
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $index => $image) {
                    $imagePath = $image->store('products/gallery', 'public');
                    $product->gallery()->create([
                        'image_path' => $imagePath,
                        'sort_order' => $index,
                    ]);
                }
            }
            
            // Process attributes and variations if variable product
            if ($validated['product_type'] === 'variable' && !empty($validated['attributes'])) {
                foreach ($validated['attributes'] as $attributeData) {
                    if (empty($attributeData['name']) || empty($attributeData['values'])) {
                        continue;
                    }
                    
                    // Convert pipe-separated values to array
                    $values = is_array($attributeData['values']) 
                        ? $attributeData['values'] 
                        : (strpos($attributeData['values'], ',') !== false && strpos($attributeData['values'], '|') === false 
                            ? explode(',', $attributeData['values'])
                            : explode('|', $attributeData['values']));
                    
                    // Clean up values
                    $values = array_map('trim', $values);
                    
                    // Store attribute
                    $attribute = $product->attributes()->create([
                        'name' => $attributeData['name'],
                        'values' => $values,
                    ]);
                }
                
                // Process variations
                if (!empty($validated['variations'])) {
                    foreach ($validated['variations'] as $variationData) {
                        if (empty($variationData['attributes']) || !isset($variationData['price'])) {
                            continue;
                        }
                        
                        $product->variations()->create([
                            'attributes' => $variationData['attributes'],
                            'price' => $variationData['price'],
                            'stock' => $variationData['stock'] ?? 0,
                            'sku' => $variationData['sku'] ?? null,
                        ]);
                    }
                }
            }
            
            return redirect()->route('dashboard.shop.admin.index')
                ->with('success', 'محصول با موفقیت اضافه شد.');
                
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating product: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'خطا در ذخیره محصول: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $product = Product::with(['attributes', 'variations', 'categories', 'gallery'])
                    ->findOrFail($id);
        
        $categories = ProductCategory::where('is_active', true)
                        ->orderBy('name')
                        ->get();
        
        return view('dashboard.shop.admin.product-edit', compact('user', 'product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $id,
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image|max:2048',
            'gallery.*' => 'nullable|image|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:product_categories,id',
            'attributes' => 'array',
            'variations' => 'array',
        ]);
        
        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        // Update product
        $product->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);
        
        // Upload new thumbnail
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            
            $thumbnailPath = $request->file('thumbnail')->store('products', 'public');
            $product->update(['thumbnail' => $thumbnailPath]);
        }
        
        // Sync categories
        if (isset($validated['categories'])) {
            $product->categories()->sync($validated['categories']);
        }
        
        // Upload new gallery images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $image) {
                $imagePath = $image->store('products/gallery', 'public');
                $product->gallery()->create([
                    'image_path' => $imagePath,
                    'sort_order' => $product->gallery()->count() + $index,
                ]);
            }
        }
        
        // Handle gallery deletions
        if ($request->has('delete_gallery')) {
            foreach ($request->delete_gallery as $galleryId) {
                $gallery = $product->gallery()->find($galleryId);
                if ($gallery) {
                    Storage::disk('public')->delete($gallery->image_path);
                    $gallery->delete();
                }
            }
        }
        
        // Process updated attributes
        if (isset($validated['attributes'])) {
            // Get existing attribute IDs
            $existingAttributeIds = $product->attributes->pluck('id')->toArray();
            $updatedAttributeIds = [];
            
            foreach ($validated['attributes'] as $attributeId => $attributeData) {
                if (empty($attributeData['name']) || empty($attributeData['values'])) {
                    continue;
                }
                
                // Convert pipe-separated values to array
                $values = is_array($attributeData['values']) 
                    ? $attributeData['values'] 
                    : (strpos($attributeData['values'], ',') !== false && strpos($attributeData['values'], '|') === false 
                        ? explode(',', $attributeData['values'])
                        : explode('|', $attributeData['values']));
                
                // Clean up values
                $values = array_map('trim', $values);
                
                if (is_numeric($attributeId)) {
                    // Update existing attribute
                    $attribute = ProductAttribute::find($attributeId);
                    if ($attribute && $attribute->product_id == $product->id) {
                        $attribute->update([
                            'name' => $attributeData['name'],
                            'values' => $values,
                        ]);
                        $updatedAttributeIds[] = $attribute->id;
                    }
                } else {
                    // Create new attribute
                    $attribute = $product->attributes()->create([
                        'name' => $attributeData['name'],
                        'values' => $values,
                    ]);
                    $updatedAttributeIds[] = $attribute->id;
                }
            }
            
            // Delete attributes that were not updated
            $attributesToDelete = array_diff($existingAttributeIds, $updatedAttributeIds);
            ProductAttribute::whereIn('id', $attributesToDelete)->delete();
        }
        
        // Process variations
        if (isset($validated['variations'])) {
            // Get existing variation IDs
            $existingVariationIds = $product->variations->pluck('id')->toArray();
            $updatedVariationIds = [];
            
            foreach ($validated['variations'] as $variationId => $variationData) {
                if (empty($variationData['attributes']) || !isset($variationData['price'])) {
                    continue;
                }
                
                if (is_numeric($variationId)) {
                    // Update existing variation
                    $variation = ProductVariation::find($variationId);
                    if ($variation && $variation->product_id == $product->id) {
                        $variation->update([
                            'attributes' => $variationData['attributes'],
                            'price' => $variationData['price'],
                            'stock' => $variationData['stock'] ?? 0,
                            'sku' => $variationData['sku'] ?? null,
                        ]);
                        $updatedVariationIds[] = $variation->id;
                    }
                } else {
                    // Create new variation
                    $variation = $product->variations()->create([
                        'attributes' => $variationData['attributes'],
                        'price' => $variationData['price'],
                        'stock' => $variationData['stock'] ?? 0,
                        'sku' => $variationData['sku'] ?? null,
                    ]);
                    $updatedVariationIds[] = $variation->id;
                }
            }
            
            // Delete variations that were not updated
            $variationsToDelete = array_diff($existingVariationIds, $updatedVariationIds);
            ProductVariation::whereIn('id', $variationsToDelete)->delete();
        }
        
        return redirect()->route('dashboard.shop.admin.index')
            ->with('success', 'محصول با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Delete thumbnail
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }
        
        // Delete gallery images
        foreach ($product->gallery as $gallery) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        
        $product->delete();
        
        return redirect()->route('dashboard.shop.admin.index')
            ->with('success', 'محصول با موفقیت حذف شد.');
    }
    
    /**
     * Show the orders management page.
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = Order::with(['user', 'items'])->orderBy('created_at', 'desc')->paginate(15);
        
        return view('dashboard.shop.admin.orders', compact('user', 'orders'));
    }
    
    /**
     * Show a specific order details.
     */
    public function orderShow($id)
    {
        $user = Auth::user();
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        
        return view('dashboard.shop.admin.order-show', compact('user', 'order'));
    }
    
    /**
     * Update order status.
     */
    public function orderUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'notes' => 'nullable|string',
        ]);
        
        $order = Order::findOrFail($id);
        $order->update($validated);
        
        return redirect()->route('dashboard.shop.admin.orders')
            ->with('success', 'وضعیت سفارش با موفقیت بروزرسانی شد.');
    }
}
