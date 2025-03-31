<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commodity;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CommodityController extends Controller
{
    public function index()
    {
        $commodities = Commodity::latest()->paginate(10);
        return view('admin.commodities.index', compact('commodities'));
    }

    public function create()
    {
        return view('admin.commodities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity_available' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|max:2048',
            'purchase_amount' => 'nullable|numeric|min:0',
            'target_sales_amount' => 'nullable|numeric|min:0',
            'profit_amount' => 'nullable|numeric',
            'allow_installment' => 'boolean',
            'max_installment_months' => 'nullable|required_if:allow_installment,1|integer|min:1|max:36',
            'initial_deposit_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        // Calculate monthly installment amount if installment is allowed
        if ($request->allow_installment) {
            $price = $validated['price'];
            $months = $validated['max_installment_months'];
            $initialDepositPercentage = $validated['initial_deposit_percentage'] ?? 0;

            $initialDeposit = ($price * $initialDepositPercentage) / 100;
            $remainingAmount = $price - $initialDeposit;
            $monthlyAmount = $remainingAmount / $months;

            $validated['monthly_installment_amount'] = round($monthlyAmount, 2);
        }

        // Handle image upload with better error handling
        if ($request->hasFile('image')) {
            try {
                // Make sure the file is valid
                $file = $request->file('image');
                if ($file->isValid()) {
                    // Store the file in the public disk under commodities folder
                    $path = $file->store('commodities', 'public');
                    $validated['image'] = $path;

                    // Log success
                    Log::info('Commodity image uploaded successfully', ['path' => $path]);
                } else {
                    Log::error('Invalid file upload attempt', ['error' => $file->getErrorMessage()]);
                    return back()->with('error', 'The uploaded file is invalid: ' . $file->getErrorMessage());
                }
            } catch (\Exception $e) {
                Log::error('Failed to upload commodity image', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->with('error', 'Failed to upload image: ' . $e->getMessage());
            }
        }

        Commodity::create($validated);

        return redirect()->route('admin.commodities.index')
            ->with('success', 'Commodity created successfully.');
    }

    public function edit(Commodity $commodity)
    {
        return view('admin.commodities.edit', compact('commodity'));
    }

    public function update(Request $request, Commodity $commodity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity_available' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|max:2048',
            'purchase_amount' => 'nullable|numeric|min:0',
            'target_sales_amount' => 'nullable|numeric|min:0',
            'profit_amount' => 'nullable|numeric',
            'allow_installment' => 'boolean',
            'max_installment_months' => 'nullable|required_if:allow_installment,1|integer|min:1|max:36',
            'initial_deposit_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        // Calculate monthly installment amount if installment is allowed
        if ($request->allow_installment) {
            $price = $validated['price'];
            $months = $validated['max_installment_months'];
            $initialDepositPercentage = $validated['initial_deposit_percentage'] ?? 0;

            $initialDeposit = ($price * $initialDepositPercentage) / 100;
            $remainingAmount = $price - $initialDeposit;
            $monthlyAmount = $remainingAmount / $months;

            $validated['monthly_installment_amount'] = round($monthlyAmount, 2);
        } else {
            $validated['max_installment_months'] = null;
            $validated['monthly_installment_amount'] = null;
            $validated['initial_deposit_percentage'] = 0;
        }

        // Handle image upload with better error handling
        if ($request->hasFile('image')) {
            try {
                // Make sure the file is valid
                $file = $request->file('image');
                if ($file->isValid()) {
                    // Delete old image if exists
                    if ($commodity->image) {
                        Storage::disk('public')->delete($commodity->image);
                    }

                    // Store the file in the public disk under commodities folder
                    $path = $file->store('commodities', 'public');
                    $validated['image'] = $path;

                    // Log success
                    Log::info('Commodity image updated successfully', ['path' => $path]);
                } else {
                    Log::error('Invalid file upload attempt during update', ['error' => $file->getErrorMessage()]);
                    return back()->with('error', 'The uploaded file is invalid: ' . $file->getErrorMessage());
                }
            } catch (\Exception $e) {
                Log::error('Failed to upload commodity image during update', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->with('error', 'Failed to upload image: ' . $e->getMessage());
            }
        }

        $commodity->update($validated);

        return redirect()->route('admin.commodities.index')
            ->with('success', 'Commodity updated successfully.');
    }

    public function destroy(Commodity $commodity)
    {
        try {
            if ($commodity->image) {
                Storage::disk('public')->delete($commodity->image);
            }

            $commodity->delete();

            return redirect()->route('admin.commodities.index')
                ->with('success', 'Commodity deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete commodity', [
                'commodity_id' => $commodity->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.commodities.index')
                ->with('error', 'Failed to delete commodity: ' . $e->getMessage());
        }
    }
}
