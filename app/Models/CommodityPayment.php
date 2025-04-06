<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommodityPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'commodity_subscription_id',
        'amount',
        'payment_date',
        'payment_type', // 'full_payment', 'initial_deposit', 'installment'
        'status', // 'pending', 'approved', 'rejected'
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(CommoditySubscription::class, 'commodity_subscription_id');
    }
}
