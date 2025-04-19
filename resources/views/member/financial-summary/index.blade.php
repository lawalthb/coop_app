@extends('layouts.member')

@section('content')
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

    <!-- Visualization Section -->
    <div class="mt-8">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Payment Visualization</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Monthly Breakdown Chart -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Breakdown</h3>
                        <canvas id="monthlyChart" width="400" height="300"></canvas>
                    </div>

                    <!-- Payment Category Pie Chart -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Distribution</h3>
                        <canvas id="categoryChart" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Breakdown Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const months = @json($months->pluck('name'));

    // Prepare data for monthly chart
    const monthlySavings = [];
    const monthlyLoans = [];
    const monthlyShares = [];
    const monthlyCommodities = [];

    @foreach($months as $month)
        // Calculate totals for each category by month
        let savingsMonthTotal = 0;
        @foreach($summary['savings'] as $typeData)
            savingsMonthTotal += {{ $typeData['months'][$month->id] ?? 0 }};
        @endforeach
        monthlySavings.push(savingsMonthTotal);

        let loansMonthTotal = 0;
        @foreach($summary['loans'] as $loanData)
            loansMonthTotal += {{ $loanData['months'][$month->id] ?? 0 }};
        @endforeach
        monthlyLoans.push(loansMonthTotal);

        monthlyShares.push({{ $summary['shares']['months'][$month->id] ?? 0 }});

        let commoditiesMonthTotal = 0;
        @foreach($summary['commodities'] as $commodityData)
            commoditiesMonthTotal += {{ $commodityData['months'][$month->id] ?? 0 }};
        @endforeach
        monthlyCommodities.push(commoditiesMonthTotal);
    @endforeach

    const monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Savings',
                    data: monthlySavings,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Loan Repayments',
                    data: monthlyLoans,
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Share Subscriptions',
                    data: monthlyShares,
                    backgroundColor: 'rgba(255, 159, 64, 0.6)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Commodity Payments',
                    data: monthlyCommodities,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: false
                },
                y: {
                    stacked: false,
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₦' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ₦' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Category Distribution Pie Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');

    // Calculate totals for each category
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

    const categoryChart = new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: ['Savings', 'Loan Repayments', 'Share Subscriptions', 'Commodity Payments'],
            datasets: [{
                data: [savingsTotal, loansTotal, sharesTotal, commoditiesTotal],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(255, 99, 132, 0.6)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return label + ': ₦' + value.toLocaleString() + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection


