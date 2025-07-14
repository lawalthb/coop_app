<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    protected $fillable = [
        'loan_id',
        'reference',
        'amount',
        'payment_date',
        'payment_method',
        'status',
        'notes',
        'posted_by',
        'month_id',
        'year_id'
    ];

    protected $casts = [
        'payment_date' => 'date'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function month()
    {
        return $this->belongsTo(Month::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }
}
