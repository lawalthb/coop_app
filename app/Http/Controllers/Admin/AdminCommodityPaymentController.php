<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommodityPayment;
use App\Models\CommoditySubscription;
use App\Models\Month;
use App\Models\Transaction;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCommodityPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = CommodityPayment::query()->with(['commoditySubscription.user', 'commoditySubscription.commodity']);

        // Filter by subscription if provided
        if ($request->has('subscription_id')) {
            $query->where('commodity_subscription_id', $request->subscription_id);
        }

        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by payment method if provided
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }

         // Filter by month if provided
        if ($request->has('month_id') && $request->month_id != '') {
            $query->where('month_id', $request->month_id);
        }

        // Filter by year if provided
        if ($request->has('year_id') && $request->year_id != '') {
            $query->where('year_id', $request->year_id);
        }

        $payments = $query->latest()->paginate(15);

        // Get subscription if filtering by subscription
        $subscription = null;
        if ($request->has('subscription_id')) {
            $subscription = CommoditySubscription::with(['user', 'commodity'])->findOrFail($request->subscription_id);
        }
  // Get months and years for filtering
        $months = Month::all();
        $years = Year::all();


       return view('admin.commodity-payments.index', compact('payments', 'subscription', 'months', 'years'));
    }


    public function show(CommodityPayment $payment)
    {
        $payment->load(['commoditySubscription.user', 'commoditySubscription.commodity']);
        return view('admin.commodity-payments.show', compact('payment'));
    }

   public function create(CommoditySubscription $subscription)
    {
        $subscription->load(['user', 'commodity', 'payments']);

        // Calculate remaining amount
        $paidAmount = $subscription->initial_deposit + ($subscription->payments->sum('amount') ?? 0);
        $remainingAmount = $subscription->total_amount - $paidAmount;

        // Get months and years for the form
        $months = Month::all();
        $years = Year::all();

        return view('admin.commodity-payments.create', compact('subscription', 'remainingAmount', 'months', 'years'));
    }

     public function store(Request $request, CommoditySubscription $subscription)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,bank_transfer,deduction',
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
            'month_id' => 'required|exists:months,id',
            'year_id' => 'required|exists:years,id',
        ]);

        // Calculate remaining amount
        $paidAmount = $subscription->initial_deposit + ($subscription->payments->sum('amount') ?? 0);
        $remainingAmount = $subscription->total_amount - $paidAmount;

        // Ensure payment amount doesn't exceed remaining amount
        if ($validated['amount'] > $remainingAmount) {
            return back()->with('error', 'Payment amount exceeds the remaining balance.');
        }

        // Create the payment record (auto-approved since admin is creating it)
        $payment = CommodityPayment::create([
            'commodity_subscription_id' => $subscription->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_reference' => $validated['payment_reference'],
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'notes' => $validated['notes'],
            'month_id' => $validated['month_id'],
            'year_id' => $validated['year_id'],
        ]);

        // Record the transaction
        Transaction::recordCommodityPayment(
            $subscription->user_id,
            $validated['amount'],
            $subscription->commodity->name,
            $subscription->commodity_id,
            $payment->payment_reference ?? ('COM-PAY-' . $payment->id)
        );

        return redirect()->route('admin.commodity-payments.index', ['subscription_id' => $subscription->id])
            ->with('success', 'Payment recorded successfully.');
    }

    public function approve(CommodityPayment $payment)
    {
        $payment->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Get the subscription details
        $subscription = $payment->commoditySubscription;

        // Record the transaction
        Transaction::recordCommodityPayment(
            $subscription->user_id,
            $payment->amount,
            $subscription->commodity->name,
            $subscription->commodity_id,
            $payment->payment_reference ?? ('COM-PAY-' . $payment->id)
        );

        return redirect()->route('admin.commodity-payments.show', $payment)
            ->with('success', 'Payment approved successfully.');
    }

    // public function approve(CommodityPayment $payment)
    // {
    //     $payment->update([
    //         'status' => 'approved',
    //         'approved_by' => Auth::id(),
    //         'approved_at' => now(),
    //     ]);

    //     return redirect()->route('admin.commodity-payments.show', $payment)
    //         ->with('success', 'Payment approved successfully.');
    // }

    public function reject(Request $request, CommodityPayment $payment)
    {
        $validated = $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        $payment->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('admin.commodity-payments.show', $payment)
            ->with('success', 'Payment rejected successfully.');
    }
}
