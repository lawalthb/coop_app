@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Request Withdrawal</h2>
            </div>

            <div class="p-6">
                <!-- Available Balances -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Available Balances</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($savingTypes as $type)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-600">{{ $type->name }}</h4>
                            <p class="text-lg font-bold text-purple-600">₦{{ number_format($balances[$type->id], 2) }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <form action="{{ route('member.withdrawals.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Savings Type</label>
                            <select name="saving_type_id" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                                <option value="">Select Savings Type</option>
                                @foreach($savingTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                            <input type="number" name="amount" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                            <input type="text" name="bank_name" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                            <input type="text" name="account_number" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Account Name</label>
                            <input type="text" name="account_name" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Withdrawal</label>
                            <textarea name="reason" rows="3" class="w-full rounded-lg border-gray-300" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('member.withdrawals.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection