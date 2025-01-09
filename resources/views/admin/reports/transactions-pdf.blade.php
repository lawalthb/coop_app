<!DOCTYPE html>
<html>
<head>
    <title>Transactions Report</title>
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
        <h2>Transactions Report</h2>
        <p>Generated on: {{ now()->format('M d, Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Credits:</strong> ₦{{ number_format($transactions->sum('credit_amount'), 2) }}</p>
        <p><strong>Total Debits:</strong> ₦{{ number_format($transactions->sum('debit_amount'), 2) }}</p>
        <p><strong>Net Balance:</strong> ₦{{ number_format($transactions->sum('credit_amount') - $transactions->sum('debit_amount'), 2) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Member</th>
                <th>Type</th>
                <th>Credit</th>
                <th>Debit</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                <td>{{ $transaction->user->surname }} {{ $transaction->user->firstname }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</td>
                <td>{{ $transaction->credit_amount > 0 ? '₦'.number_format($transaction->credit_amount, 2) : '-' }}</td>
                <td>{{ $transaction->debit_amount > 0 ? '₦'.number_format($transaction->debit_amount, 2) : '-' }}</td>
                <td>{{ $transaction->description }}</td>
                <td>{{ ucfirst($transaction->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
