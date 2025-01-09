<?php

namespace App\Imports;

use App\Models\Loan;
use App\Models\User;
use App\Models\LoanType;
use App\Helpers\TransactionHelper;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LoansImport implements ToModel, WithValidation, WithHeadingRow
{
    public function model(array $row)
    {
        $user = User::where('email', $row['member_email'])->first();
        $loanType = LoanType::find($row['loan_type_id']);

        $loan_interest = $row['duration'] > 12 ? $loanType->interest_rate_18_months : $loanType->interest_rate_12_months;

        $interestAmount = ($row['amount'] * $loan_interest * $row['duration']) / 100;
        $totalAmount = $row['amount'] + $interestAmount;
        $monthlyPayment = $totalAmount / $row['duration'];

        $loan = new Loan([
            'user_id' => $user->id,
            'loan_type_id' => $row['loan_type_id'],
            'reference' => 'LOAN-' . date('Y') . '-' . Str::random(8),
            'amount' => $row['amount'],
            'interest_amount' => $interestAmount,
            'total_amount' => $totalAmount,
            'duration' => $row['duration'],
            'monthly_payment' => $monthlyPayment,
            'start_date' => $row['start_date'],
            'end_date' => Carbon::parse($row['start_date'])->addMonths($row['duration']),
            'purpose' => $row['purpose'],
            'status' => $row['status'],
            'posted_by' => auth()->id()
        ]);

        TransactionHelper::recordTransaction(
            $user->id,
            'loan_disbursement',
            $row['amount'],
            0,
            'completed',
            'Loan Disbursement - ' . $loan->reference
        );

        return $loan;
    }

    public function rules(): array
    {
        return [
            'member_email' => ['required', 'email', 'exists:users,email'],
            'loan_type_id' => ['required', 'exists:loan_types,id'],
            'amount' => ['required', 'numeric'],
            'duration' => ['required', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'purpose' => ['required', 'string']
        ];
    }
}
