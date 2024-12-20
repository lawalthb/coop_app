@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Edit Saving Type</h2>
            </div>

            <form action="{{ route('admin.saving-types.update', $savingType) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="space-y-6 mb-8">
                    <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" value="{{ $savingType->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">{{ $savingType->description }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Financial Settings -->
                <div class="space-y-6 mb-8">
                    <h3 class="text-lg font-medium text-gray-900">Financial Settings</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Interest Rate (%)</label>
                            <input type="number" name="interest_rate" step="0.01" value="{{ $savingType->interest_rate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Minimum Amount</label>
                            <input type="number" name="minimum_balance" step="0.01" value="{{ $savingType->minimum_balance }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>
                    </div>
                </div>

                <!-- Rules and Restrictions -->
                <div class="space-y-6 mb-8">
                    <h3 class="text-lg font-medium text-gray-900">Rules and Restrictions</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="is_mandatory" value="1" {{ $savingType->is_mandatory ? 'checked' : '' }} class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                                <span class="text-sm text-gray-700">Is Mandatory</span>
                            </label>

                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="allow_withdrawal" value="1" {{ $savingType->allow_withdrawal ? 'checked' : '' }} class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                                <span class="text-sm text-gray-700">Allow Withdrawal</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Withdrawal Restriction (Days)</label>
                            <input type="number" name="withdrawal_restriction_days" value="{{ $savingType->withdrawal_restriction_days }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="space-y-6 mb-8">
                    <h3 class="text-lg font-medium text-gray-900">Status</h3>
                    <div>
                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                            <option value="active" {{ $savingType->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $savingType->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.saving-types.index') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                        Update Saving Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
