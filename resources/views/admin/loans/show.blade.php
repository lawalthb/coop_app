@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-purple-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Loan Details</h2>
                <a href="{{ route('admin.loans.index') }}" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>

            <div class="p-6 space-y-6">
                <!-- Status Banner -->
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-gray-600">Reference:</span>
                            <span class="font-medium">{{ $loan->reference }}</span>
                        </div>
                        <div>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                {{ $loan->status === 'approved' ? 'bg-green-100 text-green-800' :
                                   ($loan->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($loan->status) }}
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
                            <span class="font-medium">{{ $loan->user->surname }} {{ $loan->user->firstname }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Member No.:</span>
                            <span class="font-medium">{{ $loan->user->member_no }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Department:</span>
                            <span class="font-medium">{{ $loan->user->department->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Faculty:</span>
                            <span class="font-medium">{{ $loan->user->faculty->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Loan Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Loan Details</h3>
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                        <div>
                            <span class="text-gray-600">Loan Type:</span>
                            <span class="font-medium">{{ $loan->loanType->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Interest Rate:</span>
                            <span class="font-medium">{{ $loan->loanType->interest_rate }}%</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Principal Amount:</span>
                            <span class="font-medium">₦{{ number_format($loan->amount, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Interest Amount:</span>
                            <span class="font-medium">₦{{ number_format($loan->interest_amount, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-medium">₦{{ number_format($loan->total_amount, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Monthly repayment:</span>
                            <span class="font-medium">₦{{ number_format($loan->monthly_payment, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-medium">{{ $loan->duration }} months</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Purpose:</span>
                            <span class="font-medium">{{ $loan->purpose }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Schedule -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Schedule</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-600">Start Date:</span>
                                <span class="font-medium">{{ $loan->start_date->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">End Date:</span>
                                <span class="font-medium">{{ $loan->end_date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Loan History</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posted By</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($loan->repayments as $repayment)
                                <tr>
                                    <td class="px-4 py-3">{{ $repayment->payment_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3">{{ $repayment->reference }}</td>
                                    <td class="px-4 py-3">Repayment</td>
                                    <td class="px-4 py-3">₦{{ number_format($repayment->amount, 2) }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($repayment->payment_method) }}</td>
                                    <td class="px-4 py-3">{{ $repayment->postedBy->surname }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-center text-gray-500">No repayment history found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Approval Information -->
                @if($loan->status !== 'pending')
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Approval Information</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-600">Approved By:</span>
                                <span class="font-medium">{{ $loan->approvedBy->surname }} {{ $loan->approvedBy->firstname }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Approved Date:</span>
                                <span class="font-medium">{{ $loan->approved_at->format('M d, Y H:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-6">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-green-50 p-4 rounded-lg">
                            <span class="text-sm text-gray-600">Total Amount</span>
                            <p class="text-xl font-bold text-green-600">₦{{ number_format($loan->total_amount, 2) }}</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <span class="text-sm text-gray-600">Total Paid</span>
                            <p class="text-xl font-bold text-blue-600">₦{{ number_format($loan->repayments->sum('amount'), 2) }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <span class="text-sm text-gray-600">Balance</span>
                            <p class="text-xl font-bold text-purple-600">₦{{ number_format($loan->total_amount - $loan->repayments->sum('amount'), 2) }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Add Repayment Link Here -->
                @if($loan->status === 'approved')
                <div class="flex justify-end">
                    <a href="{{ route('admin.loans.repayments.create', $loan) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Record Payment
                    </a>
                </div>
                @endif

                <!-- Action Buttons -->

                @if($loan->status === 'pending') <div class="flex justify-end space-x-4 mt-6">
                    <form action="{{ route('admin.loans.reject', $loan) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700" onclick="return confirm('Are you sure you want to reject this loan?')">
                            Reject Loan
                        </button>
                    </form>
                    <form action="{{ route('admin.loans.approve', $loan) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700" onclick="return confirm('Are you sure you want to approve this loan?')">
                            Approve Loan
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
