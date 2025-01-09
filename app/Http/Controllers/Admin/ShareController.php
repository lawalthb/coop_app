<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Share;
use App\Models\ShareType;
use App\Models\User;
use App\Helpers\TransactionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShareController extends Controller
{
    public function index()
    {
        $shares = Share::with(['user', 'shareType', 'approvedBy', 'postedBy'])
            ->latest()
            ->paginate(10);

        return view('admin.shares.index', compact('shares'));
    }

    public function create()
    {
        $members = User::where('is_admin', false)
            ->where('admin_sign', 'Yes')
            ->get();
        $shareTypes = ShareType::where('status', 'active')->get();

        return view('admin.shares.create', compact('members', 'shareTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'share_type_id' => 'required|exists:share_types,id',
            'number_of_units' => 'required|integer|min:1',
            'remark' => 'nullable|string'
        ]);

        $shareType = ShareType::find($request->share_type_id);
        $amountPaid = $shareType->price_per_unit * $validated['number_of_units'];

        $share = Share::create([
            'user_id' => $validated['user_id'],
            'share_type_id' => $validated['share_type_id'],
            'certificate_number' => 'SHR-' . date('Y') . '-' . Str::random(8),
            'number_of_units' => $validated['number_of_units'],
            'amount_paid' => $amountPaid,
            'unit_price' => $shareType->price_per_unit,
            'posted_by' => auth()->id(),
            'remark' => $validated['remark']
        ]);

        TransactionHelper::recordTransaction(
            $validated['user_id'],
            'share_purchase',
            $amountPaid,
            0,
            'pending',
            'Share Purchase - ' . $share->certificate_number
        );

        return redirect()->route('admin.shares.index')
            ->with('success', 'Share purchase recorded successfully');
    }

    public function show(Share $share)
    {
        return view('admin.shares.show', compact('share'));
    }

    public function approve(Share $share)
    {
        $share->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        // Update transaction status
        TransactionHelper::updateTransactionStatus(
            $share->user_id,
            'share_purchase',
            $share->amount_paid,
            'completed',
            'Share Purchase Approved - ' . $share->certificate_number
        );

        return back()->with('success', 'Share purchase approved successfully');
    }

    public function reject(Share $share)
    {
        $share->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        // Update transaction status
        TransactionHelper::updateTransactionStatus(
            $share->user_id,
            'share_purchase',
            $share->amount_paid,
            'rejected',
            'Share Purchase Rejected - ' . $share->certificate_number
        );

        return back()->with('success', 'Share purchase rejected');
    }

    public function destroy(Share $share)
    {
        if ($share->status === 'approved') {
            return back()->with('error', 'Cannot delete an approved share purchase');
        }

        TransactionHelper::updateTransactionStatus(
            $share->user_id,
            'share_purchase',
            $share->amount_paid,
            'cancelled',
            'Share Purchase Cancelled - ' . $share->certificate_number
        );

        $share->delete();

        return redirect()
            ->route('admin.shares.index')
            ->with('success', 'Share purchase deleted successfully');
    }
}
