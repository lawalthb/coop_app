<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Month;
use App\Models\MonthlySavingsSetting;
use App\Models\Saving;
use App\Models\Transaction;
use App\Models\SavingType;
use App\Models\Withdrawal;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberSavingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $savingTypes = SavingType::active()->get();

        $savingsData = [];
        $totalSavingsAmount = 0;
        foreach ($savingTypes as $type) {
            $amount = Saving::where('user_id', $user->id)
                ->where('saving_type_id', $type->id)
                ->sum('amount');
            $savingsData[$type->id] = $amount;
            $totalSavingsAmount += $amount;
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

        // Calculate total withdrawals (approved only)
        $totalWithdrawals = Withdrawal::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('amount');

        // Calculate savings balance
        $savingsBalance = $totalSavingsAmount - $totalWithdrawals;

        return view('member.savings.index', compact(
            'savingTypes',
            'savingsData',
            'monthlyContributions',
            'recentTransactions',
            'currentMonthTotal',
            'totalWithdrawals',
            'totalSavingsAmount',
            'savingsBalance',
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

    public function withdrawalHistory(Request $request)
    {
        $user = auth()->user();

        $withdrawals = Withdrawal::where('user_id', $user->id)
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->start_date, function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->end_date);
            })
            ->with(['savingType', 'approvedBy'])
            ->latest()
            ->paginate(15);

        return view('member.savings.withdrawal-history', compact('withdrawals'));
    }

    // Add these methods to the MemberSavingsController

public function showSavingsSettings()
{
    $user = auth()->user();
    $savingTypes = SavingType::all();
    $months = Month::all();
    $years = Year::orderBy('year', 'desc')->get();

    $settings = MonthlySavingsSetting::where('user_id', $user->id)
        ->with(['savingType', 'month', 'year'])
        ->orderBy('year_id', 'desc')
    ->orderBy('month_id', 'desc')
        ->paginate(20);

    return view('member.savings.settings.index', compact('settings', 'savingTypes', 'months', 'years'));
}

public function createSavingsSetting()
{
    $savingTypes = SavingType::all();
    $months = Month::all();
    $years = Year::orderBy('year', 'desc')->get();

    return view('member.savings.settings.create', compact('savingTypes', 'months', 'years'));
}

public function storeSavingsSetting(Request $request)
{
    $validated = $request->validate([
        'saving_type_id' => 'required|exists:saving_types,id',
        'month_id' => 'required|exists:months,id',
        'year_id' => 'required|exists:years,id',
        'amount' => 'required|numeric|min:0',
    ]);

    // Check if a setting already exists for this combination
    $existingSetting = MonthlySavingsSetting::where([
        'user_id' => auth()->id(),
        'saving_type_id' => $validated['saving_type_id'],
        'month_id' => $validated['month_id'],
        'year_id' => $validated['year_id'],
    ])->first();

    if ($existingSetting) {
        return redirect()->back()->with('error', 'A savings setting already exists for this type, month, and year. Please edit the existing setting instead.');
    }

    // Create new setting
    $setting = MonthlySavingsSetting::create([
        'user_id' => auth()->id(),
        'saving_type_id' => $validated['saving_type_id'],
        'month_id' => $validated['month_id'],
        'year_id' => $validated['year_id'],
        'amount' => $validated['amount'],
       // 'status' => 'pending',
          'status' => 'approved', // Set to approved for immediate application
        'approved_by' => auth()->id(),
        'approved_at' => now(),
    ]);

    return redirect()->route('member.savings.settings.index')
        ->with('success', 'Monthly savings amount set successfully. It will be applied after admin approval.');
}

public function editSavingsSetting(MonthlySavingsSetting $setting)
{
    // Ensure the user can only edit their own settings
    if ($setting->user_id !== auth()->id()) {
        abort(403);
    }

    // Only allow editing of pending settings
    if ($setting->status !== 'pending') {
        return redirect()->route('member.savings.settings.index')
            ->with('error', 'Only pending settings can be edited.');
    }

    $savingTypes = SavingType::all();
    $months = Month::all();
    $years = Year::orderBy('year', 'desc')->get();

    return view('member.savings.settings.edit', compact('setting', 'savingTypes', 'months', 'years'));
}

public function updateSavingsSetting(Request $request, MonthlySavingsSetting $setting)
{
    // Ensure the user can only update their own settings
    if ($setting->user_id !== auth()->id()) {
        abort(403);
    }

    // Only allow updating of pending settings
    if ($setting->status !== 'pending') {
        return redirect()->route('member.savings.settings.index')
            ->with('error', 'Only pending settings can be updated.');
    }

    $validated = $request->validate([
        'amount' => 'required|numeric|min:0',
    ]);

    $setting->update([
        'amount' => $validated['amount'],
        'status' => 'pending', // Reset to pending if it was previously rejected
    ]);

    return redirect()->route('member.savings.settings.index')
        ->with('success', 'Monthly savings amount updated successfully.');
}

public function destroySavingsSetting(MonthlySavingsSetting $setting)
{
    // Ensure the user can only delete their own settings
    if ($setting->user_id !== auth()->id()) {
        abort(403);
    }

    // Only allow deleting of pending settings
    if ($setting->status !== 'pending') {
        return redirect()->route('member.savings.settings.index')
            ->with('error', 'Only pending settings can be deleted.');
    }

    $setting->delete();

    return redirect()->route('member.savings.settings.index')
        ->with('success', 'Monthly savings setting deleted successfully.');
}

}
