<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Share;
use App\Models\ShareTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShareController extends Controller
{
    public function index()
    {
        $shares = Share::with(['user', 'postedBy'])
            ->latest()
            ->paginate(10);

        return view('admin.shares.index', compact('shares'));
    }

    public function create()
    {
        $members = User::where('is_admin', false)
            ->where('admin_sign', 'Yes')
            ->get();

        return view('admin.shares.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'number_of_shares' => 'required|integer|min:1',
            'amount_per_share' => 'required|numeric|min:0',
            'remark' => 'nullable|string'
        ]);

        $totalAmount = $validated['number_of_shares'] * $validated['amount_per_share'];

        // Create share record
        $share = Share::create([
            'user_id' => $validated['user_id'],
            'number_of_shares' => $validated['number_of_shares'],
            'amount_per_share' => $validated['amount_per_share'],
            'total_amount' => $totalAmount,
            'certificate_number' => 'SHR-' . date('Y') . '-' . Str::random(8),
            'posted_by' => auth()->id()
        ]);

        // Record share transaction
        ShareTransaction::create([
            'user_id' => $validated['user_id'],
            'transaction_type' => 'purchase',
            'number_of_shares' => $validated['number_of_shares'],
            'amount' => $totalAmount,
            'reference' => 'STX-' . date('Y') . '-' . Str::random(8),
            'remark' => $validated['remark'],
            'posted_by' => auth()->id()
        ]);

        return redirect()->route('admin.shares.index')
            ->with('success', 'Shares allocated successfully');
    }

    public function show(Share $share)
    {
        return view('admin.shares.show', compact('share'));
    }

    public function transfer()
    {
        $members = User::where('is_admin', false)
            ->where('admin_sign', 'Yes')
            ->get();

        return view('admin.shares.transfer', compact('members'));
    }

    public function processTransfer(Request $request)
    {
        $validated = $request->validate([
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'required|exists:users,id|different:from_user_id',
            'number_of_shares' => 'required|integer|min:1',
            'remark' => 'nullable|string'
        ]);

        $fromShare = Share::where('user_id', $validated['from_user_id'])->firstOrFail();
        $toShare = Share::where('user_id', $validated['to_user_id'])->first();

        if ($fromShare->number_of_shares < $validated['number_of_shares']) {
            return back()->with('error', 'Insufficient shares for transfer');
        }

        // Update shares for sender
        $fromShare->update([
            'number_of_shares' => $fromShare->number_of_shares - $validated['number_of_shares'],
            'total_amount' => ($fromShare->number_of_shares - $validated['number_of_shares']) * $fromShare->amount_per_share
        ]);

        // Update or create shares for receiver
        if ($toShare) {
            $toShare->update([
                'number_of_shares' => $toShare->number_of_shares + $validated['number_of_shares'],
                'total_amount' => ($toShare->number_of_shares + $validated['number_of_shares']) * $toShare->amount_per_share
            ]);
        } else {
            Share::create([
                'user_id' => $validated['to_user_id'],
                'number_of_shares' => $validated['number_of_shares'],
                'amount_per_share' => $fromShare->amount_per_share,
                'total_amount' => $validated['number_of_shares'] * $fromShare->amount_per_share,
                'certificate_number' => 'SHR-' . date('Y') . '-' . Str::random(8),
                'posted_by' => auth()->id()
            ]);
        }

        // Record transfer transaction
        ShareTransaction::create([
            'user_id' => $validated['from_user_id'],
            'transaction_type' => 'transfer',
            'number_of_shares' => $validated['number_of_shares'],
            'amount' => $validated['number_of_shares'] * $fromShare->amount_per_share,
            'reference' => 'STX-' . date('Y') . '-' . Str::random(8),
            'remark' => $validated['remark'],
            'posted_by' => auth()->id()
        ]);

        return redirect()->route('admin.shares.index')
            ->with('success', 'Shares transferred successfully');
    }
}
