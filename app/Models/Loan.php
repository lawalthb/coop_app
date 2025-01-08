<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'loan_type_id',
        'reference',
        'amount',
        'interest_amount',
        'total_amount',
        'duration',
        'monthly_payment',
        'start_date',
        'end_date',
        'status',
        'purpose',
        'approved_by',
        'approved_at',
        'posted_by',
        'guarantor_name',
        'guarantor_phone',
        'guarantor_address',
        'guarantor_relationship',
        'guarantor_member_no'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loanType()
    {
        return $this->belongsTo(LoanType::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function repayments()
    {
        return $this->hasMany(LoanRepayment::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function guarantors()
    {
        return $this->hasMany(LoanGuarantor::class);
    }


}


