<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commodity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity_available',
        'is_active',
        'start_date',
        'end_date',
        'image',
        'purchase_amount',
        'target_sales_amount',
        'profit_amount',
        'allow_installment',
        'max_installment_months',
        'monthly_installment_amount',
        'initial_deposit_percentage',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'allow_installment' => 'boolean',
        'price' => 'decimal:2',
        'purchase_amount' => 'decimal:2',
        'target_sales_amount' => 'decimal:2',
        'profit_amount' => 'decimal:2',
        'monthly_installment_amount' => 'decimal:2',
        'initial_deposit_percentage' => 'decimal:2',
    ];

    public function applications()
    {
        return $this->hasMany(CommodityApplication::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(CommoditySubscription::class);
    }
}
