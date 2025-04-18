<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CommoditySubscription;
use Illuminate\Support\Facades\Auth;

class MemberCommoditySubscriptionController extends Controller
{
    public function index()
    {
       // return 5;
        $subscriptions = CommoditySubscription::where('user_id', Auth::id())
            ->with('commodity')
            ->latest()
            ->paginate(10);

        return view('member.commodity-subscriptions.index', compact('subscriptions'));
    }

    public function show(CommoditySubscription $subscription)
    {
        // Check if the subscription belongs to the logged-in user
        if ($subscription->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('member.commodity-subscriptions.show', compact('subscription'));
    }
}
