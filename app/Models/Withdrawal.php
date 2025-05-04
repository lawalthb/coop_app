<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'saving_type_id',
        'reference',
        'amount',
        'bank_name',
        'account_number',
        'account_name',
        'reason',
        'status',
        'approved_at',
        'approved_by',
        'month_id',
        'year_id',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function savingType()
    {
        return $this->belongsTo(SavingType::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


}
