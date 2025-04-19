<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Month;
use App\Models\Share;
use App\Models\ShareType;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MemberShareController extends Controller
{
    public function index()
    {
        $shares = Share::with('shareType')
        ->where('user_id', auth()->id())

            ->latest()
            ->get();
        $total_shares = Share::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->get();

        return view('member.shares.index', compact('shares', 'total_shares'));
    }

    public function create()
    {
        $shareTypes = ShareType::where('status', 'active')->get();
        return view('member.shares.create', compact('shareTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'share_type_id' => 'required|exists:share_types,id',
            'number_of_units' => 'required|integer|min:1'
        ]);

        $shareType = ShareType::find($request->share_type_id);
        $amountPaid = $shareType->price_per_unit * $validated['number_of_units'];

        Share::create([
            'user_id' => auth()->id(),
            'share_type_id' => $validated['share_type_id'],
            'certificate_number' => 'SHR-' . date('Y') . '-' . Str::random(8),
            'number_of_units' => $validated['number_of_units'],
            'amount_paid' => $amountPaid,
            'unit_price' => $shareType->price_per_unit,
            'posted_by' => auth()->id()
        ]);

        return redirect()->route('member.shares.index')
            ->with('success', 'Share purchase request submitted successfully');
    }

    public function monthlyShareSummary(Request $request)
    {
        $user = Auth::user();
        $selectedYear = $request->input('year', date('Y'));

        // Get all years for the dropdown
        $years = Year::orderBy('year', 'desc')->get();

        // Get the year_id for the selected year
        $yearRecord = Year::where('year', $selectedYear)->first();

        if (!$yearRecord) {
            return redirect()->route('member.shares.index')
                ->with('error', 'Selected year not found in the system.');
        }

        // Get all months
        $months = Month::orderBy('id')->get();

        // Initialize the data structure for the summary
        $summary = [
            'shares' => [
                'name' => 'Share Subscriptions',
                'months' => [],
                'total' => 0
            ]
        ];

        // Initialize each month with 0
        foreach ($months as $month) {
            $summary['shares']['months'][$month->id] = 0;
        }

        // Get all share purchases for the user in the selected year
        $shares = Share::where('user_id', $user->id)
            ->where('year_id', $yearRecord->id)
            ->where('status', 'approved')
            ->get();

        // Fill in the actual share amounts
        foreach ($shares as $share) {
            $summary['shares']['months'][$share->month_id] += $share->amount;
            $summary['shares']['total'] += $share->amount;
        }

        return view('member.shares.monthly-summary', compact('summary', 'months', 'years', 'selectedYear'));
    }
}
