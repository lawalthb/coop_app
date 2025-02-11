<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query()->with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $totalCredits = (clone $query)->whereIn('type', ['savings', 'entrance_fee'])->sum('credit_amount');
        $totalDebits = (clone $query)->where('type', 'withdraw')->sum('debit_amount');

        $transactions = $query->latest()->paginate(15);
        $members = User::where('is_admin', 0)->get();

        return view('admin.transactions.index', compact('transactions', 'members', 'totalCredits', 'totalDebits'));
    }

    public function show(Transaction $transaction)
    {
        return view('admin.transactions.show', compact('transaction'));
    }

    public function export(Request $request)
    {
        $filters = $request->only(['user_id', 'start_date', 'end_date', 'type']);

        return Excel::download(
            new TransactionsExport($filters),
            'transactions_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('admin.transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
