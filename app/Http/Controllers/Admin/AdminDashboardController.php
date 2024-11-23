<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Loan;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalMembers = User::where('is_admin', false)->count();
        $totalSavings = Transaction::where('type', 'savings_deposit')->sum('credit_amount');
        $activeLoans = Loan::where('status', 'approved')->count();
        $pendingApprovals = User::where('admin_sign', null)->count();

        $recentMembers = User::where('is_admin', false)
            ->latest()
            ->take(5)
            ->get();

        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalMembers',
            'totalSavings',
            'activeLoans',
            'pendingApprovals',
            'recentMembers',
            'recentTransactions'
        ));
    }
}
