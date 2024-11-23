@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Record Loan Repayment</h2>
            </div>

            <div class="p-6">
                <!-- Loan Information -->
                <div class="bg-purple-50 p-4 rounded-lg mb-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600">Member:</span>
                            <span class="font-medium">{{ $loan->user->surname }} {{ $loan->user->firstname }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Loan Reference:</span>
                            <span class="font-medium">{{ $loan->reference }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Monthly Payment:</span>
                            <span class="font-medium">₦{{ number_format($loan->monthly_payment, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Outstanding Balance:</span>
                            <span class="font-medium">₦{{ number_format($loan->total_amount - $loan->repayments->sum('amount'), 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Repayment Form -->
                <form action="{{ route('admin.loans.repayments.store', $loan) }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" name="amount" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" value="{{ $loan->monthly_payment }}" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment Date</label>
                            <input type="date" name="payment_date" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" value="{{ date('Y-m-d') }}" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <select name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" required style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;">
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cash">Cash</option>

                                <option value="deduction">Salary Deduction</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px;"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('admin.loans.show', $loan) }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">Record Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
