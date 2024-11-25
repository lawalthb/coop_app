@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Add New Loan Type</h2>
            </div>

            <form action="{{ route('admin.loan-types.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Interest Rate (%)</label>
                            <input type="number" name="interest_rate" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Maximum Duration (Months)</label>
                            <input type="number" name="max_duration" min="1" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Minimum Amount</label>
                            <input type="number" name="minimum_amount" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Maximum Amount</label>
                            <input type="number" name="maximum_amount" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.loan-types.index') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">Create Loan Type</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection