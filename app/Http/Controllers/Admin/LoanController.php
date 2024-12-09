<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\TransactionHelper;
use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanType;
use App\Models\User;
use App\Notifications\LoanStatusNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['user', 'loanType', 'approvedBy', 'postedBy'])
            ->latest()
            ->paginate(10);

        return view('admin.loans.index', compact('loans'));
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

        // Calculate loan details
        $interestAmount = ($validated['amount'] * $loanType->interest_rate * $validated['duration']) / 100;
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

        NotificationService::notify(
            $loan->user_id,
            'Loan Application Submitted',
            'Your loan application has been submitted successfully.',
            'loan_application',
            ['loan_id' => $loan->id]
        );

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
}
