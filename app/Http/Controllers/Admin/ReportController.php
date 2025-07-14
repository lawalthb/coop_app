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
use App\Models\Saving;
use App\Models\Year;
use App\Exports\SavingsSummaryExport;

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
    $months = Month::all();
    $years = Year::all();

    $query = Loan::with(['user', 'loanType']);

    if (request('loan_type_id')) {
        $query->where('loan_type_id', request('loan_type_id'));
    }
    if (request('status')) {
        $query->where('status', request('status'));
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

    // Calculate totals
    $totalLoans = Loan::where('status', 'approved')->sum('amount');
    $activeLoans = Loan::where('status', 'approved')->sum('amount');
    $totalRepayments = Loan::where('status', 'approved')->sum('amount_paid');
    $outstandingBalance = $totalLoans - $totalRepayments;

    $loans = $query->latest()->paginate(50);

    return view('admin.reports.loans', compact(
        'loans',
        'loanTypes',
        'months',
        'years',
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
    try {
        // Build the query with filters
        $query = Loan::with(['user', 'loanType']);

        if (request('loan_type_id')) {
            $query->where('loan_type_id', request('loan_type_id'));
        }
        if (request('status')) {
            $query->where('status', request('status'));
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

        // Get all loans for the PDF (not paginated)
        $loans = $query->latest()->get();

        // Calculate statistics
        $totalLoans = Loan::where('status', 'approved')->sum('amount');
        $activeLoans = Loan::where('status', 'approved')->sum('amount');
        $totalRepayments = Loan::where('status', 'approved')->sum('amount_paid');
        $outstandingBalance = $totalLoans - $totalRepayments;

        // Load the PDF view with all required data
        $pdf = PDF::loadView('admin.reports.loans-pdf', compact(
            'loans',
            'totalLoans',
            'activeLoans',
            'totalRepayments',
            'outstandingBalance'
        ));

        // Download the PDF
        return $pdf->download('loans-report.pdf');
    } catch (\Exception $e) {
        // Log the error
        \Log::error('PDF Generation Error: ' . $e->getMessage());

        // Return with error message
        return back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
    }
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
    $query = Saving::query()
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

    $savings = $query->with(['user', 'savingType'])->latest()->paginate(100);

    // Calculate totals
    $totalSavings = $query->sum('amount');
    $activeSavers = $query->distinct('user_id')->count();
    $monthlyAverage = $query->whereMonth('created_at', now()->month)->avg('amount') ?? 0;

    return view('admin.reports.savings', compact(
        'savings',
        'savingTypes',
        'members',
        'totalSavings',
        'activeSavers',
        'monthlyAverage'
    ));
}

public function savingsExcel()
{
    // Define the headers for the Excel export
    $headers = [
        'Date',
        'Member ID',
        'Member Name',
        'Saving Type',
        'Amount',
        'Status'
    ];

    // Get the savings data
    $savings = Transaction::where('type', 'savings')
        ->with(['user', 'savingType'])
        ->get();

    // Format the data for Excel
    $data = [];
    foreach ($savings as $saving) {
        $data[] = [
            'date' => $saving->created_at->format('M d, Y'),
            'member_id' => $saving->user->member_no ?? 'N/A',
            'member_name' => $saving->user->surname . ' ' . $saving->user->firstname,
            'saving_type' => $saving->savingType->name ?? 'Regular Savings',
            'amount' => $saving->credit_amount,
            'status' => ucfirst($saving->status)
        ];
    }

    // Pass both headers and data to the SavingsExport constructor
    return Excel::download(new SavingsExport($headers, $data), 'savings-report.xlsx');
}

public function savingsPdf(Request $request)
{

       ini_set('memory_limit', '5024M');

 try {
        // Get page number from request or default to 1
        $page = $request->input('page', 1);
        $perPage = 500;

        // Paginate the data
        $savings = Transaction::where('type', 'savings')
            ->with('user')
            ->latest()
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        // Calculate totals for this page only
        $totalSavings = $savings->sum('credit_amount');
        $activeSavers = $savings->pluck('user_id')->unique()->count();
        $monthlyAverage = $savings->where('created_at', '>=', now()->startOfMonth())
                                ->where('created_at', '<=', now()->endOfMonth())
                                ->avg('credit_amount') ?? 0;

        // Add page information
        $pageInfo = [
            'current' => $page,
            'total' => ceil(Transaction::where('type', 'savings')->count() / $perPage)
        ];

        // Load the PDF view with all required data
        $pdf = PDF::loadView('admin.reports.savings-pdf', compact(
            'savings',
            'totalSavings',
            'activeSavers',
            'monthlyAverage',
            'pageInfo'
        ));

        // Download the PDF
        return $pdf->download("savings-report-page-{$page}.pdf");
    } catch (\Exception $e) {
        // Log the error
        \Log::error('PDF Generation Error: ' . $e->getMessage());

        // Return with error message
        return back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
    }
}

public function savingsSummary(Request $request)
{
    $query = User::where('is_admin', false)
        ->when($request->status, function($query, $status) {
            return $query->where('status', $status);
        })
        ->when($request->search, function($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->where('member_no', 'like', "%{$search}%")
                  ->orWhere('firstname', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%");
            });
        });

    $members = $query->withSum('savings', 'amount')
                    ->withSum(['withdrawals' => function($query) {
                        $query->where('status', 'completed');
                    }], 'amount')
                    ->paginate(500);

    // Calculate totals for each member
    $membersWithTotals = $members->map(function($member) {
        $totalSaved = $member->savings_sum_amount ?? 0;
        $totalWithdrawn = $member->withdrawals_sum_amount ?? 0;
        $balance = $totalSaved - $totalWithdrawn;

        $member->total_saved = $totalSaved;
        $member->total_withdrawn = $totalWithdrawn;
        $member->balance = $balance;

        return $member;
    });

    // Calculate overall totals
    $overallTotals = [
        'total_saved' => $membersWithTotals->sum('total_saved'),
        'total_withdrawn' => $membersWithTotals->sum('total_withdrawn'),
        'total_balance' => $membersWithTotals->sum('balance'),
        'active_savers' => $membersWithTotals->where('total_saved', '>', 0)->count()
    ];

    return view('admin.reports.savings-summary', compact('members', 'overallTotals'));
}

public function savingsSummaryExcel(Request $request)
{
    
    $filters = [
        'status' => $request->status,
        'search' => $request->search,
    ];

    $filename = 'savings-summary-report-' . date('Y-m-d-H-i-s') . '.xlsx';

    return Excel::download(new SavingsSummaryExport($filters), $filename);
}
}
