<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Share;
use App\Models\ShareType;
use Illuminate\Http\Request;
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
}
