@extends('layouts.admin')

@section('content')
<div class="container mx-auto">
    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Membership Stats -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Members</h3>
                    <p class="text-2xl font-bold">{{ $totalMembers }}</p>
                    <span class="text-sm text-gray-400">+{{ $newMembersThisMonth }} this month</span>
                </div>
            </div>
        </div>

        <!-- Savings Stats -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-piggy-bank text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Saved</h3>
                    <p class="text-1xl font-bold ">₦{{ number_format($totalSavings, 2) }}</p>
                    <span class="text-sm text-gray-400">₦{{ number_format($monthlySavings, 2) }} this month</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-fuchsia-100 text-fuchsia-600">
                    <i class="fas fa-piggy-bank text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Withdrawals</h3>
                    <p class="text-1xl font-bold ">₦{{ number_format($totalWithdrawals, 2) }}</p>
                    <span class="text-sm text-gray-400">₦{{ number_format($monthlyWithdrawals, 2) }} this month</span>
                </div>
            </div>
        </div>

        <!-- Shares Stats -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-chart-pie text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Share Capital</h3>
                    <p class="text-1xl font-bold">₦{{ number_format($totalShares, 2) }}</p>
                    <span class="text-sm text-gray-400">{{ number_format($totalShareUnits) }} units</span>
                </div>
            </div>
        </div>

        <!-- Loan Stats -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-hand-holding-usd text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Active Loans</h3>
                    <p class="text-2xl font-bold">{{ $activeLoans }}</p>
                    <span class="text-sm text-gray-400">₦{{ number_format($outstandingLoans, 2) }} outstanding</span>
                </div>
            </div>
        </div>

        {{-- saving balance --}}
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-pink-100 text-pink-600">
                    <i class="fas fa-hand-holding-usd text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Saving Balance</h3>
                    <p class="text-2xl font-bold">{{ number_format($savingBalance, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Information Stats -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-folder-open text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Information</h3>
                    <p class="text-1xl font-bold">{{ number_format($totalResources) }}</p>
                    <span class="text-sm text-gray-400">{{ number_format($totalResourcesSize / (1024 * 1024), 1) }} MB storage</span>
                </div>
            </div>
        </div>

            {{-- commodity --}}
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-cyan-100 text-cyan-600">
                    <i class="fas fa-boxes text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Commodities</h3>
                    <p class="text-1xl font-bold">{{ number_format($totalCommodities) }}</p>
                    <span class="text-sm text-gray-400">{{ number_format($totalCommodityValue / (1024 * 1024), 1) }} Value</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Monthly Transactions Chart -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Monthly Transactions</h2>
            <canvas id="monthlyTransactionsChart"></canvas>
        </div>

        <!-- Financial Distribution Chart -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Financial Distribution</h2>
            <canvas id="distributionChart"></canvas>
        </div>
    </div>

    <!-- Recent Activities Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Members -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Recent Members</h2>
            <div class="space-y-4">
                @foreach($recentMembers as $member)
                <div class="flex items-center">
                    <img src="{{ asset('storage/' . $member->member_image) }}" alt="Member" class="w-10 h-10 rounded-full">
                    <div class="ml-4">
                        <p class="font-semibold">{{ $member->surname }} {{ $member->firstname }}</p>
                        <p class="text-sm text-gray-500">{{ $member->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Recent Transactions</h2>
            <div class="space-y-4">
                @foreach($recentTransactions as $transaction)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold">{{ $transaction->user->surname }}</p>
                        <p class="text-sm text-gray-500">{{ ucfirst($transaction->type) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold {{ $transaction->credit_amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                            ₦{{ number_format($transaction->credit_amount + $transaction->debit_amount, 2) }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $transaction->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Pending Loans -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Pending Loan Requests</h2>
            <div class="space-y-4">
                @foreach($pendingLoans as $loan)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold">{{ $loan->user->surname }}</p>
                        <p class="text-sm text-gray-500">{{ $loan->loanType->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold">₦{{ number_format($loan->amount, 2) }}</p>
                        <p class="text-sm text-gray-500">{{ $loan->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Transactions Chart
    const monthlyData = @json($monthlyData);
    new Chart(document.getElementById('monthlyTransactionsChart'), {
        type: 'line',
        data: {
            labels: monthlyData.map(d => d.month),
            datasets: [{
                label: 'Savings',
                data: monthlyData.map(d => d.savings),
                borderColor: '#10B981',
                tension: 0.1
            }, {
                label: 'Loans',
                data: monthlyData.map(d => d.loans),
                borderColor: '#EF4444',
                tension: 0.1
            }, {
                label: 'Shares',
                data: monthlyData.map(d => d.shares),
                borderColor: '#3B82F6',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Financial Distribution Chart
    new Chart(document.getElementById('distributionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Savings', 'Shares', 'Outstanding Loans'],
            datasets: [{
                data: [{{ $totalSavings }}, {{ $totalShares }}, {{ $outstandingLoans }}],
                backgroundColor: ['#10B981', '#3B82F6', '#EF4444']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>

@endsection
