@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-6">Create New Share Type</h2>

            <form action="{{ route('admin.share-types.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                    </div>


                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Amount</label>
                            <input type="number" name="minimum_amount" value="{{ old('minimum_amount') }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Amount</label>
                            <input type="number" name="maximum_amount" value="{{ old('maximum_amount') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dividend Rate (%)</label>
                        <input type="number" step="0.01" name="dividend_rate" value="{{ old('dividend_rate', 0) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                    </div>

                    <div class="flex space-x-4" style="display: none;">
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" checked name="is_transferable" value="1" {{ old('is_transferable') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-purple-600 focus:border-purple-500 focus:ring focus:ring-purple-200">
                                <span class="ml-2">Transferable</span>
                            </label>
                        </div>

                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" checked name="has_voting_rights" value="1" {{ old('has_voting_rights') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-purple-600 focus:border-purple-500 focus:ring focus:ring-purple-200">
                                <span class="ml-2">Voting Rights</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            Create Share Type
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
