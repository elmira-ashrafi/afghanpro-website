<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variation_id',
        'name',
        'attributes', // JSON of selected attributes
        'quantity',
        'price',
        'total',
    ];

    protected $casts = [
        'attributes' => 'array',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the order that owns the item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product for this item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variation for this item.
     */
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
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
} 