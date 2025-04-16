<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommoditySubscription;
use App\Models\CommodityPayment;
use App\Notifications\CommoditySubscriptionStatusUpdated;
use App\Notifications\CommodityPaymentStatusUpdated;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CommoditySubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = CommoditySubscription::with(['user', 'commodity'])
            ->latest()
            ->paginate(10);

        return view('admin.commodity-subscriptions.index', compact('subscriptions'));
    }

    public function show(CommoditySubscription $subscription)
    {
        $subscription->load(['commodity', 'user', 'payments']);
        return view('admin.commodity-subscriptions.show', compact('subscription'));
    }

    public function approve(CommoditySubscription $subscription)
    {
        // Check if there's enough quantity available
        if ($subscription->commodity->quantity_available < $subscription->quantity) {
            return back()->with('error', 'Not enough quantity available for this commodity.');
        }

        // Update commodity quantity
        $subscription->commodity->decrement('quantity_available', $subscription->quantity);

        // Update subscription status
        $subscription->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Notify the user
        $subscription->user->notify(new CommoditySubscriptionStatusUpdated($subscription));

        return back()->with('success', 'Subscription approved successfully.');
    }

    public function reject(Request $request, CommoditySubscription $subscription)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $subscription->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'admin_notes' => $validated['admin_notes'],
        ]);

        // Notify the user
        $subscription->user->notify(new CommoditySubscriptionStatusUpdated($subscription));

        return back()->with('success', 'Subscription rejected successfully.');
    }

    public function recordPayment(Request $request, CommoditySubscription $subscription)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:full,initial_deposit,installment',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_reference' => 'nullable|string|max:100',
            'payment_proof' => 'nullable|image|max:2048',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        // Handle payment proof if provided
        $paymentProof = 'admin-recorded-payment';
        if ($request->hasFile('payment_proof')) {
            $paymentProof = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        // Create payment record
        $payment = new CommodityPayment([
            'subscription_id' => $subscription->id,
            'amount' => $validated['amount'],
            'payment_type' => $validated['payment_type'],
            'payment_date' => $validated['payment_date'],
            'payment_reference' => $validated['payment_reference'] ?? 'ADMIN-' . Str::random(8),
            'payment_proof' => $paymentProof,
            'status' => $validated['status'],
        ]);

        $payment->save();

        // If payment is approved, update subscription details
        if ($validated['status'] === 'approved') {
            $this->processApprovedPayment($payment);
        }

        return back()->with('success', 'Payment recorded successfully.');
    }

    public function approvePayment(CommodityPayment $payment)
    {
        $payment->update([
            'status' => 'approved',
        ]);

        // Process the payment
        $this->processApprovedPayment($payment);

        // Notify the user
        $payment->subscription->user->notify(new CommodityPaymentStatusUpdated($payment));

        return back()->with('success', 'Payment approved successfully.');
    }

    public function rejectPayment(Request $request, CommodityPayment $payment)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $payment->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Notify the user
        $payment->subscription->user->notify(new CommodityPaymentStatusUpdated($payment));

        return back()->with('success', 'Payment rejected successfully.');
    }

    private function processApprovedPayment(CommodityPayment $payment)
    {
        $subscription = $payment->subscription;

        // Handle different payment types
        if ($payment->payment_type === 'full') {
            // Mark subscription as completed
            $subscription->update([
                'is_completed' => true,
            ]);
        } elseif ($payment->payment_type === 'initial_deposit') {
            // Set next payment date
            $subscription->update([
                'next_payment_date' => Carbon::now()->addMonth(),
            ]);
        } elseif ($payment->payment_type === 'installment') {
            // Increment months paid
            $subscription->increment('months_paid');

            // Check if all installments are paid
            if ($subscription->months_paid >= $subscription->installment_months) {
                $subscription->update([
                    'is_completed' => true,
                ]);
            } else {
                // Set next payment date
                $subscription->update([
                    'next_payment_date' => Carbon::now()->addMonth(),
                ]);
            }
        }
    }
}
