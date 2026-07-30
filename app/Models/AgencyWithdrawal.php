<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agency_id',
        'full_name',
        'phone',
        'city',
        'amount',
        'currency_type', // 'AFN' or 'USD'
        'wallet_type', // 'afghan_wallet' or 'dollar_wallet'
        'tracking_number',
        'status', // 'pending', 'approved', 'completed', 'rejected', 'cancelled'
        'description',
        'support_user_id',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the withdrawal request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the agency for this withdrawal.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the support user that processed the request.
     */
    public function supportUser()
    {
        return $this->belongsTo(User::class, 'support_user_id');
    }

    /**
     * Get the transaction for this request.
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'reference');
    }
} 