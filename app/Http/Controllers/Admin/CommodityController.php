<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commodity;
use Illuminate\Support\Facades\Storage;

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
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('commodities', 'public');
            $validated['image'] = $path;
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
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($commodity->image) {
                Storage::disk('public')->delete($commodity->image);
            }
            $path = $request->file('image')->store('commodities', 'public');
            $validated['image'] = $path;
        }

        $commodity->update($validated);

        return redirect()->route('admin.commodities.index')
            ->with('success', 'Commodity updated successfully.');
    }

    public function destroy(Commodity $commodity)
    {
        if ($commodity->image) {
            Storage::disk('public')->delete($commodity->image);
        }

        $commodity->delete();

        return redirect()->route('admin.commodities.index')
            ->with('success', 'Commodity deleted successfully.');
    }
}
