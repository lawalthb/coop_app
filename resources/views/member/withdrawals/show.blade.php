@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('member.withdrawals.index') }}" class="text-purple-600 hover:text-purple-800 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to My Withdrawals
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 bg-purple-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Withdrawal Request Details</h2>
                <span class="px-3 py-1 text-sm rounded-full
                    @if($withdrawal->status === 'approved') bg-green-100 text-green-800
                    @elseif($withdrawal->status === 'rejected') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($withdrawal->status) }}
                </span>
            </div>

            <div class="p-6 space-y-6">
                <!-- Reference Number -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <i class="fas fa-hashtag text-gray-400 mr-3"></i>
                        <div>
                            <span class="text-sm text-gray-500">Reference Number:</span>
                            <p class="font-medium text-gray-900">{{ $withdrawal->reference }}</p>
                        </div>
                    </div>
                </div>

                <!-- Withdrawal Details -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Withdrawal Information</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-500">Saving Type:</span>
                            <p class="font-medium text-gray-900">{{ $withdrawal->savingType->name }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Amount:</span>
                            <p class="font-medium text-gray-900">₦{{ number_format($withdrawal->amount, 2) }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Purpose:</span>
                            <p class="font-medium text-gray-900">{{ $withdrawal->purpose }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Date Requested:</span>
                            <p class="font-medium text-gray-900">{{ $withdrawal->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Bank Details -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Bank Details</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-500">Bank Name:</span>
                            <p class="font-medium text-gray-900">{{ $withdrawal->bank_name }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Account Number:</span>
                            <p class="font-medium text-gray-900">{{ $withdrawal->account_number }}</p>
                        </div>
                        <div class="col-span-2">
                            <span class="text-sm text-gray-500">Account Name:</span>
                            <p class="font-medium text-gray-900">{{ $withdrawal->account_name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Status Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Status Information</h3>

                    @if($withdrawal->status === 'pending')
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Your withdrawal request is pending approval. You will be notified once it's processed.
                                </p>
                            </div>
                        </div>
                    </div>
                    @elseif($withdrawal->status === 'approved')
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    Your withdrawal request has been approved. The funds will be transferred to your bank account.
                                </p>
                                <p class="text-sm text-green-700 mt-1">
                                    Approved on: {{ $withdrawal->approved_at ? $withdrawal->approved_at->format('M d, Y h:i A') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @elseif($withdrawal->status === 'rejected')
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-times-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Your withdrawal request has been rejected.
                                </p>
                                <p class="text-sm text-red-700 mt-1">
                                    Rejected on: {{ $withdrawal->approved_at ? $withdrawal->approved_at->format('M d, Y h:i A') : 'N/A' }}
                                </p>
                                @if($withdrawal->rejection_reason)
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-red-800">Reason for rejection:</p>
                                    <p class="text-sm text-red-700 mt-1 bg-white p-2 rounded border border-red-200">
                                        {{ $withdrawal->rejection_reason }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Timeline -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Request Timeline</h3>
                    <div class="space-y-6">
                        <!-- Created Event -->
                        <div class="relative pl-8 pb-6">
                            <div class="absolute top-0 left-0 h-full w-0.5 bg-gray-200"></div>
                            <div class="absolute top-0 left-0 -ml-1.5 mt-1.5 h-3 w-3 rounded-full border-2 border-purple-500 bg-white"></div>
                            <div class="flex flex-col">
                                <h4 class="text-sm font-medium text-gray-900">Withdrawal Requested</h4>
                                <time datetime="{{ $withdrawal->created_at }}" class="text-xs text-gray-500">
                                    {{ $withdrawal->created_at->format('M d, Y h:i A') }}
                                </time>
                                <p class="mt-1 text-sm text-gray-600">
                                    You requested a withdrawal of ₦{{ number_format($withdrawal->amount, 2) }} from your {{ $withdrawal->savingType->name }}.
                                </p>
                            </div>
                        </div>

                        <!-- Status Update Event (if not pending) -->
                        @if($withdrawal->status !== 'pending')
                        <div class="relative pl-8">
                            <div class="absolute top-0 left-0 h-full w-0.5 bg-gray-200"></div>
                            <div class="absolute top-0 left-0 -ml-1.5 mt-1.5 h-3 w-3 rounded-full border-2
                                @if($withdrawal->status === 'approved') border-green-500
                                @else border-red-500 @endif bg-white"></div>
                            <div class="flex flex-col">
                                <h4 class="text-sm font-medium text-gray-900">
                                    Withdrawal {{ ucfirst($withdrawal->status) }}
                                </h4>
                                <time datetime="{{ $withdrawal->approved_at }}" class="text-xs text-gray-500">
                                    {{ $withdrawal->approved_at ? $withdrawal->approved_at->format('M d, Y h:i A') : 'N/A' }}
                                </time>
                                <p class="mt-1 text-sm text-gray-600">
                                    @if($withdrawal->status === 'approved')
                                    Your withdrawal request was approved by the administrator.
                                    @else
                                    Your withdrawal request was rejected by the administrator.
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
