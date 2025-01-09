<!DOCTYPE html>
<html>
<head>
    <title>Entrance Fees Report</title>
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
        <h2>Entrance Fees Report</h2>
        <p>Generated on: {{ now()->format('M d, Y') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Collections:</strong> ₦{{ number_format($entranceFees->sum('credit_amount'), 2) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Member</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entranceFees as $fee)
            <tr>
                <td>{{ $fee->created_at->format('M d, Y') }}</td>
                <td>{{ $fee->user->surname }} {{ $fee->user->firstname }}</td>
                <td>₦{{ number_format($fee->credit_amount, 2) }}</td>
                <td>{{ ucfirst($fee->status) }}</td>
                <td>{{ $fee->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
