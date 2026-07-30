<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'parent_id',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the products for this category.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category', 'category_id', 'product_id');
    }

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }
    
    /**
     * Get all child category IDs including descendants
     *
     * @return Collection
     */
    public function getAllChildIds()
    {
        $ids = collect([$this->id]);
        
        foreach ($this->children as $child) {
            $ids = $ids->merge($child->getAllChildIds());
        }
        
        return $ids;
    }
    
    /**
     * Get all products from this category and its subcategories
     */
    public function getAllProducts()
    {
        $categoryIds = $this->getAllChildIds();
        
        return Product::whereHas('categories', function($query) use ($categoryIds) {
            $query->whereIn('product_categories.id', $categoryIds);
        })->get();
    }
} 