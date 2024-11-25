@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Transaction Records</h1>

            <!-- Export Button -->
            <a href="{{ route('admin.transactions.export') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                <i class="fas fa-download mr-2"></i>Export
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form action="{{ route('admin.transactions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding-left: 5px; ">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding-left: 5px; ">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type</label>
                    <select name="type" class="mt-1 block w-full rounded-md border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding-left: 5px; ">
                        <option value="">All Types</option>
                        <option value="loan_disbursement" {{ request('type') === 'loan_disbursement' ? 'selected' : '' }}>Loan Disbursement</option>
                        <option value="loan_repayment" {{ request('type') === 'loan_repayment' ? 'selected' : '' }}>Loan Repayment</option>
                        <option value="savings_deposit" {{ request('type') === 'savings_deposit' ? 'selected' : '' }}>Savings Deposit</option>
                        <option value="savings_withdrawal" {{ request('type') === 'savings_withdrawal' ? 'selected' : '' }}>Savings Withdrawal</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Debit Amt</th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credit Amt</th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->created_at->format('M d, Y H:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->reference }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->user->surname }} {{ $transaction->user->firstname }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucwords(str_replace('_', ' ', $transaction->type)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">₦{{ number_format($transaction->debit_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">₦{{ number_format($transaction->credit_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.transactions.show', $transaction) }}" class="text-purple-600 hover:text-purple-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No transactions found</td>
                        </tr>
                        @endforelse
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
