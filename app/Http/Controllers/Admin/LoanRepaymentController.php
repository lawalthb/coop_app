<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\TransactionHelper;
use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\User;
use App\Models\Month;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Exports\LoanRepaymentTemplateExport;

class LoanRepaymentController extends Controller
{


  public function index()
    {
        $loans = Loan::with(['user', 'loanType', 'repayments'])
            ->whereIn('status', ['approved', 'active', 'disbursed'])
            ->where('balance', '>', 0)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($loan) {
                // Calculate remaining months
                $startDate = Carbon::parse($loan->start_date);
                $endDate = Carbon::parse($loan->end_date);
                $currentDate = Carbon::now();

                // Total duration in months
                $totalMonths = $loan->duration;

                // Months elapsed since start
                $monthsElapsed = $startDate->diffInMonths($currentDate);

                // Remaining months
                $remainingMonths = max(0, $totalMonths - $monthsElapsed);

                // If end date has passed, set remaining months to 0
                if ($currentDate->gt($endDate)) {
                    $remainingMonths = 0;
                }

                $loan->remaining_months = $remainingMonths;
                $loan->months_elapsed = min($monthsElapsed, $totalMonths);
                $loan->is_overdue = $currentDate->gt($endDate) && $loan->balance > 0;

                return $loan;
            });

        return view('admin.loan-repayments.index', compact('loans'));
    }

    public function downloadTemplate()
    {
        return Excel::download(new LoanRepaymentTemplateExport, 'active_loans_template_' . date('Y-m-d') . '.xlsx');
    }

    public function upload()
    {
        $months = Month::all();
        $years = Year::all();
        return view('admin.loan-repayments.upload', compact('months', 'years'));
    }

    public function processUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);
dd($request);
        try {
            $file = $request->file('file');
            $paymentDate = $request->payment_date;
            $paymentMethod = $request->payment_method;
            $notes = $request->notes;

            // Read the Excel/CSV file
            $data = Excel::toArray([], $file)[0];

            // Remove header row if exists
            if (count($data) > 0 && !is_numeric($data[0][0])) {
                array_shift($data);
            }

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($data as $index => $row) {
                $rowNumber = $index + 2; // +2 because we removed header and arrays are 0-indexed

                try {
                    // Validate row data - now expecting 5 columns: email, loan_ref, amount, month_id, year_id
                    if (count($row) < 5) {
                        $errors[] = "Row {$rowNumber}: Insufficient data columns (expected 5: email, loan_reference, amount, month_id, year_id)";
                        $errorCount++;
                        continue;
                    }

                    $email = trim($row[0]);
                    $loanReference = trim($row[1]);
                    $amount = floatval($row[2]);
                    $monthId = intval($row[3]);
                    $yearId = intval($row[4]);

                    // Validate required fields
                    if (empty($email) || empty($loanReference) || $amount <= 0 || $monthId <= 0 || $yearId <= 0) {
                        $errors[] = "Row {$rowNumber}: Missing or invalid required data (Email: {$email}, Loan Ref: {$loanReference}, Amount: {$amount}, Month ID: {$monthId}, Year ID: {$yearId})";
                        $errorCount++;
                        continue;
                    }

                    // Find the user by email
                    $user = User::where('email', $email)->first();
                    if (!$user) {
                        $errors[] = "Row {$rowNumber}: Member with email '{$email}' not found";
                        $errorCount++;
                        continue;
                    }

                    // Validate month and year
                    $month = Month::find($monthId);
                    $year = Year::find($yearId);

                    if (!$month) {
                        $errors[] = "Row {$rowNumber}: Invalid month ID '{$monthId}'";
                        $errorCount++;
                        continue;
                    }

                    if (!$year) {
                        $errors[] = "Row {$rowNumber}: Invalid year ID '{$yearId}'";
                        $errorCount++;
                        continue;
                    }

                    // Find the loan
                    $loan = Loan::where('reference', $loanReference)
                        ->where('user_id', $user->id)
                        ->whereIn('status', ['approved', 'active', 'disbursed'])
                        ->where('balance', '>', 0)
                        ->first();

                    if (!$loan) {
                        $errors[] = "Row {$rowNumber}: Active loan with reference '{$loanReference}' not found for member '{$email}'";
                        $errorCount++;
                        continue;
                    }

                    // Check if amount exceeds loan balance
                    if ($amount > $loan->balance) {
                        $errors[] = "Row {$rowNumber}: Payment amount (₦" . number_format($amount, 2) . ") exceeds loan balance (₦" . number_format($loan->balance, 2) . ")";
                        $errorCount++;
                        continue;
                    }

                    // Check for duplicate repayment for same loan, month, and year
                    $existingRepayment = LoanRepayment::where('loan_id', $loan->id)
                        ->where('month_id', $monthId)
                        ->where('year_id', $yearId)
                        ->first();

                    if ($existingRepayment) {
                        $errors[] = "Row {$rowNumber}: Repayment already exists for this loan in {$month->name} {$year->year}";
                        $errorCount++;
                        continue;
                    }

                    // Create repayment record
                    $repayment = LoanRepayment::create([
                        'loan_id' => $loan->id,
                        'reference' => 'REP-' . date('Y') . '-' . Str::random(8),
                        'amount' => $amount,
                        'payment_date' => $paymentDate,
                        'payment_method' => $paymentMethod,
                        'notes' => $notes . " (Bulk upload - Row {$rowNumber} - {$month->name} {$year->year})",
                        'posted_by' => auth()->id(),
                        'month_id' => $monthId,
                        'year_id' => $yearId
                    ]);

                    // Record transaction
                    TransactionHelper::recordTransaction(
                        $loan->user_id,
                        'loan_repayment',
                        $amount,
                        0,
                        'completed',
                        'Loan Repayment - ' . $repayment->reference . ' (' . $month->name . ' ' . $year->year . ')'
                    );

                    // Update loan balance
                    $totalRepayments = $loan->repayments->sum('amount') + $amount;
                    $remainingBalance = $loan->total_amount - $totalRepayments;

                    // Update loan
                    $loan->amount_paid = $totalRepayments;
                    $loan->balance = $remainingBalance;

                    // If balance is less than 1 or zero, mark the loan as completed
                    if ($remainingBalance <= 1) {
                        $loan->status = 'completed';
                    }

                    $loan->save();
                    $successCount++;

                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    $errorCount++;
                    Log::error("Loan repayment upload error for row {$rowNumber}: " . $e->getMessage());
                }
            }

            DB::commit();

            // Prepare success message
            $message = "Upload completed! {$successCount} repayments processed successfully.";
            if ($errorCount > 0) {
                $message .= " {$errorCount} rows had errors.";
            }

            return redirect()->route('admin.loans.repayments.index')
                ->with('success', $message)
                ->with('upload_errors', $errors);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Loan repayment bulk upload failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Upload failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function create(Loan $loan)
    {
        $months = Month::all();
        $years = Year::all();
        return view('admin.loan-repayments.create', compact('loan', 'months', 'years'));
    }

   public function store(Request $request, Loan $loan)
{
    $validated = $request->validate([
        'amount' => 'required|numeric|min:0',
        'payment_date' => 'required|date',
        'payment_method' => 'required|string',
        'notes' => 'nullable|string',
        'month_id' => 'required|exists:months,id',
        'year_id' => 'required|exists:years,id'
    ]);

    // Check for duplicate repayment
    $existingRepayment = LoanRepayment::where('loan_id', $loan->id)
        ->where('month_id', $validated['month_id'])
        ->where('year_id', $validated['year_id'])
        ->first();

    if ($existingRepayment) {
        $month = Month::find($validated['month_id']);
        $year = Year::find($validated['year_id']);
        return redirect()->back()
            ->with('error', "Repayment already exists for this loan in {$month->name} {$year->year}")
            ->withInput();
    }

    $repayment = LoanRepayment::create([
        'loan_id' => $loan->id,
        'reference' => 'REP-' . date('Y') . '-' . Str::random(8),
        'amount' => $validated['amount'],
        'payment_date' => $validated['payment_date'],
        'payment_method' => $validated['payment_method'],
        'notes' => $validated['notes'],
        'posted_by' => auth()->id(),
        'month_id' => $validated['month_id'],
        'year_id' => $validated['year_id']
    ]);

    // Record transaction
    $month = Month::find($validated['month_id']);
    $year = Year::find($validated['year_id']);

    TransactionHelper::recordTransaction(
        $loan->user_id,
        'loan_repayment',
        $validated['amount'],
        0,
        'completed',
        'Loan Repayment - ' . $repayment->reference . ' (' . $month->name . ' ' . $year->year . ')'
    );

    // Update loan balance
    $totalRepayments = $loan->repayments->sum('amount') + $validated['amount'];
    $remainingBalance = $loan->total_amount - $totalRepayments;

    // Update loan balance and status if paid off
    $loan->amount_paid = $totalRepayments;
    $loan->balance = $remainingBalance;

    // If balance is less than 1 or zero, mark the loan as completed
    if ($remainingBalance <= 1) {
        $loan->status = 'completed';
    }

    $loan->save();

    return redirect()->route('admin.loans.repayments.index')
        ->with('success', 'Loan repayment recorded successfully');
    }
}
