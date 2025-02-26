<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LoansExport;
use App\Helpers\TransactionHelper;
use App\Http\Controllers\Controller;
use App\Imports\LoansImport;
use App\Models\Loan;
use App\Models\LoanType;
use App\Models\User;
use App\Notifications\LoanStatusNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LoanController extends Controller
{
public function index(Request $request)
{
    $query = Loan::with(['user', 'loanType', 'approvedBy', 'postedBy']);

    // Apply reference filter if provided
    if ($request->has('reference') && !empty($request->reference)) {
        $query->where('reference', $request->reference);
    }

    $loans = $query->latest()->paginate(50);

    // Get unique loan references with associated user information and loan type
    $loanReferences = Loan::select('loans.reference', 'users.firstname', 'users.surname', 'loan_types.name as loan_type_name')
        ->join('users', 'loans.user_id', '=', 'users.id')
        ->join('loan_types', 'loans.loan_type_id', '=', 'loan_types.id')
        ->groupBy('loans.reference', 'users.firstname', 'users.surname', 'loan_types.name')
        ->orderBy('users.surname')
        ->orderBy('users.firstname')
        ->get();

    // Append query parameters to pagination links
    if ($request->has('reference')) {
        $loans->appends(['reference' => $request->reference]);
    }

    return view('admin.loans.index', compact('loans', 'loanReferences'));
}





    public function create()
    {
        $members = User::where('is_admin', false)
            ->where('admin_sign', 'Yes')
            ->get();
        $loanTypes = LoanType::where('status', 'active')->get();

        return view('admin.loans.create', compact('members', 'loanTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'loan_type_id' => 'required|exists:loan_types,id',
            'amount' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'purpose' => 'required|string'
        ]);

        $loanType = LoanType::find($request->loan_type_id);
if($loanType ){
$loan_interest = $loanType->interest_rate;
}else{
$loan_interest = 10;
}
        // Calculate loan details for annual base
        $interestAmount = ($validated['amount'] * ($loan_interest/100) * ($validated['duration']/12));
        $totalAmount = $validated['amount'] + $interestAmount;
        $monthlyPayment = $totalAmount / $validated['duration'];

        $loan = Loan::create([
            'user_id' => $validated['user_id'],
            'loan_type_id' => $validated['loan_type_id'],
            'reference' => 'LOAN-' . date('Y') . '-' . Str::random(8),
            'amount' => $validated['amount'],
            'interest_amount' => $interestAmount,
            'total_amount' => $totalAmount,
            'duration' => $validated['duration'],
            'monthly_payment' => $monthlyPayment,
            'start_date' => $validated['start_date'],
            'end_date' => Carbon::parse($validated['start_date'])->addMonths((int)$validated['duration']),
            'purpose' => $validated['purpose'],
            'posted_by' => auth()->id()
        ]);

      $user = User::find($validated['user_id']);
$user->notify(new LoanStatusNotification($loan));



        return redirect()->route('admin.loans.index')
            ->with('success', 'Loan application submitted successfully');
    }

    public function show(Loan $loan)
    {
        return view('admin.loans.show', compact('loan'));
    }

    public function approve(Loan $loan)
    {
        $loan->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);
        // Record transaction
        TransactionHelper::recordTransaction(
            $loan->user_id,
            'loan_disbursement',
            $loan->amount,
            0,
            'completed',
            'Loan Disbursement - ' . $loan->reference
        );

        $user = $loan->user;
        $user->notify(new LoanStatusNotification($loan));
        return back()->with('success', 'Loan approved successfully');
    }

    public function reject(Loan $loan)
    {
        $loan->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return back()->with('success', 'Loan rejected');
    }


    public function import()
    {

        return view('admin.loans.import');
    }

public function processImport(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv'
    ]);

    $file = $request->file('file');

    try {
        Excel::import(new LoansImport, $file);
        return redirect()->route('admin.loans.index')
            ->with('success', 'Loans data imported successfully');
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        // Handle validation errors
        $failures = $e->failures();
        $errors = [];

        foreach ($failures as $failure) {
            $errors[] = "Row {$failure->row()}: {$failure->errors()[0]}";
        }

        return redirect()->route('admin.loans.import')
            ->with('error', implode('<br>', $errors))
            ->withInput();
    } catch (\Exception $e) {
        // Handle other errors
        return redirect()->route('admin.loans.import')
            ->with('error', 'Import failed: ' . $e->getMessage())
            ->withInput();
    }
}

public function downloadFormat()
{
    $headers = [
        'Member Email',
        'Loan Type ID',
        'Amount',
        'Duration',
        'Start Date (DD/MM/YY)',
        'Purpose',
        'Status'
    ];

    return Excel::download(new LoansExport($headers), 'loans_import_format.xlsx');
}



}


