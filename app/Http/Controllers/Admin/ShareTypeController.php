<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShareType;
use Illuminate\Http\Request;

class ShareTypeController extends Controller
{
    public function index()
    {
        $shareTypes = ShareType::latest()->paginate(10);
        return view('admin.share-types.index', compact('shareTypes'));
    }

    public function create()
    {
        return view('admin.share-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'minimum_amount' => 'required|numeric|min:0',
            'maximum_amount' => 'required|numeric|min:0',
            'dividend_rate' => 'required|numeric|min:0',
            'is_transferable' => 'boolean',
            'has_voting_rights' => 'boolean',
            'description' => 'nullable|string'
        ]);

        ShareType::create($validated);

        return redirect()->route('admin.share-types.index')
            ->with('success', 'Share type created successfully');
    }

    public function edit(ShareType $shareType)
    {
        return view('admin.share-types.edit', compact('shareType'));
    }

    public function update(Request $request, ShareType $shareType)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'minimum_amount' => 'required|numeric|min:0',
            'maximum_amount' => 'required|numeric|min:0',
            'dividend_rate' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string'
        ]);

        $shareType->update($validated);

        return redirect()->route('admin.share-types.index')
            ->with('success', 'Share type updated successfully');
    }
    public function destroy(ShareType $shareType)
    {
        if ($shareType->shares()->exists()) {
            return redirect()
                ->route('admin.share-types.index')
                ->with('error', 'This share type cannot be deleted because it is being used by members');
        }

        $shareType->delete();

        return redirect()
            ->route('admin.share-types.index')
            ->with('success', 'Share type deleted successfully');
    }

}


