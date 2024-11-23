<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Saving;
use App\Models\User;
use App\Models\Month;
use App\Models\Year;
use App\Models\SavingType;
use App\Helpers\TransactionHelper;
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
}
