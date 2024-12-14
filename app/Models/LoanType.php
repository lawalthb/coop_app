<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanType extends Model
{
    protected $fillable = [
        'name',
        'required_active_savings_months',
        'savings_multiplier',
        'interest_rate_12_months',
        'interest_rate_18_months',
        'max_duration_months',
        'minimum_amount',
        'maximum_amount',
        'allow_early_payment',
        'saved_percentage',
        'no_guarantors',
        'status'
    ];

    protected $casts = [
        'required_active_savings_months' => 'integer',
        'savings_multiplier' => 'decimal:2',
        'interest_rate_12_months' => 'decimal:2',
        'interest_rate_18_months' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
        'allow_early_payment' => 'boolean',
    ];

    public function getInterestRateForDuration($months)
    {
        if ($months <= 12) {
            return $this->interest_rate_12_months;
        }
        return $this->interest_rate_18_months;
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
