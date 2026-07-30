<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SystemSetting;

class CurrencyConversionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_currency', // 'AFN', 'USD'
        'to_currency', // 'AFN', 'USD'
        'amount',
        'conversion_rate',
        'converted_amount',
        'fee_percentage',
        'status', // 'pending', 'approved', 'rejected', 'completed'
        'admin_id', // Admin who processed this request
        'admin_notes',
        'user_notes',
        'approved_at',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'conversion_rate' => 'decimal:8',
        'converted_amount' => 'decimal:2',
        'fee_percentage' => 'decimal:2',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user who made the request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who processed the request.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the transaction for this conversion.
     */
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'reference');
    }

    /**
     * Process the currency conversion after user confirmation
     */
    public function processConversion()
    {
        // Allow processing for both approved and completed status
        if ($this->status !== 'approved' && $this->status !== 'completed') {
            return false;
        }

        // Make sure user and wallets are loaded
        $this->load('user');
        if (!$this->user || !$this->user->afghanWallet || !$this->user->dollarWallet) {
            return false;
        }

        // Make sure conversion rate and converted amount are set
        if (!$this->conversion_rate || !$this->converted_amount) {
            // Get the fee percentage (default to system settings if not stored)
            $feePercentage = $this->fee_percentage ?? 
                ($this->from_currency === 'USD' ? 
                    SystemSetting::getSetting('dollar_to_afghani_fee', 0.5) : 
                    SystemSetting::getSetting('afghani_to_dollar_fee', 1));
            
            // Calculate fee amount
            $feeAmount = ($this->amount * $feePercentage) / 100;
            $amountAfterFee = $this->amount - $feeAmount;
            
            // Calculate conversion rate and converted amount
            if ($this->from_currency === 'USD' && $this->to_currency === 'AFN') {
                $this->conversion_rate = SystemSetting::getSetting('usd_to_afn_rate', 83.5);
                $this->converted_amount = $amountAfterFee * $this->conversion_rate;
            } else {
                $this->conversion_rate = SystemSetting::getSetting('afn_to_usd_rate', 0.012);
                $this->converted_amount = $amountAfterFee * $this->conversion_rate;
            }
            
            $this->save();
        }
        
        // Process based on direction
        if ($this->from_currency === 'AFN' && $this->to_currency === 'USD') {
            // Deposit to USD wallet
            $this->user->dollarWallet->deposit($this->converted_amount);
            $this->user->dollarWallet->save();
        } else {
            // Deposit to AFN wallet
            $this->user->afghanWallet->deposit($this->converted_amount);
            $this->user->afghanWallet->save();
        }

        // Update status
        $this->status = 'completed';
        $this->completed_at = now();
        $this->save();

        // Create transaction records for the withdrawal (if not already created)
        $withdrawalTransaction = Transaction::where('reference_id', $this->id)
            ->where('reference_type', self::class)
            ->where('transaction_type', 'conversion')
            ->where('currency_type', $this->from_currency)
            ->first();
            
        if (!$withdrawalTransaction) {
            Transaction::create([
                'user_id' => $this->user_id,
                'afghan_wallet_id' => ($this->from_currency === 'AFN') ? $this->user->afghanWallet->id : null,
                'dollar_wallet_id' => ($this->from_currency === 'USD') ? $this->user->dollarWallet->id : null,
                'amount' => $this->amount,
                'currency_type' => $this->from_currency,
                'transaction_type' => 'conversion',
                'description' => "Conversion from {$this->amount} {$this->from_currency} to {$this->converted_amount} {$this->to_currency}",
                'status' => 'completed',
                'reference_id' => $this->id,
                'reference_type' => self::class,
            ]);
        } else {
            // Update existing transaction status
            $withdrawalTransaction->status = 'completed';
            $withdrawalTransaction->save();
        }

        // Create deposit transaction
        Transaction::create([
            'user_id' => $this->user_id,
            'afghan_wallet_id' => ($this->to_currency === 'AFN') ? $this->user->afghanWallet->id : null,
            'dollar_wallet_id' => ($this->to_currency === 'USD') ? $this->user->dollarWallet->id : null,
            'amount' => $this->converted_amount,
            'currency_type' => $this->to_currency,
            'transaction_type' => 'deposit',
            'description' => "Deposit from currency conversion {$this->amount} {$this->from_currency} to {$this->converted_amount} {$this->to_currency}",
            'status' => 'completed',
            'reference_id' => $this->id,
            'reference_type' => self::class,
        ]);

        return true;
    }
} 