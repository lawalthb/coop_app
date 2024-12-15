<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::where('status', 'active')
            ->latest()
            ->paginate(12);
        return view('member.resources.index', compact('resources'));
    }

    public function download(Resource $resource)
    {
        $resource->increment('download_count');
        return Storage::download($resource->file_path);
    }
}
