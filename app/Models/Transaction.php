<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'debit_amount',
        'credit_amount',
        'balance',
        'reference',
        'description',
        'posted_by',
        'transaction_date',
        'status'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'debit_amount' => 'decimal:2',
        'credit_amount' => 'decimal:2',
        'balance' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->reference = 'TRX-' . date('Y') . '-' . Str::random(8);
            $transaction->transaction_date = now();
            $transaction->posted_by = auth()->id();

            // Calculate balance
            $previousBalance = self::where('user_id', $transaction->user_id)
                ->latest()
                ->first()
                ?->balance ?? 0;

            $transaction->balance = $previousBalance + $transaction->credit_amount - $transaction->debit_amount;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public static function recordEntranceFee($userId, $amount)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'entrance_fee',
            'credit_amount' => $amount,
            'description' => 'Entrance Fee Payment'
        ]);
    }

    public static function recordSavings($userId, $amount)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'savings',
            'credit_amount' => $amount,
            'description' => 'Monthly Savings Contribution'
        ]);
    }

    public static function recordWithdrawal($userId, $amount)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'withdrawal',
            'debit_amount' => $amount,
            'description' => 'Withdrawal'
        ]);
    }

    public static function recordLoan($userId, $amount)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'loan',
            'debit_amount' => $amount,
            'description' => 'Loan Disbursement'
        ]);
    }

    public static function recordLoanRepayment($userId, $amount)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'loan_repayment',
            'credit_amount' => $amount,
            'description' => 'Loan Repayment'
        ]);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function loanType()
    {
        return $this->belongsTo(LoanType::class);
    }

 public static function recordCommodityPayment($userId, $amount, $commodityName, $commodityId, $reference = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'commodity_payment',
            'credit_amount' => $amount,
            'debit_amount' => 0,
            'description' => 'Payment for ' . $commodityName . ' (Commodity ID: ' . $commodityId . ')',
            'reference' => $reference ?? 'COM-PAY-' . Str::random(8),
            'posted_by' => auth()->id(),
            'transaction_date' => now(),
            'status' => 'completed'
        ]);
    }

     // Helper method to get formatted amount based on transaction type
    public function getFormattedAmountAttribute()
    {
        if ($this->debit_amount > 0) {
            return '₦' . number_format($this->debit_amount, 2);
        } else {
            return '₦' . number_format($this->credit_amount, 2);
        }
    }

    // Helper method to determine if this is a debit or credit transaction
    public function getTransactionTypeAttribute()
    {
        return $this->debit_amount > 0 ? 'debit' : 'credit';
    }

    public function savingType()
    {
        return $this->belongsTo(SavingType::class);
    }

    
}



