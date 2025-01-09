<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Share;
use App\Models\Loan;
use Illuminate\Http\Request;

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
}


