@extends('layouts.member')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Financial Summary for {{ $selectedYear }}</h1>

        <form action="{{ route('member.financial-summary') }}" method="GET" class="flex items-center space-x-4">
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700">Select Year</label>
                <select name="year" id="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200"
                        onchange="this.form.submit()" style="border: 1px solid #ccc; padding: 8px; font-size: 16px; border-radius: 5px;">
                    @foreach($years as $year)
                        <option value="{{ $year->year }}" {{ $selectedYear == $year->year ? 'selected' : '' }}>
                            {{ $year->year }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Savings Section -->
    @if(count($summary['savings']) > 0)
    <div class="mb-10">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Savings</h2>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Savings Type
                            </th>
                            @foreach($months as $month)
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $month->name }}
                                </th>
                            @endforeach
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-purple-50">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $savingsTotal = 0; @endphp
                        @foreach($summary['savings'] as $typeId => $data)
                            @if($data['total'] > 0)
                                @php $savingsTotal += $data['total']; @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $data['name'] }}
                                    </td>

                                    @foreach($months as $month)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            @if($data['months'][$month->id] > 0)
                                                ₦{{ number_format($data['months'][$month->id], 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center bg-purple-50">
                                        ₦{{ number_format($data['total'], 2) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        <!-- Total Row -->
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <strong>Total Savings</strong>
                            </td>

                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                    @php
                                        $monthTotal = 0;
                                        foreach($summary['savings'] as $typeData) {
                                            $monthTotal += $typeData['months'][$month->id];
                                        }
                                    @endphp

                                    @if($monthTotal > 0)
                                        ₦{{ number_format($monthTotal, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center bg-purple-50">
                                ₦{{ number_format($savingsTotal, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Loan Repayments Section -->
    @if(count($summary['loans']) > 0)
    <div class="mb-10">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Loan Repayments</h2>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Loan
                            </th>
                            @foreach($months as $month)
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $month->name }}
                                </th>
                            @endforeach
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-purple-50">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $loansTotal = 0; @endphp
                        @foreach($summary['loans'] as $loanId => $data)
                            @if($data['total'] > 0)
                                @php $loansTotal += $data['total']; @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $data['name'] }}
                                    </td>

                                    @foreach($months as $month)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            @if($data['months'][$month->id] > 0)
                                                ₦{{ number_format($data['months'][$month->id], 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center bg-purple-50">
                                        ₦{{ number_format($data['total'], 2) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        <!-- Total Row -->
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <strong>Total Loan Repayments</strong>
                            </td>

                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                    @php
                                        $monthTotal = 0;
                                        foreach($summary['loans'] as $loanData) {
                                            $monthTotal += $loanData['months'][$month->id];
                                        }
                                    @endphp

                                    @if($monthTotal > 0)
                                        ₦{{ number_format($monthTotal, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center bg-purple-50">
                                ₦{{ number_format($loansTotal, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Shares Section -->
    @if($summary['shares']['total'] > 0)
    <div class="mb-10">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Share Subscriptions</h2>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            @foreach($months as $month)
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $month->name }}
                                </th>
                            @endforeach
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-purple-50">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $summary['shares']['name'] }}
                            </td>

                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    @if($summary['shares']['months'][$month->id] > 0)
                                        ₦{{ number_format($summary['shares']['months'][$month->id], 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center bg-purple-50">
                                ₦{{ number_format($summary['shares']['total'], 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Commodity Payments Section -->
    @if(count($summary['commodities']) > 0)
    <div class="mb-10">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Commodity Payments</h2>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Commodity
                            </th>
                            @foreach($months as $month)
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $month->name }}
                                </th>
                            @endforeach
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-purple-50">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $commoditiesTotal = 0; @endphp
                        @foreach($summary['commodities'] as $subscriptionId => $data)
                            @if($data['total'] > 0)
                                @php $commoditiesTotal += $data['total']; @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $data['name'] }}
                                    </td>

                                    @foreach($months as $month)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            @if($data['months'][$month->id] > 0)
                                                ₦{{ number_format($data['months'][$month->id], 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center bg-purple-50">
                                        ₦{{ number_format($data['total'], 2) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        <!-- Total Row -->
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <strong>Total Commodity Payments</strong>
                            </td>

                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                    @php
                                        $monthTotal = 0;
                                        foreach($summary['commodities'] as $commodityData) {
                                            $monthTotal += $commodityData['months'][$month->id];
                                        }
                                    @endphp

                                    @if($monthTotal > 0)
                                                                           ₦{{ number_format($monthTotal, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center bg-purple-50">
                                ₦{{ number_format($commoditiesTotal, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Grand Total Section -->
    <div class="mb-10">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Summary of All Payments</h2>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Category
                            </th>
                            @foreach($months as $month)
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $month->name }}
                                </th>
                            @endforeach
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-purple-50">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Savings Row -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Savings
                            </td>

                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    @php
                                        $monthTotal = 0;
                                        foreach($summary['savings'] as $typeData) {
                                            $monthTotal += $typeData['months'][$month->id];
                                        }
                                    @endphp

                                    @if($monthTotal > 0)
                                        ₦{{ number_format($monthTotal, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center bg-purple-50">
                                @php
                                    $savingsTotal = 0;
                                    foreach($summary['savings'] as $typeData) {
                                        $savingsTotal += $typeData['total'];
                                    }
                                @endphp
                                ₦{{ number_format($savingsTotal, 2) }}
                            </td>
                        </tr>

                        <!-- Loan Repayments Row -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Loan Repayments
                            </td>

                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    @php
                                        $monthTotal = 0;
                                        foreach($summary['loans'] as $loanData) {
                                            $monthTotal += $loanData['months'][$month->id];
                                        }
                                    @endphp

                                    @if($monthTotal > 0)
                                        ₦{{ number_format($monthTotal, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center bg-purple-50">
                                @php
                                    $loansTotal = 0;
                                    foreach($summary['loans'] as $loanData) {
                                        $loansTotal += $loanData['total'];
                                    }
                                @endphp
                                ₦{{ number_format($loansTotal, 2) }}
                            </td>
                        </tr>

                        <!-- Shares Row -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Share Subscriptions
                            </td>

                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    @if($summary['shares']['months'][$month->id] > 0)
                                        ₦{{ number_format($summary['shares']['months'][$month->id], 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center bg-purple-50">
                                ₦{{ number_format($summary['shares']['total'], 2) }}
                            </td>
                        </tr>

                        <!-- Commodity Payments Row -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Commodity Payments
                            </td>

                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    @php
                                        $monthTotal = 0;
                                        foreach($summary['commodities'] as $commodityData) {
                                            $monthTotal += $commodityData['months'][$month->id];
                                        }
                                    @endphp

                                    @if($monthTotal > 0)
                                        ₦{{ number_format($monthTotal, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center bg-purple-50">
                                @php
                                    $commoditiesTotal = 0;
                                    foreach($summary['commodities'] as $commodityData) {
                                        $commoditiesTotal += $commodityData['total'];
                                    }
                                @endphp
                                ₦{{ number_format($commoditiesTotal, 2) }}
                            </td>
                        </tr>

                        <!-- Grand Total Row -->
                        <tr class="bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                GRAND TOTAL
                            </td>

                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-center">
                                    @php
                                        $monthGrandTotal = 0;

                                        // Add savings
                                        foreach($summary['savings'] as $typeData) {
                                            $monthGrandTotal += $typeData['months'][$month->id];
                                        }

                                        // Add loan repayments
                                        foreach($summary['loans'] as $loanData) {
                                            $monthGrandTotal += $loanData['months'][$month->id];
                                        }

                                        // Add shares
                                        $monthGrandTotal += $summary['shares']['months'][$month->id];

                                        // Add commodity payments
                                        foreach($summary['commodities'] as $commodityData) {
                                            $monthGrandTotal += $commodityData['months'][$month->id];
                                        }
                                    @endphp

                                    @if($monthGrandTotal > 0)
                                        ₦{{ number_format($monthGrandTotal, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-center bg-purple-100">
                                @php
                                    $grandTotal = $savingsTotal + $loansTotal + $summary['shares']['total'] + $commoditiesTotal;
                                @endphp
                                ₦{{ number_format($grandTotal, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Savings Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 bg-blue-500">
                <h3 class="text-lg font-semibold text-white">Total Savings</h3>
            </div>
            <div class="p-6">
                <div class="text-3xl font-bold text-gray-900 mb-2">₦{{ number_format($savingsTotal, 2) }}</div>
                <div class="text-sm text-gray-500">Your total savings for {{ $selectedYear }}</div>
            </div>
        </div>

        <!-- Loan Repayments Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 bg-purple-500">
                <h3 class="text-lg font-semibold text-white">Loan Repayments</h3>
            </div>
            <div class="p-6">
                <div class="text-3xl font-bold text-gray-900 mb-2">₦{{ number_format($loansTotal, 2) }}</div>
                <div class="text-sm text-gray-500">Your total loan repayments for {{ $selectedYear }}</div>
            </div>
        </div>

        <!-- Shares Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 bg-orange-500">
                <h3 class="text-lg font-semibold text-white">Share Subscriptions</h3>
            </div>
            <div class="p-6">
                <div class="text-3xl font-bold text-gray-900 mb-2">₦{{ number_format($summary['shares']['total'], 2) }}</div>
                <div class="text-sm text-gray-500">Your total share subscriptions for {{ $selectedYear }}</div>
            </div>
        </div>

        <!-- Commodity Payments Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 bg-red-500">
                <h3 class="text-lg font-semibold text-white">Commodity Payments</h3>
            </div>
            <div class="p-6">
                <div class="text-3xl font-bold text-gray-900 mb-2">₦{{ number_format($commoditiesTotal, 2) }}</div>
                <div class="text-sm text-gray-500">Your total commodity payments for {{ $selectedYear }}</div>
            </div>
        </div>
    </div>

    <!-- Visualization Section - Improved -->
    <div class="mb-10">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Payment Visualization</h2>
            </div>

            @if($hasData)
                <div class="p-6">
                    <div id="chartLoading" class="text-center py-4">
                        <p class="text-gray-600">Loading charts...</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="w-full" style="height: 400px; position: relative;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Breakdown</h3>
                            <canvas id="monthlyChart"></canvas>
                        </div>

                        <div class="w-full" style="height: 400px; position: relative;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Distribution</h3>
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const chartLoading = document.getElementById('chartLoading');

                        try {
                            // Debug data
                            console.log('Initializing charts...');

                            // Chart configurations
                            const monthlyChart = new Chart(document.getElementById('monthlyChart'), {
                                type: 'bar',
                                data: {
                                    labels: @json($months->pluck('name')),
                                    datasets: [{
                                        label: 'Savings',
                                        data: @json(collect($summary['savings'])->sum('total')),
                                        backgroundColor: 'rgba(59, 130, 246, 0.7)'
                                    }, {
                                        label: 'Loan Repayments',
                                        data: @json(collect($summary['loans'])->sum('total')),
                                        backgroundColor: 'rgba(139, 92, 246, 0.7)'
                                    }, {
                                        label: 'Share Subscriptions',
                                        data: @json($summary['shares']['total']),
                                        backgroundColor: 'rgba(249, 115, 22, 0.7)'
                                    }, {
                                        label: 'Commodity Payments',
                                        data: @json(collect($summary['commodities'])->sum('total')),
                                        backgroundColor: 'rgba(239, 68, 68, 0.7)'
                                    }]
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

                            const categoryChart = new Chart(document.getElementById('categoryChart'), {
                                type: 'doughnut',
                                data: {
                                    labels: ['Savings', 'Loan Repayments', 'Share Subscriptions', 'Commodity Payments'],
                                    datasets: [{
                                        data: [
                                            @json(collect($summary['savings'])->sum('total')),
                                            @json(collect($summary['loans'])->sum('total')),
                                            @json($summary['shares']['total']),
                                            @json(collect($summary['commodities'])->sum('total'))
                                        ],
                                        backgroundColor: [
                                            'rgba(59, 130, 246, 0.7)',
                                            'rgba(139, 92, 246, 0.7)',
                                            'rgba(249, 115, 22, 0.7)',
                                            'rgba(239, 68, 68, 0.7)'
                                        ]
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'right'
                                        }
                                    }
                                }
                            });

                            // Hide loading message
                            chartLoading.style.display = 'none';

                            console.log('Charts initialized successfully');
                        } catch (error) {
                            console.error('Chart initialization error:', error);
                            chartLoading.innerHTML = `
                                <div class="text-red-500">
                                    <p>Error loading charts. Please refresh the page.</p>
                                    <p class="text-sm mt-2">${error.message}</p>
                                </div>
                            `;
                        }
                    });
                </script>
            @else
                <div class="p-6 text-center">
                    <div class="py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-lg">No financial data available for {{ $selectedYear }}</p>
                        <p class="mt-2">Try selecting a different year or check back later when you have transactions.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Print and Export Options -->
    <div class="flex justify-end space-x-4 mb-10">
        <button onclick="window.print()" class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            <i class="fas fa-print mr-2"></i> Print Summary
        </button>

        <a href="{{ route('member.financial-summary.export', ['year' => $selectedYear]) }}" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            <i class="fas fa-file-excel mr-2"></i> Export to Excel
        </a>

        <a href="{{ route('member.financial-summary.pdf', ['year' => $selectedYear]) }}" class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
            <i class="fas fa-file-pdf mr-2"></i> Download PDF
        </a>
    </div>
</div>

@if($hasData)
<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Get the months for chart labels
        const months = @json($months->pluck('name'));

        // Prepare data arrays for monthly chart
        const monthlySavings = [];
        const monthlyLoans = [];
        const monthlyShares = [];
        const monthlyCommodities = [];

        // Calculate monthly totals for each category
        @foreach($months as $month)
            // Savings
            let savingsMonthTotal = 0;
            @foreach($summary['savings'] as $typeData)
                savingsMonthTotal += {{ $typeData['months'][$month->id] ?? 0 }};
            @endforeach
            monthlySavings.push(savingsMonthTotal);

            // Loans
            let loansMonthTotal = 0;
            @foreach($summary['loans'] as $loanData)
                loansMonthTotal += {{ $loanData['months'][$month->id] ?? 0 }};
            @endforeach
            monthlyLoans.push(loansMonthTotal);

            // Shares
            monthlyShares.push({{ $summary['shares']['months'][$month->id] ?? 0 }});

            // Commodities
            let commoditiesMonthTotal = 0;
            @foreach($summary['commodities'] as $commodityData)
                commoditiesMonthTotal += {{ $commodityData['months'][$month->id] ?? 0 }};
            @endforeach
            monthlyCommodities.push(commoditiesMonthTotal);
        @endforeach

        // Calculate category totals for pie chart
        let savingsTotal = 0;
        @foreach($summary['savings'] as $typeData)
            savingsTotal += {{ $typeData['total'] ?? 0 }};
        @endforeach

        let loansTotal = 0;
        @foreach($summary['loans'] as $loanData)
            loansTotal += {{ $loanData['total'] ?? 0 }};
        @endforeach

        let sharesTotal = {{ $summary['shares']['total'] ?? 0 }};

        let commoditiesTotal = 0;
        @foreach($summary['commodities'] as $commodityData)
            commoditiesTotal += {{ $commodityData['total'] ?? 0 }};
        @endforeach

        // Format currency for tooltips
        const formatCurrency = (value) => {
            return '₦' + new Intl.NumberFormat('en-NG').format(value);
        };

        // Monthly Breakdown Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Savings',
                        data: monthlySavings,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Loan Repayments',
                        data: monthlyLoans,
                        backgroundColor: 'rgba(139, 92, 246, 0.7)',
                        borderColor: 'rgba(139, 92, 246, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Share Subscriptions',
                        data: monthlyShares,
                        backgroundColor: 'rgba(249, 115, 22, 0.7)',
                        borderColor: 'rgba(249, 115, 22, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Commodity Payments',
                        data: monthlyCommodities,
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + formatCurrency(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatCurrency(value);
                            }
                        }
                    }
                }
            }
        });

        // Payment Category Pie Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Savings', 'Loan Repayments', 'Share Subscriptions', 'Commodity Payments'],
                datasets: [{
                    data: [savingsTotal, loansTotal, sharesTotal, commoditiesTotal],
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
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return label + ': ' + formatCurrency(value) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error initializing charts:', error);

        // Show error message if charts fail to load
        const chartContainers = document.querySelectorAll('canvas');
        chartContainers.forEach(container => {
            container.style.display = 'none';
            container.parentNode.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full">
                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500">Unable to load chart visualization</p>
                    <button class="mt-3 px-4 py-2 bg-purple-600 text-white text-sm rounded hover:bg-purple-700" onclick="window.location.reload()">
                        Refresh Page
                    </button>
                </div>
            `;
        });
    }
});
</script>
@endif

<style>
/* Print styles */
@media print {
    body {
        background-color: white;
        color: black;
    }

    .container {
        max-width: 100%;
        padding: 0;
    }

    button, a {
        display: none !important;
    }

    .shadow-md, .shadow-lg {
        box-shadow: none !important;
    }

    .bg-white, .bg-gray-50, .bg-gray-100 {
        background-color: white !important;
    }

    .bg-purple-600, .bg-blue-500, .bg-purple-500, .bg-orange-500, .bg-red-500 {
        background-color: white !important;
        color: black !important;
        border-bottom: 1px solid #ddd;
    }

    .text-white {
        color: black !important;
    }

    canvas {
        max-width: 100%;
        height: auto !important;
    }

    h1 {
        font-size: 24px !important;
        margin-bottom: 20px;
    }

    h2 {
        font-size: 20px !important;
        margin-top: 30px;
        margin-bottom: 15px;
    }

    table {
        page-break-inside: auto;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    thead {
        display: table-header-group;
    }

    tfoot {
        display: table-footer-group;
    }
}
</style>
@endsection


