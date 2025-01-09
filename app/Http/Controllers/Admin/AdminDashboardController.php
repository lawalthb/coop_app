<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Loan;
use App\Models\Share;
use App\Models\Saving;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Member Statistics
        $totalMembers = User::where('is_admin', false)->where('admin_sign', 'Yes')->count();
        $newMembersThisMonth = User::where('is_admin', false)
            ->whereMonth('created_at', now()->month)
            ->count();
        $totalAdmins = User::where('is_admin', true)->count();

        // Financial Statistics
        $totalSavings = Transaction::where('type', 'savings')->sum('credit_amount');
        $monthlySavings = Transaction::where('type', 'savings')
            ->whereMonth('created_at', now()->month)
            ->sum('credit_amount');

        $totalShares = Share::where('status', 'approved')->sum('amount_paid');
        $totalShareUnits = Share::where('status', 'approved')->sum('number_of_units');

        // Loan Statistics
        $activeLoans = Loan::where('status', 'approved')
        ->whereRaw('amount > paid_amount')
        ->count();

        $totalLoanAmount = Loan::where('status', 'approved')->sum('amount');
        $totalRepayments = Loan::where('status', 'approved')->sum('paid_amount');
        $outstandingLoans = $totalLoanAmount - $totalRepayments;


        // Monthly Transaction Data for Charts
        $monthlyData = collect(range(1, 12))->map(function($month) {
            return [
                'month' => Carbon::create()->month($month)->format('M'),
                'savings' => Transaction::where('type', 'savings')
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', now()->year)
                    ->sum('credit_amount'),
                'loans' => Loan::whereMonth('created_at', $month)
                    ->whereYear('created_at', now()->year)
                    ->sum('amount'),
                'shares' => Share::whereMonth('created_at', $month)
                    ->whereYear('created_at', now()->year)
                    ->sum('amount_paid')
            ];
        });

        // Recent Activities
        $recentMembers = User::where('is_admin', false)
            ->latest()
            ->take(5)
            ->get();

        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();

        $pendingLoans = Loan::where('status', 'pending')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalMembers',
            'newMembersThisMonth',
            'totalAdmins',
            'totalSavings',
            'monthlySavings',
            'totalShares',
            'totalShareUnits',
            'activeLoans',
            'totalLoanAmount',
            'totalRepayments',
            'outstandingLoans',
            'monthlyData',
            'recentMembers',
            'recentTransactions',
            'pendingLoans'
        ));
    }
}
