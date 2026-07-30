<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_name',
        'sender_telegram',
        'source_country',
        'destination_country',
        'destination_city_province',
        'recipient_name',
        'recipient_id_passport',
        'amount_usd',
        'commission_rate',
        'commission_amount',
        'payment_method', // 'dollar_wallet', 'agency_visit'
        'tracking_number',
        'agency_id', // If payment_method is 'agency_visit'
        'is_domestic',
        'status', // 'pending', 'approved', 'completed', 'rejected', 'cancelled'
        'support_user_id',
        'completed_at',
    ];

    protected $casts = [
        'is_domestic' => 'boolean',
        'amount_usd' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the money transfer request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the support user that processed the request.
     */
    public function supportUser()
    {
        return $this->belongsTo(User::class, 'support_user_id');
    }

    /**
     * Get the agency if payment method is agency visit.
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
     * Generate a unique tracking number.
     */
    public static function generateTrackingNumber()
    {
        do {
            $trackingNumber = 'MT' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }

    /**
     * Calculate commission based on destination country
     */
    public function calculateCommission($amount, $destinationCountry = null)
    {
        if ($destinationCountry === null) {
            $destinationCountry = $this->destination_country;
        }

        // Check if it's domestic (Afghanistan)
        $isDomestic = ($destinationCountry === 'Afghanistan');
        
        // Get the neighboring countries list
        $neighboringCountries = ['Iran', 'Pakistan', 'Tajikistan', 'Turkey', 'UAE'];
        $isNeighboring = in_array($destinationCountry, $neighboringCountries);
        
        // Get commission rates from system settings
        $domesticRate = SystemSetting::where('key', 'domestic_transfer_fee')->value('value') ?? 2;
        $neighboringRate = SystemSetting::where('key', 'neighboring_transfer_fee')->value('value') ?? 3;
        $internationalRate = SystemSetting::where('key', 'international_transfer_fee')->value('value') ?? 5;
        
        // Calculate fee based on destination country
        $rate = $isDomestic ? $domesticRate : ($isNeighboring ? $neighboringRate : $internationalRate);
        $rate = $rate / 100; // Convert percentage to decimal
        
        return [
            'commission_rate' => $rate * 100, // Store as percentage
            'commission_amount' => $amount * $rate,
            'total_amount' => $amount + ($amount * $rate)
        ];
    }
} 