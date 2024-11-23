<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Saving;
use App\Models\User;
use App\Models\Month;
use App\Models\Year;
use App\Models\SavingType;
use App\Helpers\TransactionHelper;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SavingController extends Controller
{
    public function index()
    {
        $savings = Saving::with(['user', 'savingType', 'month', 'year'])
            ->latest()
            ->paginate(10);

        return view('admin.savings.index', compact('savings'));
    }

    public function create()
    {
        $members = User::where('is_admin', false)
            ->where('admin_sign', 'Yes')
            ->get();
        $savingTypes = SavingType::where('status', 'active')->get();
        $months = Month::all();
        $years = Year::all();

        return view('admin.savings.create', compact('members', 'savingTypes', 'months', 'years'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'saving_type_id' => 'required|exists:saving_types,id',
            'month_id' => 'required|exists:months,id',
            'year_id' => 'required|exists:years,id',
            'remark' => 'nullable|string'
        ]);

        $user = User::find($request->user_id);
        $validated['amount'] = $user->monthly_savings;
        $validated['reference'] = 'SAV-' . date('Y') . '-' . Str::random(8);
        $validated['posted_by'] = auth()->id();

        $saving = Saving::create($validated);

        // Record transaction
        TransactionHelper::recordTransaction(
            $user->id,
            'savings',
            0,
            $validated['amount'],
            'completed',
            'Monthly Savings Contribution'
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
        $savingTypes = SavingType::where('status', 'active')->get();
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

}
