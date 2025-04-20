@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Financial Summary</h1>

            <div class="flex space-x-4">
                <form action="{{ route('admin.financial-summary.index') }}" method="GET" class="flex items-center space-x-2">
                    <select name="year" class="rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" onchange="this.form.submit()">
                        @foreach($years as $year)
                            <option value="{{ $year->year }}" {{ $selectedYear == $year->year ? 'selected' : '' }}>
                                {{ $year->year }}
                            </option>
                        @endforeach
                    </select>

                    <select name="member_id" class="rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                        <option value="">Overall Summary</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->surname }} {{ $member->firstname }} ({{ $member->member_no }})
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                        View
                    </button>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-piggy-bank text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Total Savings</h3>
                        <p class="text-xl font-bold">₦{{ number_format($summary['savings']['total'], 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-hand-holding-usd text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Loan Repayments</h3>
                        <p class="text-xl font-bold">₦{{ number_format($summary['loans']['total'], 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                        <i class="fas fa-chart-pie text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Share Subscriptions</h3>
                        <p class="text-xl font-bold">₦{{ number_format($summary['shares']['total'], 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Commodity Payments</h3>
                        <p class="text-xl font-bold">₦{{ number_format($summary['commodities']['total'], 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Member Stats -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Member Statistics</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-500">Total Members</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $summary['members']['total'] }}</div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-500">Active Members</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $summary['members']['active'] }}</div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-500">Participation Rate</div>
                    <div class="text-2xl font-bold text-gray-900">
                        {{ $summary['members']['total'] > 0 ? round(($summary['members']['active'] / $summary['members']['total']) * 100) : 0 }}%
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Monthly Breakdown</h2>
                <div class="h-80">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Categories</h2>
                <div class="h-80">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Summary Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Monthly Financial Summary for {{ $selectedYear }}</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            @foreach($months as $month)
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $month->name }}</th>
                            @endforeach
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                                            <!-- Savings Row -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Savings</td>
                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                    @if($summary['savings']['months'][$month->id] > 0)
                                        ₦{{ number_format($summary['savings']['months'][$month->id], 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-gray-900">
                                ₦{{ number_format($summary['savings']['total'], 2) }}
                            </td>
                        </tr>

                        <!-- Loan Repayments Row -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Loan Repayments</td>
                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                    @if($summary['loans']['months'][$month->id] > 0)
                                        ₦{{ number_format($summary['loans']['months'][$month->id], 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-gray-900">
                                ₦{{ number_format($summary['loans']['total'], 2) }}
                            </td>
                        </tr>

                        <!-- Share Subscriptions Row -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Share Subscriptions</td>
                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                    @if($summary['shares']['months'][$month->id] > 0)
                                        ₦{{ number_format($summary['shares']['months'][$month->id], 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-gray-900">
                                ₦{{ number_format($summary['shares']['total'], 2) }}
                            </td>
                        </tr>

                        <!-- Commodity Payments Row -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Commodity Payments</td>
                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                    @if($summary['commodities']['months'][$month->id] > 0)
                                        ₦{{ number_format($summary['commodities']['months'][$month->id], 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-gray-900">
                                ₦{{ number_format($summary['commodities']['total'], 2) }}
                            </td>
                        </tr>

                        <!-- Total Row -->
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">TOTAL</td>
                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-gray-900">
                                    @php
                                        $monthTotal =
                                            $summary['savings']['months'][$month->id] +
                                            $summary['loans']['months'][$month->id] +
                                            $summary['shares']['months'][$month->id] +
                                            $summary['commodities']['months'][$month->id];
                                    @endphp

                                    @if($monthTotal > 0)
                                        ₦{{ number_format($monthTotal, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-gray-900">
                                @php
                                    $grandTotal =
                                        $summary['savings']['total'] +
                                        $summary['loans']['total'] +
                                        $summary['shares']['total'] +
                                        $summary['commodities']['total'];
                                @endphp
                                ₦{{ number_format($grandTotal, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Export Buttons -->
        <div class="flex justify-end space-x-4 mb-10">
            <button onclick="window.print()" class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                <i class="fas fa-print mr-2"></i> Print Summary
            </button>

            <a href="{{ route('admin.financial-summary.overall.export', ['year' => $selectedYear]) }}" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-file-excel mr-2"></i> Export to Excel
            </a>

            <a href="{{ route('admin.financial-summary.overall.pdf', ['year' => $selectedYear]) }}" class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                <i class="fas fa-file-pdf mr-2"></i> Download PDF
            </a>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($months as $month)
                        '{{ $month->name }}',
                    @endforeach
                ],
                datasets: [
                    {
                        label: 'Savings',
                        data: [
                            @foreach($months as $month)
                                {{ $summary['savings']['months'][$month->id] }},
                            @endforeach
                        ],
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Loan Repayments',
                        data: [
                            @foreach($months as $month)
                                {{ $summary['loans']['months'][$month->id] }},
                            @endforeach
                        ],
                        backgroundColor: 'rgba(139, 92, 246, 0.5)',
                        borderColor: 'rgba(139, 92, 246, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Share Subscriptions',
                        data: [
                            @foreach($months as $month)
                                {{ $summary['shares']['months'][$month->id] }},
                            @endforeach
                        ],
                        backgroundColor: 'rgba(249, 115, 22, 0.5)',
                        borderColor: 'rgba(249, 115, 22, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Commodity Payments',
                        data: [
                            @foreach($months as $month)
                                {{ $summary['commodities']['months'][$month->id] }},
                            @endforeach
                        ],
                        backgroundColor: 'rgba(239, 68, 68, 0.5)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₦' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'pie',
            data: {
                labels: ['Savings', 'Loan Repayments', 'Share Subscriptions', 'Commodity Payments'],
                datasets: [{
                    data: [
                        {{ $summary['savings']['total'] }},
                        {{ $summary['loans']['total'] }},
                        {{ $summary['shares']['total'] }},
                        {{ $summary['commodities']['total'] }}
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(249, 115, 22, 0.7)',
                        'rgba(239, 68, 68, 0.7)'
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(139, 92, 246, 1)',
                        'rgba(249, 115, 22, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ₦${value.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

@endsection

