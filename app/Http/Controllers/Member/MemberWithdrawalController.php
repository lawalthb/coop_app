<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Saving;
use App\Models\Transaction;
use App\Models\SavingType;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MemberWithdrawalController extends Controller
{
    public function create()
    {
        $savingTypes = SavingType::all();

        // Calculate available balance for each saving type
        $balances = [];
        foreach ($savingTypes as $type) {
                       $credits = Saving::where('user_id', auth()->id())
                ->where('saving_type_id', $type->id)
                ->where('status', 'completed')
                ->sum('amount');

            $withdrawals = Withdrawal::where('user_id', auth()->id())
                ->where('saving_type_id', $type->id)
                ->where('status', 'approved')
                ->sum('amount');

            $balances[$type->id] = $credits - $withdrawals;
        }

        return view('member.withdrawals.create', compact('savingTypes', 'balances'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'saving_type_id' => 'required|exists:saving_types,id',
            'amount' => 'required|numeric|min:1',
            'reason' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'account_name' => 'required|string|max:255',
        ]);

        // Check if member has sufficient balance
        $savingType = SavingType::find($validated['saving_type_id']);

        $credits = Saving::where('user_id', auth()->id())
            ->where('saving_type_id', $validated['saving_type_id'])
            ->where('status', 'completed')
            ->sum('amount');

        $withdrawals = Withdrawal::where('user_id', auth()->id())
            ->where('saving_type_id', $validated['saving_type_id'])
            ->where('status', 'approved')
            ->sum('amount');

        $availableBalance = $credits - $withdrawals;

        if ($validated['amount'] > $availableBalance) {
            return back()->withInput()->with('error', 'Insufficient balance. Your available balance is ₦' . number_format($availableBalance, 2));
        }

        // Generate a unique reference
        $reference = 'WDR-' . strtoupper(Str::random(8));

        // Create the withdrawal request
        $withdrawal = Withdrawal::create([
            'user_id' => auth()->id(),
            'saving_type_id' => $validated['saving_type_id'],
            'amount' => $validated['amount'],
            'reason' => $validated['reason'],
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'],
            'reference' => $reference,
            'status' => 'pending',
        ]);

        // Notify admins about the new withdrawal request (you can implement this)

        return redirect()->route('member.withdrawals.index')
            ->with('success', 'Withdrawal request submitted successfully. It will be processed by the admin.');
    }

    public function show(Withdrawal $withdrawal)
    {
        // Ensure the withdrawal belongs to the authenticated user
        if ($withdrawal->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('member.withdrawals.show', compact('withdrawal'));
    }



  public function index(Request $request)
{
    $query = Withdrawal::where('user_id', auth()->id())
        ->with('savingType');

    // Apply status filter if provided
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    // Get the filtered withdrawals
    $withdrawals = $query->latest()->paginate(10);

    // Calculate total amounts (without pagination)
    $totalWithdrawals = Withdrawal::where('user_id', auth()->id());
    $approvedWithdrawals = Withdrawal::where('user_id', auth()->id())
        ->where('status', 'completed');

    // Apply the same status filter to the totals if provided
    if ($request->has('status') && $request->status != '') {
        $totalWithdrawals->where('status', $request->status);
    }

    // Calculate the sums
    $totalAmount = $totalWithdrawals->sum('amount');
    $approvedAmount = $approvedWithdrawals->sum('amount');

    return view('member.withdrawals.index', compact(
        'withdrawals',
        'totalAmount',
        'approvedAmount'
    ));
}




}
