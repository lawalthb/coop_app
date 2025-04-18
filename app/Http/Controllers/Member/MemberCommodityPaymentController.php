<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CommodityPayment;
use App\Models\CommoditySubscription;
use App\Models\Month;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberCommodityPaymentController extends Controller
{
    /**
     * Display a listing of payments for a subscription.
     */
    public function index(CommoditySubscription $subscription)
    {
        // Ensure the authenticated user owns this subscription
        if ($subscription->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $payments = $subscription->payments()->latest()->get();

        // Calculate payment summary
        $totalAmount = $subscription->total_amount;
        $initialDeposit = $subscription->initial_deposit ?? 0;
        $paidAmount = $initialDeposit + $payments->sum('amount');
        $remainingAmount = $totalAmount - $paidAmount;

        return view('member.commodity-payments.index', compact(
            'subscription',
            'payments',
            'totalAmount',
            'initialDeposit',
            'paidAmount',
            'remainingAmount'
        ));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(CommoditySubscription $subscription)
    {
        // Ensure the authenticated user owns this subscription
        if ($subscription->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure subscription is approved and is an installment type
        if ($subscription->status !== 'approved' || $subscription->payment_type !== 'installment') {
            return redirect()->route('member.commodity-subscriptions.show', $subscription)
                ->with('error', 'You cannot make payments for this subscription.');
        }

        // Calculate remaining amount
        $payments = $subscription->payments;
        $paidAmount = $subscription->initial_deposit + $payments->sum('amount');
        $remainingAmount = $subscription->total_amount - $paidAmount;

        // If fully paid, redirect back
        if ($remainingAmount <= 0) {
            return redirect()->route('member.commodity-subscriptions.show', $subscription)
                ->with('info', 'This subscription is already fully paid.');
        }
  // Get months and years for the form
        $months = Month::all();
        $years = Year::all();

        return view('member.commodity-payments.create', compact('subscription', 'remainingAmount', 'months', 'years'));
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request, CommoditySubscription $subscription)
    {
        // Ensure the authenticated user owns this subscription
        if ($subscription->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Calculate remaining amount
        $payments = $subscription->payments;
        $paidAmount = $subscription->initial_deposit + $payments->sum('amount');
        $remainingAmount = $subscription->total_amount - $paidAmount;

        // Validate the payment amount
        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $remainingAmount,
            'payment_method' => 'required|in:cash,bank_transfer,deduction',
            'payment_reference' => 'nullable|string|max:255',
               'month_id' => 'required|exists:months,id',
            'year_id' => 'required|exists:years,id',
        ]);

        // Create the payment record
        $payment = new CommodityPayment();
        $payment->commodity_subscription_id = $subscription->id;
        $payment->amount = $request->amount;
        $payment->payment_method = $request->payment_method;
        $payment->payment_reference = $request->payment_reference;
        $payment->status = 'pending'; // Payments need admin approval
        $payment->month_id = $request->month_id;
        $payment->year_id = $request->year_id;
        $payment->save();

        return redirect()->route('member.commodity-subscriptions.show', $subscription)
            ->with('success', 'Payment submitted successfully and is pending approval.');
    }
}
