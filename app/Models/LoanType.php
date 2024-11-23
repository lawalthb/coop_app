<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanType extends Model
{
    protected $fillable = [
        'name',
        'interest_rate',
        'max_duration',
        'minimum_amount',
        'maximum_amount',
        'status'
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
