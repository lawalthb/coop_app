<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Month;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TransactionSummaryController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        $moneyType = $request->input('money_type', 'credit');
        $departmentId = $request->input('department_id');
        $facultyId = $request->input('faculty_id');

        // Get all available years, months, departments, and faculties for the filter form
        $years = Year::orderBy('year', 'desc')->get();
        $months = Month::all();
        $departments = Department::orderBy('name')->get();
        $faculties = Faculty::orderBy('name')->get();

        // Build the query to get transaction data
        $query = Transaction::query()
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.member_no',
                'users.surname',
                'users.firstname',
                'users.othername',
                'users.department_id',
                'users.faculty_id'
            )
            ->whereYear('transactions.transaction_date', $year)
            ->whereMonth('transactions.transaction_date', $month);

        // Apply department filter if selected
        if ($departmentId) {
            $query->where('users.department_id', $departmentId);
        }

        // Apply faculty filter if selected
        if ($facultyId) {
            $query->where('users.faculty_id', $facultyId);
        }

        // Get distinct transaction types based on money type
        if ($moneyType === 'debit') {
            $query->where('transactions.debit_amount', '>', 0);
            $distinctTypes = Transaction::where('debit_amount', '>', 0)
                ->distinct()
                ->pluck('type')
                ->toArray();
        } else {
            $query->where('transactions.credit_amount', '>', 0);
            $distinctTypes = Transaction::where('credit_amount', '>', 0)
                ->distinct()
                ->pluck('type')
                ->toArray();
        }

        // Group by user to get unique members
        $query->groupBy(
            'users.id',
            'users.member_no',
            'users.surname',
            'users.firstname',
            'users.othername',
            'users.department_id',
            'users.faculty_id'
        );

        // Get paginated results
        $members = $query->paginate(20);

        // For each member, get their transaction data for each distinct type
        foreach ($members as $member) {
            $transactionData = [];
            $total = 0;

            foreach ($distinctTypes as $type) {
                $typeQuery = Transaction::where('user_id', $member->user_id)
                    ->where('type', $type)
                    ->whereYear('transaction_date', $year)
                    ->whereMonth('transaction_date', $month);

                if ($moneyType === 'debit') {
                    $amount = $typeQuery->sum('debit_amount');
                } else {
                    $amount = $typeQuery->sum('credit_amount');
                }

                $transactionData[$type] = $amount;
                $total += $amount;
            }

            $member->transaction_data = $transactionData;
            $member->total = $total;
        }

        return view('admin.reports.transaction-summary', compact(
            'members',
            'distinctTypes',
            'years',
            'months',
            'departments',
            'faculties',
            'year',
            'month',
            'moneyType',
            'departmentId',
            'facultyId'
        ));
    }

    public function exportCsv(Request $request)
    {
        // Get filter parameters
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        $moneyType = $request->input('money_type', 'credit');
        $departmentId = $request->input('department_id');
        $facultyId = $request->input('faculty_id');

        // Build the query to get transaction data
        $query = Transaction::query()
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.member_no',
                'users.surname',
                'users.firstname',
                'users.othername',
                'users.department_id',
                'users.faculty_id'
            )
            ->whereYear('transactions.transaction_date', $year)
            ->whereMonth('transactions.transaction_date', $month);

        // Apply department filter if selected
        if ($departmentId) {
            $query->where('users.department_id', $departmentId);
        }

        // Apply faculty filter if selected
        if ($facultyId) {
            $query->where('users.faculty_id', $facultyId);
        }

        // Get distinct transaction types based on money type
        if ($moneyType === 'debit') {
            $query->where('transactions.debit_amount', '>', 0);
            $distinctTypes = Transaction::where('debit_amount', '>', 0)
                ->distinct()
                ->pluck('type')
                ->toArray();
        } else {
            $query->where('transactions.credit_amount', '>', 0);
            $distinctTypes = Transaction::where('credit_amount', '>', 0)
                ->distinct()
                ->pluck('type')
                ->toArray();
        }

        // Group by user to get unique members
        $query->groupBy(
            'users.id',
            'users.member_no',
            'users.surname',
            'users.firstname',
            'users.othername',
            'users.department_id',
            'users.faculty_id'
        );

        // Get all results for export
        $members = $query->get();

        // For each member, get their transaction data for each distinct type
        foreach ($members as $member) {
            $transactionData = [];
            $total = 0;

            foreach ($distinctTypes as $type) {
                $typeQuery = Transaction::where('user_id', $member->user_id)
                    ->where('type', $type)
                    ->whereYear('transaction_date', $year)
                    ->whereMonth('transaction_date', $month);

                if ($moneyType === 'debit') {
                    $amount = $typeQuery->sum('debit_amount');
                } else {
                    $amount = $typeQuery->sum('credit_amount');
                }

                $transactionData[$type] = $amount;
                $total += $amount;
            }

            $member->transaction_data = $transactionData;
            $member->total = $total;
        }

        // Generate CSV content
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transaction_summary_' . $year . '_' . $month . '.csv"',
        ];

        $callback = function() use ($members, $distinctTypes) {
            $file = fopen('php://output', 'w');

            // Add CSV header row
            $headerRow = ['SN', 'Member No', 'Full Name'];
            foreach ($distinctTypes as $type) {
                $headerRow[] = ucwords(str_replace('_', ' ', $type));
            }
            $headerRow[] = 'Total';
            fputcsv($file, $headerRow);

            // Add data rows
            $sn = 1;
            foreach ($members as $member) {
                $row = [
                    $sn++,
                    $member->member_no,
                    $member->surname . ' ' . $member->firstname . ' ' . $member->othername
                ];

                foreach ($distinctTypes as $type) {
                    $row[] = number_format($member->transaction_data[$type] ?? 0, 2);
                }

                $row[] = number_format($member->total, 2);
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        // Get filter parameters
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        $moneyType = $request->input('money_type', 'credit');
        $departmentId = $request->input('department_id');
        $facultyId = $request->input('faculty_id');

        // Get month name
        $monthName = Carbon::createFromDate($year, $month, 1)->format('F');

        // Build the query to get transaction data
        $query = Transaction::query()
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.member_no',
                'users.surname',
                'users.firstname',
                'users.othername',
                'users.department_id',
                'users.faculty_id'
            )
            ->whereYear('transactions.transaction_date', $year)
            ->whereMonth('transactions.transaction_date', $month);

        // Apply department filter if selected
        if ($departmentId) {
            $query->where('users.department_id', $departmentId);
        }

        // Apply faculty filter if selected
        if ($facultyId) {
            $query->where('users.faculty_id', $facultyId);
        }

        // Get distinct transaction types based on money type
        if ($moneyType === 'debit') {
            $query->where('transactions.debit_amount', '>', 0);
            $distinctTypes = Transaction::where('debit_amount', '>', 0)
                ->distinct()
                ->pluck('type')
                ->toArray();
        } else {
            $query->where('transactions.credit_amount', '>', 0);
            $distinctTypes = Transaction::where('credit_amount', '>', 0)
                ->distinct()
                ->pluck('type')
                ->toArray();
        }

        // Group by user to get unique members
        $query->groupBy(
            'users.id',
            'users.member_no',
            'users.surname',
            'users.firstname',
            'users.othername',
            'users.department_id',
            'users.faculty_id'
        );

        // Get all results for export
        $members = $query->get();

        // For each member, get their transaction data for each distinct type
        foreach ($members as $member) {
            $transactionData = [];
            $total = 0;

            foreach ($distinctTypes as $type) {
                $typeQuery = Transaction::where('user_id', $member->user_id)
                    ->where('type', $type)
                    ->whereYear('transaction_date', $year)
                    ->whereMonth('transaction_date', $month);

                if ($moneyType === 'debit') {
                    $amount = $typeQuery->sum('debit_amount');
                } else {
                    $amount = $typeQuery->sum('credit_amount');
                }

                $transactionData[$type] = $amount;
                $total += $amount;
            }

            $member->transaction_data = $transactionData;
            $member->total = $total;
        }

        // Get department and faculty names if filters are applied
        $departmentName = null;
        if ($departmentId) {
            $department = Department::find($departmentId);
            $departmentName = $department ? $department->name : null;
        }

        $facultyName = null;
        if ($facultyId) {
            $faculty = Faculty::find($facultyId);
            $facultyName = $faculty ? $faculty->name : null;
        }

        // Generate PDF
        $pdf = PDF::loadView('admin.reports.transaction-summary-pdf', [
            'members' => $members,
            'distinctTypes' => $distinctTypes,
            'year' => $year,
            'month' => $month,
            'monthName' => $monthName,
            'moneyType' => $moneyType,
            'departmentName' => $departmentName,
            'facultyName' => $facultyName
        ]);

        return $pdf->download('transaction_summary_' . $year . '_' . $month . '.pdf');
    }
}
