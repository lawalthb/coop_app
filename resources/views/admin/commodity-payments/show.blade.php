@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Navigation -->
        <div class="mb-6">
            <a href="{{ route('admin.commodity-payments.index', ['subscription_id' => $payment->commoditySubscription->id]) }}" class="text-purple-600 hover:text-purple-800 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Payments
            </a>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-purple-600 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Payment Details</h2>
                <span class="px-3 py-1 text-sm rounded-full
                    @if($payment->status === 'approved') bg-green-100 text-green-800
                    @elseif($payment->status === 'rejected') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-8">
                <!-- Payment ID -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <i class="fas fa-hashtag text-gray-400 mr-3"></i>
                        <div>
                            <span class="text-sm text-gray-500">Payment ID:</span>
                            <p class="font-medium text-gray-900">#{{ $payment->id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Two Column Layout for Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column: Payment Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Payment Information</h3>

                        <!-- Payment Details -->
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Amount:</span>
                                <span class="font-medium text-gray-900">₦{{ number_format($payment->amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Payment Method:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $payment->payment_method === 'cash' ? 'bg-green-100 text-green-800' :
                                   ($payment->payment_method === 'bank_transfer' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Payment Reference:</span>
                                <span class="font-medium">{{ $payment->payment_reference ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Payment Date:</span>
                                <span class="font-medium">{{ $payment->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>

                        <!-- Subscription Details -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Subscription Details</h4>
                            <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Commodity:</span>
                                    <span class="font-medium">{{ $payment->commoditySubscription->commodity->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Quantity:</span>
                                    <span class="font-medium">{{ $payment->commoditySubscription->quantity }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Total Amount:</span>
                                    <span class="font-medium">₦{{ number_format($payment->commoditySubscription->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Payment Type:</span>
                                    <span class="font-medium">
                                        @if(isset($payment->commoditySubscription->payment_type) && $payment->commoditySubscription->payment_type == 'installment')
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Installment</span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Full Payment</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Member & Status Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Member Information</h3>

                                                <!-- Member Details -->
                        <div class="flex items-start mb-4">
                            @if($payment->commoditySubscription->user->member_image)
                            <div class="flex-shrink-0 h-16 w-16">
                                <img class="h-16 w-16 rounded-full object-cover" src="{{ asset('storage/' . $payment->commoditySubscription->user->member_image) }}" alt="{{ $payment->commoditySubscription->user->surname }}">
                            </div>
                            @else
                            <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-400 text-2xl"></i>
                            </div>
                            @endif
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">
                                    {{ $payment->commoditySubscription->user->surname }} {{ $payment->commoditySubscription->user->firstname }}
                                </h4>
                                <p class="text-sm text-gray-600">{{ $payment->commoditySubscription->user->member_no ?? 'No Member ID' }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $payment->commoditySubscription->user->email }}</p>
                            </div>
                        </div>

                        <!-- Status Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-md font-medium text-gray-900 mb-3">Status Information</h4>

                            <div class="flex items-center mb-3">
                                <span class="text-sm text-gray-500 mr-3">Current Status:</span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($payment->status === 'approved') bg-green-100 text-green-800
                                    @elseif($payment->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>

                            @if($payment->status === 'pending')
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            This payment is pending approval. Use the buttons below to approve or reject.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @elseif($payment->status === 'approved')
                            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700">
                                            This payment has been approved.
                                        </p>
                                        @if($payment->approved_by)
                                        <p class="text-xs text-green-600 mt-1">
                                            Approved by: {{ \App\Models\User::find($payment->approved_by)->surname ?? 'Unknown' }}
                                            on {{ $payment->approved_at ? $payment->approved_at->format('M d, Y h:i A') : 'Unknown date' }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @elseif($payment->status === 'rejected')
                            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-times-circle text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">
                                            This payment has been rejected.
                                        </p>
                                        @if($payment->approved_by)
                                        <p class="text-xs text-red-600 mt-1">
                                            Rejected by: {{ \App\Models\User::find($payment->approved_by)->surname ?? 'Unknown' }}
                                            on {{ $payment->approved_at ? $payment->approved_at->format('M d, Y h:i A') : 'Unknown date' }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($payment->notes)
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Notes:</h4>
                                <div class="bg-white p-3 rounded-lg border border-gray-200">
                                    <p class="text-gray-700">{{ $payment->notes }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Progress (for installment plans) -->
                @if(isset($payment->commoditySubscription->payment_type) && $payment->commoditySubscription->payment_type == 'installment')
                @php
                    $initialDeposit = $payment->commoditySubscription->initial_deposit ?? 0;
                    $paidAmount = $initialDeposit + ($payment->commoditySubscription->payments->sum('amount') ?? 0);
                    $remainingAmount = $payment->commoditySubscription->total_amount - $paidAmount;
                    $percentPaid = ($paidAmount / $payment->commoditySubscription->total_amount) * 100;
                @endphp

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Progress</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Total Paid</h4>
                            <p class="mt-1 text-xl font-bold text-green-600">₦{{ number_format($paidAmount, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Including initial deposit of ₦{{ number_format($initialDeposit, 2) }}</p>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Remaining Balance</h4>
                            <p class="mt-1 text-xl font-bold text-blue-600">₦{{ number_format($remainingAmount, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Out of ₦{{ number_format($payment->commoditySubscription->total_amount, 2) }}</p>
                        </div>

                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Completion</h4>
                            <p class="mt-1 text-xl font-bold text-purple-600">{{ number_format($percentPaid, 1) }}%</p>

                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ min(100, $percentPaid) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                @if($payment->status === 'pending')
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                            onclick="document.getElementById('reject-modal').classList.remove('hidden')">
                        Reject Payment
                    </button>

                    <form action="{{ route('admin.commodity-payments.approve', $payment) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                                onclick="return confirm('Are you sure you want to approve this payment?')">
                            Approve Payment
                        </button>
                    </form>
                </div>

                <!-- Reject Modal -->
                <div id="reject-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Payment</h3>
                        <form method="POST" action="{{ route('admin.commodity-payments.reject', $payment) }}">
                            @csrf
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Reason for Rejection</label>
                                <textarea name="notes" id="notes" rows="3" required
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
