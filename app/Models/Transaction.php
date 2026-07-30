<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'afghan_wallet_id',
        'dollar_wallet_id',
        'amount',
        'currency_type', // 'AFN' or 'USD'
        'transaction_type', // 'deposit', 'withdraw', 'transfer', 'order', 'conversion', 'refund'
        'description',
        'status', // 'pending', 'completed', 'failed', 'cancelled'
        'reference_id', // Can reference an order ID, transfer request ID, etc.
        'reference_type', // Model class name (e.g., Order::class, TransferRequest::class)
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user who made the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Afghan wallet associated with this transaction.
     */
    public function afghanWallet()
    {
        return $this->belongsTo(AfghanWallet::class);
    }

    /**
     * Get the Dollar wallet associated with this transaction.
     */
    public function dollarWallet()
    {
        return $this->belongsTo(DollarWallet::class);
    }

    /**
     * Get the related model based on reference_id and reference_type.
     */
    public function reference()
    {
        if (!$this->reference_type || !$this->reference_id) {
            return null;
        }

        return app($this->reference_type)->find($this->reference_id);
    }
} 