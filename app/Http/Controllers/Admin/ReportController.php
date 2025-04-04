<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Share;
use App\Models\Loan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Exports\MembersExport;
use App\Exports\AdminsExport;
use App\Exports\EntranceFeesExport;
use App\Exports\SavingsExport;
use App\Exports\SharesExport;
use App\Exports\LoansExport;
use App\Exports\TransactionsExport;
use App\Models\SavingType;
use App\Models\ShareType;
use App\Models\LoanType;
use App\Models\Month;
use App\Models\Year;

class ReportController extends Controller
{

    public function index()
    {
        return view('admin.reports.index');
    }


    public function members(Request $request)
    {
        $members = User::where('is_admin', false)
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->date_from, function($query, $date) {
                return $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function($query, $date) {
                return $query->whereDate('created_at', '<=', $date);
            })
            ->withCount(['shares', 'loans', 'transactions'])
            ->paginate(50);

        return view('admin.reports.members', compact('members'));
    }


    // Add these methods for each report type
    public function membersExcel()
    {
        return Excel::download(new MembersExport, 'members-report.xlsx');
    }

    public function membersPdf()
    {
        $members = User::where('is_admin', false)->get();
        $pdf = PDf::loadView('admin.reports.members-pdf', compact('members'));
        return $pdf->download('members-report.pdf');
    }


    public function admins()
    {
        $admins = User::where('is_admin', true)
            ->withCount(['approvedShares', 'approvedLoans'])
            ->paginate(15);

        return view('admin.reports.admins', compact('admins'));
    }

  public function entranceFees(Request $request)
{
    $query = Transaction::where('type', 'entrance_fee')
        ->when($request->status, function($query, $status) {
            return $query->where('status', $status);
        })
        ->when($request->date_from, function($query, $date) {
            return $query->whereDate('created_at', '>=', $date);
        })
        ->when($request->date_to, function($query, $date) {
            return $query->whereDate('created_at', '<=', $date);
        });

    // Get totals before pagination
    $totals = [
        'total_amount' => $query->sum('credit_amount'),
        'completed_count' => (clone $query)->where('status', 'completed')->count(),
        'pending_count' => (clone $query)->where('status', 'pending')->count()
    ];

    $entranceFees = $query->with('user')->paginate(50);

    return view('admin.reports.entrance-fees', compact('entranceFees', 'totals'));
}


 public function shares()
{
    $query = Share::with(['user', 'shareType']);

    if (request('share_type_id')) {
        $query->where('share_type_id', request('share_type_id'));
    }
    if (request('month_id')) {
        $query->where('month_id', request('month_id'));
    }
    if (request('year_id')) {
        $query->where('year_id', request('year_id'));
    }
    if (request('date_from')) {
        $query->whereDate('created_at', '>=', request('date_from'));
    }
    if (request('date_to')) {
        $query->whereDate('created_at', '<=', request('date_to'));
    }

    $totalAmount = $query->sum('amount_paid');
    $totalApproved = $query->whereNotNull('approved_by')->count();
    $totalNotyet =Share::with(['user', 'shareType'])->whereNull('approved_by')->count();
    $totalShareholders = $query->distinct('user_id')->count('user_id');

    $shares = $query->latest()->paginate(50);
    $shareTypes = ShareType::all();
    $months = Month::all();
    $years = Year::all();

    return view('admin.reports.shares', compact('shares', 'shareTypes', 'months', 'years', 'totalAmount', 'totalApproved', 'totalNotyet', 'totalShareholders'));
}

    public function loans()
    {
        $loanTypes = LoanType::all();
        $loans = Loan::with(['user', 'loanType'])
            ->latest()
            ->paginate(50);

        $totalLoans = Loan::where('status', 'approved')->sum('amount');
        $activeLoans = Loan::where('status', 'approved')->where('balance', '>', 0)->sum('amount');
        $totalRepayments = Loan::where('status', 'approved')->sum('amount_paid');
        $outstandingBalance = Loan::where('status', 'approved')->sum('balance');

        return view('admin.reports.loans', compact(
            'loans',
            'loanTypes',
            'totalLoans',
            'activeLoans',
            'totalRepayments',
            'outstandingBalance'
        ));
    }

    public function transactions(Request $request)
    {
        $transactions = Transaction::with('user')
            ->when($request->type, function($query, $type) {
                return $query->where('type', $type);
            })
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->date_from, function($query, $date) {
                return $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function($query, $date) {
                return $query->whereDate('created_at', '<=', $date);
            })
            ->paginate(100);

        return view('admin.reports.transactions', compact('transactions'));
    }

    public function adminsExcel()
    {
        return Excel::download(new AdminsExport, 'admins-report.xlsx');
    }

    public function adminsPdf()
    {
        $admins = User::where('is_admin', true)->get();
        $pdf = PDF::loadView('admin.reports.admins-pdf', compact('admins'));
        return $pdf->download('admins-report.pdf');
    }

    public function entranceFeesExcel()
    {
        return Excel::download(new EntranceFeesExport, 'entrance-fees-report.xlsx');
    }

    public function entranceFeesPdf()
    {
        $entranceFees = Transaction::where('type', 'entrance_fee')->with('user')->get();
        $pdf = PDF::loadView('admin.reports.entrance-fees-pdf', compact('entranceFees'));
        return $pdf->download('entrance-fees-report.pdf');
    }

    public function sharesExcel()
    {
        return Excel::download(new SharesExport, 'shares-report.xlsx');
    }

    public function sharesPdf()
    {
        $shares = Share::with(['user', 'shareType'])->get();
        $pdf = PDF::loadView('admin.reports.shares-pdf', compact('shares'));
        return $pdf->download('shares-report.pdf');
    }

    public function loansExcel()
    {
        return Excel::download(new LoansExport, 'loans-report.xlsx');
    }

    public function loansPdf()
    {
        $loans = Loan::with(['user', 'loanType'])->get();
        $pdf = PDF::loadView('admin.reports.loans-pdf', compact('loans'));
        return $pdf->download('loans-report.pdf');
    }

    public function transactionsExcel()
    {
        return Excel::download(new TransactionsExport, 'transactions-report.xlsx');
    }

    public function transactionsPdf()
    {
        $transactions = Transaction::with('user')->get();
        $pdf = PDF::loadView('admin.reports.transactions-pdf', compact('transactions'));
        return $pdf->download('transactions-report.pdf');
    }


  public function savings(Request $request)
{
    $query = Transaction::where('type', 'savings')
        ->when($request->member_id, function($query, $memberId) {
            return $query->where('user_id', $memberId);
        })
        ->when($request->saving_type, function($query, $type) {
            return $query->where('saving_type_id', $type);
        })
        ->when($request->date_from, function($query, $date) {
            return $query->whereDate('created_at', '>=', $date);
        })
        ->when($request->date_to, function($query, $date) {
            return $query->whereDate('created_at', '<=', $date);
        });

    // Get members for dropdown
    $members = User::where('is_admin', false)->get();

    // Get saving types
    $savingTypes = SavingType::all();

    $savings = $query->with(['user'])->latest()->paginate(10);

    // Calculate totals
    $totalSavings = $query->sum('credit_amount');
    $activeSavers = $query->distinct('user_id')->count();
    $monthlyAverage = $query->whereMonth('created_at', now()->month)->avg('credit_amount') ?? 0;

    return view('admin.reports.savings', compact(
        'savings',
        'savingTypes',
        'members',
        'totalSavings',
        'activeSavers',
        'monthlyAverage'
    ));
}


}





