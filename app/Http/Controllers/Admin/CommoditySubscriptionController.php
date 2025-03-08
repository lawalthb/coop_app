<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommoditySubscription;

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

        return back()->with('success', 'Subscription rejected successfully.');
    }
}
