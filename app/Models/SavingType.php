<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'interest_rate',
        'minimum_balance',
        'is_mandatory',
        'allow_withdrawal',
        'withdrawal_restriction_days',
        'status'
    ];

    protected $casts = [
        'interest_rate' => 'decimal:2',
        'minimum_balance' => 'decimal:2',
        'is_mandatory' => 'boolean',
        'allow_withdrawal' => 'boolean'
    ];

    public function savings()
    {
        return $this->hasMany(Saving::class);
    }

    public function scopeWithdrawable($query)
    {
        return $query->where('name', 'not like', '%withdraw%');
    }

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'inactive');
    }
}
