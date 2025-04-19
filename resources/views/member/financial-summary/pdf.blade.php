<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Financial Summary - {{ $selectedYear }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Financial Summary for {{ $selectedYear }}</h1>
        <p>Member: {{ $user->surname }} {{ $user->firstname }} ({{ $user->member_no }})</p>
        <p>Generated on: {{ now()->format('M d, Y') }}</p>
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
    <div class="section-title">Savings</div>
    <table>
        <thead>
            <tr>
                <th>Savings Type</th>
                @foreach($months as $month)
                    <th class="text-center">{{ $month->name }}</th>
                @endforeach
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $savingsTotal = 0; @endphp
            @foreach($summary['savings'] as $typeId => $data)
                @if($data['total'] > 0)
                    @php $savingsTotal += $data['total']; @endphp
                    <tr>
                        <td>{{ $data['name'] }}</td>
                        @foreach($months as $month)
                            <td class="text-right">
                                @if($data['months'][$month->id] > 0)
                                    ₦{{ number_format($data['months'][$month->id], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                        <td class="text-right">₦{{ number_format($data['total'], 2) }}</td>
                    </tr>
                @endif
            @endforeach
            <tr class="total-row">
                <td>Total Savings</td>
                @foreach($months as $month)
                    <td class="text-right">
                        @php
                            $monthTotal = 0;
                            foreach($summary['savings'] as $typeData) {
                                $monthTotal += $typeData['months'][$month->id];
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
                <td class="text-right">₦{{ number_format($savingsTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>
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
    <div class="section-title">Loan Repayments</div>
    <table>
        <thead>
            <tr>
                <th>Loan</th>
                @foreach($months as $month)
                    <th class="text-center">{{ $month->name }}</th>
                @endforeach
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $loansTotal = 0; @endphp
            @foreach($summary['loans'] as $loanId => $data)
                @if($data['total'] > 0)
                    @php $loansTotal += $data['total']; @endphp
                    <tr>
                        <td>{{ $data['name'] }}</td>
                        @foreach($months as $month)
                            <td class="text-right">
                                @if($data['months'][$month->id] > 0)
                                    ₦{{ number_format($data['months'][$month->id], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                        <td class="text-right">₦{{ number_format($data['total'], 2) }}</td>
                    </tr>
                @endif
            @endforeach
            <tr class="total-row">
                <td>Total Loan Repayments</td>
                @foreach($months as $month)
                    <td class="text-right">
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
                <td class="text-right">₦{{ number_format($loansTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Shares Section -->
    @if($summary['shares']['total'] > 0)
    <div class="section-title">Share Subscriptions</div>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                @foreach($months as $month)
                    <th class="text-center">{{ $month->name }}</th>
                @endforeach
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $summary['shares']['name'] }}</td>
                @foreach($months as $month)
                    <td class="text-right">
                        @if($summary['shares']['months'][$month->id] > 0)
                            ₦{{ number_format($summary['shares']['months'][$month->id], 2) }}
                        @else
                            -
                        @endif
                    </td>
                @endforeach
                <td class="text-right">₦{{ number_format($summary['shares']['total'], 2) }}</td>
            </tr>
        </tbody>
    </table>
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
    <div class="section-title">Commodity Payments</div>
    <table>
        <thead>
            <tr>
                <th>Commodity</th>
                @foreach($months as $month)
                    <th class="text-center">{{ $month->name }}</th>
                @endforeach
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $commoditiesTotal = 0; @endphp
            @foreach($summary['commodities'] as $subscriptionId => $data)
                @if($data['total'] > 0)
                    @php $commoditiesTotal += $data['total']; @endphp
                    <tr>
                        <td>{{ $data['name'] }}</td>
                        @foreach($months as $month)
                            <td class="text-right">
                                @if($data['months'][$month->id] > 0)
                                    ₦{{ number_format($data['months'][$month->id], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                        <td class="text-right">₦{{ number_format($data['total'], 2) }}</td>
                    </tr>
                @endif
            @endforeach
            <tr class="total-row">
                <td>Total Commodity Payments</td>
                @foreach($months as $month)
                    <td class="text-right">
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
                <td class="text-right">₦{{ number_format($commoditiesTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Grand Total Section -->
    <div class="section-title">Summary of All Payments</div>
    <table>
        <thead>
            <tr>
                <th>Payment Category</th>
                @foreach($months as $month)
                    <th class="text-center">{{ $month->name }}</th>
                @endforeach
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            <!-- Savings Row -->
            <tr>
                <td>Savings</td>
                @foreach($months as $month)
                    <td class="text-right">
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
                <td class="text-right">
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
                <td>Loan Repayments</td>
                @foreach($months as $month)
                    <td class="text-right">
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
                <td class="text-right">
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
                <td>Share Subscriptions</td>
                @foreach($months as $month)
                    <td class="text-right">
                        @if($summary['shares']['months'][$month->id] > 0)
                            ₦{{ number_format($summary['shares']['months'][$month->id], 2) }}
                        @else
                            -
                        @endif
                    </td>
                @endforeach
                <td class="text-right">₦{{ number_format($summary['shares']['total'], 2) }}</td>
            </tr>

            <!-- Commodity Payments Row -->
            <tr>
                <td>Commodity Payments</td>
                @foreach($months as $month)
                    <td class="text-right">
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
                <td class="text-right">
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
            <tr class="total-row">
                <td>GRAND TOTAL</td>
                @foreach($months as $month)
                    <td class="text-right">
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
                <td class="text-right">
                    @php
                        $grandTotal = $savingsTotal + $loansTotal + $summary['shares']['total'] + $commoditiesTotal;
                    @endphp
                    ₦{{ number_format($grandTotal, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 10px; text-align: center; color: #666;">
        <p>This is a computer-generated document. No signature is required.</p>
        <p>OGITECH Cooperative Society &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>

