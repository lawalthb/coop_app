<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commodity;
use App\Models\CommoditySubscription;
use Illuminate\Support\Facades\Auth;

class MemberCommodityController extends Controller
{
    public function index()
    {
        $commodities = Commodity::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->where(function($query) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
            })
            ->where('quantity_available', '>', 0)
            ->latest()
            ->paginate(12);

        return view('member.commodities.index', compact('commodities'));
    }

    public function show(Commodity $commodity)
    {
        return view('member.commodities.show', compact('commodity'));
    }

    public function subscribe(Request $request, Commodity $commodity)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $commodity->quantity_available,
            'reason' => 'nullable|string',
        ]);

        $subscription = CommoditySubscription::create([
            'user_id' => Auth::id(),
            'commodity_id' => $commodity->id,
            'quantity' => $validated['quantity'],
            'status' => 'pending',
            'reason' => $validated['reason'] ?? null,
            'total_amount' => $commodity->price * $validated['quantity'],
        ]);

        return redirect()->route('member.commodity-subscriptions.index')
            ->with('success', 'Subscription submitted successfully.');
    }
}
