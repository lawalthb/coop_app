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

    $loanType = LoanType::find($request->loan_type_id);

    if (!$loanType) {
        return back()->withInput()->withErrors([
            'loan_type_id' => "Invalid loan type selected"
        ]);
    }

    // Prepare validation rules based on guarantor requirements
    $validationRules = [
        'loan_type_id' => 'required|exists:loan_types,id',
        'amount' => 'required|numeric|min:1000',
        'purpose' => 'required|string|max:500',
    ];
// Validate duration against loan type's maximum duration
    $request->validate([
        'duration' => [
            'required',
            'integer',
            'min:1',
            'max:' . $loanType->duration_months
        ]
    ], [
        'duration.max' => 'The loan duration cannot exceed ' . $loanType->duration_months . ' months for this loan type.'
    ]);


    // Only add guarantor validation if this loan type requires guarantors
    if ($loanType->no_guarantors > 0) {
        $validationRules['guarantor_ids'] = 'required|array';
        $validationRules['guarantor_ids.*'] = 'required|exists:users,id';
    }

    $validated = $request->validate($validationRules);
    $validated['duration'] = $request->duration;

    // Validate number of guarantors only if required
    if ($loanType->no_guarantors > 0) {
        if (count($request->guarantor_ids ?? []) !== $loanType->no_guarantors) {
            return back()->withInput()->withErrors([
                'guarantor_ids' => "This loan type requires exactly {$loanType->no_guarantors} guarantor(s)"
            ]);
        }
    }

  

    $interestAmount = ($validated['amount'] * $loan_interest) / 100;
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

    // Create guarantor records and send notifications only if guarantors are required
    if ($loanType->no_guarantors > 0 && isset($request->guarantor_ids)) {
        foreach ($request->guarantor_ids as $guarantorId) {
            LoanGuarantor::create([
                'loan_id' => $loan->id,
                'user_id' => $guarantorId,
                'status' => 'pending'
            ]);

            $guarantor = User::find($guarantorId);
            $guarantor->notify(new LoanGuarantorRequest($loan));
        }

        return redirect()->route('member.loans.index')
            ->with('success', 'Loan application submitted successfully. Waiting for guarantor approval.');
    }

    // If no guarantors required, return a different success message
    return redirect()->route('member.loans.index')
        ->with('success', 'Loan application submitted successfully. Waiting for admin approval.');
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

    public function respondToGuarantorRequest(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'response' => 'required|in:approved,rejected',
            'reason' => 'required_if:response,rejected|string|nullable'
        ]);

        $guarantor = LoanGuarantor::where('loan_id', $loan->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $guarantor->update([
            'status' => $validated['response'],
            'comment' => $validated['reason']
        ]);

        return redirect()->route('member.guarantor-requests')->with('success', 'Response submitted successfully');
    }

    public function guarantorRequests()
    {
        $guarantorRequests = LoanGuarantor::where('user_id', auth()->id())
            ->with(['loan.user'])
            ->latest()
            ->get();

        return view('member.loans.guarantor-requests', compact('guarantorRequests'));
    }


    public function showGuarantorRequest(Loan $loan)
    {
        // Verify the authenticated user is a guarantor for this loan
        $guarantorRequest = LoanGuarantor::where('loan_id', $loan->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('member.loans.guarantor-response', compact('loan', 'guarantorRequest'));
    }
}
