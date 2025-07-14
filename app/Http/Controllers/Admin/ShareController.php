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
   public function index(Request $request)
{
    $query = Share::with(['user', 'shareType', 'approvedBy', 'postedBy', 'month', 'year']);

    // Apply filters if provided
    if ($request->filled('share_type_id')) {
        $query->where('share_type_id', $request->share_type_id);
    }

    if ($request->filled('month_id')) {
        $query->where('month_id', $request->month_id);
    }

    if ($request->filled('year_id')) {
        $query->where('year_id', $request->year_id);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Add filter by member
    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    // Calculate total amount based on the same filters (without pagination)
    $totalShares = (clone $query)->sum('amount_paid');

    // Get the filtered shares
    $shares = $query->latest()->paginate(50);

    // Get data for filter dropdowns
    $shareTypes = ShareType::all();
    $months = Month::all();
    $years = Year::all();
    $members = User::where('is_admin', false)
                  ->where('admin_sign', 'Yes')
                  ->orderBy('surname')
                  ->orderBy('firstname')
                  ->get();

    return view('admin.shares.index', compact(
        'shares',
        'shareTypes',
        'months',
        'years',
        'members',
        'totalShares'
    ));
}

public function create()
{
    $members = User::where('is_admin', false)
        ->where('admin_sign', 'Yes')
        ->get();
$shareTypes = ShareType::where('status', 'active')
    ->where('name', 'NOT LIKE', '%withdraw%')
    ->get();
    $months = Month::all();
    $years = Year::all();

    return view('admin.shares.create', compact('members', 'shareTypes', 'months', 'years'));
}


public function withdraw()
{
    $members = User::where('is_admin', false)
        ->where('admin_sign', 'Yes')
        ->get();
   $shareTypes = ShareType::where('status', 'active')
    ->where('name', 'LIKE', '%withdraw%')
    ->get();
    $months = Month::all();
    $years = Year::all();

    return view('admin.shares.withdraw', compact('members', 'shareTypes', 'months', 'years'));
}




    public function store(Request $request)
    {
        $validated = $request->validate([
    'user_id' => 'required|exists:users,id',
    'share_type_id' => 'required|exists:share_types,id',
    'amount_paid' => 'required|integer|min:0.01',
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

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);

    Excel::import(new SharesImport, $request->file('file'));

    return redirect()->route('admin.shares.index')
        ->with('success', 'Shares imported successfully');
}


public function withdrawStore(Request $request){
       $validated = $request->validate([
    'user_id' => 'required|exists:users,id',
    'share_type_id' => 'required|exists:share_types,id',
    'amount_paid' => 'required|integer|min:0.01',
    'month_id' => 'required|exists:months,id',
    'year_id' => 'required|exists:years,id',
    'remark' => 'nullable|string'
]);


       $share = Share::create([
    'user_id' => $validated['user_id'],
    'share_type_id' => $validated['share_type_id'],
    'certificate_number' => 'SHRWD-' . date('Y') . '-' . Str::random(8),
    'amount_paid' => -abs($validated['amount_paid']),
    'month_id' => $validated['month_id'],
    'year_id' => $validated['year_id'],
    'posted_by' => auth()->id(),
     'status' => 'approved',
    'remark' => $validated['remark']
]);
        TransactionHelper::recordTransaction(
            $validated['user_id'],
            'share_purchase',

            $validated['amount_paid'],
            0,
            'completed',
            'Share Withdraw - ' . $share->certificate_number
        );

        return redirect()->route('admin.shares.index')
            ->with('success', 'Share Withdraw recorded successfully');
}
}
