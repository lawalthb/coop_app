<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\User;
use App\Models\SavingType;
use App\Helpers\TransactionHelper;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
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


   public function index()
    {
        $savingTypes = SavingType::all();

        $query = Withdrawal::with(['user', 'savingType']);

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('saving_type_id')) {
            $query->where('saving_type_id', request('saving_type_id'));
        }

        if (request('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }

        if (request('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }

        $withdrawals = $query->latest()->paginate(10);

       // Calculate statistics
        $totalWithdrawals = Withdrawal::sum('amount');
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->sum('amount');
        $approvedWithdrawals = Withdrawal::where('status', 'approved')->sum('amount');

        return view('admin.withdrawals.index', compact(
            'withdrawals',
            'savingTypes',
            'totalWithdrawals',
            'pendingWithdrawals',
            'approvedWithdrawals'
        ));
    }
     public function show(Withdrawal $withdrawal)
    {
        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function approve(Withdrawal $withdrawal)
    {
        // Check if withdrawal is already processed
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'This withdrawal request has already been processed.');
        }

        try {
            DB::beginTransaction();

            // Update withdrawal status
            $withdrawal->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Record the transaction
            Transaction::create([
                'user_id' => $withdrawal->user_id,
                'type' => 'withdrawal',
                'amount' => $withdrawal->amount,
                'reference' => $withdrawal->reference,
                'status' => 'completed',
                'description' => 'Withdrawal - ' . $withdrawal->purpose,
            ]);

            DB::commit();

            // Send notification to member (you can implement this)

            return redirect()->route('admin.withdrawals.index')
                ->with('success', 'Withdrawal request approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Withdrawal $withdrawal)
    {
        // Check if withdrawal is already processed
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'This withdrawal request has already been processed.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            // Update withdrawal status
            $withdrawal->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Send notification to member (you can implement this)

            return redirect()->route('admin.withdrawals.index')
                ->with('success', 'Withdrawal request rejected successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

}
