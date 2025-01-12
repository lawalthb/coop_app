<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\AccountActivatedEmail;
use Illuminate\Support\Facades\Mail;
use App\Helpers\TransactionHelper;



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
        $new_member_no =TransactionHelper::generateUniqueMemberNo();
        dd($new_member_no);
        $member->update([
            'admin_sign' => 'Yes',
            'member_no' => $new_member_no,
            'is_approved' => 1,
            'approved_at' => now(),
        ]);

        Mail::to($member->email)->send(new AccountActivatedEmail($member));

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
        //return view('admin.members.pdf', compact('member'));

        $pdf = PDF::loadView('admin.members.pdf', compact('member'));
        return $pdf->download($member->surname . '_' . $member->firstname . '_details.pdf');
    }

    public function authorityDeduct(User $member)
    {
        $pdf = PDF::loadView('admin.members.authority-deduct', compact('member'));
        return $pdf->download('authority-deduct-' . $member->member_no . '.pdf');
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


    public function create()
    {
        $faculties = Faculty::all();
        $states = State::all();

        return view('admin.members.create', compact('faculties', 'states'));
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'surname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'staff_no' => 'required|string|max:50|unique:users',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'faculty_id' => 'required|exists:faculties,id',
            'department_id' => 'required|exists:departments,id',
            'state_id' => 'required|exists:states,id',
            'lga_id' => 'required|exists:lgas,id',
            'date_join' => 'required|date',
            'monthly_savings' => 'required|numeric|min:0',
            'password' => 'required|string|min:8|confirmed',
            'member_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('member_image')) {
            $imagePath = $request->file('member_image')->store('member-images', 'public');
            $validated['member_image'] = $imagePath;
        }

        // Set additional fields
        $validated['password'] = bcrypt($validated['password']);
        $validated['is_admin'] = false;
        $validated['admin_sign'] = 'Yes';
        $validated['is_approved'] = 1;
        $validated['member_no'] = TransactionHelper::generateUniqueMemberNo() ;
        $validated['approved_at'] = now();
        User::create($validated);

        return redirect()->route('admin.members')
            ->with('success', 'Member created successfully');
    }

 
}
