<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SavingType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SavingTypeController extends Controller
{
    public function index()
    {
        $savingTypes = SavingType::withdrawable()->where('status', 'active')->latest()->paginate(10);
        return view('admin.saving-types.index', compact('savingTypes'));
    }

    public function create()
    {
        return view('admin.saving-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'minimum_balance' => 'required|numeric|min:0',
            'is_mandatory' => 'boolean',
            'allow_withdrawal' => 'boolean',
            'withdrawal_restriction_days' => 'required|integer|min:0'
        ]);

        $validated['code'] = Str::upper(Str::slug($request->name, '_'));

        SavingType::create($validated);

        return redirect()->route('admin.saving-types.index')
            ->with('success', 'Saving type created successfully');
    }

    public function edit(SavingType $savingType)
    {
        return view('admin.saving-types.edit', compact('savingType'));
    }

    public function update(Request $request, SavingType $savingType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'minimum_balance' => 'required|numeric|min:0',
            'is_mandatory' => 'boolean',
            'allow_withdrawal' => 'boolean',
            'withdrawal_restriction_days' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        $savingType->update($validated);

        return redirect()->route('admin.saving-types.index')
            ->with('success', 'Saving type updated successfully');
    }
}
