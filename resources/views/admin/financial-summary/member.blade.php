@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">
                Member Financial Summary: {{ $member->surname }} {{ $member->firstname }}
            </h1>

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
    @foreach($members as $m)
        <option value="{{ $m->id }}" {{ $member->id == $m->id ? 'selected' : '' }}>
            {{ $m->surname }} {{ $m->firstname }} ({{ $m->member_no }})
        </option>
    @endforeach
</select>

                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                        View
                    </button>
                </form>
            </div>
        </div>

        <!-- Member Info Card -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex items-start">
                @if($member->member_image)
                <div class="flex-shrink-0 mr-4">
                    <img src="{{ asset('storage/' . $member->member_image) }}" alt="{{ $member->surname }}" class="h-20 w-20 rounded-full object-cover">
                </div>
                @endif

                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-gray-900">{{ $member->surname }} {{ $member->firstname }} {{ $member->othername }}</h2>
                    <p class="text-gray-600">Member No: {{ $member->member_no }}</p>
                    <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <span class="text-gray-500 text-sm">Department:</span>
                            <p class="font-medium">{{ $member->department->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Faculty:</span>
                            <p class="font-medium">{{ $member->faculty->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Joined:</span>
                            <p class="font-medium">{{ $member->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Export Buttons -->
        <div class="flex justify-end space-x-4 mb-6">
            <button onclick="window.print()" class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                <i class="fas fa-print mr-2"></i> Print Summary
            </button>

                    <a href="{{ route('admin.financial-summary.member.export', ['member' => $member->id, 'year' => $selectedYear]) }}&member_id={{$member->id}}" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
    <i class="fas fa-file-excel mr-2"></i> Export to Excel
</a>

<a href="{{ route('admin.financial-summary.member.pdf', ['member' => $member->id, 'year' => $selectedYear]) }}&member_id={{$member->id}}" class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
    <i class="fas fa-file-pdf mr-2"></i> Download PDF
</a>
        </div>

        <!-- Savings Section -->
        @php
            $hasSavings = false;
            foreach($summary['savings'] as $typeData) {
                if($typeData['total'] > 0) {
                    $hasSavings = true;
                    break;
                }
            }
        @endphp

        @if($hasSavings)
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 bg-blue-600">
                <h2 class="text-xl font-semibold text-white">Savings</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saving Type</th>
                            @foreach($months as $month)
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $month->name }}</th>
                            @endforeach
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $savingsTotal = 0; @endphp
                        @foreach($summary['savings'] as $typeId => $data)
                            @if($data['total'] > 0)
                                @php $savingsTotal += $data['total']; @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $data['name'] }}</td>
                                    @foreach($months as $month)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                            @if($data['months'][$month->id] > 0)
                                                ₦{{ number_format($data['months'][$month->id], 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-gray-900">
                                        ₦{{ number_format($data['total'], 2) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Total Savings</td>
                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-gray-900">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-gray-900">
                                ₦{{ number_format($savingsTotal, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Loan Repayments Section -->
        @php
            $hasLoans = false;
            foreach($summary['loans'] as $loanData) {
                if($loanData['total'] > 0) {
                    $hasLoans = true;
                    break;
                }
            }
        @endphp

        @if($hasLoans)
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 bg-purple-600">
                <h2 class="text-xl font-semibold text-white">Loan Repayments</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan</th>
                            @foreach($months as $month)
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $month->name }}</th>
                            @endforeach
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $loansTotal = 0; @endphp
                        @foreach($summary['loans'] as $loanId => $data)
                            @if($data['total'] > 0)
                                @php $loansTotal += $data['total']; @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $data['name'] }}</td>
                                    @foreach($months as $month)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                            @if($data['months'][$month->id] > 0)
                                                ₦{{ number_format($data['months'][$month->id], 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-gray-900">
                                        ₦{{ number_format($data['total'], 2) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Total Loan Repayments</td>
                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-gray-900">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-gray-900">
                                ₦{{ number_format($loansTotal, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Share Subscriptions Section -->
        @if($summary['shares']['total'] > 0)
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 bg-orange-600">
                <h2 class="text-xl font-semibold text-white">Share Subscriptions</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            @foreach($months as $month)
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $month->name }}</th>
                            @endforeach
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $summary['shares']['name'] }}</td>
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
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Commodity Payments Section -->
        @php
            $hasCommodities = false;
            foreach($summary['commodities'] as $commodityData) {
                if($commodityData['total'] > 0) {
                    $hasCommodities = true;
                    break;
                }
            }
        @endphp

        @if($hasCommodities)
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 bg-red-600">
                <h2 class="text-xl font-semibold text-white">Commodity Payments</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commodity</th>
                            @foreach($months as $month)
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $month->name }}</th>
                            @endforeach
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $commoditiesTotal = 0; @endphp
                        @foreach($summary['commodities'] as $subscriptionId => $data)
                            @if($data['total'] > 0)
                                @php $commoditiesTotal += $data['total']; @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $data['name'] }}</td>
                                    @foreach($months as $month)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                            @if($data['months'][$month->id] > 0)
                                                ₦{{ number_format($data['months'][$month->id], 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-gray-900">
                                        ₦{{ number_format($data['total'], 2) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Total Commodity Payments</td>
                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-gray-900">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-gray-900">
                                ₦{{ number_format($commoditiesTotal, 2) }}
                            </td>
                        </tr>
                    </tbody>
                               </table>
            </div>
        </div>
        @endif

        <!-- Grand Total Section -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-10">
            <div class="px-6 py-4 bg-gray-800">
                <h2 class="text-xl font-semibold text-white">Grand Total</h2>
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
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">GRAND TOTAL</td>
                            @foreach($months as $month)
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-gray-900">
                                    @php
                                        $monthTotal = 0;

                                        // Add savings
                                        foreach($summary['savings'] as $typeData) {
                                            $monthTotal += $typeData['months'][$month->id];
                                        }

                                        // Add loan repayments
                                        foreach($summary['loans'] as $loanData) {
                                            $monthTotal += $loanData['months'][$month->id];
                                        }

                                        // Add share subscriptions
                                        $monthTotal += $summary['shares']['months'][$month->id];

                                        // Add commodity payments
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-gray-900">
                                @php
                                    $grandTotal = 0;

                                    // Add savings total
                                    foreach($summary['savings'] as $typeData) {
                                        $grandTotal += $typeData['total'];
                                    }

                                    // Add loan repayments total
                                    foreach($summary['loans'] as $loanData) {
                                        $grandTotal += $loanData['total'];
                                    }

                                    // Add share subscriptions total
                                    $grandTotal += $summary['shares']['total'];

                                    // Add commodity payments total
                                    foreach($summary['commodities'] as $commodityData) {
                                        $grandTotal += $commodityData['total'];
                                    }
                                @endphp

                                ₦{{ number_format($grandTotal, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

