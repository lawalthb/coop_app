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
            $description = match ($type) {
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

    public static function updateTransactionStatus($userId, $type, $amount, $status, $description)
    {
        $transaction = Transaction::where([
            'user_id' => $userId,
            'type' => $type,
        ])
            ->where(function ($query) use ($amount) {
                $query->where('debit_amount', $amount)
                    ->orWhere('credit_amount', $amount);
            })
            ->latest()
            ->first();

        if ($transaction) {
            $transaction->update([
                'status' => $status,
                'description' => $description
            ]);
        }

        return true;
    }


    public static function generateUniqueMemberNo()
    {
        $base_number = \App\Models\User::count() + 1;
        $new_member_no = 'OASCMS-' . str_pad($base_number, 4, '0', STR_PAD_LEFT);

        while (\App\Models\User::where('member_no', $new_member_no)->exists()) {
            $base_number++;
            $new_member_no = 'OASCMS-' . str_pad($base_number, 4, '0', STR_PAD_LEFT);
        }

        return $new_member_no;
    }


}


