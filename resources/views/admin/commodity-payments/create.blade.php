@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Back Navigation -->
        <div class="mb-6">
            <a href="{{ route('admin.commodity-payments.index', ['subscription_id' => $subscription->id]) }}" class="text-purple-600 hover:text-purple-800 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Payments
            </a>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Record Payment</h2>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Subscription Summary -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-md font-medium text-gray-900 mb-3">Subscription Details</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-500">Member:</span>
                            <p class="font-medium">{{ $subscription->user->surname }} {{ $subscription->user->firstname }}</p>
                        </div>

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
                            <p class="font-medium text-blue-600">₦{{ number_format($remainingAmount, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <form action="{{ route('admin.commodity-payments.store', $subscription) }}" method="POST">
                    @csrf

                    @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                    @endif

                    <div class="space-y-6">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Payment Amount</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₦</span>
                                <input type="number" name="amount" id="amount" step="0.01" min="1" max="{{ $remainingAmount }}" required
                                       class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                       style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Maximum amount: ₦{{ number_format($remainingAmount, 2) }}</p>
                        </div>

                        <div>
                                                     <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                            <select name="payment_method" id="payment_method" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                    style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="deduction">Salary Deduction</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div>
        <label for="month_id" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
        <select name="month_id" id="month_id" required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
            @foreach($months as $month)
                <option value="{{ $month->id }}">{{ $month->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="year_id" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
        <select name="year_id" id="year_id" required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
            @foreach($years as $year)
                <option value="{{ $year->id }}">{{ $year->year }}</option>
            @endforeach
        </select>
    </div>
</div>

                        <div>
                            <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-1">Payment Reference (Optional)</label>
                            <input type="text" name="payment_reference" id="payment_reference"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                   style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;">
                            <p class="mt-1 text-sm text-gray-500">E.g., receipt number, transaction ID, etc.</p>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="3"
                                     class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                     style="border: 1px solid #ccc; padding: 10px; font-size: 16px; border-radius: 5px;"></textarea>
                        </div>

                        <div class="pt-4 flex justify-end space-x-4">
                            <a href="{{ route('admin.commodity-payments.index', ['subscription_id' => $subscription->id]) }}"
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                                Record Payment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Format amount as currency when typing
    document.getElementById('amount').addEventListener('input', function(e) {
        const amount = this.value;
        document.getElementById('formattedAmount').textContent = new Intl.NumberFormat('en-NG', {
            style: 'currency',
            currency: 'NGN'
        }).format(amount);
    });
</script>
@endsection
