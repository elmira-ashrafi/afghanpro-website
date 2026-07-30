<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'province',
        'phone',
        'email',
        'contact_person',
        'is_active',
        'latitude',
        'longitude',
        'working_hours',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'working_hours' => 'array',
    ];

    /**
     * Get the orders associated with this agency.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the trade account requests associated with this agency.
     */
    public function tradeAccountRequests()
    {
        return $this->hasMany(TradeAccountRequest::class);
    }

    /**
     * Get the money transfers associated with this agency.
     */
    public function moneyTransfers()
    {
        return $this->hasMany(MoneyTransfer::class);
    }

    /**
     * Get the agency withdrawals associated with this agency.
     */
    public function agencyWithdrawals()
    {
        return $this->hasMany(AgencyWithdrawal::class);
    }
} 