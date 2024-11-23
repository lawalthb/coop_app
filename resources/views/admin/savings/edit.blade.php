@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Edit Savings Entry</h2>
            </div>

            <form action="{{ route('admin.savings.update', $saving) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Member Information (Read-only) -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Member Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Member Name</label>
                                <input type="text" value="{{ $saving->user->surname }} {{ $saving->user->firstname }}" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100" disabled style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Member No.</label>
                                <input type="text" value="{{ $saving->user->member_no }}" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100" disabled style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                            </div>
                        </div>
                    </div>

                    <!-- Saving Details -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Saving Details</h3>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Amount</label>
                                <input type="number" name="amount" value="{{ $saving->amount }}" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Saving Type</label>
                                <select name="saving_type_id" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                                    @foreach($savingTypes as $type)
                                    <option value="{{ $type->id }}" {{ $saving->saving_type_id == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Period Selection -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Period</h3>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Month</label>
                                <select name="month_id" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                                    @foreach($months as $month)
                                    <option value="{{ $month->id }}" {{ $saving->month_id == $month->id ? 'selected' : '' }}>
                                        {{ $month->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Year</label>
                                <select name="year_id" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                                    @foreach($years as $year)
                                    <option value="{{ $year->id }}" {{ $saving->year_id == $year->id ? 'selected' : '' }}>
                                        {{ $year->year }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Remarks</label>
                        <textarea name="remark" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200">{{ $saving->remark }}</textarea style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">{{ $saving->remark }}</textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.savings') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                        Update Savings Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
