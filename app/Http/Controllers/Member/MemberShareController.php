<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ShareTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;

class MemberShareController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalShares = ShareTransaction::where('user_id', $user->id)
            ->where('transaction_type', 'purchase')
            ->sum('amount');

        $monthlySubscription = $user->share_subscription;

        $recentShareTransactions = ShareTransaction::where('user_id', $user->id)
            ->where('transaction_type', 'purchase')
            ->latest()
            ->take(10)
            ->get();

        return view('member.shares.index', compact('totalShares', 'monthlySubscription', 'recentShareTransactions'));
    }
}
