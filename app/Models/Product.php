<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'thumbnail',
        'is_variable',
        'status', // 'active', 'inactive'
    ];

    protected $casts = [
        'is_variable' => 'boolean',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the attributes for this product.
     */
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * Get the variations for this product.
     */
    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * Get the gallery images for this product.
     */
    public function gallery()
    {
        return $this->hasMany(ProductGallery::class);
    }

    /**
     * Get all orders for this product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the categories for this product.
     */
    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category', 'product_id', 'category_id');
    }

    /**
     * Find the cheapest variation price
     */
    public function getMinPriceAttribute()
    {
        if (!$this->is_variable || $this->variations->isEmpty()) {
            return 0;
        }
        
        // Only consider variations with stock > 0 or stock = -1 (unlimited)
        $inStockVariations = $this->variations->filter(function($variation) {
            return $variation->stock > 0 || $variation->stock == -1;
        });
        
        if ($inStockVariations->isEmpty()) {
            return 0;
        }
        
        return $inStockVariations->min('price');
    }

    /**
     * Find the most expensive variation price
     */
    public function getMaxPriceAttribute()
    {
        if (!$this->is_variable || $this->variations->isEmpty()) {
            return 0;
        }
        
        // Only consider variations with stock > 0 or stock = -1 (unlimited)
        $inStockVariations = $this->variations->filter(function($variation) {
            return $variation->stock > 0 || $variation->stock == -1;
        });
        
        if ($inStockVariations->isEmpty()) {
            return 0;
        }
        
        return $inStockVariations->max('price');
    }

    /**
     * Check if product has stock
     */
    public function hasStock()
    {
        if (!$this->is_variable) {
            return true; // Simple products don't track stock in this system
        }
        
        // Check if any variation has stock > 0 or stock = -1 (unlimited)
        return $this->variations->filter(function($variation) {
            return $variation->stock > 0 || $variation->stock == -1;
        })->count() > 0;
    }

    /**
     * Get formatted price range
     */
    public function getPriceRangeAttribute()
    {
        if (!$this->is_variable || $this->variations->isEmpty()) {
            return '0 افغانی';
        }
        
        // Only consider variations with stock > 0 or stock = -1 (unlimited)
        $inStockVariations = $this->variations->filter(function($variation) {
            return $variation->stock > 0 || $variation->stock == -1;
        });
        
        if ($inStockVariations->isEmpty()) {
            return 'ناموجود';
        }
        
        $min = $inStockVariations->min('price');
        $max = $inStockVariations->max('price');
        
        if ($min == $max) {
            return number_format($min) . ' افغانی';
        }
        
        return 'از ' . number_format($min) . ' افغانی';
    }

    /**
     * Get the thumbnail URL attribute.
     *
     * @return string|null
     */
    public function getThumbnailUrlAttribute()
    {
        if (empty($this->thumbnail)) {
            return null;
        }
        
        // Check if thumbnail is a full URL
        if (filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
            return $this->thumbnail;
        }
        
        // Otherwise, treat it as a local path
        return asset('storage/' . $this->thumbnail);
    }
} 