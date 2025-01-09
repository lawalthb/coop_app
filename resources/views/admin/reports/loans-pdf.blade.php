<!DOCTYPE html>
<html>
<head>
    <title>Loans Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Loans Report</h2>
        <p>Generated on: {{ now()->format('M d, Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Loans:</strong> ₦{{ number_format($loans->sum('amount'), 2) }}</p>
        <p><strong>Outstanding Balance:</strong> ₦{{ number_format($loans->sum('balance'), 2) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Reference</th>
                <th>Member</th>
                <th>Loan Type</th>
                <th>Amount</th>
                <th>Balance</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
            <tr>
                <td>{{ $loan->created_at->format('M d, Y') }}</td>
                <td>{{ $loan->reference }}</td>
                <td>{{ $loan->user->surname }} {{ $loan->user->firstname }}</td>
                <td>{{ $loan->loanType->name }}</td>
                <td>₦{{ number_format($loan->amount, 2) }}</td>
                <td>₦{{ number_format($loan->balance, 2) }}</td>
                <td>{{ ucfirst($loan->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
