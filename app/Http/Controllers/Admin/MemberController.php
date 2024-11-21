<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $members = User::where('is_admin', false)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('firstname', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('member_no', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);

        if ($search && $members->isEmpty()) {
            return view('admin.members.index', compact('members', 'search'))
            ->with('warning', 'No members found matching "' . $search . '"');
        }

        return view('admin.members.index', compact('members', 'search'));
    }


    public function show(User $member)
    {
        return view('admin.members.show', compact('member'));
    }

    public function approve(User $member)
    {
        $member->update(['admin_sign' => 'Yes']);
        return back()->with('success', 'Member approved successfully');
    }

    public function reject(User $member)
    {
        $member->update(['admin_sign' => 'No']);
        return back()->with('success', 'Member rejected successfully');
    }

    public function suspend(User $member)
    {
        $member->update(['admin_sign' => 'No']);
        return back()->with('warning', 'Member suspended successfully');
    }

    public function activate(User $member)
    {
        $member->update(['status' => 'active']);
        return back()->with('success', 'Member activated successfully');
    }

    public function destroy(User $member)
    {
        $member->delete();
        return redirect()->route('admin.members')->with('danger', 'Member deleted successfully');
    }


    public function downloadPDF(User $member)
    {
        $pdf = PDF::loadView('admin.members.pdf', compact('member'));
        return $pdf->download($member->surname . '_' . $member->firstname . '_details.pdf');
    }


    public function edit(User $member)
    {
        $states = State::all();
        $faculties = Faculty::all();
        $departments = Department::all();
        return view('admin.members.edit', compact('member', 'states', 'faculties', 'departments'));
    }

    public function update(Request $request, User $member)
    {
        $validated = $request->validate([
            'title' => 'required',
            'firstname' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users,email,' . $member->id,
            'phone_number' => 'required',
            'staff_no' => 'required',
            'faculty_id' => 'required',
            'department_id' => 'required',
            'monthly_savings' => 'required|numeric',
            'share_subscription' => 'required|numeric'
        ]);

        $member->update($validated);
        return redirect()->route('admin.members.show', $member)->with('success', 'Member updated successfully');
    }
}
