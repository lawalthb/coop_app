<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanRepayment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoanRepaymentController extends Controller
{
    public function create(Loan $loan)
    {
        return view('admin.loan-repayments.create', compact('loan'));
    }

    public function store(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $repayment = LoanRepayment::create([
            'loan_id' => $loan->id,
            'reference' => 'REP-' . date('Y') . '-' . Str::random(8),
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'],
            'posted_by' => auth()->id()
        ]);

        // Record transaction
        TransactionHelper::recordTransaction(
            $loan->user_id,
            'loan_repayment',
            $validated['amount'],
            0,
            'completed',
            'Loan Repayment - ' . $repayment->reference
        );

        return redirect()->route('admin.loans.show', $loan)
            ->with('success', 'Loan repayment recorded successfully');
    }
}
