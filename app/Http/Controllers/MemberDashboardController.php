<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use App\Models\Withdrawal;
use App\Models\SavingType;
use Illuminate\Http\Request;

class MemberDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Calculate total savings
        $totalSavings = Saving::where('user_id', $user->id)
            ->sum('amount');

        // Calculate total withdrawals (only approved ones)
        $totalWithdrawals = Withdrawal::where('user_id', $user->id)
            ->where('status', 'completed') // Only count approved withdrawals
            ->sum('amount');

        // Calculate savings balance
        $savingsBalance = $totalSavings - $totalWithdrawals;

        $monthlyContribution = $user->monthly_savings ?? 0;
        $shareCapital = $user->share_subscription ?? 0;
        // Get recent savings transactions
        $recentTransactions = Saving::where('user_id', $user->id)
            ->with(['savingType', 'month', 'year'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();



        return view('member.dashboard', compact(
            'totalSavings',
            'totalWithdrawals',
            'savingsBalance',
            'monthlyContribution',
            'shareCapital',
            'recentTransactions',

        ));
    }
}
