<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanType;
use Illuminate\Http\Request;

class LoanTypeController extends Controller
{
    public function index()
    {
        $loanTypes = LoanType::all();
        return view('admin.loan-types.index', compact('loanTypes'));
    }

    public function create()
    {
        return view('admin.loan-types.create');
    }

  public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'required_active_savings_months' => 'required|integer|min:3',
            'savings_multiplier' => 'required|numeric|min:1',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'duration_months' => 'required|integer|min:1',
            'minimum_amount' => 'required|numeric|min:0',
            'maximum_amount' => 'required|numeric|gt:minimum_amount',
            'allow_early_payment' => 'boolean',
            'no_guarantors' => 'required|integer|min:0',
        ]);

        try {
            LoanType::create($validated);
            return redirect()->route('admin.loan-types.index')
                ->with('success', 'Loan type created successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(LoanType $loanType)
    {
        return view('admin.loan-types.edit', compact('loanType'));
    }

       public function update(Request $request, LoanType $loanType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            //'required_active_savings_months' => 'required|integer|min:6',
           // 'savings_multiplier' => 'required|numeric|min:1',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'duration_months' => 'required|integer|min:1',
            'minimum_amount' => 'required|numeric|min:0',
            'maximum_amount' => 'required|numeric|gt:minimum_amount',
            'status' => 'required|in:active,inactive',
            'saved_percentage' => 'nullable|in:50,100,150,200,250,300,None',
            'no_guarantors' => 'required|integer|min:0',
        ]);

        $loanType->update($validated);
        return redirect()->route('admin.loan-types.index')->with('success', 'Loan type updated successfully');
    }



    public function show(LoanType $loanType)
    {
        return view('admin.loan-types.show', compact('loanType'));
    }

    public function destroy(LoanType $loanType)
    {
        $loanType->delete();
        return redirect()->route('admin.loan-types.index')
            ->with('success', 'Loan type deleted successfully');
    }
}
