<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\LoanType;
use Illuminate\Http\Request;

class LoanCalculatorController extends Controller
{
    public function index()
    {
        $loanTypes = LoanType::where('status', 'active')->get();
        return view('member.loan-calculator.index', compact('loanTypes'));
    }

    public function checkEligibility(Request $request)
    {
        $validated = $request->validate([
            'loan_type_id' => 'required|exists:loan_types,id',
            'amount' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1|max:18'
        ]);

        $member = auth()->user();
        $loanType = LoanType::findOrFail($validated['loan_type_id']);

        $eligibility = [
            'eligible' => true,
            'messages' => [],
        ];

        // Check savings duration
        $savingsDuration = $member->getSavingsDuration();
        if ($savingsDuration < $loanType->required_active_savings_months) {
            $eligibility['eligible'] = false;
            $eligibility['messages'][] = "You need {$loanType->required_active_savings_months} months of active savings. Current: {$savingsDuration} months";
        }

        // Calculate total active savings
        $totalActiveSavings = $member->savings()
            ->whereNot('remark', 'used_for_loan')
            ->sum('amount');

        // Check maximum amount based on savings multiplier
        $maxLoanBasedOnSavings = $totalActiveSavings * $loanType->savings_multiplier;
        if ($validated['amount'] > $maxLoanBasedOnSavings) {
            $eligibility['eligible'] = false;
            $eligibility['messages'][] = "Based on your active savings of ₦" . number_format($totalActiveSavings, 2) .
                                       ", you can borrow up to ₦" . number_format($maxLoanBasedOnSavings, 2) .
                                       " (". $loanType->savings_multiplier . "x your savings)";
        }

        // Check maximum amount from loan type
        if ($validated['amount'] > $loanType->maximum_amount) {
            $eligibility['eligible'] = false;
            $eligibility['messages'][] = "Requested amount exceeds maximum allowed amount of ₦" . number_format($loanType->maximum_amount, 2);
        }

        $interestRate = $validated['duration'] <= 12 ? $loanType->interest_rate_12_months : $loanType->interest_rate_18_months;

        $totalInterest = ($validated['amount'] * $interestRate / 100);
        $totalAmount = $validated['amount'] + $totalInterest;
        $monthlyRepayment = $totalAmount / $validated['duration'];

        $loanDetails = [
            'principal' => $validated['amount'],
            'interest_rate' => $interestRate,
            'total_interest' => $totalInterest,
            'total_amount' => $totalAmount,
            'monthly_repayment' => $monthlyRepayment,
            'duration' => $validated['duration']
        ];

        return view('member.loan-calculator.results', compact('eligibility', 'loanDetails', 'loanType'));
    }


}
