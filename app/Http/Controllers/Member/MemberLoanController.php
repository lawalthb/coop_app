<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanGuarantor;
use App\Models\LoanType;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\LoanGuarantorRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;


class MemberLoanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $activeLoans = Loan::where('user_id', $user->id)
            ->where('status', 'approved')
            ->with('loanType')
            ->get();

        $loanHistory = Loan::where('user_id', $user->id)
            ->with('loanType')
            ->latest()
            ->get();

        $availableLoanTypes = LoanType::all();

        return view('member.loans.index', compact('activeLoans', 'loanHistory', 'availableLoanTypes'));
    }
    public function create()
    {
        $loanTypes = LoanType::where('status', 'active')->get();
        $members = User::where('is_admin', 0)
            ->where('id', '!=', auth()->id())
            ->get();
        return view('member.loans.create', compact('loanTypes', 'members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_type_id' => 'required|exists:loan_types,id',
            'amount' => 'required|numeric|min:1000',
            'duration' => 'required|integer|min:1',
            'purpose' => 'required|string|max:500',
            'guarantor_ids' => 'required|array',
            'guarantor_ids.*' => 'required|exists:users,id'
        ]);

        $loanType = LoanType::find($validated['loan_type_id']);

        // Validate number of guarantors matches loan type requirement
        if (count($validated['guarantor_ids']) !== $loanType->no_guarantors) {
            return back()->withInput()->withErrors([
                'guarantor_ids' => "This loan type requires exactly {$loanType->no_guarantors} guarantor(s)"
            ]);
        }

        // Calculate loan details
        $interestAmount = ($validated['amount'] * $loanType->interest_rate * $validated['duration']) / 100;
        $totalAmount = $validated['amount'] + $interestAmount;
        $monthlyPayment = $totalAmount / $validated['duration'];
        $startDate = now();
        $endDate = Carbon::parse($startDate)->addMonths((int)$validated['duration']);

        $loan = Loan::create([
            'user_id' => auth()->id(),
            'reference' => 'LOAN-' . strtoupper(uniqid()),
            'loan_type_id' => $validated['loan_type_id'],
            'amount' => $validated['amount'],
            'interest_amount' => $interestAmount,
            'total_amount' => $totalAmount,
            'monthly_payment' => $monthlyPayment,
            'duration' => $validated['duration'],
            'purpose' => $validated['purpose'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'pending',
            'posted_by' => auth()->id(),
        ]);

        // Create guarantor records and send notifications
        foreach ($validated['guarantor_ids'] as $guarantorId) {
            LoanGuarantor::create([
                'loan_id' => $loan->id,
                'user_id' => $guarantorId,
                'status' => 'pending'
            ]);

            $guarantor = User::find($guarantorId);
            Notification::send($guarantor, new LoanGuarantorRequest($loan));
        }

        return redirect()->route('member.loans.index')
            ->with('success', 'Loan application submitted successfully. Waiting for guarantor approval.');
    }

    public function show(Loan $loan)
    {
        //  $this->authorize('view', $loan);

        $repayments = $loan->repayments()
            ->latest()
            ->get();

        return view('member.loans.show', compact('loan', 'repayments'));
    }

    protected function authorize($ability, $arguments = [])
    {
        if (!auth()->user()->can($ability, $arguments)) {
            abort(403);
        }
    }
}
