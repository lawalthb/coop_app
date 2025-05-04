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
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Throwable;

class LoansImport implements ToModel, WithValidation, WithHeadingRow, SkipsOnError
{
    use Importable, SkipsErrors;

    public function model(array $row)
    {
        try {
            Log::info('Processing loan import row', ['data' => $row]);

            // Check if member exists
            $user = User::where('email', $row['member_email'])->first();
            if (!$user) {
                Log::error('Loan import failed: Member not found', ['email' => $row['member_email']]);
                throw new \Exception("Member with email {$row['member_email']} not found");
            }

            // Check if loan type exists
            $loanType = LoanType::find($row['loan_type_id']);
            if (!$loanType) {
                Log::error('Loan import failed: Loan type not found', ['loan_type_id' => $row['loan_type_id']]);
                throw new \Exception("Loan type with ID {$row['loan_type_id']} not found");
            }

            // Replace the current date parsing code with this more flexible approach
            try {
                // First try standard format
                if (is_numeric($row['start_date'])) {
                    // Handle Excel serial date
                    $startDate = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['start_date']));
                } else {
                    // Try parsing as string format
                    $startDate = Carbon::createFromFormat('d/m/y', $row['start_date']);
                }
            } catch (\Exception $e) {
                Log::error('Loan import failed: Invalid date format', [
                    'start_date' => $row['start_date'],
                    'error' => $e->getMessage()
                ]);
                throw new \Exception("Invalid date format for start_date: {$row['start_date']}. Expected format: dd/mm/yy");
            }

            $currentDate = Carbon::now();

            // Calculate loan details using annual base formula
            $loan_interest = $row['percentage'];
            $interestAmount = ($row['amount'] * ($loan_interest/100) * ($row['duration']/12));
            $totalAmount = $row['amount'] + $interestAmount;
            $monthlyPayment = $totalAmount / $row['duration'];

            Log::info('Loan calculation details', [
                'principal' => $row['amount'],
                'interest_rate' => $loan_interest,
                'duration' => $row['duration'],
                'interest_amount' => $interestAmount,
                'total_amount' => $totalAmount,
                'monthly_payment' => $monthlyPayment
            ]);

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
            Log::info('Loan record created', ['loan_id' => $loan->id, 'reference' => $loan->reference]);

            // Calculate number of repayments needed
            $monthsDiff = $startDate->diffInMonths($currentDate);
            $repaymentsNeeded = min($monthsDiff, $row['duration']);

            Log::info('Processing historical repayments', [
                'months_diff' => $monthsDiff,
                'repayments_needed' => $repaymentsNeeded
            ]);

            // Track total repayments amount
            $totalRepayments = 0;

            // Process historical repayments
            for ($i = 1; $i <= $repaymentsNeeded; $i++) {
                $repaymentDate = $startDate->copy()->addMonths($i);

                try {
                    // Create loan repayment record
                    $repayment = LoanRepayment::create([
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

                    Log::info('Repayment record created', [
                        'repayment_id' => $repayment->id,
                        'loan_id' => $loan->id,
                        'amount' => $monthlyPayment,
                        'date' => $repaymentDate->format('Y-m-d')
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create repayment record', [
                        'loan_id' => $loan->id,
                        'repayment_number' => $i,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Update loan balance and status
            $remainingBalance = $totalAmount - $totalRepayments;
            $loan->amount_paid = $totalRepayments;
            $loan->balance = $remainingBalance;

            // If balance is less than 1 or zero, mark the loan as completed
            if ($remainingBalance <= 1) {
                $loan->status = 'completed';
                Log::info('Loan marked as completed', ['loan_id' => $loan->id, 'balance' => $remainingBalance]);
            }

            $loan->save();
            Log::info('Loan balance updated', [
                'loan_id' => $loan->id,
                'total_paid' => $totalRepayments,
                'balance' => $remainingBalance,
                'status' => $loan->status
            ]);

            // Record initial loan disbursement
            try {
                TransactionHelper::recordTransaction(
                    $user->id,
                    'loan_disbursement',
                    $row['amount'],
                    0,
                    'completed',
                    'Historical Loan Disbursement - ' . $loan->reference,
                    $startDate
                );
                Log::info('Loan disbursement transaction recorded', ['loan_id' => $loan->id, 'amount' => $row['amount']]);
            } catch (\Exception $e) {
                Log::error('Failed to record loan disbursement transaction', [
                    'loan_id' => $loan->id,
                    'error' => $e->getMessage()
                ]);
            }

            return $loan;

        } catch (\Exception $e) {
            Log::error('Loan import failed with exception', [
                'row' => $row,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
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

    /**
     * Handle errors during import
     */
    public function onError(Throwable $e)
    {
        Log::error('Loan import row skipped due to error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
}
