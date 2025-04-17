@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">
            @if(isset($subscription))
                Payments for {{ $subscription->commodity->name }} Subscription
            @else
                Commodity Payments
            @endif
        </h1>

        @if(isset($subscription))
            <div class="flex space-x-4">
                <a href="{{ route('admin.commodity-subscriptions.show', $subscription) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Subscription
                </a>
                <a href="{{ route('admin.commodity-payments.create', $subscription) }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <i class="fas fa-plus mr-2"></i> Record Payment
                </a>
            </div>
        @endif
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    @if(isset($subscription))
    <!-- Subscription Summary -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Subscription Summary</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Member</h3>
                    <p class="mt-1 text-lg font-semibold">{{ $subscription->user->surname }} {{ $subscription->user->firstname }}</p>
                    <p class="text-sm text-gray-500">{{ $subscription->user->member_no ?? 'No Member ID' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Commodity</h3>
                    <p class="mt-1 text-lg font-semibold">{{ $subscription->commodity->name }}</p>
                    <p class="text-sm text-gray-500">Quantity: {{ $subscription->quantity }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Payment Plan</h3>
                    <p class="mt-1 text-lg font-semibold">
                        @if(isset($subscription->payment_type) && $subscription->payment_type == 'installment')
                            Installment ({{ $subscription->installment_months }} months)
                        @else
                            Full Payment
                        @endif
                    </p>
                    <p class="text-sm text-gray-500">Total: ₦{{ number_format($subscription->total_amount, 2) }}</p>
                </div>
            </div>

            @if(isset($subscription->payment_type) && $subscription->payment_type == 'installment')
            @php
                $initialDeposit = $subscription->initial_deposit ?? 0;
                $paidAmount = $initialDeposit + ($subscription->payments->sum('amount') ?? 0);
                $remainingAmount = $subscription->total_amount - $paidAmount;
                $percentPaid = ($paidAmount / $subscription->total_amount) * 100;
            @endphp

            <div class="mt-6 pt-6 border-t">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">Total Amount</h4>
                        <p class="mt-1 text-xl font-bold text-gray-900">₦{{ number_format($subscription->total_amount, 2) }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">Amount Paid</h4>
                        <p class="mt-1 text-xl font-bold text-green-600">₦{{ number_format($paidAmount, 2) }}</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">Remaining Balance</h4>
                        <p class="mt-1 text-xl font-bold text-blue-600">₦{{ number_format($remainingAmount, 2) }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">Payment Progress</h4>
                        <p class="mt-1 text-xl font-bold text-purple-600">{{ number_format($percentPaid, 1) }}%</p>
                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                            <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ min(100, $percentPaid) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="p-4 border-b bg-gray-50">
            <form action="{{ route('admin.commodity-payments.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                @if(isset($subscription))
                <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                @else
                <div>
                    <label for="subscription_id" class="block text-sm font-medium text-gray-700">Subscription</label>
                    <select name="subscription_id" id="subscription_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                        <option value="">All Subscriptions</option>
                        @foreach(\App\Models\CommoditySubscription::with(['user', 'commodity'])->get() as $sub)
                        <option value="{{ $sub->id }}" {{ request('subscription_id') == $sub->id ? 'selected' : '' }}>
                            {{ $sub->user->surname }} - {{ $sub->commodity->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                        <option value="">All Methods</option>
                        <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="deduction" {{ request('payment_method') === 'deduction' ? 'selected' : '' }}>Salary Deduction</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Filter
                    </button>
                    <a href="{{ route('admin.commodity-payments.index', isset($subscription) ? ['subscription_id' => $subscription->id] : []) }}" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                    @if(!isset($subscription))
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member/Commodity</th>
                    @endif
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payments as $payment)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        #{{ $payment->id }}
                    </td>

                    @if(!isset($subscription))
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $payment->commoditySubscription->user->surname }} {{ $payment->commoditySubscription->user->firstname }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $payment->commoditySubscription->commodity->name }} (x{{ $payment->commoditySubscription->quantity }})
                        </div>
                    </td>
                    @endif

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ₦{{ number_format($payment->amount, 2) }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        {{ $payment->payment_method === 'cash' ? 'bg-green-100 text-green-800' :
                           ($payment->payment_method === 'bank_transfer' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $payment->payment_reference ?? 'N/A' }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($payment->status === 'approved') bg-green-100 text-green-800
                        @elseif($payment->status === 'rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $payment->created_at->format('M d, Y') }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.commodity-payments.show', $payment) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-eye"></i> View
                        </a>

                        @if($payment->status === 'pending')
                        <form method="POST" action="{{ route('admin.commodity-payments.approve', $payment) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3" onclick="return confirm('Are you sure you want to approve this payment?')">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>

                        <button type="button" class="text-red-600 hover:text-red-900"
                                onclick="document.getElementById('reject-modal-{{ $payment->id }}').classList.remove('hidden')">
                            <i class="fas fa-times"></i> Reject
                        </button>
                        @endif
                    </td>
                </tr>

                <!-- Reject Modal -->
                @if($payment->status === 'pending')
                <div id="reject-modal-{{ $payment->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
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
                                        onclick="document.getElementById('reject-modal-{{ $payment->id }}').classList.add('hidden')">
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
                @empty
                <tr>
                    <td colspan="{{ isset($subscription) ? '7' : '8' }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        No payments found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>
@endsection
