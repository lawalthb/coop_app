@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.commodity-subscriptions.index') }}" class="text-purple-600 hover:text-purple-900">
                <i class="fas fa-arrow-left mr-2"></i> Back to Subscriptions
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

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Member Information</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-500">Name:</span>
                                <p class="font-medium">{{ $subscription->user->surname }} {{ $subscription->user->firstname }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Member ID:</span>
                                <p class="font-medium">{{ $subscription->user->member_no }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Email:</span>
                                <p class="font-medium">{{ $subscription->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Commodity Information</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-500">Commodity:</span>
                                <p class="font-medium">{{ $subscription->commodity->name }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Unit Price:</span>
                                <p class="font-medium">₦{{ number_format($subscription->commodity->price, 2) }}</p>
                            </div>
                            <div class="flex space-x-8">
                                <div>
                                    <span class="text-sm text-gray-500">Quantity:</span>
                                    <p class="font-medium">{{ $subscription->quantity }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Total Amount:</span>
                                    <p class="font-medium">₦{{ number_format($subscription->total_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Payment Information</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-gray-500">Payment Type:</span>
                                <p class="font-medium">{{ $subscription->payment_type === 'installment' ? 'Installment' : 'Full Payment' }}</p>
                            </div>

                            @if($subscription->payment_type === 'installment')
                            <div>
                                <span class="text-sm text-gray-500">Initial Deposit:</span>
                                <p class="font-medium">₦{{ number_format($subscription->initial_deposit, 2) }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Monthly Amount:</span>
                                <p class="font-medium">₦{{ number_format($subscription->monthly_amount, 2) }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Installment Period:</span>
                                <p class="font-medium">{{ $subscription->installment_months }} months</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Months Paid:</span>
                                <p class="font-medium">{{ $subscription->months_paid }} / {{ $subscription->installment_months }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Payment Progress:</span>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                                    <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $subscription->payment_progress_percentage }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $subscription->payment_progress_percentage }}% complete</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Next Payment Due:</span>
                                <p class="font-medium">
                                    @if($subscription->is_completed)
                                        <span class="text-green-600">Fully Paid</span>
                                    @elseif($subscription->next_payment_date)
                                        {{ $subscription->next_payment_date->format('M d, Y') }}
                                    @else
                                        <span class="text-yellow-600">Pending initial payment</span>
                                    @endif
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($subscription->reason)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Reason for Subscription</h3>
                    <p class="text-gray-700">{{ $subscription->reason }}</p>
                </div>
                @endif

                @if($subscription->admin_notes)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Admin Notes</h3>
                    <p class="text-gray-700">{{ $subscription->admin_notes }}</p>
                </div>
                @endif

                <!-- Status Actions -->
                @if($subscription->status === 'pending')
                <div class="flex space-x-4">
                    <form action="{{ route('admin.commodity-subscriptions.approve', $subscription) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                            Approve Subscription
                        </button>
                    </form>

                    <button type="button" onclick="toggleRejectForm()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                        Reject Subscription
                    </button>
                </div>

                <div id="rejectForm" class="hidden mt-4 p-4 bg-red-50 rounded-lg">
                    <form action="{{ route('admin.commodity-subscriptions.reject', $subscription) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                            <textarea name="admin_notes" id="admin_notes" rows="3" required
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200"
                                      style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;"></textarea>
                        </div>
                        <div class="flex justify-end">

                            <button type="button" onclick="toggleRejectForm()" class="mr-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                                Cancel
                            </button>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                                Confirm Rejection
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Record Payment Section (for approved subscriptions) -->
                @if($subscription->status === 'approved' && !$subscription->is_completed)
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Record Payment</h3>

                    <button type="button" onclick="togglePaymentForm()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                        Record New Payment
                    </button>

                    <div id="paymentForm" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
                        <form action="{{ route('admin.commodity-subscriptions.record-payment', $subscription) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (₦)</label>
                                    <input type="number" name="amount" id="amount" step="0.01" min="0.01" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                           style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;"
                                           @if($subscription->payment_type === 'installment')
                                               @if($subscription->months_paid === 0)
                                                   value="{{ $subscription->initial_deposit }}"
                                               @else
                                                   value="{{ $subscription->monthly_amount }}"
                                               @endif
                                           @else
                                               value="{{ $subscription->total_amount }}"
                                           @endif>
                                </div>

                                <div>
                                    <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-1">Payment Type</label>
                                    <select name="payment_type" id="payment_type" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                            style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                                        @if($subscription->payment_type === 'full')
                                            <option value="full">Full Payment</option>
                                        @else
                                            @if($subscription->months_paid === 0)
                                                <option value="initial_deposit">Initial Deposit</option>
                                            @endif
                                            <option value="installment">Monthly Installment</option>
                                        @endif
                                    </select>
                                </div>

                                <div>
                                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                                    <input type="date" name="payment_date" id="payment_date" required max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                           style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                                </div>

                                <div>
                                    <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-1">Payment Reference</label>
                                    <input type="text" name="payment_reference" id="payment_reference"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                           style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;"
                                           placeholder="Receipt number or transaction ID">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-1">Payment Proof (Optional)</label>
                                    <input type="file" name="payment_proof" id="payment_proof" accept="image/*"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                           style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" id="status" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                                            style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;">
                                        <option value="approved">Approved</option>
                                        <option value="pending">Pending</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" onclick="togglePaymentForm()" class="mr-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                                    Record Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Payment History -->
                @if($subscription->payments && $subscription->payments->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Payment History</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($subscription->payments as $payment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($payment->payment_type === 'full')
                                            Full Payment
                                        @elseif($payment->payment_type === 'initial_deposit')
                                            Initial Deposit
                                        @else
                                            Monthly Installment
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₦{{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->payment_reference }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($payment->status === 'approved') bg-green-100 text-green-800
                                            @elseif($payment->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($payment->status === 'pending')
                                        <div class="flex space-x-2">
                                            <form action="{{ route('admin.commodity-payments.approve', $payment) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>

                                            <button type="button" onclick="showRejectPaymentModal({{ $payment->id }})" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </div>
                                        @endif

                                        @if($payment->payment_proof && $payment->payment_proof !== 'admin-recorded-payment')
                                        <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank" class="text-blue-600 hover:text-blue-900 block mt-1">
                                            <i class="fas fa-file-image"></i> View Proof
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Timeline</h3>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-clock text-gray-400"></i>
                            <span>Submitted on {{ $subscription->created_at->format('M d, Y \a\t h:i A') }}</span>
                        </div>

                        @if($subscription->status !== 'pending')
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-check-circle text-{{ $subscription->status === 'approved' ? 'green' : 'red' }}-500"></i>
                            <span>{{ $subscription->status === 'approved' ? 'Approved' : 'Rejected' }} on {{ ($subscription->status === 'approved' ? $subscription->approved_at : $subscription->rejected_at)->format('M d, Y \a\t h:i A') }}</span>
                        </div>
                        @endif

                        @if($subscription->is_completed)
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-check-double text-green-500"></i>
                            <span>Payment completed on {{ $subscription->updated_at->format('M d, Y \a\t h:i A') }}</span>
                        </div>
                        @endif

                        @if($subscription->status === 'approved' && isset($subscription->payment_type) && $subscription->payment_type == 'installment')
<div class="mt-4">
    <a href="{{ route('admin.commodity-payments.index', ['subscription_id' => $subscription->id]) }}"
       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
        <i class="fas fa-money-bill-wave mr-2"></i> View Payment History
    </a>
</div>
@endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Payment Modal -->
<div id="rejectPaymentModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Payment</h3>

        <form id="rejectPaymentForm" action="" method="POST">
            @csrf
            <div class="mb-4">
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                <textarea name="rejection_reason" id="rejection_reason" rows="3" required
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200"
                          style="border: 1px solid #ccc; font-size: 16px; border-radius: 10px;"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="hideRejectPaymentModal()" class="mr-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                    Cancel
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                    Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    function toggleRejectForm() {
        const form = document.getElementById('rejectForm');
        form.classList.toggle('hidden');
    }

    function togglePaymentForm() {
        const form = document.getElementById('paymentForm');
        form.classList.toggle('hidden');
    }

    function showRejectPaymentModal(paymentId) {
        const modal = document.getElementById('rejectPaymentModal');
        const form = document.getElementById('rejectPaymentForm');

        // Set the form action URL
        form.action = `/admin/commodity-payments/${paymentId}/reject`;

        // Show the modal
        modal.classList.remove('hidden');
    }

    function hideRejectPaymentModal() {
        const modal = document.getElementById('rejectPaymentModal');
        modal.classList.add('hidden');
    }
</script>
@endsection
