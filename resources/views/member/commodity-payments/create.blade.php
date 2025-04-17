@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('member.commodity-payments.index', $subscription) }}" class="text-purple-600 hover:text-purple-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Payment History
        </a>
    </div>

    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-purple-600">
            <h2 class="text-xl font-semibold text-white">Make a Payment</h2>
        </div>

        <div class="p-6">
            <!-- Subscription Summary -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Subscription Summary</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Commodity:</span>
                        <p class="font-medium">{{ $subscription->commodity->name }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Total Amount:</span>
                        <p class="font-medium">₦{{ number_format($subscription->total_amount, 2) }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Remaining Balance:</span>
                        <p class="font-medium text-purple-700">₦{{ number_format($remainingAmount, 2) }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Monthly Payment:</span>
                        <p class="font-medium">₦{{ number_format($subscription->monthly_amount, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <form action="{{ route('member.commodity-payments.store', $subscription) }}" method="POST" class="space-y-6">
                @csrf

                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Payment Amount (₦)</label>
                    <input type="number" name="amount" id="amount"
                           min="1" max="{{ $remainingAmount }}" step="0.01"
                           value="{{ old('amount', $subscription->monthly_amount) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                           style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;" required>
                    <p class="text-sm text-gray-500 mt-1">Maximum payment: ₦{{ number_format($remainingAmount, 2) }}</p>

                    @error('amount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select name="payment_method" id="payment_method"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                            style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;" required>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="deduction">Salary Deduction</option>
                    </select>

                    @error('payment_method')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-1">Payment Reference (Optional)</label>
                    <input type="text" name="payment_reference" id="payment_reference"
                           value="{{ old('payment_reference') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                           style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                    <p class="text-sm text-gray-500 mt-1">For bank transfers, please provide the transaction reference.</p>

                    @error('payment_reference')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Your payment will be pending until approved by an administrator.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end space-x-4">
                    <a href="{{ route('member.commodity-payments.index', $subscription) }}"
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Submit Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
