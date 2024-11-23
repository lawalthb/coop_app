<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user'])
            ->latest()
            ->paginate(20);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        return view('admin.transactions.show', compact('transaction'));
    }

    public function export(Request $request)
    {
        $transactions = Transaction::with(['user'])
            ->when($request->start_date, function($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->end_date);
            })
            ->when($request->type, function($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->get();

        return Excel::download(new TransactionsExport($transactions), 'transactions.xlsx');
    }
}
