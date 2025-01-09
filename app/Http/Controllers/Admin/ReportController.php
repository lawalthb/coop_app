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
            ->paginate(15);

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
        $entranceFees = Transaction::where('type', 'entrance_fee')
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->date_from, function($query, $date) {
                return $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function($query, $date) {
                return $query->whereDate('created_at', '<=', $date);
            })
            ->with('user')
            ->paginate(15);

        return view('admin.reports.entrance-fees', compact('entranceFees'));
    }

    public function shares(Request $request)
    {
        $shares = Share::with(['user', 'shareType'])
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->date_from, function($query, $date) {
                return $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function($query, $date) {
                return $query->whereDate('created_at', '<=', $date);
            })
            ->paginate(15);

        return view('admin.reports.shares', compact('shares'));
    }

    public function loans(Request $request)
    {
        $loans = Loan::with(['user', 'loanType'])
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->date_from, function($query, $date) {
                return $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function($query, $date) {
                return $query->whereDate('created_at', '<=', $date);
            })
            ->paginate(15);

        return view('admin.reports.loans', compact('loans'));
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
            ->paginate(15);

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
}




