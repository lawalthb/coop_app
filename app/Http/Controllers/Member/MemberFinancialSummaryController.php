<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CommodityPayment;
use App\Models\CommoditySubscription;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\Month;
use App\Models\Saving;
use App\Models\SavingType;
use App\Models\Share;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberFinancialSummaryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedYear = $request->input('year', date('Y'));

        // Get all years for the dropdown
        $years = Year::orderBy('year', 'desc')->get();

        // Get the year_id for the selected year
        $yearRecord = Year::where('year', $selectedYear)->first();

        if (!$yearRecord) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Selected year not found in the system.');
        }

        // Get all months
        $months = Month::orderBy('id')->get();

        // Initialize the summary structure
        $summary = [
            'savings' => [],
            'loans' => [],
            'shares' => [
                'name' => 'Share Subscriptions',
                'months' => [],
                'total' => 0
            ],
            'commodities' => []
        ];

        // Initialize months for shares
        foreach ($months as $month) {
            $summary['shares']['months'][$month->id] = 0;
        }

        // Get savings summary
        $savingTypes = SavingType::all();
        foreach ($savingTypes as $type) {
            $summary['savings'][$type->id] = [
                'name' => $type->name,
                'months' => [],
                'total' => 0
            ];

            foreach ($months as $month) {
                $summary['savings'][$type->id]['months'][$month->id] = 0;
            }
        }

        $savings = Saving::where('user_id', $user->id)
            ->where('year_id', $yearRecord->id)
            ->get();

        foreach ($savings as $saving) {
            if (isset($summary['savings'][$saving->saving_type_id])) {
                $summary['savings'][$saving->saving_type_id]['months'][$saving->month_id] += $saving->amount;
                $summary['savings'][$saving->saving_type_id]['total'] += $saving->amount;
            }
        }

        // Get loan repayments summary
        $loans = Loan::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();

        foreach ($loans as $loan) {
            $summary['loans'][$loan->id] = [
                'name' => $loan->loanType->name . ' (' . $loan->reference . ')',
                'months' => [],
                'total' => 0
            ];

            foreach ($months as $month) {
                $summary['loans'][$loan->id]['months'][$month->id] = 0;
            }
        }

        $repayments = LoanRepayment::whereIn('loan_id', $loans->pluck('id'))
            ->where('year_id', $yearRecord->id)
            ->get();

        foreach ($repayments as $repayment) {
            if (isset($summary['loans'][$repayment->loan_id])) {
                $summary['loans'][$repayment->loan_id]['months'][$repayment->month_id] += $repayment->amount;
                $summary['loans'][$repayment->loan_id]['total'] += $repayment->amount;
            }
        }

        // Get shares summary
        $shares = Share::where('user_id', $user->id)
            ->where('year_id', $yearRecord->id)
            ->where('status', 'approved')
            ->get();

        foreach ($shares as $share) {
            $summary['shares']['months'][$share->month_id] += $share->amount;
            $summary['shares']['total'] += $share->amount;
        }

        // Get commodity payments summary
        $subscriptions = CommoditySubscription::where('user_id', $user->id)
            ->where('status', 'approved')
            ->with('commodity')
            ->get();

        foreach ($subscriptions as $subscription) {
            $summary['commodities'][$subscription->id] = [
                'name' => $subscription->commodity->name . ' (' . $subscription->reference . ')',
                'months' => [],
                'total' => 0
            ];

            foreach ($months as $month) {
                $summary['commodities'][$subscription->id]['months'][$month->id] = 0;
            }
        }

        $payments = CommodityPayment::whereIn('commodity_subscription_id', $subscriptions->pluck('id'))
            ->where('year_id', $yearRecord->id)
            ->where('status', 'approved')
            ->get();

        foreach ($payments as $payment) {
            if (isset($summary['commodities'][$payment->commodity_subscription_id])) {
                $summary['commodities'][$payment->commodity_subscription_id]['months'][$payment->month_id] += $payment->amount;
                $summary['commodities'][$payment->commodity_subscription_id]['total'] += $payment->amount;
            }
        }

        return view('member.financial-summary.index', compact('summary', 'months', 'years', 'selectedYear'));
    }
}
