<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EntranceFee;
use App\Models\Month;
use App\Models\Year;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\AccountActivatedEmail;
use Illuminate\Support\Facades\Mail;

class EntranceFeeController extends Controller
{
    public function index()
    {
        $entranceFees = EntranceFee::with(['user', 'month', 'year', 'postedBy'])
            ->latest()
            ->paginate(10);

        return view('admin.entrance-fees.index', compact('entranceFees'));
    }

    public function create()
    {
        $members = User::where('is_admin', false)->where('admin_sign','No')->get();
        $months = Month::all();
        $years = Year::all();

        return view('admin.entrance-fees.create', compact('members', 'months', 'years'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'month_id' => 'required|exists:months,id',
            'year_id' => 'required|exists:years,id',
            'remark' => 'nullable|string'
        ]);

        $validated['posted_by'] = auth()->id();

        EntranceFee::create($validated);

        if ($request->has('approve_member')) {
            User::where('id', $request->user_id)->update(['admin_sign' => 'Yes']);
            
            $member = User::where('id', $request->user_id)->first();
            Mail::to($member->email)->send(new AccountActivatedEmail($member));

        }


        return redirect()->route('admin.entrance-fees')
            ->with('success', 'Entrance fee added successfully');
    }

    public function edit(EntranceFee $entranceFee)
    {
        $members = User::where('is_admin', false)->get();
        $months = Month::all();
        $years = Year::all();

        return view('admin.entrance-fees.edit',
            compact('entranceFee', 'members', 'months', 'years'));
    }

    public function update(Request $request, EntranceFee $entranceFee)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'month_id' => 'required|exists:months,id',
            'year_id' => 'required|exists:years,id',
            'remark' => 'nullable|string'
        ]);

        $entranceFee->update($validated);

        return redirect()->route('admin.member.entrance-fees')
            ->with('success', 'Entrance fee updated successfully');
    }

    public function destroy(EntranceFee $entranceFee)
    {
        $entranceFee->delete();

        return back()->with('success', 'Entrance fee deleted successfully');
    }

    // Additional helpful methods
    public function export()
    {
        // Export entrance fees to Excel/CSV
    }

    public function generateReceipt(EntranceFee $entranceFee)
    {
        // Generate PDF receipt
    }

    public function bulkUpload(Request $request)
    {
        // Handle bulk upload of entrance fees
    }
}
