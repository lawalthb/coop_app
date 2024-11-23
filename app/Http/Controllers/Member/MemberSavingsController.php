<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Saving;
use App\Models\Transaction;
use App\Models\SavingType;
use Illuminate\Http\Request;

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
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        $recentTransactions = Saving::where('user_id', $user->id)
            ->with('savingType')
            ->latest()
            ->take(10)
            ->get();

        // Calculate current month's total payments
        $currentMonthTotal = Saving::where('user_id', $user->id)
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
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
            ->when($request->type, function($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->start_date, function($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->end_date);
            })
            ->latest()
            ->paginate(15);

        return view('member.savings.history', compact('transactions'));
    }
}
