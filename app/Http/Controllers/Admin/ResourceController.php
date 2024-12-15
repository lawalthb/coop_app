<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::latest()->paginate(10);
        return view('admin.resources.index', compact('resources'));
    }

    public function create()
    {
        return view('admin.resources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240'
        ]);

        $file = $request->file('file');
        $path = $file->store('resources');

        Resource::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id()
        ]);

        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource uploaded successfully');
    }

    public function destroy(Resource $resource)
    {
        Storage::delete($resource->file_path);
        $resource->delete();
        return back()->with('success', 'Resource deleted successfully');
    }
}
