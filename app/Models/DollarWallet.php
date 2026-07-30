<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DollarWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transactions for this wallet.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'dollar_wallet_id');
    }

    /**
     * Add to wallet balance
     * 
     * @param float $amount
     * @return bool
     */
    public function deposit($amount)
    {
        if ($amount <= 0) {
            return false;
        }

        $this->balance += $amount;
        return $this->save();
    }

    /**
     * Subtract from wallet balance
     * 
     * @param float $amount
     * @return bool
     */
    public function withdraw($amount)
    {
        if ($amount <= 0 || $amount > $this->balance) {
            return false;
        }

        $this->balance -= $amount;
        return $this->save();
    }
} 