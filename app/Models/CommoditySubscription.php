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
        'reason',
        'admin_notes',
        'approved_at',
        'rejected_at',
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
