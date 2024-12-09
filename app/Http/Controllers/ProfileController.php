<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\State;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $states = State::all();
        return view('profile.show', compact('user', 'states'));
    }

    public function edit()
    {
        $user = auth()->user();
        $states = State::where('status', 'active')->get();
        $faculties = Faculty::where('status', 'active')->get();

        return view('profile.edit', compact('user', 'states', 'faculties'));
    }
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'title' => 'required|string',
            'surname' => 'required|string',
            'firstname' => 'required|string',
            'othername' => 'nullable|string',
            'religion' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'phone_number' => 'required|string',
            'home_address' => 'required|string',
            'state_id' => 'required|exists:states,id',
            'lga_id' => 'required|exists:lgas,id',
            'profile_image' => 'nullable|image|max:2048',
        ]);


        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $path;
        }

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'Password updated successfully');
    }
}
