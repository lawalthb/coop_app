<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Saving;
use App\Models\Transaction;
use App\Models\SavingType;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class MemberWithdrawalController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $savingTypes = SavingType::withdrawable()->where('status', 'active')->get();

        $balances = [];
        foreach ($savingTypes as $type) {
            $deposits = Saving::where('user_id', $user->id)
                ->where('saving_type_id', $type->id)

                ->sum('amount');

            $withdrawals = Withdrawal::where('user_id', $user->id)
                ->where('saving_type_id', $type->id)


                ->sum('amount');

            $balances[$type->id] = $deposits - $withdrawals;
        }

        return view('member.withdrawals.create', compact('savingTypes', 'balances'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'saving_type_id' => 'required|exists:saving_types,id',
            'amount' => 'required|numeric|min:1000',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
            'reason' => 'required|string|max:500',
        ]);

        $user = auth()->user();

        // Check available balance
        $deposits = Saving::where('user_id', $user->id)
             ->where('saving_type_id', $validated['saving_type_id'])

            ->sum('amount');

        $withdrawals = Withdrawal::where('user_id', $user->id)
             ->where('saving_type_id', $validated['saving_type_id'])

            ->sum('amount');

        $availableBalance = $deposits - $withdrawals;

        if ($validated['amount'] > $availableBalance) {
            return back()->withErrors(['amount' => 'Insufficient balance']);
        }

        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'saving_type_id' => $validated['saving_type_id'],

            'amount' => $validated['amount'],
            'reference' => 'WTH-' . strtoupper(uniqid()),
            'status' => 'pending',
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'],
            'reason' => $validated['reason'],
        ]);

        return redirect()->route('member.withdrawals.index')->with('success', 'Withdrawal request submitted successfully');
    }

    public function index()
    {
        $withdrawals = Withdrawal::where('user_id', auth()->id())
            ->with('savingType')
            ->latest()
            ->paginate(10);

        return view('member.withdrawals.index', compact('withdrawals'));
    }
}
