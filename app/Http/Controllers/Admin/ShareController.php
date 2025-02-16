<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Share;
use App\Models\ShareType;
use App\Models\User;
use App\Models\Month;
use App\Models\Year;

use App\Helpers\TransactionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Imports\SharesImport;
use Maatwebsite\Excel\Facades\Excel;

class ShareController extends Controller
{
    public function index()
    {
        $shares = Share::with(['user', 'shareType', 'approvedBy', 'postedBy', 'month', 'year'])
            ->latest()
            ->paginate(50);

        return view('admin.shares.index', compact('shares'));
    }

public function create()
{
    $members = User::where('is_admin', false)
        ->where('admin_sign', 'Yes')
        ->get();
    $shareTypes = ShareType::where('status', 'active')->get();
    $months = Month::all();
    $years = Year::all();

    return view('admin.shares.create', compact('members', 'shareTypes', 'months', 'years'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
    'user_id' => 'required|exists:users,id',
    'share_type_id' => 'required|exists:share_types,id',
    'amount_paid' => 'required|integer|min:1',
    'month_id' => 'required|exists:months,id',
    'year_id' => 'required|exists:years,id',
    'remark' => 'nullable|string'
]);


       $share = Share::create([
    'user_id' => $validated['user_id'],
    'share_type_id' => $validated['share_type_id'],
    'certificate_number' => 'SHR-' . date('Y') . '-' . Str::random(8),
    'amount_paid' => $validated['amount_paid'],
    'month_id' => $validated['month_id'],
    'year_id' => $validated['year_id'],
    'posted_by' => auth()->id(),
     'status' => 'approved',
    'remark' => $validated['remark']
]);
        TransactionHelper::recordTransaction(
            $validated['user_id'],
            'share_purchase',

            0,
            $validated['amount_paid'],
            'completed',
            'Share Purchase - ' . $share->certificate_number
        );

        return redirect()->route('admin.shares.create')
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

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);

    Excel::import(new SharesImport, $request->file('file'));

    return redirect()->route('admin.shares.index')
        ->with('success', 'Shares imported successfully');
}
}
