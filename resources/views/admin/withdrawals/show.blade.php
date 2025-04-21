@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-purple-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Withdrawal Request Details</h2>
                <a href="{{ route('admin.withdrawals.index') }}" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>

            <div class="p-6 space-y-6">
                <!-- Status Banner -->
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-gray-600">Reference:</span>
                            <span class="font-medium">{{ $withdrawal->reference }}</span>
                        </div>
                        <div>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                {{ $withdrawal->status === 'approved' ? 'bg-green-100 text-green-800' :
                                   ($withdrawal->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($withdrawal->status) }}
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
                            <span class="font-medium">{{ $withdrawal->user->surname }} {{ $withdrawal->user->firstname }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Member No.:</span>
                            <span class="font-medium">{{ $withdrawal->user->member_no }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Department:</span>
                            <span class="font-medium">{{ $withdrawal->user->department->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Faculty:</span>
                            <span class="font-medium">{{ $withdrawal->user->faculty->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Withdrawal Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Withdrawal Details</h3>
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                        <div>
                            <span class="text-gray-600">Saving Type:</span>
                            <span class="font-medium">{{ $withdrawal->savingType->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Amount:</span>
                            <span class="font-medium">₦{{ number_format($withdrawal->amount, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Date Requested:</span>
                            <span class="font-medium">{{ $withdrawal->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Purpose:</span>
                            <span class="font-medium">{{ $withdrawal->purpose }}</span>
                        </div>
                    </div>
                </div>

                <!-- Bank Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bank Details</h3>
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                        <div>
                            <span class="text-gray-600">Bank Name:</span>
                            <span class="font-medium">{{ $withdrawal->bank_name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Account Number:</span>
                            <span class="font-medium">{{ $withdrawal->account_number }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Account Name:</span>
                            <span class="font-medium">{{ $withdrawal->account_name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Approval Information -->
                @if($withdrawal->status !== 'pending')
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        {{ $withdrawal->status === 'approved' ? 'Approval' : 'Rejection' }} Information
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-600">
                                    {{ $withdrawal->status === 'approved' ? 'Approved' : 'Rejected' }} By:
                                </span>
                                <span class="font-medium">
                                    {{ $withdrawal->approvedBy->surname ?? '' }} {{ $withdrawal->approvedBy->firstname ?? '' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600">
                                    {{ $withdrawal->status === 'approved' ? 'Approved' : 'Rejected' }} Date:
                                </span>
                                <span class="font-medium">
                                    {{ $withdrawal->approved_at ? $withdrawal->approved_at->format('M d, Y h:i A') : 'N/A' }}
                                </span>
                            </div>
                            @if($withdrawal->status === 'rejected' && $withdrawal->rejection_reason)
                            <div class="col-span-2">
                                <span class="text-gray-600">Rejection Reason:</span>
                                <p class="mt-1 text-sm text-gray-800 bg-white p-3 rounded border border-gray-200">
                                    {{ $withdrawal->rejection_reason }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                @if($withdrawal->status === 'pending')
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                            onclick="document.getElementById('reject-modal').classList.remove('hidden')">
                        Reject Withdrawal
                    </button>
                    <form action="{{ route('admin.withdrawals.approve', $withdrawal) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                                onclick="return confirm('Are you sure you want to approve this withdrawal?')">
                            Approve Withdrawal
                        </button>
                    </form>
                </div>

                <!-- Reject Modal -->
                <div id="reject-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Withdrawal Request</h3>
                        <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}">
                            @csrf
                            <div>
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Reason for Rejection</label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="3" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                        style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;"></textarea>
                            </div>
                            <div class="mt-4 flex justify-end space-x-3">
                                <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded"
                                        onclick="document.getElementById('reject-modal').classList.add('hidden')">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">
                                    Confirm Rejection
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
