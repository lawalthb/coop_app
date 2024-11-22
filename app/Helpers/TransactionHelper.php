<?php

namespace App\Helpers;

use App\Models\Transaction;

class TransactionHelper
{

  //  use App\Helpers\TransactionHelper;

// Record entrance fee
//TransactionHelper::recordTransaction($userId, 'entrance_fee', 0, 5000);

// Record withdrawal
//TransactionHelper::recordTransaction($userId, 'withdrawal', 10000, 0);

    public static function recordTransaction($userId, $type, $debitAmount = 0, $creditAmount = 0, $status = 'completed', $description = null)
    {
        // Set default description based on type if not provided
        if (!$description) {
            $description = match($type) {
                'entrance_fee' => 'Entrance Fee Payment',
                'entrance_fee_used' => 'Entrance Fee Accepted',
                'savings' => 'Monthly Savings Contribution',
                'shares' => 'Share Capital Contribution',
                'loan' => 'Loan Disbursement',
                'loan_repayment' => 'Loan Repayment',
                'withdrawal' => 'Withdrawal Transaction',
                default => ucfirst(str_replace('_', ' ', $type))
            };
        }

        return Transaction::create([
            'user_id' => $userId,
            'type' => $type,
            'debit_amount' => $debitAmount,
            'credit_amount' => $creditAmount,
            'description' => $description,
            'status' => $status
        ]);
    }
}
