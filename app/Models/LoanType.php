<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanType extends Model
{
    protected $fillable = [
        'name',
        'required_active_savings_months',
        'savings_multiplier',
        'interest_rate',
        'duration_months',
        'minimum_amount',
        'maximum_amount',
        'allow_early_payment',
        'saved_percentage',
        'no_guarantors',
        'status',
         'application_fee'
    ];

    protected $casts = [
        'required_active_savings_months' => 'integer',
        'savings_multiplier' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'duration_months' => 'integer',
        'minimum_amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
        'allow_early_payment' => 'boolean',
          'application_fee' => 'decimal:2',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
