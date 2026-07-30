<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HesabPayPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'phone_number',
        'tracking_code',
        'session_id',
        'transaction_id',
        'payment_url',
        'status',
        'description',
        'response_data',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transaction associated with this payment.
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'reference_id', 'tracking_code')
            ->where('reference_type', 'hesabpay_payment');
    }
} 