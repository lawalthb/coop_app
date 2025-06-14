<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SavingsExport;
use App\Http\Controllers\Controller;
use App\Models\Saving;
use App\Models\User;
use App\Models\Month;
use App\Models\Year;
use App\Models\SavingType;
use App\Helpers\TransactionHelper;
use App\Imports\SavingsImport;
use App\Models\MonthlySavingsSetting;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SavingController extends Controller
{
   public function index(Request $request)
{
    $query = Saving::with(['user', 'savingType', 'month', 'year', 'postedBy']);

    if ($request->filled('month')) {
        $query->where('month_id', $request->month);
    }

    if ($request->filled('year')) {
        $query->where('year_id', $request->year);
    }

    if ($request->filled('type')) {
        $query->where('saving_type_id', $request->type);
    }

    // Calculate total savings based on the same filters
    $totalSavings = (clone $query)->sum('amount');

    $savings = $query->latest()->paginate(100);

    $months = Month::all();
    $years = Year::all();
    $savingTypes = SavingType::where('status', 'active')->get();

    return view('admin.savings.index', compact('savings', 'months', 'years', 'savingTypes', 'totalSavings'));
}



   public function create()
{
    $members = User::where('is_admin', false)
        ->where('admin_sign', 'Yes')
        ->get();
    $savingTypes = SavingType::withdrawable()->where('status', 'active')->get();
    $months = Month::all();
    $years = Year::all();

    // Get current month and year for default settings
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;

    // Get the Month and Year models for the current month/year
    $currentMonthModel = Month::where('id', $currentMonth)->first();
    $currentYearModel = Year::where('year', $currentYear)->first();

    // Pass current month and year IDs to the view
    $currentMonthId = $currentMonthModel ? $currentMonthModel->id : null;
    $currentYearId = $currentYearModel ? $currentYearModel->id : null;

    return view('admin.savings.create', compact(
        'members',
        'savingTypes',
        'months',
        'years',
        'currentMonthId',
        'currentYearId'
    ));
}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'saving_type_id' => 'required|exists:saving_types,id',
            'month_id' => 'required|exists:months,id',
            'year_id' => 'required|exists:years,id',
            'amount' => 'nullable|numeric|min:0',
            'remark' => 'nullable|string'
        ]);

        $user = User::find($request->user_id);
        $savingType = SavingType::find($request->saving_type_id);

        // Use custom amount if provided, otherwise use monthly savings
        $amount = $validated['amount'] ?? $user->monthly_savings;

        // Calculate interest if applicable
        $interestAmount = ($amount * $savingType->interest_rate) / 100;
        $totalAmount = $amount + $interestAmount;

        $saving = Saving::create([
            'user_id' => $user->id,
            'saving_type_id' => $savingType->id,
            'amount' => $totalAmount,
            'month_id' => $validated['month_id'],
            'year_id' => $validated['year_id'],
            'reference' => 'SAV-' . date('Y') . '-' . Str::random(8),
            'remark' => $validated['remark'] . "- New Loan",
            'posted_by' => auth()->id()
        ]);

        // Record transaction with interest details
        TransactionHelper::recordTransaction(
            $user->id,
            'savings',
            0,
            $totalAmount,
            'completed',
            'Monthly Savings Contribution (Interest: ' . $savingType->interest_rate . '%)'
        );

        return redirect()->route('admin.savings')
            ->with('success', 'Savings entry created successfully');
    }


    public function bulkCreate()
    {
        $members = User::where('is_admin', false)
            ->where('admin_sign', 'Yes')
            ->orderBy('surname')
            ->get();
        $months = Month::all();
        $years = Year::all();

        return view('admin.savings.bulk', compact('members', 'months', 'years'));
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'selected_members' => 'required|array',
            'selected_members.*' => 'exists:users,id',
            'month_id' => 'required|exists:months,id',
            'year_id' => 'required|exists:years,id'
        ]);

        $defaultSavingType = SavingType::where('is_mandatory', true)->first();

        foreach ($request->selected_members as $memberId) {
            $user = User::find($memberId);

            $saving = Saving::create([
                'user_id' => $memberId,
                'saving_type_id' => $defaultSavingType->id,
                'amount' => $user->monthly_savings,
                'month_id' => $request->month_id,
                'year_id' => $request->year_id,
                'reference' => 'SAV-' . date('Y') . '-' . Str::random(8),
                'posted_by' => auth()->id()
            ]);

            TransactionHelper::recordTransaction(
                $memberId,
                'savings',
                0,
                $user->monthly_savings,
                'completed',
                'Monthly Savings Contribution'
            );
        }

        return redirect()->route('admin.savings')
            ->with('success', 'Bulk savings entries created successfully');
    }

    public function show(Saving $saving)
    {
        return view('admin.savings.show', compact('saving'));
    }

    public function edit(Saving $saving)
    {
        $savingTypes = SavingType::withdrawable()->where('status', 'active')->get();
        $months = Month::all();
        $years = Year::all();

        return view('admin.savings.edit', compact('saving', 'savingTypes', 'months', 'years'));
    }

    public function update(Request $request, Saving $saving)
    {
        $validated = $request->validate([
            'saving_type_id' => 'required|exists:saving_types,id',
            'month_id' => 'required|exists:months,id',
            'year_id' => 'required|exists:years,id',
            'amount' => 'required|numeric|min:0',
            'remark' => 'nullable|string'
        ]);

        // Update saving record
        $saving->update($validated);

        // Update related transaction
        Transaction::where('user_id', $saving->user_id)
            ->where('type', 'savings')
            ->where('created_at', $saving->created_at)
            ->update([
                'credit_amount' => $validated['amount'],
                'description' => 'Monthly Savings Contribution - Updated'
            ]);

        return redirect()->route('admin.savings')
            ->with('success', 'Savings entry and related transaction updated successfully');
    }

    public function destroy(Saving $saving)
    {

        Transaction::where('user_id', $saving->user_id)
            ->where('type', 'savings')
            ->where('credit_amount', $saving->amount)
            ->where('created_at', $saving->created_at)
            ->delete();


        $saving->delete();

        return redirect()->route('admin.savings')
            ->with('success', 'Savings entry and related transaction deleted successfully');
    }

    public function import()
    {

        return view('admin.savings.import');
    }

    public function processImport(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls'
        ]);

        $file = $request->file('file');

        // Process the imported file
        Excel::import(new SavingsImport, $file);

        return redirect()->route('admin.savings')
        ->with('success', 'Savings data imported successfully');
    }
    public function downloadFormat()
    {
        $headers = [
            'Member Email',
            'Saving Type ID',
            'Amount',
            'Month ID',
            'Year ID'
        ];

        $filename = 'savings_import_format.xlsx';

        return Excel::download(new SavingsExport($headers), $filename);
    }

   public function showWithdrawForm()
{
    $members = User::where('is_admin', false)
        ->orderBy('surname')
        ->get();
    $savingTypes = SavingType::where('name', 'like', '%withdraw%')->where('status', 'active')->get();
    $months = Month::all();
    $years = Year::all();
    return view('admin.savings.withdraw', compact('members', 'savingTypes', 'months', 'years'));
}

public function withdraw(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'saving_type_id' => 'required|exists:saving_types,id',
        'amount' => 'required|numeric|min:0',
        'month_id' => 'required|exists:months,id',
        'year_id' => 'required|exists:years,id',
        'remark' => 'nullable|string'
    ]);

    $withdrawal = Saving::create([
        'user_id' => $validated['user_id'],
        'saving_type_id' => $validated['saving_type_id'],
        'amount' => -abs($validated['amount']),
        'month_id' => $validated['month_id'],
        'year_id' => $validated['year_id'],
        'reference' => 'WTH-' . Str::random(10),
        'status' => 'completed',
        'remark' => 'Withdrawal: ' . $validated['remark'],
        'posted_by' => auth()->id()
    ]);

    TransactionHelper::recordTransaction(
        $validated['user_id'],
        'withdrawal',
        abs($validated['amount']),
        0,

        'completed',
        'Savings Withdrawal - ' . $withdrawal->reference
    );

    return redirect()->route('admin.savings')
        ->with('success', 'Withdrawal processed successfully');
}


public function monthlySavingsSettings()
{
    $settings = MonthlySavingsSetting::with(['user', 'savingType', 'month', 'year'])
       ->orderBy('year_id', 'desc')
    ->orderBy('month_id', 'desc')
        ->paginate(100);


    return view('admin.savings.settings.index', compact('settings'));
}

public function approveSavingsSetting(MonthlySavingsSetting $setting)
{
    $setting->update([
        'status' => 'approved',
        'approved_by' => auth()->id(),
        'approved_at' => now(),
    ]);

    // Update the user's monthly_savings amount if this is the current month/year
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;

    $settingMonth = $setting->month->id;
    $settingYear = $setting->year->year;

    if ($settingMonth == $currentMonth && $settingYear == $currentYear) {
        $setting->user->update([
            'monthly_savings' => $setting->amount
        ]);
    }

    return redirect()->route('admin.savings.settings.index')
        ->with('success', 'Monthly savings setting approved successfully.');
}


public function rejectSavingsSetting(Request $request, MonthlySavingsSetting $setting)
{
    $validated = $request->validate([
        'admin_notes' => 'required|string|max:500',
    ]);

    $setting->update([
        'status' => 'rejected',
        'admin_notes' => $validated['admin_notes'],
        'approved_by' => auth()->id(),
        'approved_at' => now(),
    ]);

    return redirect()->route('admin.savings.settings.index')
        ->with('success', 'Monthly savings setting rejected successfully.');
}


/**
 * Get the member's monthly savings amount for the specified month/year and saving type
 */
public function getMemberSavingsAmount(User $member, $yearId, $monthId, $savingTypeId)
{
    // Try to find an approved setting for the specified month, year, and saving type
    $setting = MonthlySavingsSetting::where('user_id', $member->id)
        ->where('month_id', $monthId)
        ->where('year_id', $yearId)
        ->where('saving_type_id', $savingTypeId)
        ->where('status', 'approved')
        ->first();

    if ($setting) {
        // Use the amount from monthly_savings_settings
        return response()->json([
            'success' => true,
            'amount' => $setting->amount,
            'source' => 'settings'
        ]);
    } else {
        // Fallback to the user's default monthly_savings
        return response()->json([
            'success' => true,
            'amount' => $member->monthly_savings,
            'source' => 'user_profile'
        ]);
    }
}

    }





