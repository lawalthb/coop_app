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
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2',
        'purchase_amount' => 'decimal:2',
        'target_sales_amount' => 'decimal:2',
        'profit_amount' => 'decimal:2',
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
