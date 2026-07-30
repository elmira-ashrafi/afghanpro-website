<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type', // 'percentage', 'fixed'
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'starts_at',
        'expires_at',
        'usage_limit',
        'usage_count',
        'is_active',
        'max_uses_per_user',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Check if coupon is valid
     * 
     * @param float|null $orderAmount
     * @param int|null $userId
     * @return array|bool Array with validation status and reason if invalid, or true if valid
     */
    public function isValid($orderAmount = null, $userId = null)
    {
        // Check if coupon is active
        if (!$this->is_active) {
            return [
                'valid' => false,
                'reason' => 'کد تخفیف غیرفعال شده است'
            ];
        }

        // Check usage limit
        if ($this->usage_limit > 0 && $this->usage_count >= $this->usage_limit) {
            return [
                'valid' => false,
                'reason' => 'تعداد دفعات مجاز استفاده از این کد تخفیف به پایان رسیده است',
                'max_uses_reached' => true
            ];
        }
        
        // Check per-user usage limit
        if ($userId && $this->max_uses_per_user > 0 && $this->usedByUser($userId) >= $this->max_uses_per_user) {
            return [
                'valid' => false,
                'reason' => 'شما به حداکثر دفعات مجاز استفاده از این کد تخفیف رسیده‌اید',
                'already_used' => true
            ];
        }

        // Check expiration
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return [
                'valid' => false,
                'reason' => 'زمان شروع استفاده از این کد تخفیف فرا نرسیده است',
                'not_started' => true,
                'starts_at' => $this->starts_at->format('Y-m-d')
            ];
        }
        
        if ($this->expires_at && $now->gt($this->expires_at)) {
            return [
                'valid' => false,
                'reason' => 'تاریخ استفاده از این کد تخفیف به پایان رسیده است',
                'expired' => true,
                'expired_at' => $this->expires_at->format('Y-m-d')
            ];
        }

        // Check minimum order amount
        if ($orderAmount !== null && $this->min_order_amount > 0 && $orderAmount < $this->min_order_amount) {
            return [
                'valid' => false,
                'reason' => 'مبلغ سبد خرید شما کمتر از حداقل مبلغ مورد نیاز برای استفاده از این کد تخفیف است',
                'min_amount' => $this->min_order_amount,
                'current_amount' => $orderAmount
            ];
        }

        return true;
    }

    /**
     * Calculate discount amount for a given order amount
     */
    public function calculateDiscount($orderAmount, $userId = null)
    {
        $validationResult = $this->isValid($orderAmount, $userId);
        if ($validationResult !== true) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            $discount = $orderAmount * ($this->discount_value / 100);
            
            // Check max discount amount
            if ($this->max_discount_amount > 0 && $discount > $this->max_discount_amount) {
                $discount = $this->max_discount_amount;
            }
            
            return $discount;
        } else { // Fixed amount
            return min($this->discount_value, $orderAmount);
        }
    }

    /**
     * Increment the usage count
     */
    public function incrementUsage()
    {
        $this->usage_count++;
        return $this->save();
    }

    /**
     * Check how many times this coupon has been used by the user
     */
    public function usedByUser($userId)
    {
        return Order::where('user_id', $userId)
            ->where('coupon_code', $this->code)
            ->where('payment_status', 'paid')
            ->count();
    }
} 