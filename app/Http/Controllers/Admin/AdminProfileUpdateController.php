<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileUpdateRequest;
use App\Models\User;
use App\Notifications\ProfileUpdateStatusNotification;
use Illuminate\Http\Request;

class AdminProfileUpdateController extends Controller
{
    public function index()
    {
        $requests = ProfileUpdateRequest::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.profile-updates.index', compact('requests'));
    }

    public function show(ProfileUpdateRequest $request)
    {
        return view('admin.profile-updates.show', compact('request'));
    }

    public function approve(ProfileUpdateRequest $request)
    {
        $user = $request->user;

        // Update user profile with requested changes
        $user->update($request->only([
            'title', 'surname', 'firstname', 'othername', 'home_address',
            'department_id', 'faculty_id', 'phone_number', 'email', 'dob',
            'nationality', 'state_id', 'lga_id', 'nok', 'nok_relationship',
            'nok_address', 'marital_status', 'religion', 'nok_phone',
            'monthly_savings', 'share_subscription', 'month_commence',
            'staff_no', 'signature_image', 'date_join', 'member_image'
        ]));

        $request->update(['status' => 'approved']);

        $user->notify(new ProfileUpdateStatusNotification($request));

        return back()->with('success', 'Profile update request approved successfully');
    }

    public function reject(Request $request, ProfileUpdateRequest $profileRequest)
    {
        $request->validate(['reason' => 'required|string']);

        $profileRequest->update([
            'status' => 'rejected',
            'admin_remarks' => $request->reason
        ]);

        $profileRequest->user->notify(new ProfileUpdateStatusNotification($profileRequest));

        return back()->with('success', 'Profile update request rejected');
    }
}
