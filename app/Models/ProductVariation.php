<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attributes', // Stored as JSON - combination of attributes
        'price',
        'stock',
        'sku',
    ];

    protected $casts = [
        'attributes' => 'array',
        'price' => 'decimal:2',
    ];

    /**
     * Get the product that owns the variation.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get a formatted display of the attributes
     */
    public function getAttributesDisplayAttribute()
    {
        if (empty($this->attributes)) {
            return '';
        }

        $display = [];
        foreach ($this->attributes as $name => $value) {
            $display[] = "$name: $value";
        }

        return implode(' / ', $display);
    }

    /**
     * Get a formatted display of the stock
     */
    public function getStockDisplayAttribute()
    {
        if ($this->stock == -1) {
            return 'نامحدود';
        }
        
        return $this->stock;
    }
} 