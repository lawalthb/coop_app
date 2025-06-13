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
    // Basic validation for quantity
    $validationRules = [
        'quantity' => 'required|integer|min:1|max:' . $commodity->quantity_available,
        'reason' => 'nullable|string|max:500',
    ];

    // Add installment-specific validation rules if this commodity allows installments
    if ($commodity->allow_installment && $request->payment_type === 'installment') {
        $validationRules['payment_type'] = 'required|in:full,installment';
        $validationRules['initial_deposit'] = 'required|numeric|min:' .
            ($commodity->price * $commodity->initial_deposit_percentage / 100);
    }

    $validated = $request->validate($validationRules);

    // Generate a unique reference number
    $reference = 'COM-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8)) . '-' . date('Ymd');

    // Calculate total amount
    $totalAmount = $commodity->price * $validated['quantity'];

    // Determine payment details based on payment type
    $paymentType = $request->payment_type ?? 'full';
    $initialDeposit = 0;
    $remainingAmount = 0;
    $installmentMonths = 0;
    $monthlyAmount = 0;

    if ($paymentType === 'installment' && $commodity->allow_installment) {
        $initialDeposit = $validated['initial_deposit'];
        $remainingAmount = $totalAmount - $initialDeposit;
        $installmentMonths = $commodity->max_installment_months;
        $monthlyAmount = $remainingAmount / $installmentMonths;
    }

    // Create the subscription
    $subscription = CommoditySubscription::create([
        'user_id' => Auth::id(),
        'commodity_id' => $commodity->id,
        'quantity' => $validated['quantity'],
        'status' => 'pending',
        'unit_price' => $commodity->price,
        'notes' => $validated['reason'] ?? "",
        'reference' => $reference,
        'total_amount' => $totalAmount,
        'payment_type' => $paymentType,
        'initial_deposit' => $initialDeposit,
        'remaining_amount' => $remainingAmount,
        'installment_months' => $installmentMonths,
        'monthly_amount' => $monthlyAmount,
    ]);

    // Update commodity quantity
    $commodity->quantity_available -= $validated['quantity'];
    $commodity->save();

    return redirect()->route('member.commodity-subscriptions.index')
        ->with('success', 'Subscription submitted successfully.');
}

}
