<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'values', // Stored as JSON
    ];

    protected $casts = [
        'values' => 'array',
    ];

    /**
     * Get the product that owns the attribute.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 