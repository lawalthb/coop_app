<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Overall Financial Summary - {{ $selectedYear }}</title>
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
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
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
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .summary-card {
            width: 23%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .summary-card h3 {
            margin: 0 0 5px;
            font-size: 12px;
            color: #666;
        }
        .summary-card p {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
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
        <p>Overall Financial Summary for {{ $selectedYear }}</p>
        <p>Generated on: {{ now()->format('M d, Y') }}</p>
    </div>

    <div class="summary-cards">
        <div class="summary-card">
            <h3>Total Savings</h3>
            <p>₦{{ number_format($summary['savings']['total'], 2) }}</p>
        </div>
        <div class="summary-card">
            <h3>Loan Repayments</h3>
            <p>₦{{ number_format($summary['loans']['total'], 2) }}</p>
        </div>
        <div class="summary-card">
            <h3>Share Subscriptions</h3>
            <p>₦{{ number_format($summary['shares']['total'], 2) }}</p>
        </div>
        <div class="summary-card">
            <h3>Commodity Payments</h3>
            <p>₦{{ number_format($summary['commodities']['total'], 2) }}</p>
        </div>
    </div>

    <h2 class="section-title">Monthly Financial Summary</h2>
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
            <!-- Savings Row -->
            <tr>
                <td>Savings</td>
                @foreach($months as $month)
                    <td>
                        @if($summary['savings']['months'][$month->id] > 0)
                            ₦{{ number_format($summary['savings']['months'][$month->id], 2) }}
                        @else
                            -
                        @endif
                    </td>
                @endforeach
                <td>₦{{ number_format($summary['savings']['total'], 2) }}</td>
            </tr>

            <!-- Loan Repayments Row -->
            <tr>
                <td>Loan Repayments</td>
                @foreach($months as $month)
                    <td>
                        @if($summary['loans']['months'][$month->id] > 0)
                            ₦{{ number_format($summary['loans']['months'][$month->id], 2) }}
                        @else
                            -
                        @endif
                    </td>
                @endforeach
                <td>₦{{ number_format($summary['loans']['total'], 2) }}</td>
            </tr>

            <!-- Share Subscriptions Row -->
            <tr>
                <td>Share Subscriptions</td>
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

            <!-- Commodity Payments Row -->
            <tr>
                <td>Commodity Payments</td>
                @foreach($months as $month)
                    <td>
                        @if($summary['commodities']['months'][$month->id] > 0)
                            ₦{{ number_format($summary['commodities']['months'][$month->id], 2) }}
                        @else
                            -
                        @endif
                    </td>
                @endforeach
                <td>₦{{ number_format($summary['commodities']['total'], 2) }}</td>
            </tr>

            <!-- Total Row -->
            <tr class="total-row">
                <td>TOTAL</td>
                @foreach($months as $month)
                    <td>
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
                <td>
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

    <div class="footer">
        <p>OGITECH Cooperative Society - Financial Summary Report</p>
        <p>Generated on {{ now()->format('M d, Y H:i:s') }}</p>
    </div>
</body>
</html>
