@extends('layouts.admin')

@section('content')
<div class="container mx-auto">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Members</h3>
                    <p class="text-2xl font-bold">{{ $totalMembers ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Savings</h3>
                    <p class="text-2xl font-bold">₦{{ number_format($totalSavings ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-hand-holding-usd text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Active Loans</h3>
                    <p class="text-2xl font-bold">{{ $activeLoans ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Pending Approvals</h3>
                    <p class="text-2xl font-bold">{{ $pendingApprovals ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Members -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Recent Members</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentMembers ?? [] as $member)
                        <tr>
                            <td class="px-6 py-4">{{ $member->firstname }} {{ $member->surname }}</td>
                            <td class="px-6 py-4">{{ $member->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $member->admin_sign === 'Yes' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $member->admin_sign === 'Yes' ? 'Approved' : 'Pending' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">No recent members</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Recent Transactions</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentTransactions ?? [] as $transaction)
                        <tr>
                            <td class="px-6 py-4">{{ $transaction->user->firstname }}</td>
                            <td class="px-6 py-4">₦{{ number_format($transaction->credit_amount, 2) }}</td>
                            <td class="px-6 py-4">{{ $transaction->type }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">No recent transactions</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
