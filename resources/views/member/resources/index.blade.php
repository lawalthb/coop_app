@extends('layouts.member')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Information</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($resources as $resource)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $resource->title }}</h3>
                    @if($resource->description)
                        <p class="text-gray-600 mb-4">{{ $resource->description }}</p>
                    @endif
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <span>{{ number_format($resource->file_size / 1024, 2) }} KB</span>
                        <span>{{ $resource->download_count }} downloads</span>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('member.resources.download', $resource) }}"
                           class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                            <i class="fas fa-download mr-2"></i>
                            Download
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $resources->links() }}
        </div>
    </div>
</div>
@endsection
