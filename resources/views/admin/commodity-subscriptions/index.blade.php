@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Commodity Subscriptions</h1>
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

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4 border-b bg-gray-50">
            <form action="{{ route('admin.commodity-subscriptions.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
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
                    <label for="commodity_id" class="block text-sm font-medium text-gray-700">Commodity</label>
                    <select name="commodity_id" id="commodity_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                        <option value="">All Commodities</option>
                        @foreach(\App\Models\Commodity::all() as $commodity)
                        <option value="{{ $commodity->id }}" {{ request('commodity_id') == $commodity->id ? 'selected' : '' }}>{{ $commodity->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="payment_type" class="block text-sm font-medium text-gray-700">Payment Type</label>
                    <select name="payment_type" id="payment_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                        <option value="">All Payment Types</option>
                        <option value="full" {{ request('payment_type') === 'full' ? 'selected' : '' }}>Full Payment</option>
                        <option value="installment" {{ request('payment_type') === 'installment' ? 'selected' : '' }}>Installment</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Filter
                    </button>
                    <a href="{{ route('admin.commodity-subscriptions.index') }}" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commodity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Details</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subscriptions as $subscription)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($subscription->user->member_image)
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $subscription->user->member_image) }}" alt="{{ $subscription->user->surname }}">
                                </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $subscription->user->surname }} {{ $subscription->user->firstname }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $subscription->user->member_no ?? 'No Member ID' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($subscription->commodity->image)
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $subscription->commodity->image) }}" alt="{{ $subscription->commodity->name }}">
                                </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $subscription->commodity->name }}</div>
                                    <div class="text-xs text-gray-500">Unit Price: ₦{{ number_format($subscription->commodity->price, 2) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $subscription->quantity }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                <div class="font-medium">₦{{ number_format($subscription->total_amount, 2) }}</div>

                                @if(isset($subscription->payment_type) && $subscription->payment_type == 'installment')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Installment Plan
                                    </span>
                                    <div class="mt-1 text-xs text-gray-500">
                                        <div>{{ $subscription->installment_months }} months</div>
                                        <div>Monthly: ₦{{ number_format($subscription->monthly_amount, 2) }}</div>
                                    </div>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Full Payment
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($subscription->status === 'approved')
                                @if(isset($subscription->payment_type) && $subscription->payment_type == 'installment')
                                    @php
                                        $initialDeposit = $subscription->initial_deposit ?? 0;
                                        $paidAmount = $initialDeposit + ($subscription->payments->sum('amount') ?? 0);
                                        $remainingAmount = $subscription->total_amount - $paidAmount;
                                        $percentPaid = ($paidAmount / $subscription->total_amount) * 100;
                                    @endphp

                                    <div class="text-xs text-gray-500 mb-1">
                                        <span class="font-medium">Paid:</span> ₦{{ number_format($paidAmount, 2) }}
                                    </div>
                                    <div class="text-xs text-gray-500 mb-1">
                                        <span class="font-medium">Balance:</span> ₦{{ number_format($remainingAmount, 2) }}
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(100, $percentPaid) }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500">{{ number_format($percentPaid, 1) }}% paid</div>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Fully Paid
                                    </span>
                                @endif
                            @else
                                <span class="text-xs text-gray-500">Payment pending approval</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($subscription->status === 'approved') bg-green-100 text-green-800
                            @elseif($subscription->status === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $subscription->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.commodity-subscriptions.show', $subscription) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                <i class="fas fa-eye"></i> View
                            </a>

                            @if($subscription->status === 'pending')
                            <form method="POST" action="{{ route('admin.commodity-subscriptions.approve', $subscription) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3" onclick="return confirm('Are you sure you want to approve this subscription?')">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>

                            <button type="button" class="text-red-600 hover:text-red-900"
                                    onclick="document.getElementById('reject-modal-{{ $subscription->id }}').classList.remove('hidden')">
                                <i class="fas fa-times"></i> Reject
                            </button>
                            @endif

                            @if($subscription->status === 'approved' && isset($subscription->payment_type) && $subscription->payment_type == 'installment')
                            <a href="{{ route('admin.commodity-payments.index', ['subscription_id' => $subscription->id]) }}" class="text-blue-600 hover:text-blue-900 ml-3">
                                <i class="fas fa-money-bill-wave"></i> Payments
                            </a>
                            @endif
                        </td>
                    </tr>

                    <!-- Reject Modal -->
                    <div id="reject-modal-{{ $subscription->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
                        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Subscription</h3>
                            <form method="POST" action="{{ route('admin.commodity-subscriptions.reject', $subscription) }}">
                                @csrf
                                <div>
                                    <label for="admin_notes" class="block text-sm font-medium text-gray-700">Reason for Rejection</label>
                                    <textarea name="admin_notes" id="admin_notes" rows="3" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                            style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;"></textarea>
                                </div>
                                <div class="mt-4 flex justify-end space-x-3">
                                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded"
                                            onclick="document.getElementById('reject-modal-{{ $subscription->id }}').classList.add('hidden')">
                                        Cancel
                                    </button>
                                                                      <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">
                                        Confirm Rejection
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No commodity subscriptions found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $subscriptions->links() }}
    </div>

    <!-- Summary Cards -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Total Subscriptions</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $subscriptions->total() }}</p>
            <div class="mt-2 flex justify-between text-sm">
                <span class="text-gray-500">Pending: {{ $pendingCount ?? 0 }}</span>
                <span class="text-gray-500">Approved: {{ $approvedCount ?? 0 }}</span>
                <span class="text-gray-500">Rejected: {{ $rejectedCount ?? 0 }}</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Total Value</h3>
            <p class="text-3xl font-bold text-green-600">₦{{ number_format($totalValue ?? 0, 2) }}</p>
            <p class="mt-2 text-sm text-gray-500">Total value of all subscriptions</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Installment Plans</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $installmentCount ?? 0 }}</p>
            <p class="mt-2 text-sm text-gray-500">{{ $installmentPercentage ?? 0 }}% of all subscriptions</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Collection Rate</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $collectionRate ?? 0 }}%</p>
            <p class="mt-2 text-sm text-gray-500">Payments collected vs. expected</p>
        </div>
    </div>
</div>
@endsection
