@extends('layouts.member')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Transaction History</h1>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-sm font-medium text-gray-500">Total Credits</h3>
                <p class="text-2xl font-bold text-green-600">₦{{ number_format($totalCredits, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-sm font-medium text-gray-500">Total Debits</h3>
                <p class="text-2xl font-bold text-red-600">₦{{ number_format($totalDebits, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-sm font-medium text-gray-500">Balance</h3>
                <p class="text-2xl font-bold text-purple-600">₦{{ number_format($totalCredits - $totalDebits, 2) }}</p>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credit Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Debit Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->reference }}</td>
                            <td class="px-6 py-4">{{ $transaction->type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-green-600">
                                {{ $transaction->type === 'savings' ? '₦'.number_format($transaction->credit_amount, 2) : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-red-600">
                                {{ $transaction->type === 'withdraw' ? '₦'.number_format($transaction->debit_amount, 2) : '-' }}
                            </td>


                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('member.transactions.show', $transaction) }}" class="text-purple-600 hover:text-purple-900">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
