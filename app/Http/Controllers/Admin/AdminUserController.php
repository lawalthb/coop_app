<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::where('is_admin', true)->with('roles')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'surname' => 'required',
            'firstname' => 'required',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required|array'
        ]);

        $admin = User::create([
            'title' => $validated['title'],
            'surname' => $validated['surname'],
            'firstname' => $validated['firstname'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
            'is_admin' => true,
            'member_no' => 'ADM' . rand(1000, 9999),
            'admin_sign' => 'Yes',
            'department_id' => 1,
            'faculty_id' => 1,
            'state_id' => 1,
            'lga_id' => 1,
            'is_admin'=>1,
            'is_approved'=>1,
            'salary_deduction_agreement'=>1,
            'member_declaration'=>1,


        ]);

        $admin->roles()->sync($request->roles);

        return redirect()->route('admin.admins.index')->with('success', 'Admin created successfully');
    }

    public function edit(User $admin)
    {
        $roles = Role::all();
        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    public function update(Request $request, User $admin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'role' => 'required|exists:roles,id',
            'password' => 'nullable|min:8',
        ]);

        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $admin->update(['password' => bcrypt($validated['password'])]);
        }

        // Sync the role
        $admin->roles()->sync([$validated['role']]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin user updated successfully');
    }

}


