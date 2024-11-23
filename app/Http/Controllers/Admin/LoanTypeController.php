<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanType;
use Illuminate\Http\Request;

class LoanTypeController extends Controller
{
    public function index()
    {
        $loanTypes = LoanType::latest()->paginate(10);
        return view('admin.loan-types.index', compact('loanTypes'));
    }

    public function create()
    {
        return view('admin.loan-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:loan_types',
            'interest_rate' => 'required|numeric|min:0',
            'max_duration' => 'required|integer|min:1',
            'minimum_amount' => 'required|numeric|min:0',
            'maximum_amount' => 'required|numeric|gt:minimum_amount',
        ]);

        LoanType::create($validated);

        return redirect()->route('admin.loan-types.index')
            ->with('success', 'Loan type created successfully');
    }

    public function edit(LoanType $loanType)
    {
        return view('admin.loan-types.edit', compact('loanType'));
    }

    public function update(Request $request, LoanType $loanType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:loan_types,name,' . $loanType->id,
            'interest_rate' => 'required|numeric|min:0',
            'max_duration' => 'required|integer|min:1',
            'minimum_amount' => 'required|numeric|min:0',
            'maximum_amount' => 'required|numeric|gt:minimum_amount',
            'status' => 'required|in:active,inactive',
        ]);

        $loanType->update($validated);

        return redirect()->route('admin.loan-types.index')
            ->with('success', 'Loan type updated successfully');
    }
}
