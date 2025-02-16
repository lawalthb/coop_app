@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-6">Edit Share Type</h2>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Please correct the following errors:
                            </h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.share-types.update', $shareType) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" value="{{ old('name', $shareType->name) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                    </div>



                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Amount</label>
                            <input type="number" name="minimum_units" value="{{ old('minimum_units', $shareType->minimum_units) }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Amount</label>
                            <input type="number" name="maximum_units" value="{{ old('maximum_units', $shareType->maximum_units) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dividend Rate (%)</label>
                            <input type="number" step="0.01" name="dividend_rate" value="{{ old('dividend_rate', $shareType->dividend_rate) }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" required class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                                <option value="active" {{ old('status', $shareType->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $shareType->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">{{ old('description', $shareType->description) }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.share-types.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 mr-2">Cancel</a>
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            Update Share Type
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
