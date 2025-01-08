<?php

namespace App\Http\Controllers;

use App\Models\ProfileUpdateRequest;
use App\Models\User;
use App\Notifications\ProfileUpdateRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileUpdateController extends Controller
{
    public function requestUpdate(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'title' => 'nullable|string',
            'surname' => 'nullable|string',
            'firstname' => 'nullable|string',
            'othername' => 'nullable|string',
            'home_address' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'faculty_id' => 'nullable|exists:faculties,id',
            'phone_number' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'dob' => 'nullable|date',
            'nationality' => 'nullable|string',
            'state_id' => 'nullable|exists:states,id',
            'lga_id' => 'nullable|exists:lgas,id',
            'nok' => 'nullable|string',
            'nok_relationship' => 'nullable|string',
            'nok_address' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'religion' => 'nullable|string',
            'nok_phone' => 'nullable|string',
            'monthly_savings' => 'nullable|numeric',
            'share_subscription' => 'nullable|numeric',
            'month_commence' => 'nullable|string',
            'staff_no' => 'nullable|string',
            'date_join' => 'nullable|date',
            'member_image' => 'nullable|image|max:2048',
            'signature_image' => 'nullable|image|max:2048',
        ]);

        // Handle file uploads
        if ($request->hasFile('member_image')) {
            $validated['member_image'] = $request->file('member_image')->store('members', 'public');
        }

        if ($request->hasFile('signature_image')) {
            $validated['signature_image'] = $request->file('signature_image')->store('signatures', 'public');
        }

        $updateRequest = ProfileUpdateRequest::create([
            'user_id' => auth()->id(),
            ...$validated
        ]);

        // Notify all admins
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            $admin->notify(new ProfileUpdateRequestNotification($updateRequest));
        }

        return redirect()->back()->with('success', 'Profile update request submitted successfully');
    }
}
