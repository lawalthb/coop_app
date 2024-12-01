<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberDashboardController extends Controller
{
    public function index()
    {
      
        $user = auth()->user();

        $totalSavings = 0; // Calculate from savings table
        $monthlyContribution = $user->monthly_savings ?? 0;
        $shareCapital = $user->share_subscription ?? 0;
        $recentTransactions = []; // Get from transactions table

        return view('member.dashboard', compact(
            'totalSavings',
            'monthlyContribution',
            'shareCapital',
            'recentTransactions'
        ));
    }
}
