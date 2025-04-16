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
        'payment_method',
        'payment_reference',
        'status',
        'approved_by',
        'approved_at',
        'notes'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the subscription that this payment belongs to.
     */
    public function subscription()
    {
        return $this->belongsTo(CommoditySubscription::class, 'commodity_subscription_id');
    }

    /**
     * Get the admin who approved this payment.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
