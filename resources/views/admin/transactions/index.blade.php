@extends('layouts.admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Transaction Records</h1>

            <!-- Export Button -->
            <a href="{{ route('admin.transactions.export') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                <i class="fas fa-download mr-2"></i>Export
            </a>
        </div>

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
                <h3 class="text-sm font-medium text-gray-500">Net Balance</h3>
                <p class="text-2xl font-bold text-purple-600">₦{{ number_format($totalCredits - $totalDebits, 2) }}</p>
            </div>
        </div>
        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form action="{{ route('admin.transactions.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Searchable Member Select -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Member</label>
                        <select name="user_id" class="select2 w-full rounded-lg border-gray-300" style="width: 100%; border: 1px solid #ccc; font-size: 16px; border-radius: 5px; padding: 10px;">
                            <option value="">All Members</option>
                            @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ request('user_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->surname }} {{ $member->firstname }} ({{ $member->member_no }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full rounded-lg border-gray-300 " style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full rounded-lg border-gray-300" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                        <select name="type" class="w-full rounded-lg border-gray-300 select" style="border: 1px solid #ccc;  font-size: 16px; border-radius: 5px; padding: 10px;">
                            <option value="">All Types</option>
                            <option value="savings" {{ request('type') === 'savings' ? 'selected' : '' }}>savings</option>
                            <option value="withdraw" {{ request('type') === 'withdraw' ? 'selected' : '' }}>withdraws</option>
                            <option value="loan" {{ request('type') === 'loan' ? 'selected' : '' }}>loans</option>
                            <option value="loan_interest" {{ request('type') === 'loan_interest' ? 'selected' : '' }}>Loan Interest</option>
                            <option value="expenses" {{ request('type') === 'expenses' ? 'selected' : '' }}>Expenses</option>
                            <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Income</option>

                              <option value="entrance_fee" {{ request('type') === 'entrance_fee' ? 'selected' : '' }}>Entrance Fee</option>



                        </select>

                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                        <i class="fas fa-filter mr-2"></i>Apply Filters
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
                            <td class="px-6 py-4 whitespace-nowrap text-green-600">₦{{ number_format($transaction->debit_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-red-600">₦{{ number_format($transaction->credit_amount, 2) }}</td>
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
                    <tfoot class="bg-gray-50 font-semibold">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right">Total:</td>
                            <td class="px-6 py-4 text-green-600">₦{{ number_format($transactions->sum('credit_amount'), 2) }}</td>
                            <td class="px-6 py-4 text-red-600">₦{{ number_format($transactions->sum('debit_amount'), 2) }}</td>
                            <td colspan="2" class="px-6 py-4">
                                Balance:
                                <span class="{{ $transactions->sum('credit_amount') - $transactions->sum('debit_amount') >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    ₦{{ number_format($transactions->sum('credit_amount') - $transactions->sum('debit_amount'), 2) }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Search member...',
            allowClear: true
        });
    });
</script>

@endsection
