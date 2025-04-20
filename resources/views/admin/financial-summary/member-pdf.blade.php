<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Member Financial Summary - {{ $member->surname }} {{ $member->firstname }}</title>
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
        .member-info {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .member-info h2 {
            margin: 0 0 5px;
            font-size: 16px;
        }
        .member-info-grid {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        .member-info-item {
            width: 33%;
            margin-bottom: 5px;
        }
        .member-info-label {
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: right;
        }
        th:first-child, td:first-child {
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>OGITECH Cooperative Society</h1>
        <p>Member Financial Summary for {{ $selectedYear }}</p>
        <p>Generated on: {{ now()->format('M d, Y') }}</p>
    </div>

    <div class="member-info">
        <h2>{{ $member->surname }} {{ $member->firstname }} {{ $member->othername }}</h2>
        <p>Member No: {{ $member->member_no }}</p>

        <div class="member-info-grid">
                            <div class="member-info-label">Department:</div>
                <div>{{ $member->department->name ?? 'N/A' }}</div>
            </div>
            <div class="member-info-item">
                <div class="member-info-label">Faculty:</div>
                <div>{{ $member->faculty->name ?? 'N/A' }}</div>
            </div>
            <div class="member-info-item">
                <div class="member-info-label">Joined:</div>
                <div>{{ $member->created_at->format('M d, Y') }}</div>
            </div>
        </div>
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
    <h2 class="section-title">Savings</h2>
    <table>
        <thead>
            <tr>
                <th>Saving Type</th>
                @foreach($months as $month)
                    <th>{{ $month->name }}</th>
                @endforeach
                <th>Total</th>
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
                            <td>
                                @if($data['months'][$month->id] > 0)
                                    ₦{{ number_format($data['months'][$month->id], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                        <td>₦{{ number_format($data['total'], 2) }}</td>
                    </tr>
                @endif
            @endforeach
            <tr class="total-row">
                <td>Total Savings</td>
                @foreach($months as $month)
                    <td>
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
                <td>₦{{ number_format($savingsTotal, 2) }}</td>
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
    <h2 class="section-title">Loan Repayments</h2>
    <table>
        <thead>
            <tr>
                <th>Loan</th>
                @foreach($months as $month)
                    <th>{{ $month->name }}</th>
                @endforeach
                <th>Total</th>
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
                            <td>
                                @if($data['months'][$month->id] > 0)
                                    ₦{{ number_format($data['months'][$month->id], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                        <td>₦{{ number_format($data['total'], 2) }}</td>
                    </tr>
                @endif
            @endforeach
            <tr class="total-row">
                <td>Total Loan Repayments</td>
                @foreach($months as $month)
                    <td>
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
                <td>₦{{ number_format($loansTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Share Subscriptions Section -->
    @if($summary['shares']['total'] > 0)
    <h2 class="section-title">Share Subscriptions</h2>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                @foreach($months as $month)
                    <th>{{ $month->name }}</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $summary['shares']['name'] }}</td>
                @foreach($months as $month)
                    <td>
                        @if($summary['shares']['months'][$month->id] > 0)
                            ₦{{ number_format($summary['shares']['months'][$month->id], 2) }}
                        @else
                            -
                        @endif
                    </td>
                @endforeach
                <td>₦{{ number_format($summary['shares']['total'], 2) }}</td>
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
    <h2 class="section-title">Commodity Payments</h2>
    <table>
        <thead>
            <tr>
                <th>Commodity</th>
                @foreach($months as $month)
                    <th>{{ $month->name }}</th>
                @endforeach
                <th>Total</th>
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
                            <td>
                                @if($data['months'][$month->id] > 0)
                                    ₦{{ number_format($data['months'][$month->id], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                        <td>₦{{ number_format($data['total'], 2) }}</td>
                    </tr>
                @endif
            @endforeach
            <tr class="total-row">
                <td>Total Commodity Payments</td>
                @foreach($months as $month)
                    <td>
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
                <td>₦{{ number_format($commoditiesTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Grand Total Section -->
    <h2 class="section-title">Grand Total</h2>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                @foreach($months as $month)
                    <th>{{ $month->name }}</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr class="total-row">
                <td>GRAND TOTAL</td>
                @foreach($months as $month)
                    <td>
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
                <td>
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

    <div class="footer">
        <p>OGITECH Cooperative Society - Member Financial Summary Report</p>
        <p>Generated on {{ now()->format('M d, Y H:i:s') }}</p>
    </div>
</body>
</html>
