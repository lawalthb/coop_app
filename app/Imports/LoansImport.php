<?php

namespace App\Imports;

use App\Models\Loan;
use App\Models\User;
use App\Models\LoanType;
use App\Helpers\TransactionHelper;
use App\Models\LoanRepayment;
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
    $startDate = Carbon::createFromFormat('d/m/y', $row['start_date']);
    $currentDate = Carbon::now();

    // Calculate loan details using annual base formula
    $loan_interest = $row['percentage'];
    $interestAmount = ($row['amount'] * ($loan_interest/100) * ($row['duration']/12));
    $totalAmount = $row['amount'] + $interestAmount;
    $monthlyPayment = $totalAmount / $row['duration'];

    // Create the loan record
    $loan = new Loan([
        'user_id' => $user->id,
        'loan_type_id' => $row['loan_type_id'],
        'reference' => 'LOAN-' . date('Y') . '-' . Str::random(8),
        'amount' => $row['amount'],
        'interest_amount' => $interestAmount,
        'total_amount' => $totalAmount,
        'duration' => $row['duration'],
        'monthly_payment' => $monthlyPayment,
        'start_date' => $startDate,
        'end_date' => $startDate->copy()->addMonths($row['duration']),
        'purpose' => $row['purpose'],
        'status' => 'approved',
        'posted_by' => auth()->id(),
        'approved_by' => auth()->id(),
        'approved_at' => $startDate
    ]);

    $loan->save();

    // Calculate number of repayments needed
    $monthsDiff = $startDate->diffInMonths($currentDate);
    $repaymentsNeeded = min($monthsDiff, $row['duration']);

    // Track total repayments amount
    $totalRepayments = 0;

    // Process historical repayments
    for ($i = 1; $i <= $repaymentsNeeded; $i++) {
        $repaymentDate = $startDate->copy()->addMonths($i);

        // Create loan repayment record
        LoanRepayment::create([
            'loan_id' => $loan->id,
            'reference' => 'REP-' . date('Y') . '-' . Str::random(8),
            'amount' => $monthlyPayment,
            'payment_date' => $repaymentDate,
            'payment_method' => 'import',
            'notes' => 'Historical Loan Repayment Import',
            'posted_by' => auth()->id()
        ]);

        // Record transaction
        TransactionHelper::recordTransaction(
            $user->id,
            'loan_repayment',
            $monthlyPayment,
            0,
            'completed',
            'Historical Loan Repayment - ' . $loan->reference,
            $repaymentDate
        );

        // Add to total repayments
        $totalRepayments += $monthlyPayment;
    }

    // Update loan balance and status
    $remainingBalance = $totalAmount - $totalRepayments;
    $loan->amount_paid = $totalRepayments;
    $loan->balance = $remainingBalance;

    // If balance is less than 1 or zero, mark the loan as completed
    if ($remainingBalance <= 1) {
        $loan->status = 'completed';
    }

    $loan->save();

    // Record initial loan disbursement
    TransactionHelper::recordTransaction(
        $user->id,
        'loan_disbursement',
        $row['amount'],
        0,
        'completed',
        'Historical Loan Disbursement - ' . $loan->reference,
        $startDate
    );

    return $loan;
}



    public function rules(): array
{
    return [
        'member_email' => ['required', 'exists:users,email'],
        'loan_type_id' => ['required', 'exists:loan_types,id'],
        'amount' => ['required', 'numeric', 'min:0'],
        'duration' => ['required', 'integer', 'min:1'],
        'start_date' => ['required'],
        'purpose' => ['required', 'string'],
        'percentage' => ['required', 'numeric'],
        'status' => ['nullable', 'string', 'in:pending,approved,rejected']
    ];
}

}
