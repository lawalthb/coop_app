<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use App\Models\SavingType;
use Illuminate\Http\Request;

class MemberDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalSavings = Saving::where('user_id', $user->id)
            ->sum('amount');

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
            'monthlyContribution',
            'shareCapital',
            'recentTransactions',
          
        ));
    }
}
