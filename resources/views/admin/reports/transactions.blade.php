@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Transactions Report</h1>
            <button onclick="window.print()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                <i class="fas fa-print mr-2"></i>Print Report
            </button>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form action="{{ route('admin.reports.transactions') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                    <select name="type" class="w-full rounded-lg border-gray-300">
                        <option value="">All Types</option>
                        <option value="entrance_fee" {{ request('type') == 'entrance_fee' ? 'selected' : '' }}>Entrance Fee</option>
                        <option value="savings" {{ request('type') == 'savings' ? 'selected' : '' }}>Savings</option>
                        <option value="shares" {{ request('type') == 'shares' ? 'selected' : '' }}>Shares</option>
                        <option value="loan" {{ request('type') == 'loan' ? 'selected' : '' }}>Loan</option>
                        <option value="loan_repayment" {{ request('type') == 'loan_repayment' ? 'selected' : '' }}>Loan Repayment</option>
                        <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="w-full rounded-lg border-gray-300">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 w-full">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Credits</h3>
                <p class="text-3xl font-bold text-green-600">₦{{ number_format($transactions->sum('credit_amount'), 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Debits</h3>
                <p class="text-3xl font-bold text-red-600">₦{{ number_format($transactions->sum('debit_amount'), 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Net Balance</h3>
                <p class="text-3xl font-bold text-purple-600">₦{{ number_format($transactions->sum('credit_amount') - $transactions->sum('debit_amount'), 2) }}</p>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Debit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->user->surname }} {{ $transaction->user->firstname }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-green-600">
                            {{ $transaction->credit_amount > 0 ? '₦'.number_format($transaction->credit_amount, 2) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-red-600">
                            {{ $transaction->debit_amount > 0 ? '₦'.number_format($transaction->debit_amount, 2) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' :
                                   ($transaction->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
