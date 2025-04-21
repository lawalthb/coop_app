<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loans Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin-bottom: 5px;
        }
        .header p {
            margin-top: 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .summary-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
        .summary-box h3 {
            margin-top: 0;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .summary-box p {
            margin: 0;
            font-size: 16px;
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
        <h1>OGITECH COOPERATIVE SOCIETY</h1>
        <h2>Loans Report</h2>
        <p>Generated on: {{ date('F d, Y h:i A') }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <h3>Total Loans</h3>
            <p>₦{{ number_format($totalLoans, 2) }}</p>
        </div>
        <div class="summary-box">
            <h3>Active Loans</h3>
            <p>₦{{ number_format($activeLoans, 2) }}</p>
        </div>
        <div class="summary-box">
            <h3>Total Repayments</h3>
            <p>₦{{ number_format($totalRepayments, 2) }}</p>
        </div>
        <div class="summary-box">
            <h3>Outstanding Balance</h3>
            <p>₦{{ number_format($outstandingBalance, 2) }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Reference</th>
                <th>Member</th>
                <th>Loan Type</th>
                <th>Amount</th>
                <th>Duration</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $loan)
            <tr>
                <td>{{ $loan->created_at->format('M d, Y') }}</td>
                <td>{{ $loan->reference }}</td>
                <td>{{ $loan->user->surname }} {{ $loan->user->firstname }}</td>
                <td>{{ $loan->loanType->name }}</td>
                <td>₦{{ number_format($loan->amount, 2) }}</td>
                <td>{{ $loan->duration }} months</td>
                <td>{{ ucfirst($loan->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">No loan transactions found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>OGITECH COOPERATIVE SOCIETY - Confidential Document</p>
    </div>
</body>
</html>
