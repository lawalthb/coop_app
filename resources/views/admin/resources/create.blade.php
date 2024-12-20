@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Upload New Resource</h2>
            </div>

            <form action="{{ route('admin.resources.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" name="title" class="w-full rounded-lg border-gray-300" required
                               style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300"
                                  style="border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File</label>
                        <input type="file" name="file" class="w-full" required>
                        <p class="mt-1 text-sm text-gray-500">Supported formats: PDF, Excel, Word, Images, Videos (Max: 10MB)</p>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.resources.index') }}"
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Cancel</a>
                        <button type="submit"
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Upload Resource</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
