<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Loan;
use App\Models\Share;
use App\Models\Saving;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\Commodity;
use App\Models\Resource;
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
            ->whereYear('created_at', now()->year)
            ->count();
        $totalAdmins = User::where('is_admin', true)->count();

        // Financial Statistics
        $totalSavings = Saving::sum('amount');
        $monthlySavings = Saving::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $totalShares = Share::where('status', 'approved')->sum('amount_paid');
        $totalShareUnits = Share::where('status', 'approved')->count();

        // Loan Statistics
        $activeLoans = Loan::where('status', 'approved')
            ->whereRaw('amount > COALESCE(paid_amount, 0)')
            ->count();

        $totalLoanAmount = Loan::where('status', 'approved')->sum('amount');
        $totalRepayments = Loan::where('status', 'approved')->sum('paid_amount');
        $outstandingLoans = $totalLoanAmount - $totalRepayments;

        // Withdrawal Statistics
        $totalWithdrawals = Withdrawal::where('status', 'completed')->sum('amount');
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();
        $monthlyWithdrawals =  Withdrawal::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('amount');
        // Loan Disbursement Statistics
        $totalLoanDisbursed = Loan::where('status', 'approved')->sum('amount');
        $totalLoansCount = Loan::where('status', 'approved')->count();

        // Commodity Statistics
        $totalCommodities = Commodity::where('is_active', true)->count();
        $totalCommodityValue = Commodity::where('is_active', true)->sum('target_sales_amount');

        // Resource Statistics
        $totalResources = Resource::where('status', 'active')->count();
        $totalResourcesSize = Resource::where('status', 'active')->sum('file_size');

        // Monthly Transaction Data for Charts
        $monthlyData = collect(range(1, 12))->map(function($month) {
            return [
                'month' => Carbon::create()->month($month)->format('M'),
                'savings' => Saving::whereMonth('created_at', $month)
                    ->whereYear('created_at', now()->year)
                    ->sum('amount'),
                'loans' => Loan::whereMonth('created_at', $month)
                    ->whereYear('created_at', now()->year)
                    ->where('status', 'approved')
                    ->sum('amount'),
                'shares' => Share::whereMonth('created_at', $month)
                    ->whereYear('created_at', now()->year)
                    ->where('status', 'approved')
                    ->sum('amount_paid'),
                'withdrawals' => Withdrawal::whereMonth('created_at', $month)
                    ->whereYear('created_at', now()->year)
                    ->where('status', 'approved')
                    ->sum('amount')
            ];
        });
        //saving balance
        $savingBalance = $totalSavings - $totalWithdrawals;

        // Recent Activities
        $recentMembers = User::where('is_admin', false)
            ->where('admin_sign', 'Yes')
            ->latest()
            ->take(5)
            ->get();

        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();

        $pendingLoans = Loan::where('status', 'pending')
            ->with(['user', 'loanType'])
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
            'totalWithdrawals',
            'pendingWithdrawals',
            'monthlyWithdrawals',
            'totalLoanDisbursed',
            'totalLoansCount',
            'totalCommodities',
            'totalCommodityValue',
            'totalResources',
            'totalResourcesSize',
            'monthlyData',
            'recentMembers',
            'recentTransactions',
            'pendingLoans',
            'savingBalance'
        ));
    }
}
