@extends('layouts.member')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('member.commodity-subscriptions.index') }}" class="text-purple-600 hover:text-purple-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to My Subscriptions
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-purple-600 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Subscription Details</h2>
            <span class="px-3 py-1 text-sm rounded-full
                @if($subscription->status === 'approved') bg-green-100 text-green-800
                @elseif($subscription->status === 'rejected') bg-red-100 text-red-800
                @else bg-yellow-100 text-yellow-800 @endif">
                {{ ucfirst($subscription->status) }}
            </span>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Commodity Information</h3>
                    <div class="flex items-start mb-4">
                        @if($subscription->commodity->image)
                        <img src="{{ asset('storage/' . $subscription->commodity->image) }}" alt="{{ $subscription->commodity->name }}" class="w-15 h-15 object-cover rounded mr-4">
                        @else
                        <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center mr-4">
                            <i class="fas fa-shopping-basket text-gray-400 text-2xl"></i>
                        </div>
                        @endif
                        <div>
                            <h4 class="text-md font-semibold">{{ $subscription->commodity->name }}</h4>
                            <p class="text-sm text-gray-600 mt-1">Unit Price: ₦{{ number_format($subscription->unit_price, 2) }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-500">Quantity:</span>
                            <p class="font-medium">{{ $subscription->quantity }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Total Amount:</span>
                            <p class="font-medium">₦{{ number_format($subscription->total_amount, 2) }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Subscription Date:</span>
                            <p class="font-medium">{{ $subscription->created_at->format('M d, Y h:i A') }}</p>
                        </div>

                        <!-- Payment Type Information -->
                        <div>
                            <span class="text-sm text-gray-500">Payment Type:</span>
                            <p class="font-medium">
                                @if(isset($subscription->payment_type) && $subscription->payment_type == 'installment')
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Installment</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Full Payment</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Installment Details Section -->
                    @if(isset($subscription->payment_type) && $subscription->payment_type == 'installment')
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Installment Details</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Initial Deposit:</span>
                                <span class="font-medium">₦{{ number_format($subscription->initial_deposit, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Remaining Amount:</span>
                                <span class="font-medium">₦{{ number_format($subscription->remaining_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Installment Period:</span>
                                <span class="font-medium">{{ $subscription->installment_months }} months</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Monthly Payment:</span>
                                <span class="font-medium">₦{{ number_format($subscription->monthly_amount, 2) }}</span>
                            </div>

                            @if($subscription->status === 'approved')
                            <div class="mt-4 pt-3 border-t border-blue-200">
                                <a href="{{ route('member.commodity-payments.index', ['subscription' => $subscription->id]) }}"
                                   class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                                    View Payment History
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Status Information</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm text-gray-500">Current Status:</span>
                            <p class="font-medium mt-1">
                                <span class="px-2 py-1 rounded-full
                                @if($subscription->status === 'approved') bg-green-100 text-green-800
                                @elseif($subscription->status === 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </p>
                        </div>

                        @if($subscription->status === 'pending')
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Your subscription is pending approval. You will be notified once it's processed.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($subscription->status === 'approved')
                        <div class="bg-green-50 border-l-4 border-green-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">
                                        @if(isset($subscription->payment_type) && $subscription->payment_type == 'installment')
                                            Your subscription has been approved. Please make your monthly payments on time.
                                        @else
                                            Your subscription has been approved. You can collect your commodity from the cooperative office.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($subscription->status === 'rejected')
                        <div class="bg-red-50 border-l-4 border-red-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-times-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        Your subscription has been rejected.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($subscription->reason)
                        <div>
                            <span class="text-sm text-gray-500">Your Reason for Subscription:</span>
                            <p class="mt-1 text-gray-700 bg-gray-50 p-3 rounded">{{ $subscription->reason }}</p>
                        </div>
                        @endif

                        @if($subscription->admin_notes)
                        <div>
                            <span class="text-sm text-gray-500">Admin Notes:</span>
                            <p class="mt-1 text-gray-700 bg-gray-50 p-3 rounded">{{ $subscription->admin_notes }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Payment Summary for Approved Installment Subscriptions -->
                    @if($subscription->status === 'approved' && isset($subscription->payment_type) && $subscription->payment_type == 'installment')
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Payment Summary</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-green-50 p-3 rounded-lg">
                                <div class="text-xs text-gray-500">Total Amount</div>
                                <div class="font-medium text-green-700">₦{{ number_format($subscription->total_amount, 2) }}</div>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <div class="text-xs text-gray-500">Paid Amount</div>
                                <div class="font-medium text-blue-700">₦{{ number_format($subscription->initial_deposit + ($subscription->payments->sum('amount') ?? 0), 2) }}</div>
                            </div>
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <div class="text-xs text-gray-500">Remaining</div>
                                <div class="font-medium text-purple-700">₦{{ number_format($subscription->total_amount - ($subscription->initial_deposit + ($subscription->payments->sum('amount') ?? 0)), 2) }}</div>
                            </div>
                        </div>

                        @if($subscription->status === 'approved' && isset($subscription->payment_type) && $subscription->payment_type == 'installment')
                        <div class="mt-4">
                            <a href="{{ route('member.commodity-payments.create', ['subscription' => $subscription->id]) }}"
                               class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded">
                                Make a Payment
                            </a>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Timeline</h3>
                <div class="space-y-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center">
                                <i class="fas fa-plus text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Subscription Created</h4>
                            <p class="text-sm text-gray-500">{{ $subscription->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    @if($subscription->status !== 'pending')
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full {{ $subscription->status === 'approved' ? 'bg-green-500' : 'bg-red-500' }} flex items-center justify-center">
                                <i class="fas {{ $subscription->status === 'approved' ? 'fa-check' : 'fa-times' }} text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">
                                Subscription {{ ucfirst($subscription->status) }}
                            </h4>
                            <p class="text-sm text-gray-500">{{ $subscription->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Payment Timeline Events -->
                    @if(isset($subscription->payments) && $subscription->payments->count() > 0)
                        @foreach($subscription->payments as $payment)
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">
                                    Payment Received
                                </h4>
                                <p class="text-sm text-gray-500">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                                <p class="text-sm text-gray-700">Amount: ₦{{ number_format($payment->amount, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
