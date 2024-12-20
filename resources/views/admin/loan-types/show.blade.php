@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Loan Type Details</h2>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <p class="mt-1 text-lg">{{ $loanType->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Required Savings Months</label>
                        <p class="mt-1 text-lg">{{ $loanType->required_active_savings_months }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Interest Rate (12 months)</label>
                        <p class="mt-1 text-lg">{{ $loanType->interest_rate_12_months }}%</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Interest Rate (18 months)</label>
                        <p class="mt-1 text-lg">{{ $loanType->interest_rate_18_months }}%</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Minimum Amount</label>
                        <p class="mt-1 text-lg">₦{{ number_format($loanType->minimum_amount, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Maximum Amount</label>
                        <p class="mt-1 text-lg">₦{{ number_format($loanType->maximum_amount, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Number of Guarantors</label>
                        <p class="mt-1 text-lg">{{ $loanType->no_guarantors }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1 text-lg">{{ ucfirst($loanType->status) }}</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <a href="{{ route('admin.loan-types.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        Back to List
                    </a>
                    <a href="{{ route('admin.loan-types.edit', $loanType) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
