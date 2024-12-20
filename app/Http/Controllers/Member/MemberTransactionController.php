<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class MemberTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        $totalCredits = Transaction::where('user_id', auth()->id())
          
            ->sum('credit_amount');

        $totalDebits = Transaction::where('user_id', auth()->id())

            ->sum('debit_amount');

        return view('member.transactions.index', compact('transactions', 'totalCredits', 'totalDebits'));
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        return view('member.transactions.show', compact('transaction'));
    }
}
