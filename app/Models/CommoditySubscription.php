<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommoditySubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'commodity_id',
        'quantity',
        'total_amount',
        'status',
        'note',
         'reference',
        'admin_notes',
        'unit_price',
        'approved_at',
        'rejected_at',
        'admin_notes',
        'payment_type',
        'initial_deposit',
        'monthly_amount',
        'installment_months',
        'months_paid',
        'next_payment_date',
        'is_completed'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'total_amount' => 'decimal:2',
         'total_amount' => 'decimal:2',
        'initial_deposit' => 'decimal:2',
        'monthly_amount' => 'decimal:2',
        'is_completed' => 'boolean',
        'next_payment_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }

     public function payments()
    {
        return $this->hasMany(CommodityPayment::class);
    }

    // Helper methods
    public function getRemainingAmountAttribute()
    {
        if ($this->payment_type === 'full' || $this->is_completed) {
            return 0;
        }

        $totalPaid = $this->initial_deposit + ($this->monthly_amount * $this->months_paid);
        return $this->total_amount - $totalPaid;
    }

    public function getPaymentProgressPercentageAttribute()
    {
        if ($this->payment_type === 'full' || $this->is_completed) {
            return 100;
        }

        $totalPaid = $this->initial_deposit + ($this->monthly_amount * $this->months_paid);
        return min(100, round(($totalPaid / $this->total_amount) * 100));
    }

    
}
