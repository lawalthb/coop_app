<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = User::where('is_admin', false)
            ->latest()
            ->paginate(10);

        return view('admin.members.index', compact('members'));
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
        $member->update(['status' => 'suspended']);
        return back()->with('success', 'Member suspended successfully');
    }

    public function activate(User $member)
    {
        $member->update(['status' => 'active']);
        return back()->with('success', 'Member activated successfully');
    }
}
