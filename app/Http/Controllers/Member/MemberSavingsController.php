<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Month;
use App\Models\Saving;
use App\Models\Transaction;
use App\Models\SavingType;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberSavingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $savingTypes = SavingType::all();

        $savingsData = [];
        foreach ($savingTypes as $type) {
            $savingsData[$type->id] = Saving::where('user_id', $user->id)
                ->where('saving_type_id', $type->id)
                ->sum('amount');
        }

        $monthlyContributions = Saving::where('user_id', $user->id)
            ->whereMonth('month_id', now()->month)
            ->sum('amount');

        $recentTransactions = Saving::where('user_id', $user->id)
            ->with('savingType')
            ->latest()
            ->take(10)
            ->get();

        // Calculate current month's total payments
        $current_year_id = Year::where('year', now()->year)->first()->id;
        $currentMonthTotal = Saving::where('user_id', $user->id)
            ->where('month_id', now()->month)
            ->where('year_id', $current_year_id)
            ->sum('amount');

        return view('member.savings.index', compact(
            'savingTypes',
            'savingsData',
            'monthlyContributions',
            'recentTransactions',
            'currentMonthTotal',
        ));
    }

    public function savingsHistory(Request $request)
    {
        $user = auth()->user();

        $transactions = SavingType::where('user_id', $user->id)
            ->when($request->type, function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->start_date, function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->end_date);
            })
            ->latest()
            ->paginate(15);

        return view('member.savings.history', compact('transactions'));
    }

    public function monthlySummary(Request $request)
    {
        try {
            $user = Auth::user();
            $selectedYear = $request->input('year', date('Y'));

            // Get all years for the dropdown (with error handling)
            $years = Year::orderBy('year', 'desc')->get();

            if ($years->isEmpty()) {
                return redirect()->route('member.savings')
                    ->with('error', 'No years found in the system.');
            }

            // Get the year_id for the selected year
            $yearRecord = Year::where('year', $selectedYear)->first();

            if (!$yearRecord) {
                return redirect()->route('member.savings')
                    ->with('error', 'Selected year not found in the system.');
            }

            // Get all months (with error handling)
            $months = Month::orderBy('id')->get();

            if ($months->isEmpty()) {
                return redirect()->route('member.savings')
                    ->with('error', 'No months found in the system.');
            }

            // Get all saving types (with error handling)
            $savingTypes = SavingType::all();

            if ($savingTypes->isEmpty()) {
                return redirect()->route('member.savings')
                    ->with('error', 'No saving types found in the system.');
            }

            // Initialize the data structure for the summary
            $summary = [];

            foreach ($savingTypes as $type) {
                $summary[$type->id] = [
                    'name' => $type->name,
                    'months' => [],
                    'total' => 0
                ];

                // Initialize each month with 0
                foreach ($months as $month) {
                    $summary[$type->id]['months'][$month->id] = 0;
                }
            }

            // Get all savings for the user in the selected year
            $savings = Saving::where('user_id', $user->id)
                ->where('year_id', $yearRecord->id)
                ->get();

            // Fill in the actual savings amounts
            foreach ($savings as $saving) {
                if (isset($summary[$saving->saving_type_id])) {
                    $summary[$saving->saving_type_id]['months'][$saving->month_id] += $saving->amount;
                    $summary[$saving->saving_type_id]['total'] += $saving->amount;
                }
            }

            return view('member.savings.monthly-summary', [
                'summary' => $summary ?: [], // Ensure summary is always an array
                'months' => $months,
                'years' => $years,
                'selectedYear' => $selectedYear,
            ]);

        } catch (\Exception $e) {
            \Log::error('Monthly Summary Error: ' . $e->getMessage());
            return redirect()->route('member.savings')
                ->with('error', 'An error occurred while generating the monthly summary.');
        }
    }
}
