<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeAccountRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'lastname',
        'telegram_number',
        'broker_name',
        'city_province',
        'amount',
        'description',
        'payment_method', // 'dollar_wallet', 'agency_visit'
        'trade_account_username', // To be filled after support approval
        'trade_account_password', // To be filled after support approval
        'status', // 'pending', 'approved', 'completed', 'rejected', 'cancelled'
        'tracking_code', // Generated unique tracking number
        'agency_id', // If payment_method is 'agency_visit'
        'support_user_id', // ID of support staff who processed the request
        'completed_at', // When the request was completed
        'credentials_submitted',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'completed_at' => 'datetime',
        'credentials_submitted' => 'boolean',
    ];

    /**
     * Get the user who made the request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the support user who processed the request.
     */
    public function supportUser()
    {
        return $this->belongsTo(User::class, 'support_user_id');
    }

    /**
     * Get the agency for this request.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the transaction for this request.
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'reference');
    }

    /**
     * Generate a unique tracking code.
     */
    public static function generateTrackingCode()
    {
        do {
            $trackingCode = 'TR' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('tracking_code', $trackingCode)->exists());

        return $trackingCode;
    }
} 