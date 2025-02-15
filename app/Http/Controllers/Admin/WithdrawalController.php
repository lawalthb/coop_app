<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\User;
use App\Models\SavingType;
use App\Helpers\TransactionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WithdrawalController extends Controller
{
    public function create()
    {
      $members = User::where('is_admin', false)
        ->orderBy('surname')
        ->get();
        $savingTypes = SavingType::all();
        return view('admin.withdrawals.create', compact('members', 'savingTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'saving_type_id' => 'required|exists:saving_types,id',
            'amount' => 'required|numeric|min:0',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
            'reason' => 'required|string'
        ]);

        $withdrawal = Withdrawal::create([
            'user_id' => $validated['user_id'],
            'saving_type_id' => $validated['saving_type_id'],
            'reference' => 'WTH-' . Str::random(10),
            'amount' => $validated['amount'],
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'],
            'reason' => $validated['reason'],
            'status' => 'completed',
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);

        // Record the transaction
        TransactionHelper::recordTransaction(
            $validated['user_id'],
            'withdrawal',
            0,
            $validated['amount'],
            'completed',
            'Savings Withdrawal - ' . $withdrawal->reference
        );

        return redirect()->route('admin.withdrawals.create')
            ->with('success', 'Withdrawal recorded successfully');
    }
}
