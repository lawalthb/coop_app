@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-purple-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Transaction Details</h2>
                <a href="{{ route('admin.transactions.index') }}" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>

            <div class="p-6 space-y-6">
                <!-- Transaction Information -->
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600">Reference:</span>
                            <span class="font-medium">{{ $transaction->reference }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Member Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Member Information</h3>
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                        <div>
                            <span class="text-gray-600">Name:</span>
                            <span class="font-medium">{{ $transaction->user->surname }} {{ $transaction->user->firstname }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Member N.:</span>
                            <span class="font-medium">{{ $transaction->user->member_no }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Department:</span>
                            <span class="font-medium">{{ $transaction->user->department->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Faculty:</span>
                            <span class="font-medium">{{ $transaction->user->faculty->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Transaction Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Details</h3>
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                        <div>
                            <span class="text-gray-600">Type:</span>
                            <span class="font-medium">{{ ucwords(str_replace('_', ' ', $transaction->type)) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Amount:</span>
                            <span class="font-medium">₦{{ number_format($transaction->amount, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Date:</span>
                            <span class="font-medium">{{ $transaction->created_at->format('M d, Y H:i A') }}</span>
                        </div>
                        @if($transaction->description)
                        <div class="col-span-2">
                            <span class="text-gray-600">Description:</span>
                            <span class="font-medium">{{ $transaction->description }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Related Information -->
                @if($transaction->loan_id && $transaction->loan && $transaction->loan->loanType && ($transaction->type === 'loan_repayment' || $transaction->type === 'loan_disbursement'))
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Loan Information</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-600">Loan Reference:</span>
                                <span class="font-medium">
                                    {{ $transaction->loan ? $transaction->loan->reference : 'N/A' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600">Loan Type:</span>
                                <span class="font-medium">{{ $transaction->loan->loanType->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
