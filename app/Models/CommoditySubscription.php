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
        'admin_notes'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }
}
